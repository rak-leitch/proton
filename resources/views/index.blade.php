<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <title>Index</title>
        {{ Vite::useBuildDirectory('adepta/proton/assets')->withEntryPoints('resources/js/proton.js') }} 
    </head>
    <body>
        <div id="proton"></div>
    </body>
</html>
