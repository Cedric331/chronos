<!DOCTYPE html>
<html lang="fr">

<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Bienvenue sur Chronos</title>
    <script src="https://cdn.tailwindcss.com"></script>
</head>

<body>
<div class="flex items-center justify-center min-h-screen p-5 bg-gray-600 min-w-screen">
    <div class="max-w-xl p-8 text-center text-gray-800 bg-white shadow-xl lg:max-w-3xl rounded-3xl lg:p-12">
        <h3 class="text-2xl">Bienvenue sur Chronos {{ $user['name'] }}</h3>
        <div class="mt-4">
            <p class="mt-4 text-sm">
                <a type="button" href="{{ $user['url'] }}" target="_blank" class="px-2 py-2 text-blue-200 bg-blue-600 rounded">
                    Terminer mon inscription
                </a>
            </p>
        </div>
    </div>
</div>

</body>
</html>
