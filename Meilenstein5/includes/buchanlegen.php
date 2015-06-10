<?php

if ($_SERVER["REQUEST_METHOD"] == "GET") {
	$autor = $_GET['autor'];
	$titel = $_GET['titel'];
	$kapitel = $_GET['kapitel'] . " Kapitel";
	$buchart = $_GET['art'];
	$isbn = $_GET['isbn'];
	$erscheinungsjahr = $_GET['jahr'];
	$auflage = $_GET['auflage'] . ". Auflage";

	$daten = $autor . ", " . $titel . ", " . $kapitel . ", " . $buchart
	          . ", " . $isbn . ", " . $erscheinungsjahr . ", "
	          . $auflage . ";" . "\n" . "\n";
	
	$my_file = 'books.txt';
	$handle = fopen($my_file, 'a') or die('Cannot open file:  '.$my_file); 
	fwrite($handle, $daten);
	
}

?>