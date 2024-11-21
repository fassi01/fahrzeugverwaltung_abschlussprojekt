<div class="baseHeader">
	<h1>Fahrer Stammdaten</h1>
</div>

<div class="mainView">
	<div class="viewHeader">
		<div id="suche">Suchen</div>
		<div>
			Fahrer<input id="sucheFahrer"></input>
		</div>
	</div>
	<div class="viewTable">
		<div class="left">
			<div class="designation">
				<div class="text">Fahrer-ID</div>
				<div class="text">Pers. Nr.</div>
				<div class="text">Anrede</div>
				<div class="text">Vorname</div>
				<div class="text">Nachname</div>
				<div class="text">Straße</div>
				<div class="text">Hausnr.</div>
				<div class="text">PDL</div>
				<div class="text">Ort</div>
				<div class="text">Firma</div>
				<div class="text">KST</div>
				<div class="text">Innenauftrag</div>
				<div class="text">Anlagen</div>
			</div>
			<div class="dropdown">
				<div id="kfzID">
					<input class="input" size="10" />KFZ_ID<input class="input"
						id="inputKFZ_ID" size="10" />
				</div>
				<div>
					<input class="input" size="25" />
				</div>
				<div>
					<input class="input" size="30 " />
				</div>
				<div>
					<input class="input" size="30" />
				</div>
				<div>
					<input class="input" size="30" />
				</div>
				<div>
					<input class="input" size="10" />
				</div>
				<div>
					<input class="input" size="10" />
				</div>
				<div>
					<input class="input" size="10" />
				</div>
				<div>
					<input class="input" type="date" />
				</div>
				<div>
					<input class="input" type="date" />
				</div>
				<div>
					<input class="input" size="10" />
				</div>
				<div>
					<input class="input" size="10" />
				</div>
				<div>
					<input class="input" size="10" />
				</div>
			</div>
		</div>
		<div class="right">
			<div class="designation">
				<div class="text">Telefon</div>
				<div class="text">Handy</div>
				<div class="text">Email</div>
				<div class="text">Status aktiv</div>
				<div class="text">DKV Kartennummer</div>
				<div class="text">KDV Karte gesperrt</div>
				<div class="text">Führerscheinnummer</div>
				<div class="text">Führerschein Ablaufdatum</div>
				<div class="text">Letzte Führerschein-Kontrolle</div>
				<div class="text">Kontrolle durch</div>
				<div class="text">Letzte UVV-Unterweisung</div>
				<div class="text" id="fahrzeugInaktiv">Kennzeichnung gelöscht</div>
			</div>
			<div class="dropdown">
				<div>
					<input class="input" size="10" type="date" />
				</div>
				<div>
					<input id="inputPS" class="input" size="10" />PS<input id="inputKW"
						class="input" size="10" />KW
				</div>
				<div>
					<input class="input" size="10 " />
				</div>
				<div>
					<input class="input" size="10" type="number" /><span>€</span>
				</div>
				<div>
					<input class="input" type="date" />
				</div>
				<div>
					<input class="input" size="10" />
				</div>
				<div>
					<input class="input" size="10" />
				</div>
				<div>
					<input class="input" size="10" />
				</div>
				<div>
					<input id="fahrzeugButton" type="checkbox"></input>
				</div>
				<div>
					<input class="input" size="10" />
				</div>
				<div>
					<input class="input" size="10" />
				</div>
				<div>
					<input class="input" size="10" />
				</div>
			</div>
		</div>
	</div>
	<div class="viewButtons">
		<button id="arrowLeft" class="Buttons">&#9668;</button>
		<button id="arrowRight" class="Buttons">&#9658;</button>
		<button class="Buttons" id="change" onclick="change(0)">Ändern</button>
		<button class="Buttons" id="cancel" onclick="change(1)">Abbrechen</button>
		<button class="Buttons">Neues Fahrzeug</button>
		<button class="Buttons">Speicher</button>
		<button class="Buttons">Löschen</button>
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