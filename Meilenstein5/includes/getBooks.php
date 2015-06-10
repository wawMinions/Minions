<?php
// JSON Definition
header('Content-Type: application/json');
// json Request
$json=$_GET["json"];
// Pfad zur JSON Objekt-Datei
$filename = 'script/'.$json.'_books.json';
// Daten werden ausgewertet und JSON-Datei wird ausgegeben. Im Fehlerfalle Fehlermeldung.
if (file_exists($filename)) {
	$json_obj = file_get_contents($filename);
	
	echo $json_obj;
}

?>