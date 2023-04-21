<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">

<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <meta name="author" content="Fabian Kruse">

    {{-- META-TAGS --}}
    @yield('meta_tags')

    <title>@yield('title', config('global.title'))</title>

    {{-- Main Styles --}}
    <link rel="stylesheet" href="{{ mix('css/app.css') }}">

    {{-- Main Scripts --}}
    <script defer src="{{ mix('js/app.js') }}"></script>

    {{-- Add additional script or styles --}}
    @yield('custom_script_style')
</head>

<body class="@yield('classes_body')">
    @yield('page_content')

    {{-- Initalize a custom init call stack --}}
    <script>
        if (window.customInit === undefined) {
            window.customInit = [];
        }
    </script>

    {{-- Inline Scripts --}}
    @hasSection('view_specific_js')
        @yield('view_specific_js')
    @endif
</body>

</html>
