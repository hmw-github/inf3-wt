<!DOCTYPE html>
<html>
<head></head>
<body>
<?php

/*
Schreibe ein PHP-Skript, das folgende Ausgabe erzeugt, 
wobei die persönlichen Angaben aus Variablen stammen:
Mein Name ist Anna (string)
Ich bin 35 Jahre alt (integer)
Meine Größe ist 1.82 Meter (double)
Habe ich Hunger? 1 (boolean)
*/
$name = "Anna";
$alter = 35;
$groesse = 1.82;
$hunger = true;

echo "Mein Name ist " . $name . " (" . gettype($name) . ")<br>" .
  "Ich bin " . $alter . " Jahre alt (" . gettype($alter) . ")<br>" . 
  "Meine Größe ist " . $groesse . " Meter (" . gettype($groesse) . ")<br>" .
  "Habe ich Hunger? " . $hunger . " (" . gettype($hunger) . ")";

?>
</body>
</html>