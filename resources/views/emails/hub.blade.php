<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
</head>
<body class="font-sans antialiased">
<h2>Bienvenue sur Chronos</h2>
<p>Votre HUB <strong>{{ $data['hub'] }}</strong> est désormais activé</p>
<ul>
    <li><strong>Nom :</strong></li> : {{ $data['name'] }}
    <li><strong>Email identifiant :</strong></li> : {{ $data['email'] }}
    <li><strong><a href="{{ $data['url'] }}" target="_blank">Cliquer ici</a> pour finaliser l'inscription </strong></li>
</ul>
</body>
</html>
