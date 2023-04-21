{{-- @php
    phpinfo();
@endphp --}}

@extends('master')

{{-- meta_tags --}}
@section('meta_tags')
    <meta name="description" content="Transcription Service - Index">
@endsection

{{-- title --}}
@section('title') Transcription Service - Index @endsection

@section('classes_body')
    text-center bg-light h-center
@stop

@section('page_content')

<div class="d-flex justify-content-center align-items-center flex-column h-100">
    <a class="btn btn-primary btn-lg m-2" href="{{ route('audio.transcribe') }}" role="button">Transcribe</a>
    <a class="btn btn-primary btn-lg m-2" href="{{ route('audio.split') }}" role="button">Split</a>
</div>

@stop

@section('view_specific_js')
    @parent
    <script>
        window.customInit.push(function() {
            $('#audio-upload-form').on('change',function(){
                $('#audio-upload-form').submit();
                $('#loading-spinner').addClass('show');
            });
        });
    </script>
@stop
