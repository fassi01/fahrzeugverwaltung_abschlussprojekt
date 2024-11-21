<div class="header">
	<div class="headerRight">
		<a href=login/logout>Abmelden</a>
		<a href="Konto">Konto</a>
	</div>

	<div class="user">
		<!--Die Mailadresse des angemeldeten Nutzers wird im header angezeigt -->
		<?= session()->get('u_mail'); ?>
	</div>
</div>
<div class="topnav" id="myTopnav">
	<a href="Home" class="active">Home</a>
	<a href="FahrzeugeVerwalten">Fahrzeuge verwalten</a>
	<a href="FahrerStammdaten">Fahrer Stammdaten</a>
	<div class="dropdown">
		<button class="dropbtn">Überlassungen
			<i class="fa fa-caret-down"></i>
		</button>
		<div class="dropdown-content">
			<a href="FahrzeugUebergabe">Fahrzeug Übergabe</a>
			<a href="FahrzeugRueckgabe">Fahrzeug Rückgabe</a>
		</div>
	</div>
	<div class="dropdown">
		<button class="dropbtn">Berichte
			<i class="fa fa-caret-down"></i>
		</button>
		<div class="dropdown-content">
			<a href="BerichtErstellen">Bericht erstellen</a>
			<a href="BerichtOrdnungswidrigkeit">Bericht Ordnungswidrigkeit</a>
			<a href="BerichtPersonalabteilung">Bericht für Personalabteilung</a>
		</div>
	</div>
	<div class="dropdown">
		<button class="dropbtn">Benutzerverwaltung
			<i class="fa fa-caret-down"></i>
		</button>
		<div class="dropdown-content">
			<a href="UserList">Benutzerübersicht</a>
			<a href="UserAuthRoleList">Berechtigungen</a>
		</div>
	</div>

	<a href="javascript:void(0);" class="icon" onclick="myFunction()">&#8803;</a>
</div>

<script>
function myFunction() {
  var x = document.getElementById("myTopnav");
  if (x.className === "topnav") {
    x.className += " responsive";
  } else {
    x.className = "topnav";
  }
}
</script>