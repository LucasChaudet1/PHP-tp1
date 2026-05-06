<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <title>Accueil</title>
    <link rel="stylesheet" type="text/css" href="Style.css"/>

</head>
<body>
<?php
session_start();

$ip = $_SERVER['REMOTE_ADDR'] ?? 'unknown';
if ($ip === '::1') {
    $ip = '127.0.0.1';
}

$storageDir = __DIR__ . '/stockage/' .  preg_replace('/[^a-zA-Z0-9]/', '_', $ip);
$responsesFile = $storageDir . '/responses.txt';
$alreadyResponded = is_file($responsesFile);
?>
<header class="main_head">
<h1>Accueil</h1>

<nav>
    <ul>
        <li>
            <?php if ($alreadyResponded): ?>
                <span style="cursor: not-allowed;">Formulaire d'inscription (déjà répondu)</span>
            <?php else: ?>
                <a href="formulaire.php">Formulaire d'inscription</a>
            <?php endif; ?>
        </li>
    </ul>
</nav>



</header>

<p></p>
</body>
</html>