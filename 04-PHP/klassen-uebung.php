<?php

class Produkt {
  private string $name;
  private float $preis;

  public function __construct(string $name, float $preis) {
    $this->name = $name;
    $this->preis = $preis;
  }

  public function getName(): string {
    return $this->name;
  }
  public function getPreis(): float {
    return $this->preis;
  }
}

class WarenkorbPosition {
  private Produkt $produkt;
  private int $anzahl;

  public function __construct(Produkt $produkt, int $anzahl) {
    $this->produkt = $produkt;
    $this->anzahl = $anzahl;
  }
  public function gesamtPreis(): float {
    return $this->produkt->getPreis() * $this->anzahl;
  }

  public function zeigeInfo(): string {
    return $this->produkt->getName() . " (" . $this->anzahl . " × " . 
      $this->produkt->getPreis() . " €) = " . $this->gesamtPreis() . " €";
  }
}

class Warenkorb {
  protected array $positionen;

  public function __construct() {
    $this->positionen = [];
  }

  public function neuePosition(Produkt $produkt, int $anzahl): void {
    $this->positionen[] = new WarenkorbPosition($produkt, $anzahl);
  }

  public function berechneGesamtpreis(): float {
    $gesamtPreis = 0;

    foreach ($this->positionen as $position) {
      $gesamtPreis += $position->gesamtPreis();
    }
    return $gesamtPreis;
  }

  public function zeigeWarenkorb(): void {
    foreach ($this->positionen as $position) {
      echo $position->zeigeInfo() . "<br>";
    }
    echo "Gesamtpreis: " . $this->berechneGesamtpreis() . "<br>";
  }
}

// Vererbung
class RabattWarenkorb extends Warenkorb {
  private int $rabatt;

  public function __construct(int $rabatt) {
    parent::__construct();
    $this->rabatt = $rabatt;
  }

  public function berechneGesamtpreis(): float {
    return parent::berechneGesamtpreis() * (1 - $this->rabatt/100.0);
  }
 
  public function zeigeWarenkorb(): void {
    foreach ($this->positionen as $position) {
      echo $position->zeigeInfo() . "<br>";
    }
    echo "Gesamtpreis: " . parent::berechneGesamtpreis() . "<br>";
    echo "Rabatt: " . $this->rabatt . "%, neuer Gesamtpreis: " . $this->berechneGesamtpreis() . "<br>";
  }
}

// Test 
$warenkorb = new Warenkorb();
$warenkorb->neuePosition(new Produkt("Apfel", 0.50), 3);
$warenkorb->neuePosition(new Produkt("Brot", 2.30), 1);
$warenkorb->neuePosition(new Produkt("Milch", 1.20), 2);
$warenkorb->zeigeWarenkorb();

$warenkorb = new RabattWarenkorb(10);
$warenkorb->neuePosition(new Produkt("Apfel", 0.50), 3);
$warenkorb->neuePosition(new Produkt("Brot", 2.30), 1);
$warenkorb->neuePosition(new Produkt("Milch", 1.20), 2);
$warenkorb->zeigeWarenkorb();

?>