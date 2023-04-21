@extends('master')

{{-- meta_tags --}}
@section('meta_tags')
    <meta name="description" content="Transcription Service - Transcription">
@endsection

{{-- title --}}
@section('title') Transcription Service - Transcription @endsection

@section('classes_body')
    text-center bg-light h-center
@stop

@section('page_content')

<div id="audio-player" class="d-flex justify-content-center align-items-center">
<audio controls src="{{ $audioFile }}" autoplay></audio>
</div>

<div id="transcription-text" class="text-center">
{!! $transcription !!}
</div>

<div id="loading-spinner" class="d-flex justify-content-center align-items-center">
    <i class="fa fa-solid fa-spinner fa-spin"></i>
</div>

@endSection

@section('view_specific_js')
    @parent
    <script>
        window.customInit.push(function() {
            $('#generate-form').on('submit',function(){
                $('#loading-spinner').addClass('show');
            });
        });
    </script>
@stop
