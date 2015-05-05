function formChecker(form, vorname, nachname) {

	document.getElementById(vorname.id).style.borderColor = "";
	document.getElementById(nachname.id).style.borderColor = "";
	document.getElementById(autor.id).style.borderColor = "";
	

	regex = /^[\sa-zA-ZßäöüÄÖÜ]+$/; //Ueberpueft den angegebenen Vornamen auf korrekte Syntax
	if(!regex.test(vorname.value)) {
		return getMessage(vorname);
	}

	if(!regex.test(nachname.value)) { //Ueberpueft den angegebenen Nachnamen auf korrekte Syntax
		return getMessage(nachname);
	}
	
	if(!regex.test(autor.value)) { //Ueberpueft den angegebenen Autor auf korrekte Syntax
		return getMessage(autor);
	}
	
	if(titel.value == "") { //Ueberpueft den angegebenen Titel auf korrekte Syntax
		return getMessage(titel);
	}
	
	regex = /^[0-9]+$/;
	if((!regex.test(isbn.value)) || (isbn.value.length != 13)) { //Ueberpueft die angegebene ISBN auf korrekte Syntax
		return getMessage(isbn);
	}
	
	DatumAktuell = new Date(); //holt sich das aktuelle Datum
	JahrAktuell = DatumAktuell.getFullYear();  //speichert das Jahr in einer Variable
	if((!regex.test(jahr.value)) || (jahr.value.length != 4) || jahr.value < 0 || jahr.value > JahrAktuell) { //Ueberprueft das angegebene Jahr auf korrekte Syntax
		return getMessage(jahr);
	}
	
	if(!regex.test(auflage.value) || auflage.value < 0) { //Ueberprueft, ob die Auflage den Syntaxregeln entspricht
		return getMessage(auflage);
	}
} 




function getMessage(bezeichnung) {
	alert(unescape("Einige Eingaben sind fehlerhaft. Bitte %FCberpr%FCfen Sie ihre Eingaben"));
	bezeichnung.focus();
	document.getElementById(bezeichnung.id).style.borderColor = "red";
	return false;	
}