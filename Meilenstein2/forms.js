function formChecker(form, vorname, nachname, titel, autor, isbn, jahr, auflage) {
/*
 * Bei einer weiteren Syntaxpruefung werden alle zuvor rot markierten Felder
 * auf den Initialzustand zurueck gesetzt.
 */

	var fehler = false;
	
	document.getElementById(vorname.id).style.borderColor = "";
	document.getElementById(nachname.id).style.borderColor = "";
	document.getElementById(autor.id).style.borderColor = "";
	document.getElementById(isbn.id).style.borderColor="";
	document.getElementById(jahr.id).style.borderColor="";
	document.getElementById(auflage.id).style.borderColor="";
	

	regex = /^[\sa-zA-ZßäöüÄÖÜ]+$/; //Ueberpueft den angegebenen Vornamen auf korrekte Syntax
	if(!regex.test(vorname.value)) {
		fehler = getMessage(fehler, vorname);
	}

	if(!regex.test(nachname.value)) { //Ueberpueft den angegebenen Nachnamen auf korrekte Syntax
		fehler = getMessage(fehler, nachname);
	}
	
	if(!regex.test(autor.value)) { //Ueberpueft den angegebenen Autor auf korrekte Syntax
		fehler = getMessage(fehler, autor);
	}
	
	if(titel.value == "") { //Ueberpueft den angegebenen Titel auf korrekte Syntax
		fehler = getMessage(fehler, titel);
	}
	
	regex = /^[0-9]+$/;
	if((!regex.test(isbn.value)) || (isbn.value.length != 13)) { //Ueberpueft die angegebene ISBN auf korrekte Syntax
		fehler = getMessage(fehler, isbn);
	}
	
	DatumAktuell = new Date(); //holt sich das aktuelle Datum
	JahrAktuell = DatumAktuell.getFullYear();  //speichert das Jahr in einer Variable
	if((!regex.test(jahr.value)) || (jahr.value.length != 4) || jahr.value < 0 || jahr.value > JahrAktuell) { //Ueberprueft das angegebene Jahr auf korrekte Syntax
		fehler = getMessage(fehler, jahr);
	}
	
	if(!regex.test(auflage.value) || auflage.value < 0) { //Ueberprueft, ob die Auflage den Syntaxregeln entspricht
		fehler = getMessage(fehler, auflage);
	}
	
	return !fehler;
} 

	

/*
 * Funktion um fehlerhafte Felder rot zu markieren und den Fokus auf das erste fehlerhafte
 * Feld zu setzen.
 */
function getMessage(fehler, bezeichnung) {
	if(fehler == false) {
	alert(unescape("Einige Eingaben sind fehlerhaft. Bitte %FCberpr%FCfen Sie ihre Eingaben"));
	bezeichnung.focus();
	fehler = true;
	}
	document.getElementById(bezeichnung.id).style.borderColor = "red"; //markiert die Felder rot
	return true;	
}