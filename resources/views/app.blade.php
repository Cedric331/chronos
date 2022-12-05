<!DOCTYPE html>
<html lang="fr">
    <head>
        <meta charset="utf-8">
        <link rel="shortcut icon" href="{{ asset('favicon.png') }}">
        <meta name="viewport" content="width=device-width, initial-scale=0.8">
        <meta name="robots" content="noindex,nofollow">
        <meta name="csrf-token" content="{{ csrf_token() }}">

        <title inertia>{{ config('app.name', 'Laravel') }}</title>
{{--        <link href="https://fonts.googleapis.com/css2?family=Source+Sans+Pro:ital,wght@0,200;0,300;0,400;0,600;0,700;0,900;1,200;1,300;1,400;1,600;1,700;1,900&display=swap" rel="stylesheet">--}}
        <link rel="stylesheet" href="{{ mix('css/app.css') }}">
        @routes
        <script src="{{ mix('js/app.js') }}" defer></script>
    </head>
    <body class="font-sans antialiased">
        @inertia

    </body>
</html>

<style>
    body {
        margin: 0;
        display: flex;
        flex-direction: column;
        min-height: 100vh;
    }
</style>
