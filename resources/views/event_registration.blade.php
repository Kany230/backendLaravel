<!DOCTYPE html>
<html>
<head>
    <title>Confirmation d'inscription</title>
</head>
<body>
    <h2>Bonjour {{ $user->name }},</h2>
    <p>Vous êtes bien inscrit à l'événement suivant :</p>

    <ul>
        <li><strong>Titre :</strong> {{ $event->title }}</li>
        <li><strong>Date :</strong> {{ $event->date }}</li>
        <li><strong>Lieu :</strong> {{ $event->location }}</li>
    </ul>

    <p>Merci pour votre inscription !</p>
</body>
</html>