<div class="baseHeader">
	<h1>Fahrzeug Übergabe</h1>
</div>

<div class="mainView">
	<div class="viewHeader">
		<div class="left">
			<div id="sucheUebergabe">Fahrzeug suchen</div>
			<div>Kennzeichen<input id="inputUebergabe"></input></div>
		</div>
		<div class="right">
			<div id="sucheUebergabe">an Fahrer</div>
			<div>Fahrer Nachname<input id="inputUebergabe"></input></div>
		</div>
		<button id="uebergabeButton">Übergabe anlegen</button>
	</div>
	<div class="viewTable">
		<div class="left">
			<div class="designation">
				<div class="text">Überlassung-ID</div>
				<div class="text">Übergabedatum</div>
				<div class="text">Übergabe KM-Stand</div>
				<div class="text">Übergabe durch</div>
			</div>
			<div class="dropdown">
				<div><input class="input" size="25"/></div>
				<div><input class="input" size="25"/></div>
				<div><input class="input" size="25"/></div>
				<div><input class="input" size="30"/></div>
			</div>
		</div>
		<div class="right">
			<div class="designation">
				<div class="text">Übergabe Sommerreifen</div>
				<div class="text">Übergabe Winterreifen</div>
				<div class="text">Übergabe Schlüssel</div>
			</div>
			<div class="dropdown">
				<div><input class="input" size="25"/></div>
				<div><input class="input" size="25"/></div>
				<div><input class="input" size="10 "/></div>
			</div>
		</div>
	</div>
	<div class="viewButtons">
		<button class="Buttons" id="change" onclick="change(0)">Ändern</button>
		<button class="Buttons" id="cancel" onclick="change(1)">Abbrechen</button>
		<button class="Buttons">Neue Übergabe</button>
		<button class="Buttons">Speicher</button>
		<button class="Buttons">Löschen</button>
		<button class="Buttons">Übergabe drucken</button>
	</div>
	<div class="viewFooter">
		<div class="left">
			<div class="designation">
				<div class="text">KFZ-ID</div>
				<div class="text">Leasinggeber</div>
				<div class="text" class="text">Kennzeichen</div>
				<div class="text">Fahrgestellnummer</div>
				<div class="text">Nächste HU</div>
				<div class="text">Leasing Ende</div>
				<div class="text">Leasing Laufleistung</div>
				<div class="text">Brutto-Listenpreis</div>
			</div>
			<div class="dropdown">
				<div><input class="input" size="25"/></div>
				<div><input class="input" size="25"/></div>
				<div><input class="input" size="30"/></div>
				<div><input class="input" size="30"/></div>
				<div><input class="input" size="25"/></div>
				<div><input class="input" size="25"/></div>
				<div><input class="input" size="30"/></div>
				<div><input class="input" size="30"/></div>
			</div>
		</div>
		<div class="right">
			<div class="designation">
				<div class="text">Fahrer-ID</div>
				<div class="text">Anrede</div>
				<div class="text">Vorname</div>
				<div class="text">Nachname</div>
				<div class="text">Straße</div>
				<div class="text">Hausnummer</div>
				<div class="text">PLZ</div>
				<div class="text">Ort</div>
				<div class="text">Firma</div>
			</div>
			<div class="dropdown">
				<div><input class="input" size="25"/></div>
				<div><input class="input" size="25"/></div>
				<div><input class="input" size="30"/></div>
				<div><input class="input" size="30"/></div>
				<div><input class="input" size="25"/></div>
				<div><input class="input" size="25"/></div>
				<div><input class="input" size="30"/></div>
				<div><input class="input" size="30"/></div>
				<div><input class="input" size="30"/></div>
			</div>
		</div>
	</div>
	<script>
		function change (x) {
			if(x == 0) {
    			const change = document.querySelectorAll(".input");
    			change.forEach(input => {
    				input.style.pointerEvents = 'auto';
    			});
    			document.getElementById("change").style.display = "none";
    			document.getElementById("cancel").style.display = "block";
    			
			} else if (x == 1) {
				const change = document.querySelectorAll(".input");
    			change.forEach(input => {
    				input.style.pointerEvents = 'none';
    			});
    			document.getElementById("change").style.display = "block";
    			document.getElementById("cancel").style.display = "none";
			}
			
			
		}
	</script>
</div>