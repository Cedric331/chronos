<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Bienvenue sur Chronos</title>
    <script src="https://cdn.tailwindcss.com"></script>
</head>
    <body class="font-sans antialiased">
            <h2>Bienvenue sur Chronos</h2>
            <p>Votre HUB <strong>{{ $data['hub'] }}</strong> est désormais activé</p>
            <div class="mt-4">
                <p class="mt-4 text-sm">
                    <a type="button" href="{{ $data['url'] }}" target="_blank" class="px-2 py-2 text-blue-200 bg-blue-600 rounded">
                        Terminer mon inscription
                    </a>
                </p>
            </div>
    </body>
</html>
