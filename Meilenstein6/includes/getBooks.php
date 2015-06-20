<?php

/*Content Type wird auf JSON gesetzt damit der Client weiß, dass es 
 * sich um ein JSON File handelt.
 */
header('Content-Type: application/json');

//Die Request Art wird in einer Variable gespeichert 
$json= $_GET["json"];

//Speicherung der Anmeldedaten für den Server in Variablen

	$host = "localhost";
	$user = "Daniel";
	$pwd = "wertherw";
	$dbName = "myBooks";
	
	
	//Eine Verbindung zum SQL Server erstellen
	$con = new MySQLi( $host, $user, $pwd, $dbName );
	$con->set_charset ( "utf8" );
	
	/*
	 * Auswertung, ob die Horror Bücher oder die Romane angezeigt werden sollen.
	 * Die dementsprechende SQL Abfrage wird erstellt.
	 */
	
	if ($json == 'horror') {
		$abfrage = "SELECT autor,titel, kapitel, buchart, ISBN, erscheinungsjahr, auflage FROM myBooks.buch WHERE genre = 'horror'";
	}
	else {
		$abfrage = "SELECT autor,titel, kapitel, buchart, ISBN, erscheinungsjahr, auflage FROM myBooks.buch WHERE genre = 'roman'";
	}

	//Speicherung des Ergebnises der SQL Abfrage
	
	$ergebnis = mysqli_query($con, $abfrage);
	
	$output = array();
	
	//Das Ergebnis der SQL Abfrage wird Ausgelesen und in ein Array gesoeichert.
	while($row=mysqli_fetch_assoc($ergebnis)) 
		 $output[]=$row;
	
	//liefert alle Werte des Arrays array mit einem numerischen Index.
	$out = array_values($output);
	
	//Das Ergebnis wird in eine JSON Datei umgewandelt/encodiert.
	$out = json_encode($out);
		
		$json_final = '{ "bookdata": '.$out.'}';
		
	//JSON Datei wird an den aufrufenden Client zurück gegeben.	
	echo $json_final;	

	//SQL Verbindung wird geschlossen
	$con->close();


?>