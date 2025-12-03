<?php
class AdressenDAO {
	private $daten = array(
    );
	
	public function __construct() {
        $this->erzeugeAdresse(
            new Adresse("Singer, Georg", "12345", "Beispielhausen"));
        $this->erzeugeAdresse(
            new Adresse("Coder, Codie", "54321", "Sourcetown"));  
	}

	public function gibAdressenZuFilter($name, $plz, $ort, $sortierung) {
		// Filter addresses based on search criteria
		$gefiltert = array();
		foreach ($this->daten as $adresse) {
			$namePasst = empty($name) ||
				stripos($adresse->getName(), $name) !== false;
			$plzPasst = empty($plz) ||
				strpos($adresse->getPlz(), $plz) !== false;
			$ortPasst = empty($ort) ||
				stripos($adresse->getOrt(), $ort) !== false;

			if ($namePasst && $plzPasst && $ortPasst) {
				$gefiltert[] = $adresse;
			}
		}

		// Sort the filtered results
		if (!empty($sortierung)) {
			switch ($sortierung) {
				case 'sortName':
					usort($gefiltert, function($a, $b) {
						return strcasecmp($a->getName(), $b->getName());
					});
					break;
				case 'sortPlz':
					usort($gefiltert, function($a, $b) {
						return strcmp($a->getPlz(), $b->getPlz());
					});
					break;
				case 'sortOrt':
					usort($gefiltert, function($a, $b) {
						return strcasecmp($a->getOrt(), $b->getOrt());
					});
					break;
			}
		}

		return $gefiltert;
	}
	
	public function gibAdresseZuName($name) {
		return $this->daten[$name];
	}
	
	public function loescheAdresse($name) {
		unset($this->daten[$name]);
	}
	
	public function erzeugeAdresse($adresse) {
		$this->daten[$adresse->getName()] = $adresse;
	}
	
	public function aendereAdresse($adresse) {
		$adr = $this->daten[$adresse->getName()];
		if (isset($adr)) {
			$adr->setPlz($adresse->getPlz());
			$adr->setOrt($adresse->getOrt());
		}
	}
}
?>