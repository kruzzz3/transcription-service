<?php

namespace App\Http\Controllers;

use App\Http\Requests\AudioUploadRequest;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Tectalic\OpenAi\Authentication;
use Tectalic\OpenAi\Manager;
use Tectalic\OpenAi\Models\AudioTranscriptions\CreateRequest;
use Illuminate\Http\Response;
use ZipArchive;

class TranscriptionController extends Controller
{
    private $openAIClient;
    private $whisperVersion = 'whisper-1';
    private $chunkSize = 20; // MB
    private $pathPrefix = '/var/www/storage/app/';


    public function __construct()
    {
        $this->openAIClient = Manager::build(new \GuzzleHttp\Client(), new Authentication(config('keys.open_ai_api_key')));
    }

    public function index()
    {
        return view('index');
    }

    public function transcribe()
    {
        return view('transcribe');
    }

    public function split()
    {
        return view('split');
    }

    public function upload(AudioUploadRequest $request)
    {
        if ($request->hasFile('file') && $request->file('file')->isValid()) {
            $file = $request->file('file');
            $storagePath = $file->store($request['path_to_store']); // Save File
            $storagePathForOpenAI = $this->pathPrefix . $storagePath;
        }

        $chunks = $this->splitAudioFile($storagePathForOpenAI);
        $transcription = '';
        foreach($chunks as $chunk) {
            $transcription .= $this->getTranscription($chunk);
        }

        $transcription = '<p>'. preg_replace('/\.\s/', '.<br>',  $transcription) . '</p>';

        $storagePathFrontend = 'http://localhost/' . str_replace("public", 'storage', $storagePath);

        return redirect()->route('audio.showTranscribed')->with([
            'audioFile' => $storagePathFrontend,
            'transcription' => $transcription,
        ]);
    }

    public function showTranscribed()
    {
        $audioFile = session()->get('audioFile');
        $transcription = session()->get('transcription');

        return view('transcribed')->with([
            'audioFile' => $audioFile,
            'transcription' => $transcription,
        ]);
    }

    public function splitAndDownload(AudioUploadRequest $request)
    {
        // Define the files you want to download
        if ($request->hasFile('file') && $request->file('file')->isValid()) {
            $file = $request->file('file');
            $storagePath = $file->store($request['path_to_store']); // Save File
            $storagePathForOpenAI = $this->pathPrefix . $storagePath;
        }

        $chunks = $this->splitAudioFile($storagePathForOpenAI);

        // Create a new ZipArchive instance
        $zip = new ZipArchive();

        // Create a temporary file for the zip
        $tmpFile = tempnam(sys_get_temp_dir(), 'zip');
        $zip->open($tmpFile, ZipArchive::CREATE);

        // Add each file to the zip
        foreach ($chunks as $file) {
            // Get the contents of the file
            $contents = Storage::get(str_replace($this->pathPrefix, '', $file));

            // Add the file to the zip
            $zip->addFromString(basename($file), $contents);
        }

        // Close the ZipArchive instance
        $zip->close();

        // Set the appropriate headers for the response
        $headers = [
            'Content-Type' => 'application/zip',
            'Content-Disposition' => 'attachment; filename=download.zip',
            'Content-Length' => filesize($tmpFile)
        ];

        // Send the zip file to the user
        return new Response(file_get_contents($tmpFile), 200, $headers);
    }

    private function getTranscription($storagePath) {
        /** @var \Tectalic\OpenAi\Models\AudioTranscriptions\CreateResponse $response */
        $response = $this->openAIClient->audioTranscriptions()->create(
            new CreateRequest([
                'file' => $storagePath,
                'model' =>$this->whisperVersion,
            ])
        )->toModel();

        return $response->text;
    }

    private function splitAudioFile($filePath) {
        // Get the bitrate
        $output = shell_exec('ffmpeg -i "' . $filePath . '" 2>&1');
        preg_match("/bitrate: (\d+) kb\/s/", $output, $matches);
        $bitrate = intval($matches[1]);

        // Get the file format
        $fileFormat = pathinfo($filePath)['extension'];

        // Calculate the duration of the chunks
        $segmentDuration = ($this->chunkSize * 1024 * 8) / $bitrate;

        // Create temporary directory to store chunks
        $tempDir = 'private/temp_' . time();
        Storage::makeDirectory($tempDir);
        $outputFilePath = $this->pathPrefix . $tempDir . '/%03d.' . $fileFormat;

        // Create Chunks
        $command = 'ffmpeg -i "' . $filePath . '" -f segment -segment_time ' . $segmentDuration . ' -c copy ' . $outputFilePath;
        exec($command);

        // Get all chunk-filepaths
        $chunks = [];
        for ($i = 0; $i <= 100; $i++) {
            $formattedNumber = sprintf('%03d', $i);
            $chunk = $tempDir . '/' . $formattedNumber .'.' . $fileFormat;
            if (Storage::exists($chunk)) {
                $chunks[] = $this->pathPrefix . $chunk;
                continue;
            }
            break;
        }

        return $chunks;
    }

}
