<!DOCTYPE html>
<html lang="fr">

<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Contact</title>
    <script src="https://cdn.tailwindcss.com"></script>
</head>

    <body>
        <div class="flex items-center justify-center min-h-screen p-5 bg-gray-600 min-w-screen">
            <div class="max-w-xl p-8 text-center text-gray-800 bg-white shadow-xl lg:max-w-3xl rounded-3xl lg:p-12">
                <h3 class="text-2xl">{{ $content['subject'] }}</h3>
                <div class="mt-4">
                    <p class="mt-4 text-sm">
                        @foreach( $content['text'] as $line)
                            {{ $line }}
                            <br>
                        @endforeach
                    </p>
                </div>
            </div>
            <br>
            <br>
            <div class="max-w-xl p-8 text-center text-gray-800 bg-white shadow-xl lg:max-w-3xl rounded-3xl lg:p-12">
                <h3 class="text-2xl">Information de l'auteur</h3>
                <div class="mt-4">
                    <p class="mt-4 text-sm">
                       Message envoyé par {{ $content['author']['name'] }}
                        <br>
                       Email : {{ $content['author']['email'] }}
                        <br>
                       Identifiant {{ $content['author']['identifiant'] }}
                    </p>
                </div>
            </div>

        </div>
    </body>
</html>
