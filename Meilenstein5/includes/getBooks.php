<?php

/*Content Type wird auf JSON gesetzt damit der Client weiß, dass es 
 * sich um ein JSON File handelt.
 */
header('Content-Type: application/json');

//Die Request Art wird in einer Variable gespeichert 
$json=$_GET["json"];

/*Der Pfad zur JSON Datei wird in einer Variablen gespeichert.
 *Entweder wird die Horror oder die Roman JSON Datei gespeichert.
 */
$filename = 'script/'.$json.'_books.json';

/*Überprüfung ob das gewünschte File existiert. Falls ja werden die Inhalte in einer
 *JSON Datei gespeichert
 */
if (file_exists($filename)) {
	$json_obj = file_get_contents($filename);
	
	//Übergabe die Datei an den Client (Response)
	echo $json_obj;
}

?>