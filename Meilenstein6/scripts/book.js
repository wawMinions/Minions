//Funtkion um die Horror Bücher auszulesen
function initialize() {
	getJSON('horror');
}


/*
 * Ändert die Hintergrundfarbe des Buttons, je nachdem welche Bücher gerade
 * angezeigt werden und ruft die passende Bücher vom Client ab.
 */
function tabSwitch(genre) {

	switch(genre) {
	case 1: 
		document.getElementById('horror').style.backgroundColor = "rgb(63, 72, 204)";
		document.getElementById('roman').style.backgroundColor = "rgb(0, 162, 232)";
		getJSON('horror');
		break;


	case 2:	
		document.getElementById('roman').style.backgroundColor = "rgb(63, 72, 204)";
		document.getElementById('horror').style.backgroundColor = "rgb(0, 162, 232)";
		getJSON('roman');
		break;
	}


}

/*
 * Laedt die Daten aus den JSON Dateien und schreibt sie in eine Tabelle.
 */
function loadData(jsonData) {

	jsonToHTML = "<table class='inventoryTable'>" +
	"<tr><th>Autor</th><th>Titel</th><th>Kapitel</th>" +
	"<th>Art des Buches</th><th>ISBN</th>" +
	"<th>Erscheinungsjahr</th><th>Auflage</th></tr>";

	for(i=0;i<jsonData.bookdata.length;i++) {
		jsonToHTML= jsonToHTML + 
		"<tr><td>"+ jsonData.bookdata[i].autor+"</td>" +
		"<td>"+ jsonData.bookdata[i].titel+"</td>" +
		"<td>"+ jsonData.bookdata[i].kapitel+"</td>" +
		"<td>"+ jsonData.bookdata[i].buchart+"</td>" +
		"<td>"+ jsonData.bookdata[i].ISBN+"</td>" +
		"<td>"+ jsonData.bookdata[i].erscheinungsjahr+"</td>" +
		"<td>"+ jsonData.bookdata[i].auflage+"</td></tr>";

	}
	jsonToHTML= jsonToHTML + "</table>";

}




function getJSON(book_genre) {
	//Die passende php Datei für den Serveraufruf wird bekannt gemacht.
	var url = "getBooks.php";
	var parameter = "json="+book_genre;

	//Checkt ob kein Genre angegeben wurde
	if (book_genre=="") {
		document.getElementById("inventoryTable").innerHTML="";
		return;
	} 
	if (window.XMLHttpRequest) {
		//Ein neues XMLHTTPRequest Object wird angelegt.
		myRequest=new XMLHttpRequest();
	} 

	myRequest.onreadystatechange=function() {
		if (myRequest.readyState==4 && myRequest.status==200) { 	//Prüft ob die Anfrage akzeptiert wurde
			var jsonString = myRequest.responseText;  //Die Antwortnachricht wird in einem String gespeichert
			var jsonData = JSON.parse(jsonString);	//String wird in eine Json Datei umgewandelt.
			loadData(jsonData);						//JSON wird der Methode übergebenm, die eine Tabelle ausgibt
			document.getElementById("inventoryTable").innerHTML=jsonToHTML;
		}
	}

	
	myRequest.open("GET", "getBooks.php?json="+book_genre, true); //Request wird erstellt
	myRequest.setRequestHeader('Content-Type', 'application/json');
	myRequest.send(); //Request wird gesendet
}
