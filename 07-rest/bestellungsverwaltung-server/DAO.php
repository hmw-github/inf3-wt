<?php 

/**
 * Data Access Object für Bestellungsobjekte
 * Die Daten werden beim Erzeugen des Objekts aus der Datei `data.json' geladen.
 * Bei einer Änderung werden alle Bestellungen gespeichert.
 */
class DAO {
    private $bestellungen;
    private $nextId; // nächste BestellungsID

	public function __construct() {
        $this->nextId = 0;
        $datenJSON = file_get_contents("data.json");
        $arr = json_decode($datenJSON);
        if ($arr === NULL) {
            $this->bestellungen = array();
        } else {
            // höchste ID bestimmen und IDs als keys in array eintragen
            foreach ($arr as $bestellung) {
                $this->bestellungen[$bestellung->id] = $bestellung;
                $this->nextId = max($this->nextId, $bestellung->id);
                $positionen = array();
                foreach ($bestellung->positionen as $position) {
                    $positionen[$position->nr] = $position;
                }
                $bestellung->positionen = $positionen;
            }
        }
        $this->nextId += 1;
	}

	public function findeBestellungen() {
        $ergebnis = [];
        foreach($this->bestellungen as $bestellung) {
            $b = clone $bestellung; // Kopie erstellen und Positionen zurücksetzen
            $b->positionen = [];
            $b->anzahlPositionen = count($bestellung->positionen);
            $ergebnis[] = $b;
        }
        
		return $ergebnis;
	}

    public function findePositionenZuBestellung($bestellungId) {
        if (array_key_exists($bestellungId, $this->bestellungen)) {
            return $this->bestellungen[$bestellungId]->positionen;
        }        
        return NULL;
    }

    public function erzeugeBestellung($daten) {
        try {
            $neu = json_decode($daten);
            $neu->id = $this->nextId++;
            $neu->positionen = array();
            $this->bestellungen[$neu->id] = $neu;
            file_put_contents('data.json', json_encode($this->bestellungen));    
            return $neu;
        } catch (Exception $exception) {
            echo $exception;
            return NULL;
        }
    }

    public function aktualisiereBestellung($bestellungId, $daten) {
        $neu = json_decode($daten);
        if (!array_key_exists($bestellungId, $this->bestellungen)) 
            return false;
        $neu->positionen = $this->bestellungen[$bestellungId]->positionen;
        $this->bestellungen[$bestellungId] = $neu;
        file_put_contents('data.json', json_encode($this->bestellungen));
        return true;
    }

    public function loescheBestellung($bestellungId) {
        if (!array_key_exists($bestellungId, $this->bestellungen)) 
            return false;
        unset($this->bestellungen[$bestellungId]);
        file_put_contents('data.json', json_encode($this->bestellungen));
        return true;
    }

    public function sucheBestellungen($suchString) {
        $ergebnis = array();
        foreach($this->bestellungen as $bestellung) {
            if (strpos($bestellung->kunde, $suchString) !== FALSE) {
                $ergebnis[] = $bestellung;
            }
        }
        return $ergebnis;
    }

    public function erzeugePosition($bestellungId, $daten) {
        $neu = json_decode($daten);
        if (!array_key_exists($bestellungId, $this->bestellungen)) 
            return false;

        $positionen = $this->bestellungen[$bestellungId]->positionen;
        $nrArray = array();

        if (count($positionen) === 0) {
            $neu->nr = 1;
        } else {
            foreach($positionen as $pos) {
                $nrArray[] = $pos->nr;
            }
            $neu->nr = max($nrArray) + 1; // nr um eins größer als Maximum    
        }
        $positionen[$neu->nr] = $neu;
        $this->bestellungen[$bestellungId]->positionen = $positionen;
        file_put_contents('data.json', json_encode($this->bestellungen));
        return true;
    }

    public function aktualisierePosition($bestellungId, $nr, $daten) {
        $position = json_decode($daten);
        if (!array_key_exists($bestellungId, $this->bestellungen)) 
            return false;

        $positionen = $this->bestellungen[$bestellungId]->positionen;
        if (array_key_exists($nr, $positionen)) {
            $positionen[$nr] = $position;
            $this->bestellungen[$bestellungId]->positionen = $positionen;
            file_put_contents('data.json', json_encode($this->bestellungen));
            return true;
        }        
        return false;                
    }

    public function loeschePosition($bestellungId, $nr) {
        if (!array_key_exists($bestellungId, $this->bestellungen)) 
            return false;

        $positionen = $this->bestellungen[$bestellungId]->positionen;
        if (array_key_exists($nr, $positionen)) {
            unset($positionen[$nr]);
            $this->bestellungen[$bestellungId]->positionen = $positionen;
            file_put_contents('data.json', json_encode($this->bestellungen));
            return true;
        }        
        return false;                
    }
}
?>