<?php

/*Die Ressourcen der Dateneingabe für die Bücher werden angefordert,
 * dekodiert und in Variablen gespeichert
 */

if ($_SERVER["REQUEST_METHOD"] == "GET") {
	$autor = $_GET['autor'];
	$titel = $_GET['titel'];
	$kapitel = $_GET['kapitel'] . " Kapitel";
	$buchart = $_GET['art'];
	$isbn = $_GET['isbn'];
	$erscheinungsjahr = $_GET['jahr'];
	$auflage = $_GET['auflage'] . ". Auflage";

	
	//Daten werden in einer zusammenhängenden Textdatei gespeichert.
	$daten = $autor . ", " . $titel . ", " . $kapitel . ", " . $buchart
	          . ", " . $isbn . ", " . $erscheinungsjahr . ", "
	          . $auflage . ";" . "\n" . "\n";
	
	//Die zu beschreibende Textdatei wird initialisiert
	$my_file = 'books.txt';
	
	//Textdatei wird geöffnet
	$handle = fopen($my_file, 'a') or die('Cannot open file:  '.$my_file); 

	//Daten werden in die Textdatei geschrieben
	fwrite($handle, $daten);
	
}

?>