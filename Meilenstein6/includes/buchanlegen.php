<?php

//Speicherung der Anmeldedaten für den Server in Variablen

$host = "localhost";
$user = "Daniel";
$pwd = "wertherw";
$dbName = "myBooks";

//Eine Verbindung zum SQL Server erstellen

$con = new MySQLi ( $host, $user, $pwd, $dbName );
$con->set_charset ( "utf8" );

/*Die Ressourcen der Dateneingabe für die Bücher werden angefordert,
 * dekodiert und in Variablen gespeichert
 */

if ($_SERVER ["REQUEST_METHOD"] == "GET") {
	
	$vorname = $_GET ['vorname'];
	$name = $_GET ['nachname'];
	$autor = $_GET ['autor'];
	$titel = $_GET ['titel'];
	$kapitel = $_GET ['kapitel'];
	$buchart = $_GET ['art'];
	$isbn = $_GET ['isbn'];
	$erscheinungsjahr = $_GET ['jahr'];
	$auflage = $_GET ['auflage'];
	$genre = $_GET ['genre'];
	
	/*
	 * Überprüft, ob die Checkbox Favorit gesetzt ist oder nicht und speichert
	 * den passenden String in einer Variable
	 */
	
	if (isset ( $_GET ['buchfavorit'] )) {
		$favorit = 'Ja';
	} else {
		$favorit = 'Nein';
	}
	
	/*Überprüfung der Syntax der Eingaben. Ist eine Eingabe fehlerhaft, wird
	 * die Variable error auf true gesetzt und es wird eine Fehlermeldung ausgegeben
	 */
	
	$error = false;
	
	$syntaxRule = '/^[a-zA-ZäöüÄÖÜß ]+$/'; // Ueberpueft den angegebenen Vornamen auf korrekte Syntax
	if (! preg_match ( $syntaxRule, $vorname ) || $vorname == "") {
		$error = true;
	}
	
	if (! preg_match ( $syntaxRule, $name ) || $name == "") { // Ueberpueft den angegebenen Nachnamen auf korrekte Syntax
		$error = true;
	}
	
	if (! preg_match ( $syntaxRule, $autor ) || $autor == "") { // Ueberpueft den angegebenen Autor auf korrekte Syntax
		$error = true;
	}
	
	if (! preg_match ( $syntaxRule, $titel ) || $titel == "") { // Ueberpueft den angegebenen Titel auf korrekte Syntax
		$error = true;
	}
	
	$syntaxRule = "/^[0-9]+$/";
	if (! preg_match ( $syntaxRule, $isbn ) || (strlen ( $isbn ) != 13) || $isbn == "") { // Ueberpueft die angegebene ISBN auf korrekte Syntax
		$error = true;
	}
	
	if (! preg_match ( $syntaxRule, $erscheinungsjahr ) || strlen ( $erscheinungsjahr ) != 4 || $erscheinungsjahr < 1000 || $erscheinungsjahr > date ( "Y" ) || $erscheinungsjahr == "") { // Ueberprueft das angegebene Jahr auf korrekte Syntax
		$error = true;
	}
	
	if (! preg_match ( $syntaxRule, $auflage ) || $auflage < 0 || $auflage == "") { // Ueberprueft, ob die Auflage den Syntaxregeln entspricht
		$error = true;
	}
	
	if ($error) {
		exit("Fehler, die eingegebenen Werte sind nicht in Ordnung");
	} 
	
	//$titel = utf8_decode($titel);

	/*Abfrage, in der Datenbank, ob zu angegebenem Benutzernamen bereits eine user ID
	 * vorhanden ist. Ist dies der Fall, wird die userID gespeichert.
	 */

		$check_user = $con->prepare ( "SELECT userID FROM myBooks.user WHERE Vorname = ? AND Nachname = ?;" );
		$check_user->bind_param ( 'ss', $vorname, $name );
		$check_user->execute ();
		$check_user->store_result ();

		
		if ($check_user->num_rows >= 1) {
			
			$check_user->bind_result ( $userID );
			$check_user->fetch();
			$check_user->close();
			
		} else {
			
			/*
			 * Anlegen eines neuen Benutzers in der Datenbank
			 */
		
			$insert_user = $con->prepare ( "INSERT INTO myBooks.user (vorname, nachname) VALUES (?, ?)" );
			$insert_user->bind_param ( 'ss', $vorname, $name );
			$insert_user->execute ();
			$insert_user->close ();
			
			$getID = $con->prepare ( "SELECT userID ISBN FROM myBooks.user WHERE Vorname = ? AND Nachname = ?;" );
			$getID->bind_param ( 'ss', $vorname, $name );
			$getID->execute ();
			$getID->store_result ();
			
			/*
			 * Die User ID des neuen Nutzers wird abgefragt und gepspeichert.
			 */
			
			if ($getID->num_rows >= 1) {
				
				$getID->bind_result ( $userID );
				$getID->fetch();
			}
			$getID->close();
		}
		
		/*
		 * Abfrage, ob der eingetragene User das selbe Buch bereits ausgeliehen hat.
		 * Ist dies der Fall, wird eine Fehlermeldung ausgegeben und das Programmm beendet.
		 */
		
		$check_user_book = $con->prepare ( "SELECT ISBN, userID FROM myBooks.ausleihe WHERE ISBN = ? AND userID = ?;" );
		$check_user_book->bind_param ( 'si', $isbn, $userID );
		$check_user_book->execute ();
		$check_user_book->store_result ();


		if($check_user_book->num_rows >= 1) {
			
			exit ("Dieser User hat das dieser Buch bereits ausgeliehen");
		}
		else {
			
			/*Überprüfung, ob das Buch bereits in der Datenbank enthalten ist. Falls ja, wird
			 * es nicht erneut in der Tabelle Buch gespeichert, falls nein wird ein neuer
			 * Eintrag erzeugt
			 */
		
			$check_book = $con->prepare ( "SELECT ISBN FROM myBooks.buch WHERE ISBN = ?;" );
			$check_book ->bind_param ('s', $isbn);
			$check_book ->execute();
			$check_book ->store_result ();
			
			if($check_book->num_rows == 0) {
				
				$new_book = $con->prepare ( "INSERT INTO myBooks.buch (Autor, Titel, Kapitel, Buchart, ISBN, Erscheinungsjahr, Auflage, Genre)
											VALUES (?,?,?,?,?,?,?,?);" );
				$new_book->bind_param ('ssssssss', $autor, $titel, $kapitel, $buchart, $isbn, $erscheinungsjahr, $auflage, $genre );
				$new_book->execute ();
				$new_book->close();
			}
			
			/*
			 * Ein neuer Eintrag in der Tabelle ausleihe wird erzeugt. Zusätzlich wird das
			 * Attribut Favorit in dieser Tabelle gespeichert.
			 */
			
			$new_entry = $con->prepare ( "INSERT INTO myBooks.ausleihe (ISBN, userID, Favorit) VALUES (?, ?, ?);" );
			$new_entry->bind_param ( 'sis', $isbn, $userID, $favorit);
			$new_entry->execute ();
			$new_entry->close();
			
			
		}
		
}	
	print '<br>Datensatz hinzugefügt<br/>';
	print '<a href="javascript:history.back()">Zur&uuml;ck zu Buch anlegen</a>';
	$con->close (); // Die Verbindung wird geschlossen
	

?>