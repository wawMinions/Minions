<?php
/*
 * if ($_SERVER ["REQUEST_METHOD"] == "GET") {
 * $autor = $_GET ['autor'];
 * $titel = $_GET ['titel'];
 * $kapitel = $_GET ['kapitel'] . " Kapitel";
 * $buchart = $_GET ['art'];
 * $isbn = $_GET ['isbn'];
 * $erscheinungsjahr = $_GET ['jahr'];
 * $auflage = $_GET ['auflage'] . ". Auflage";
 *
 * $daten = $autor . ", " . $titel . ", " . $kapitel . ", " . $buchart . ", " . $isbn . ", " . $erscheinungsjahr . ", " . $auflage . ";" . "\n" . "\n";
 *
 * $my_file = 'books.txt';
 * $handle = fopen ( $my_file, 'a' ) or die ( 'Cannot open file: ' . $my_file );
 * fwrite ( $handle, $daten );
 * }
 */

$host = "localhost";
$user = "Daniel";
$pwd = "wertherw";
$dbName = "myBooks";

$con = new MySQLi ( $host, $user, $pwd, $dbName );
$con->set_charset ( "UTF-8" );

if ($_SERVER ["REQUEST_METHOD"] == "GET") {
	
	$vorname = utf8_decode($_GET ['vorname']);
	$name = utf8_decode($_GET ['nachname']);
	$autor = utf8_decode($_GET ['autor']);
	$titel = utf8_decode($_GET ['titel']);
	$kapitel = $_GET ['kapitel'];
	$buchart = $_GET ['art'];
	$isbn = $_GET ['isbn'];
	$erscheinungsjahr = $_GET ['jahr'];
	$auflage = $_GET ['auflage'];
	$genre = utf8_decode($_GET ['genre']);
	

	if (isset($_GET ['favorit'])) {
		$favorit = 'Ja';
	} else {
		$favorit = 'Nein';
	}
	
	$error = false;
	
	$syntaxRule = '/^[a-zA-ZäöüÄÖÜß ]+$/'; // Ueberpueft den angegebenen Vornamen auf korrekte Syntax
	if (!preg_match( $syntaxRule, $vorname) || $vorname=="") {
		$error = true;
	}
	
	if (!preg_match( $syntaxRule, $name) || $name=="") { // Ueberpueft den angegebenen Nachnamen auf korrekte Syntax
		$error = true;
	}
	
	if (!preg_match( $syntaxRule, $autor) || $autor=="") { // Ueberpueft den angegebenen Autor auf korrekte Syntax
		$error = true;
	}
	
	if (!preg_match( $syntaxRule, $titel) || $titel=="") { // Ueberpueft den angegebenen Titel auf korrekte Syntax
		$error = true;
	}
	
	$syntaxRule = "/^[0-9]+$/";
	if (!preg_match( $syntaxRule, $isbn) || (strlen($isbn) != 13) || $isbn=="" ) { // Ueberpueft die angegebene ISBN auf korrekte Syntax
		$error = true;
	}
	
	
	if (!preg_match($syntaxRule, $erscheinungsjahr) || strlen($erscheinungsjahr)!=4 || $erscheinungsjahr<1000 || $erscheinungsjahr > date("Y") || $erscheinungsjahr=="") { // Ueberprueft das angegebene Jahr auf korrekte Syntax
		$error = true;
	}
	
	if (!preg_match($syntaxRule, $auflage) || $auflage < 0 || $auflage=="") { // Ueberprueft, ob die Auflage den Syntaxregeln entspricht
		$error = true;
	}
	
	$check = $con->prepare ( "SELECT Vorname, Nachname, ISBN FROM myBooks.buch_user WHERE Vorname = ? AND Nachname = ? AND ISBN = ?;" );
	$check->bind_param ( 'sss', $vorname, $name, $isbn );
	$check->execute ();
	$check->store_result ();
	
	if ($error) {
		print "Fehler, die eingegebenen Werte sind nicht in Ordnung";
	}
	else if ($check->num_rows >= 1) {
		// Der Benutzer und dieses Buch sind bereits in der Datenbank
		print "Fehler, Benutzer und dieses Buch sind bereits in der Datenbank";
	} else {
		$eintrag = $con->prepare ( "INSERT INTO myBooks.buch_user
					(Vorname, Nachname, Favorit, Autor, Titel, Kapitel, Buchart, ISBN, Erscheinungsjahr, Auflage, Genre)
					VALUES (?,?,?,?,?,?,?,?,?,?,?);");
		$eintrag->bind_param ( 'sssssssssss', $vorname, $name, $favorit,$autor,$titel,$kapitel,$buchart,$isbn,$erscheinungsjahr,$auflage,$genre);
		$eintrag->execute ();
		$eintrag->close ();
	}
	$check->close ();

	$con->close ();
}

?>