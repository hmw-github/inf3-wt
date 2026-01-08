/**
 * hier URL des Server-Verzeichnisses vorgeben
 */
var basisURL = 'http://localhost:8080/bestellungsverwaltung-server';

/**
 * Führt einen HTTP-GET-Aufruf aus.
 * @param {*} url Adresse, die aufgerufen werden soll
 * @param {*} successFunction wird aufgerufen im Erfolgsfall
 * @param {*} errorFunction wird im Fehlerfall aufgerufen
 */
function httpGet(url, successFunction, errorFunction) {
	var xmlHttp = false;

	if (typeof XMLHttpRequest != 'undefined') {
		xmlHttp = new XMLHttpRequest();
	}
	if (xmlHttp) {
		xmlHttp.open('GET', url, true);
		xmlHttp.onreadystatechange = 
			function () {
				if (this.readyState == 4) {
                    if (this.status == 200) {
                        let responseJSON = JSON.parse(xmlHttp.response);
                        successFunction(responseJSON);
                    } else {
                        errorFunction(this.status);
                    }
				}
			};
		xmlHttp.send(null);
    }
}

function httpDelete(url, successFunction, errorFunction) {
	var xmlHttp = false;

	if (typeof XMLHttpRequest != 'undefined') {
		xmlHttp = new XMLHttpRequest();
	}
	if (xmlHttp) {
		xmlHttp.open('DELETE', url, true);
		xmlHttp.onreadystatechange = 
			function () {
				if (this.readyState == 4) {
                    if (this.status == 200) {
                        successFunction();
                    } else {
                        errorFunction(this.status);
                    }
				}
			};
		xmlHttp.send(null);
    }
}

/**
 * Lädt Bestellungen vom Server durch Aufruf von 
 *      GET bestellungenMitPositionen
 * und schreibt die Daten in das DOM (als Tabelle).
 */
function ladeBestellungen() {
    let url = basisURL + '/bestellungen';

    positionenSichtbar(false);
    httpGet(url, function(bestellungen) {
        positionenAlsArrayFuer(bestellungen);

        const table = document.getElementById('bestellungen-table-id');

        loescheTabelle(table);

        // Bestellungen in Tabelle schreiben
        for (let bestellung of bestellungen) {
            belegeBestellungsZeile(table, bestellung);
        }
    }, function(errorcode) {
        alert('Fehler beim Zugriff auf den Server: ' + errorcode);
    })
}

/**
 * Lädt Positionen vom Server durch Aufruf von 
 *      GET bestellungen/{id}/positionen
 * und schreibt die Daten in das DOM (als Tabelle).
 * @param {*} bestellung Bestellungsobjekt, zu der die Pos. geladen werden sollen 
 */
function ladePositionen(bestellung) {
    let url = basisURL + '/bestellung/' + bestellung.id + '/positionen';

    httpGet(url, function(positionen) {
        let header = document.getElementById('positionen-header-id');
        header.innerHTML = 'Positionen für Kunde ' + bestellung.kunde + ' (id = ' + bestellung.id + ')';

        positionen = positionenAlsArray(positionen);

        const container = document.getElementById("positionen-container-id");
        let classes = container.classList;
        document.getElementById('position-message').innerHTML = '';
        if (positionen.length == 0) {
            positionenSichtbar(false);
            document.getElementById('position-message').innerHTML = 'Keine Positionen vorhanden!';
        } else {
            positionenSichtbar(true);
            const table = document.getElementById('positionen-table-id');
            loescheTabelle(table);
    
            // Positionen in Tabelle schreiben
            for (let position of positionen) {
                belegePositionsZeile(table, bestellung, position);
            }
        }
    }, function(errorcode) {
        alert('Fehler beim Zugriff auf den Server: ' + errorcode);
    })
}

/**
 * Löscht die Bestellung bestellung.
 */
function loescheBestellung(bestellung) {
    httpDelete(basisURL + '/bestellung/' + bestellung.id,
        () => {
            ladeBestellungen();
        }, () => alert('Bestellung konnte nicht gelöscht werden!')
    );
}

/**
 * Löscht die Position positionNr in der Bestellung bestellung.
 */
function loeschePosition(bestellung, positionNr) {
    httpDelete(basisURL + '/bestellung/' + bestellung.id + '/position/' + positionNr,
        () => {
            ladeBestellungen();
            ladePositionen(bestellung);
        }, () => alert('Position konnte nicht gelöscht werden!')
    );
}

/**
 * Blendet die Positionsliste ein oder aus.
 * @param {*} sichtbar True: einblenden, False: ausblenden
 */
function positionenSichtbar(sichtbar) {
    const container = document.getElementById("positionen-container-id");
    let classes = container.classList;

    if (sichtbar) {
        classes.remove('invisible');
    } else {
        classes.add('invisible');
    }
}

/**
 * Wandelt die Positionen-Objekte (kommt so vom Server) der Bestellungen in Arrays um
 * @param {*} bestellungen 
 */
function positionenAlsArrayFuer(bestellungen) {
    for (let bestellung of bestellungen) {
        bestellung.positionen = positionenAlsArray(bestellung.positionen);
    }
}

/**
 * Liefert ein Array zu dem übergebenen Positionen-Objekt (kommt so vom Server)
 * @param {*} positionen 
 */
function positionenAlsArray(positionen) {
    let ergebnis = [];

    for (let key in positionen) {
        if (positionen.hasOwnProperty(key)) {
            ergebnis.push(positionen[key]);
        }
    }
    return ergebnis;
}

/**
 * Löscht die Datenzeilen aus der übergebenen Tabelle (nicht die Titelzeile)
 * @param {*} table 
 */
function loescheTabelle(table) {
    while (table.rows.length > 1) {
        table.deleteRow(1);
    }
}

/**
 * Erzeugt eine Zeile zu der übergebenen Bestellung in der Tabelle
 * @param {*} table 
 * @param {*} bestellung 
 */
function belegeBestellungsZeile(table, bestellung) {
	var tr = table.children[1].insertRow(-1); // einfügen am Ende	
	var td  = tr.insertCell(0);
	
   	var inhalt  = document.createTextNode(bestellung.id);
   	td.appendChild(inhalt);
   	
	td  = tr.insertCell(1);
   	inhalt = document.createTextNode(bestellung.kunde);
   	td.appendChild(inhalt);

	td  = tr.insertCell(2);
   	inhalt = document.createTextNode(new Date(bestellung.datum).toLocaleDateString("de-de"));
   	td.appendChild(inhalt);

	td  = tr.insertCell(3);
   	inhalt  = document.createTextNode(bestellung.anzahlPositionen);
    td.appendChild(inhalt);

    td  = tr.insertCell(4);
    button = document.createElement('button');
    button.innerHTML = 'löschen';
    button.classList.add('btn', 'btn-warning');
    button.onclick = function() {
        loescheBestellung(bestellung);
    };
    td.appendChild(button);
       
    tr.onclick = function() {
        selektiereZeile(this, table);
        ladePositionen(bestellung);
    };
}

/**
 * Handler für die Selektion einer Zeile, setzt die selektiert-Style-Klasse
 */
function selektiereZeile(tr, table) {
    let trList = document.getElementsByClassName('selektiert');

    for (let selektiertesTR of trList) {
        selektiertesTR.classList.remove('selektiert');
    }

    tr.classList.add('selektiert');
}

/**
 * Erzeugt eine Zeile zu der übergebenen Position in der Positions-Tabelle
 * @param {*} table 
 * @param {*} position 
 */
function belegePositionsZeile(table, bestellung, position) {
	var tr = table.children[1].insertRow(-1); // einfügen am Ende	
	var td  = tr.insertCell(0);
	
   	var inhalt  = document.createTextNode(position.nr);
   	td.appendChild(inhalt);
   	
	td  = tr.insertCell(1);
   	inhalt = document.createTextNode(position.artikel);
   	td.appendChild(inhalt);

    td  = tr.insertCell(2);
   	inhalt = document.createTextNode(position.menge);
    td.appendChild(inhalt);

    td  = tr.insertCell(3);
   	button = document.createElement('button');
    button.innerHTML = 'löschen';
    button.classList.add('btn', 'btn-warning');
    button.onclick = function() {
        loeschePosition(bestellung, position.nr);
    };
    td.appendChild(button);
}