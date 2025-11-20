<!DOCTYPE html>
<html>
<head></head>
<body>
<?php

function test(int $n): int {
  return $n + 1;
}

//test("abc");
$name = "Hugo";

/*
Schreibe ein PHP-Skript, das folgende Ausgabe auf einer Webseite erzeugt:
- Eine Überschrift: Willkommen auf meiner Seite!
- Einen Absatz, der deinen Namen enthält: Mein Name ist ...
- Eine Liste mit drei deiner Hobbys.
- Einen Absatz, in dem das aktuelle Jahr mit PHP ausgegeben wird.
  👉 Verwende ausschließlich echo, um den HTML-Code auszugeben.

Tipp:
Mit date("Y") kannst du das aktuelle Jahr ausgeben.
*/

echo "<h1>Willkomen auf meiner Seite!</h1>";
echo "Mein Name ist " . $name;
echo "<ul>";
echo "<li>Angeln</li>";
echo "<li>Tauchen</li>";
echo "<li>Schwimmen</li>";
echo "</ul>";
echo "Aktuelles Jahr: " . date("Y");
?>
</body>
</html>
