<?php
function berechneDurchschnitt(array $zahlen): float | int | null {
    if (count($zahlen) === 0) {
      return null;
    }

    $summe = 0;
    foreach ($zahlen as $zahl) {
      $summe += $zahl;
    }

    return $summe / count($zahlen);
}

// Tests
$liste1 = [2, 4, 6, 8];
$liste2 = [10, 20, 30];
$liste3 = [];

// Array: [2, 4, 6, 8] → Durchschnitt: 5
echo 'Array: [2, 4, 6, 8] → Durchschnitt: ' . berechneDurchschnitt($liste1) . '<br>';
// Array: [10, 20, 30] → Durchschnitt: 20
echo 'Array: [10, 20, 30] → Durchschnitt: ' . berechneDurchschnitt($liste2) . '<br>';
$erg = berechneDurchschnitt($liste3);
// Array: [] → Durchschnitt: keine Werte
echo 'Array: [] → Durchschnitt: ' . ($erg === null ? 'keine Werte' : $erg) . '<br>';
?>