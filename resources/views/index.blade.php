<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <meta name="csrf-token" content="{{ csrf_token() }}">
        <title>Index</title>
        {{ Vite::useBuildDirectory('adepta/proton')->withEntryPoints('resources/js/proton.js') }}
        <script>
            window.protonApiBase = '{{ url('proton/api') }}';
        </script>
    </head>
    <body>
        <div id="proton"></div>
    </body>
</html>
