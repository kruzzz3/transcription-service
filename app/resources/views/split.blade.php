{{-- @php
    phpinfo();
@endphp --}}

@extends('master')

{{-- meta_tags --}}
@section('meta_tags')
    <meta name="description" content="Transcription Service - Audio Split">
@endsection

{{-- title --}}
@section('title') Transcription Service - Audio Split @endsection

@section('classes_body')
    text-center bg-light h-center
@stop

@section('page_content')

<form id="audio-upload-form" class="d-flex justify-content-center align-items-center flex-column" action="{{ route('audio.splitAndDownload') }}" method="post" enctype="multipart/form-data">
    @method('POST')
    @csrf

    <h1>Split</h1>

    <input required id="file" name="file" type="file" accept=".mp3,.m4a" class="">

    <i class="fa fa-solid fa-upload"></i>
</form>

<div id="loading-spinner" class="d-flex justify-content-center align-items-center">
    <i class="fa fa-solid fa-spinner fa-spin"></i>
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
