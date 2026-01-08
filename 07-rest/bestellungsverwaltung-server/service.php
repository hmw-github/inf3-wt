<?php
spl_autoload_register(function($class_name) {
    include $class_name . '.php';
});

$dao = new DAO();

/*
 * Service-Implementierungen (Datenmodell siehe data.json)
 *  Operation	                HTTP-Befehl	Pfad	                        Request Body	Reply	        Status-Werte
 *  ladeBestellungen	        GET	        bestellungen	                - 	            Bestellung[]	200
 *  erzeugeBestellung	        POST	    bestellung	                    Bestellung	    Bestellung      201
 *  aktualisiereBestellung	    PUT	        bestellung/{id}	                Bestellung	    -   	        200, 404
 *  loescheBestellung	        DELETE	    bestellung/{id}	                - 	            -	            200, 404
 *  ladePositionenZuBestellung	GET	        bestellung/{id}/positionen	    -	            Bestellposition[]	200, 404
 *  erzeugePosition	            POST	    bestellung/{id}/position	    Bestellposition	Bestellposition	200, 404
 *  aktualisierePosition	    PUT	        bestellung/{id}/position/{nr}	Bestellposition	- 	            200, 404
 *  loeschePosition	            DELETE	    bestellung/{id}/position/{nr}	- 	            - 	            200, 404
 */

function getBestellungen() {
    global $dao;

	$bestellungen = $dao->findeBestellungen();
    echo json_encode($bestellungen);
    http_response_code(200); // kein Fehler
}
function getBestellung($bestellungId) {
    global $dao;

    $bestellungen = $dao->findeBestellungen();
    foreach($bestellungen as $b) {
        if($b->{'id'} == $bestellungId) {
            echo json_encode($b);
            http_response_code(200); // kein Fehler
            return;
        }
    }
    http_response_code(404);
}

function getPositionenZuBestellung($bestellungId) {
    global $dao;

    $positionen = $dao->findePositionenZuBestellung($bestellungId);
    if ($positionen === NULL) {
        http_response_code(404);
    } else {
        echo json_encode($positionen);
        http_response_code(200); // kein Fehler
    }
}

function postBestellung($body) {
    global $dao;

    $result = $dao->erzeugeBestellung($body);
    echo json_encode($result);
    http_response_code(201);     
}

function putBestellung($bestellungId, $daten) {
    global $dao;

    $result = $dao->aktualisiereBestellung($bestellungId, $daten);
    if ($result === false) {
        http_response_code(404);     
    } else {
        http_response_code(200);     
    }
}

function deleteBestellung($bestellungId) {
    global $dao;

    $result = $dao->loescheBestellung($bestellungId);
    if ($result === false) {
        http_response_code(404);     
    } else {
        http_response_code(200);     
    }
}

function postPosition($bestellungId, $body) {
    global $dao;

    try {
        $result = $dao->erzeugePosition($bestellungId, $body);
        http_response_code(201);    
    } catch (Exception $exception) {
        echo $exception;
    }
}

function putPosition($bestellungId, $matrikelnummer, $body) {
    global $dao;

    $result = $dao->aktualisierePosition($bestellungId, $matrikelnummer, $body);
    if ($result === false) {
        http_response_code(404);
    } else {
        http_response_code(201);     
    }    
}

function deletePosition($bestellungId, $matrikelnummer) {
    global $dao;

    $result = $dao->loeschePosition($bestellungId, $matrikelnummer);
    if ($result === false) {
        http_response_code(404);
    } else {
        http_response_code(200);     
    }    
}

/*
 * Hilfsfunktionen
 */

// sollte ein Cross Origin Resource Sharing (CORS) request als OPTION call kommen,
// wird einfach alles erlaubt...
// ruft man einen fremden Server auf, so schickt der Browser einen OPTIONS request vorweg,
// um die Erlaubnis zu erlangen, den eigentlichen Aufruf durchzuführen
function handleCORS($requestType, $url, $body) {
    header("Access-Control-Allow-Origin: *");
}

// ungültige Anfrage
function badRequest($requestType, $url, $body) {
 	http_response_code(400);
    die('Ungültiger Request: '.$requestType.' '.$url.' '.$body);        
}

/*
 * Service Dispatcher: zerlegt die Anfrage und ruft die passende
 * Service-Implementierung auf
 */
$url = $_REQUEST['_url']; // -> Pfad
$requestType = $_SERVER['REQUEST_METHOD']; // -> HTTP Kommando
//$headers = getallheaders();
$body = file_get_contents('php://input'); 

try {
    // Cross Origin Resource Sharing
    handleCORS($requestType, $url, $body);
    if ($requestType === "OPTIONS") { // CORS: OPTIONS request kommt vor eig. request
        http_response_code(200);
        header("Access-Control-Allow-Methods: GET, POST, DELETE, PUT, OPTIONS");
        header("Access-Control-Allow-Headers: Pragma, Cache-Control");
    } else if (preg_match("/\/bestellungen\/suche\/(.*)/", $url)) {
        // TODO: supply missing code to call sucheBestellungen($search); implement sucheBestellungen($search)
    } else if ($url === '/bestellungen') {
        if ($requestType === 'GET') {
            getBestellungen();
        } else {
            throw new Exception('bad request');
        }
    } else if ($url === '/bestellung' && $requestType === "POST") {
        postBestellung($body);
    } else if (preg_match("/\/bestellung\/[0-9]*\/positionen/", $url) &&
        $requestType === 'GET') {
        $parts = explode('/', $url);
        if (count($parts) !== 4) {
            throw new Exception('bad request');
        } else {
            $bestellungId = $parts[2];
            getPositionenZuBestellung($bestellungId);
        }
    } else if (preg_match("/\/bestellung\/[0-9]*\/position\/[0-9]*/", $url)) {
        $parts = explode('/', $url);
        if (count($parts) !== 5) {
            throw new Exception('bad request');
        } else {
            $bestellungId = $parts[2];
            $nr = $parts[4];
            if ($requestType === 'PUT') {
                putPosition($bestellungId, $nr, $body);
            } else if ($requestType === 'DELETE') {
                deletePosition($bestellungId, $nr);
            } else {
                throw new Exception('bad request');
            }
        }
    } else if (preg_match("/\/bestellung\/[0-9]*\/position/", $url) &&
        $requestType === 'POST') {
        $parts = explode('/', $url);
        if (count($parts) !== 4) {
            throw new Exception('bad request');
        } else {
            $bestellungId = $parts[2];
            postPosition($bestellungId, $body);
        }
    } else if (preg_match("/\/bestellung\/[0-9]*/", $url)) {
        $parts = explode('/', $url);
        if (count($parts) !== 3) {
            throw new Exception('bad request');
        } else {
            $bestellungId = $parts[2];
            if ($requestType === 'PUT') {
                putBestellung($bestellungId, $body);
            } else if ($requestType === 'DELETE') {
                deleteBestellung($bestellungId);
            } else if ($requestType === 'GET') {
                getBestellung($bestellungId);
            } else {
                badRequest($requestType, $url, $body);        
            }
        }
    } else {
        throw new Exception('bad request');
    }
} catch (Exception $e) {
    badRequest($requestType, $url, $body);
}

?>