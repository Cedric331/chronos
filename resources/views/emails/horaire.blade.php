<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Modification des horaires</title>
    <link href="https://unpkg.com/tailwindcss@^2/dist/tailwind.min.css" rel="stylesheet">
</head>
<body class="font-sans antialiased">

<h2>Modification des horaires sur Chronos</h2>

<p>Voici vos nouveaux horaires</p>

<div class="mt-4">
@foreach($data as $item)
        <hr>
        <div>
           <h3>{{ $item['date'] }} {{ $item['type'] === 'Iti1' ? ' - Technicien' : '' }}</h3>
            @if ($item['type'] !== 'CP' && $item['debut_journee'] !== null)
            <div>
                    @if($item['type'] !== 'Iti1')
                    <b>{{ $item['teletravail'] ? 'Télétravail' : 'Hub'}}</b>
                    @endif
                <br>
                <p>Début de Journée : {{ $item['debut_journee'] }}</p>
                <p>{{ $item['debut_pause'] ? 'Pause Déjeuner :' . $item['debut_pause'] : '' }}</p>
                <p>{{ $item['fin_pause'] ? 'Fin Déjeuner :' . $item['fin_pause'] : '' }}</p>
                <p>Fin de Journée : {{ $item['fin_journee'] }}</p>
            </div>
            @elseif ($item['debut_journee'] === null && $item['type'] !== 'CP')
                <p>Repos</p>
            @else
                <p>Congé Payé</p>
            @endif
        </div>
    @endforeach
</div>
</body>
</html>
