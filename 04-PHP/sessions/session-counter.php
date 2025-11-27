<?php
session_start();

// --- Reset-Button gedrückt? ---
if (isset($_POST['reset'])) {
    $_SESSION['count'] = 0;
    $_SESSION['start'] = time(); // Startzeit neu setzen
}

// --- Logout-Button gedrückt? ---
if (isset($_POST['logout'])) {
    session_unset();     // alle Session-Variablen löschen
    session_destroy();   // Session beenden
    // Seite neu laden, um neue Session zu starten
    header("Location: " . $_SERVER['PHP_SELF']);
    exit();
}

// --- Session initialisieren ---
if (!isset($_SESSION['count'])) {
    $_SESSION['count'] = 1;
    $_SESSION['start'] = time();
} else {
    $_SESSION['count']++;
}

// --- Laufzeit berechnen ---
$elapsed = time() - $_SESSION['start'];
$minutes = floor($elapsed / 60);
$seconds = $elapsed % 60;

?>
<!DOCTYPE html>
<html lang="de">
<head>
    <meta charset="UTF-8">
    <title>Session Besucherzähler</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            max-width: 600px;
            margin: 40px auto;
            padding: 20px;
            border-radius: 10px;
            background: #f4f4f4;
            box-shadow: 0 0 8px rgba(0,0,0,0.1);
        }
        h1 { color: #333; }
        .info { margin-top: 10px; color: #555; }
        form { margin-top: 20px; }
        input[type=submit] {
            margin-right: 10px;
            padding: 8px 16px;
            border: none;
            background-color: #4CAF50;
            color: white;
            border-radius: 5px;
            cursor: pointer;
        }
        input[type=submit]:hover {
            background-color: #45a049;
        }
    </style>
</head>
<body>

<h1>👋 Willkommen auf meiner Seite!</h1>

<p>Sie haben diese Seite in dieser Sitzung <strong><?= $_SESSION['count'] ?></strong> Mal aufgerufen.</p>

<div class="info">
    <p>Session gestartet am: <strong><?= date("d.m.Y H:i:s", $_SESSION['start']) ?></strong></p>
    <p>Sitzung läuft seit: <strong><?= $minutes ?> Minuten <?= $seconds ?> Sekunden</strong></p>
</div>

<form method="post">
    <input type="submit" name="reset" value="Zähler zurücksetzen">
    <input type="submit" name="logout" value="Session beenden">
</form>

</body>
</html>
