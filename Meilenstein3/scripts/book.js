function initialize() {
	getJSON('horror');
}


/*
 * Wechselt die Sichtbarkeit zwischen den beiden Tabellen.
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

	jsonHTMLFormat = "<table class='inventoryTable'>" +
	"<tr><th>Autor</th><th>Titel</th><th>Kapitel</th>" +
	"<th>Art des Buches</th><th>ISBN</th>" +
	"<th>Erscheinungsjahr</th><th>Auflage</th></tr>";

	for(i=0;i<jsonData.bookdata.length;i++) {
		jsonHTMLFormat= jsonHTMLFormat + 
		"<tr><td>"+ jsonData.bookdata[i].autor+"</td>" +
		"<td>"+ jsonData.bookdata[i].titel+"</td>" +
		"<td>"+ jsonData.bookdata[i].kapitel+"</td>" +
		"<td>"+ jsonData.bookdata[i].buchart+"</td>" +
		"<td>"+ jsonData.bookdata[i].ISBN+"</td>" +
		"<td>"+ jsonData.bookdata[i].erscheinungsjahr+"</td>" +
		"<td>"+ jsonData.bookdata[i].auflage+"</td></tr>";

	}
	jsonHTMLFormat= jsonHTMLFormat + "</table>";

}




function getJSON(book_genre) {
	var url = "getBooks.php";
	var parameter = "json="+book_genre;

	if (book_genre=="") {
		document.getElementById("inventoryTable").innerHTML="";
		return;
	} 
	if (window.XMLHttpRequest) {
		myRequest=new XMLHttpRequest();
	} else {  // old browser compatibility (IE5, IE6)
		myRequest=new ActiveXObject("Microsoft.XMLHTTP");
	}
	myRequest.onreadystatechange=function() {
		if (myRequest.readyState==4 && myRequest.status==200) {
			var jsonString = myRequest.responseText;
			var jsonData = JSON.parse(jsonString);
			loadData(jsonData);
			document.getElementById("inventoryTable").innerHTML=jsonHTMLFormat;
		}
	}


	myRequest.open("GET", "getBooks.php?json="+book_genre, true);
	myRequest.setRequestHeader('Content-Type', 'application/json');
	myRequest.send();
}
