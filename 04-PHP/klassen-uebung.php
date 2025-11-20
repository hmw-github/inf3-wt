<?php

class Produkt {
  private $name;
  private $preis;

  public function __construct($name, $preis) {
    $this->name = $name;
    $this->preis = $preis;
  }

  public function getName() {
    return $this->name;
  }
  public function getPreis() {
    return $this->preis;
  }
}

class WarenkorbPosition {
  private $produkt;
  private $anzahl;

  public function __construct($produkt, $anzahl) {
    $this->produkt = $produkt;
    $this->anzahl = $anzahl;
  }
  public function gesamtPreis() {
    return $this->produkt->getPreis() * $this->anzahl;
  }

  public function zeigeInfo() {
    return $this->produkt->getName() . " (" . $this->anzahl . " × " . 
      $this->produkt->getPreis() . " €) = " . $this->gesamtPreis() . " €";
  }
}

class Warenkorb {
  private $positionen;

  public function __construct() {
    $this->positionen = [];
  }

  public function neuePosition($produkt, $anzahl) {
    $this->positionen[] = new WarenkorbPosition($produkt, $anzahl);
  }

  public function berechneGesamtpreis() {
    $gesamtPreis = 0;

    foreach ($this->positionen as $position) {
      $gesamtPreis += $position->gesamtPreis();
    }
    return $gesamtPreis;
  }

  public function zeigeWarenkorb() {
    foreach ($this->positionen as $position) {
      echo $position->zeigeInfo() . "<br>";
    }
    echo "Gesamtpreis: " . $this->berechneGesamtpreis();
  }
}

?>