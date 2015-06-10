function formChecker(form, vorname, nachname, titel, autor, isbn, jahr, auflage) {
/*
 * Bei einer weiteren Syntaxpruefung werden alle zuvor rot markierten Felder
 * auf den Initialzustand zurueck gesetzt.
 */

	var error = false;
	var counter = 1;
	
	document.getElementById(vorname.id).style.borderColor = "";
	document.getElementById(nachname.id).style.borderColor = "";
	document.getElementById(autor.id).style.borderColor = "";
	document.getElementById(isbn.id).style.borderColor="";
	document.getElementById(jahr.id).style.borderColor="";
	document.getElementById(auflage.id).style.borderColor="";
	

	var syntaxRule = /^[\sa-zA-ZßäöüÄÖÜ]+$/; //Ueberpueft den angegebenen Vornamen auf korrekte Syntax
	if(!syntaxRule.test(vorname.value)) {
		counter = getMessage(vorname, counter);
	}

	if(!syntaxRule.test(nachname.value)) { //Ueberpueft den angegebenen Nachnamen auf korrekte Syntax
		counter = getMessage(nachname, counter);
	}
	
	if(!syntaxRule.test(autor.value)) { //Ueberpueft den angegebenen Autor auf korrekte Syntax
		counter = getMessage(autor, counter);
	}
	
	if(titel.value == "") { //Ueberpueft den angegebenen Titel auf korrekte Syntax
		counter = getMessage(titel, counter);
	}
	
	syntaxRule = /^[0-9]+$/;
	if((!syntaxRule.test(isbn.value)) || (isbn.value.length != 13)) { //Ueberpueft die angegebene ISBN auf korrekte Syntax
		counter = getMessage(isbn, counter);
	}
	
	DatumAktuell = new Date(); //holt sich das aktuelle Datum
	JahrAktuell = DatumAktuell.getFullYear();  //speichert das Jahr in einer Variable
	if((!syntaxRule.test(jahr.value)) || (jahr.value.length != 4) || jahr.value < 0 || jahr.value > JahrAktuell) { //Ueberprueft das angegebene Jahr auf korrekte Syntax
		counter = getMessage(jahr, counter);
	}
	
	if(!syntaxRule.test(auflage.value) || auflage.value < 0) { //Ueberprueft, ob die Auflage den Syntaxregeln entspricht
		counter = getMessage(auflage, counter);
	}
	
	if(counter == 1) {
		error = false;
	}
	else {
		error = true;
	}
	
	return !error
} 

	

/*
 * Funktion um fehlerhafte Felder rot zu markieren und den Fokus auf das erste fehlerhafte
 * Feld zu setzen.
 */
function getMessage(bezeichnung, zaehler) {
	if(zaehler == 1) {
	alert(unescape("Einige Eingaben sind fehlerhaft. Bitte %FCberpr%FCfen Sie ihre Eingaben"));
	bezeichnung.focus();
	zaehler++;
	}
	document.getElementById(bezeichnung.id).style.borderColor = "red"; //markiert die Felder rot
	return zaehler;	
}