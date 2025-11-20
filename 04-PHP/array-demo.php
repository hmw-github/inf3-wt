<?php

$zahlen = array(1, 2, 3);
var_dump($zahlen);

for ($i = 0; $i < count($zahlen); ++$i) {
  echo $zahlen[$i] . "<br>";
}

// Ausgabe der "2"
echo $zahlen[1] . "<br>";

// Speichere 2 Personen mit ihrem Alter in einem Array
$personen = array(
  "Anna" => 23,
  "Paul" => 19
);

foreach($personen as $name => $alter) {
  echo $name . ", " . $alter . "<br>";
}