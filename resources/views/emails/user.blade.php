<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
</head>
<body class="font-sans antialiased">
    <h2>Bienvenue sur Chronos</h2>
    <p>Vous allez pouvoir suivre depuis le site www.chronos-hub.fr votre planning</p>
<ul>
    <li><strong>Nom :</strong></li>{{ $user['name'] }}
    <li><strong>Email identifiant :</strong></li>{{ $user['email'] }}
    <li><strong><a href="{{ $user['url'] }}" target="_blank">Cliquer ici</a> pour finaliser l'inscription </strong></li>
</ul>
</body>
</html>
