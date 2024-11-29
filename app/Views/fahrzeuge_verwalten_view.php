<?php use App\Libraries\SiteAuth;
if (isset($fahrzeug)){
    
}

if (isset($_GET['modus'])){
    $modus = $_GET['modus'];
}else{
    $modus = '';
}
?>

<div class="baseHeader">
	<h1>Fahrzeuge Verwalten</h1>
</div>


<?php 
$valError = session()->getFlashdata('fahrzeugError') ? true : false;
$valOK = session()->getFlashdata('OK') ? true : false;
if($valError):?>
<div class="messageBoxError" readonly><?= session()->getFlashdata('fahrzeugError'); ?></div>
<?php elseif ($valOK):?>
<div class="messageBoxOk" readonly><?= session()->getFlashdata('OK'); ?></div>
<?php endif;?>

<div class="mainView">
<form id="myForm" action="fahrzeugeVerwalten" method="POST">
	<div class="viewHeader">
		<div id="suche">Suchen</div>
		<div>
			Fahrzeug Kennzeichen
<!-- 			<input id="fahrzeugKennzeichen"></input> -->
			<datalist id="dropDownFahrzeuge">
				<?php 
				foreach($kennzeichen as $kennung){
				    echo "<option class='kfz_id' id='" . $kennung['kfz_id'] . "' value='" . $kennung['kennzeichen'] ."'>";
				}	
				
				?>
            </datalist>            
			<input list="dropDownFahrzeuge" id="dropDownInput" name="dropDownFahrzeug">
<!-- 			<div id="inaktiveFahrzeuge"> -->
<!-- 				<input type="checkbox" name='checkActive' id='checkActive'></input> -->
<!-- 				Nur gelöschte/inaktive Fahrzeuge -->
<!-- 			</div> -->
		</div>
	</div>
	<div class="viewTable">
		<div class="left">
			<div class="description">
				<div class="text">Kennzeichen</div>
				<div class="text">Hersteller</div>
				<div class="text">Modell</div>
				<div class="text">KFZ-Details</div>
				<div class="text">Zusatzinformation</div>
				<div class="text">FIN</div>
				<div class="text">Art</div>
				<div class="text">Art der Beschaffung</div>
				<div class="text">Kaufdatum</div>
				<div class="text">Baujahr</div>
			</div>
			<div class="value">
			<?php   
		    echo "<div id='kfzID'><input class='input' size='10' name='kennzeichen' value='" . $fahrzeug['kennzeichen'] . "'/>
                KFZ_ID<input class='input' id='inputKFZ_ID' size='10' name='kfz_id' value='" . $fahrzeug['kfz_id'] . "'/></div>";
		    ?>
		    <select name="dropDownHersteller" id="dropDownHersteller" class="input">
                <?php
                /*Die Schleife vergleicht jeden Hersteller aus dem Array mit der ID, die ausgewählt wurde.
                 * Demzufolge wird dann auch der Hersteller in der Anwendung angezeigt.*/
                foreach($allData['hersteller'] as $hersteller) {
                    $selected = ($fahrzeug['hersteller_id'] == $hersteller['hersteller_id']) ? 'selected' : '';
                    echo "<option value='" . $hersteller['hersteller_id'] . "' $selected>" . $hersteller['hersteller_name'] . "</option>";
                }
                ?>
            </select>
            <br>
            <select name="dropDownModell" id="dropDownModell" class="input">
                <?php
                foreach($allData['modell'] as $modell) {
                    echo "<option value='" . $modell['modell_id'] . "' data-hersteller='" . $modell['hersteller_id'] . "' " . 
                         (($modell['modell_id'] == $fahrzeug['modell_id']) ? 'selected' : '') . ">" . 
                         $modell['modell_name'] . 
                         "</option>";
                }
                ?>
            </select>
<!--             <button class='buttonNew' type='button'>Neu</button> -->
            <br>
            <select name="dropDownDetails" id="dropDownDetails" class='input'>
				<?php 
    				foreach($allData['kfz_details'] as $details){
    				        $selected = '';
    				        if($details['kfz_details_id'] == $fahrzeug['kfz_details_id']) $selected = 'selected';
    				        echo "<option value='" . $details['kfz_details_id'] . "' " . $selected . ">" . $details['kfz_details_name'] . "</option>";
    				}
				?>
            </select>
            <br>
            <select name="dropDownInformation" id="dropDownInformation" class='input'>
				<?php 
    				foreach($allData['zusatzinformationen'] as $inforamtion){
    				        $selected = '';
    				        if($inforamtion['zusatzinformationen_id'] == $fahrzeug['zusatzinformationen_id']) $selected = 'selected';
    				        echo "<option value='" . $inforamtion['zusatzinformationen_id'] . "' " . $selected . ">" . $inforamtion['zusatzinformationen_name'] . "</option>";
    				}
				?>
            </select>
            <?php echo "<div><input class='input' id='fahrgestellnummer' size='20' name='fahrgestellnummer' value='" . $fahrzeug['fahrgestellnummer'] . "'/></div>";?>
			<select name="dropDownArt" id="dropDownArt" class='input'>
			<?php
			foreach($allData['art'] as $art){
			    
			    $selected = '';
			    if($art['art_id'] == $fahrzeug['art_id']) $selected = 'selected';
			    echo "<option value='" . $art['art_id'] . "' " . $selected . ">" . $art['art_name'] . "</option>";
			}
			?>
            </select>
            <br>
            <select name="dropDownBeschaffung" id="dropDownBeschaffung" class='input'>
			<?php
			foreach($allData['beschaffung'] as $beschaffung){
			    
			    $selected = '';
			    if($beschaffung['beschaffung_id'] == $fahrzeug['beschaffung_id']) $selected = 'selected';
			    echo "<option value='" . $beschaffung['beschaffung_id'] . "' " . $selected . ">" . $beschaffung['beschaffung_name'] . "</option>";
			}
			?>
            </select>
            <?php 
			echo "<div><input class='input' type='date' name='kaufdatum' value='" . $fahrzeug['kaufdatum'] . "'/></div>";
			echo "<div><input class='input' type='date' name='baujahr' value='" . $fahrzeug['baujahr'] . "'/></div>";			    
			    
			
					
 			?>
			</div>
		</div>
		<div class="right">
			<div class="description">
				<div class="text">Erstzulassung</div>
				<div class="text">Leistung</div>
				<div class="text">Kraftstoff</div>
				<div class="text">Brutto-Listenpreis</div>
				<div class="text">Nächste HU</div>
				<div class="text">Zugelassen auf Firma</div>
				<div class="text">Besitzer</div>
				<div class="text">Innenauftrag</div>
				<div class="text" id="fahrzeugInaktiv">Fahrzeug gelöscht/inaktiv</div>
			</div>
			<div class="value">
			<?php 
			if($fahrzeug['fahrzeug_inaktiv'] == 1){
			    $activity = "checked";
			} else {
			    $activity = "";
			};
			
			
			echo "<div><input class='input' size='10' name='erstzulassung' type='date'  value='" . $fahrzeug['erstzulassung'] . "'/></div>";
			echo "<div><input id='inputPS' class='input' size='10' name='leistungPS' type='number' value='" . $fahrzeug['leistung_ps'] . "'/>PS<input id='inputKW' class='input' size='10' name='leistungKW' type='number' value='" . $fahrzeug['leistung_kw'] . "'/>KW</div>";
			?>
			<select name="dropDownKraftstoff" id="dropDownKraftstoff" class='input'>
			<?php
			foreach($allData['kraftstoff'] as $kraftstoff){
			    
			    $selected = '';
			    if($kraftstoff['kraftstoff_id'] == $fahrzeug['kraftstoff_id']) $selected = 'selected';
			    echo "<option value='" . $kraftstoff['kraftstoff_id'] . "' " . $selected . ">" . $kraftstoff['kraftstoff_name'] . "</option>";
			}
			?>
            </select>
            <br>
            <?php
				echo "<div><input class='input' size='10'  step='0.01' name='bruttoListenpreis' type='number'  value='" . $fahrzeug['brutto_listenpreis'] . "'/><span>€</span></div>";
				echo "<div><input class='input'  name='naechsteHU' type='month'  value='" . $fahrzeug['naechste_hu'] . "'/></div>";
				?>
			<select name="dropDownFirma" id="dropDownFirma" class='input'>
			<?php
			foreach($allData['firma'] as $firma){
			    
			    $selected = '';
			    if($firma['firma_id'] == $fahrzeug['firma_id']) $selected = 'selected';
			    echo "<option value='" . $firma['firma_id'] . "' " . $selected . ">" . $firma['firma_name'] . "</option>";
			}
			?>
            </select>
            <br>
            <select name="dropDownBesitzer" id="dropDownBesitzer" class='input'>
			<?php
			foreach($allData['besitzer'] as $besitzer){
			    
			    $selected = '';
			    if($besitzer['besitzer_id'] == $fahrzeug['besitzer_id']) $selected = 'selected';
			    echo "<option value='" . $besitzer['besitzer_id'] . "' " . $selected . ">" . $besitzer['besitzer_name'] . "</option>";
			}
			?>
            </select>
            <br>
            <?php
				echo "<div><input class='input' size='20' name='innenauftrag'  value='" . $fahrzeug['innenauftrag'] . "'/></div>";
				echo "<div><input class='input' id='fahrzeugButton' name='activity' type='checkbox' value='" . $fahrzeug['fahrzeug_inaktiv'] . "' " . $activity . "></input></div>";
            ?>
			</div>
		</div>
	</div>
    	<div class="viewButtons">
     		<button id="arrowLeft" type="button" class="Buttons">&#9668;</button><!-- Vorheriges Fahrzeug -->
    		<button id="arrowRight" type="button" class="Buttons">&#9658;</button><!-- Nachfolgendes Fahrzeug -->
    		<?php if(SiteAuth::getPermissionLvl('MANAGE_FAHRZEUGE_VERWALTEN') == '2'):?>
    		<button class="Buttons" type="button" id="change" <?php if(!$modus == 'show') echo "disabled"?>>Ändern</button>
    		<button class="Buttons" type="button" id="cancel">Abbrechen</button>
    		<button class="Buttons" type="button" id="newCar">Neues Fahrzeug</button>
    		<button class="Buttons" type="submit" id="save">Speichern</button>
    		<button class="Buttons" type="button" id="delete" <?php if(!$modus == 'show') echo "disabled"?>>Löschen</button>
    		<button class="Buttons" type="submit" id="deleteCar">Fahrzeug löschen</button>
    		<?php endif;?>
    	</div>
	<div class="viewNav">
		<button class="footerButtons" type="button" id="buttonLeasing">Leasing</button>
		<button class="footerButtons" type="button" id="buttonVersicherung">Versicherung</button>
		<button class="footerButtons" type="button" id="buttonAnhaenge">Anhänge</button>
		<button class="footerButtons" type="button" id="buttonHistorie">Historie</button>
		<button class="footerButtons" type="button" id="buttonNotizen">Notizen</button>
		<button class="footerButtons" type="button" id="buttonSchaeden">Schäden</button>
		<div class="line"></div>
	</div>
	<div id="viewLeasing">
		<div class="left">
			<div class="description">
				<div class="text">Leasinggeber</div>
				<div class="text">Leasingnehmer</div>
				<div class="text">Leasingvertragsnummer</div>
				<div class="text">Leasing Start</div>
				<div class="text">Leasing Ende</div>
				<div class="text">Leasing Laufleistung</div>
			</div>
			<div class="value">
			
		    <select name="dropDownLeasinggeber" id="dropDownLeasinggeber" class='input'>
				<?php 
    				foreach($allData['leasingGeber'] as $leasingGeber){
    				    $selected = '';
    				    if($fahrzeug['leasing_geber_id'] == $leasingGeber['leasing_geber_id']) $selected = 'selected';
    				    echo "<option value='" . $leasingGeber['leasing_geber_id'] . "' " . $selected . ">" . $leasingGeber['leasing_geber_name'] . "</option>"; 
    				}
				?>
            </select>
            <br>
            <select name="dropDownLeasingnehmer" id="dropDownLeasingnehmer" class='input'>
				<?php 
    				foreach($allData['leasingNehmer'] as $leasingNehmer){
    				    $selected = '';
    				    if($fahrzeug['leasing_nehmer_id'] == $leasingNehmer['leasing_nehmer_id']) $selected = 'selected';
    				    echo "<option value='" . $leasingNehmer['leasing_nehmer_id'] . "' " . $selected . ">" . $leasingNehmer['leasing_nehmer_name'] . "</option>"; 
    				}
				?>
            </select>
            <br>
            
            <?php
			echo "<div><input class='input' name='LeasingVertragsnummer' type='number' value='" . $fahrzeug['leasing_vertragsnummer'] . "'/></div>";
			echo "<div><input class='input' name='leasingStart' type='date'  value='" . $fahrzeug['leasing_start'] . "'/></div>";
			echo "<div><input class='input' name='leasingEnde' type='date'  value='" . $fahrzeug['leasing_ende'] . "'/></div>";
			echo "<div><input class='input' name='LeasingLaufleistung' type='number' value='" . $fahrzeug['leasing_laufleistung'] . "'/></div>";
			?>
			</div>
		</div>
		<div class="right">
			<div class="description">
				<div class="text">Mietsonderzahlung</div>
				<div class="text">Finanzrate monatlich</div>
				<div class="text">Wartungspauschale</div>
				<div class="text">Schadensmanagement</div>
				<div class="text">Reifenpauschale</div>
				<div class="text">Leasingkosten monatlich</div>
			</div>
			<div class="value">
			<?php 
			echo "
				<div><input class='input' name='mietsonderzahlung' type='number' step='0.01' value='" . $fahrzeug['mietsonderzahlung'] . "'/><span>€</span></div>
				<div><input class='input' name='finanzrate' type='number' step='0.01' value='" . $fahrzeug['finanzrate_monatlich'] . "'/><span>€</span></div>
				<div><input class='input' name='wartungspauschale' type='number' step='0.01' value='" . $fahrzeug['wartungspauschale'] . "'/><span>€</span></div>
				<div><input class='input' name='schadensmanagement' type='number' step='0.01' value='" . $fahrzeug['schadensmanagement'] . "'/><span>€</span></div>
				<div><input class='input' name='reifenpauschale' type='number' step='0.01' value='" . $fahrzeug['reifenpauschale'] . "'/><span>€</span></div>
				<div><input class='input' name='leasingkosten' type='number' step='0.01' value='" . $fahrzeug['leasingkosten_monatlich'] . "'/><span>€</span></div>"
			?>
			</div>
		</div>
	</div>
	<div id="viewVersicherung" <?php if(!session()->get('viewVersicherung')) echo"style='display: none;'"?>>
		<div class="left">
			<div class="description">
				<div class="text">Versicherungsgeber</div>
				<div class="text">Versicherungsnehmer</div>
				<div class="text">Versicherungsschein</div>
				<div class="text">Versicherung Selbstbeteiligung TK</div>
			</div>
			<div class="value">
			<select name="dropDownVersicherunggeber" id="dropDownVersicherunggeber" class='input'>
				<?php 
    				foreach($allData['versicherungGeber'] as $versicherungGeber){
    				    $selected = '';
    				    if($fahrzeug['versicherung_geber_id'] == $versicherungGeber['versicherung_geber_id']) $selected = 'selected';
    				    echo "<option value='" . $versicherungGeber['versicherung_geber_id'] . "' " . $selected . ">" . $versicherungGeber['versicherung_geber_name'] . "</option>"; 
    				}
				?>
            </select>
            <br>
            <select name="dropDownVersicherungnehmer" id="dropDownVersicherungnehmer" class='input'>
				<?php 
    				foreach($allData['versicherungNehmer'] as $versicherungNehmer){
    				    $selected = '';
    				    if($fahrzeug['versicherung_nehmer_id'] == $versicherungNehmer['versicherung_nehmer_id']) $selected = 'selected';
    				    echo "<option value='" . $versicherungNehmer['versicherung_nehmer_id'] . "' " . $selected . ">" . $versicherungNehmer['versicherung_nehmer_name'] . "</option>"; 
    				}
				?>
            </select>
            <br>
			<?php 
			echo "  <div><input class='input' name='versicherungSchein' type='number' value='" . $fahrzeug['versicherungsschein'] . "'/><span></span></div>";
			echo "	<div><input class='input' name='selbstbeteiligungTK' type='number' step='0.01' value='" . $fahrzeug['selbstbeteiligung_tk'] . "'/><span>€</span></div>";
			?>
			</div>
		</div>
		<div class="right">
			<div class="description">
				<div class="text">Versicherung Selbstbeteiligung VK</div>
				<div class="text">KFZ Steuer</div>
				<div class="text">Versicherung pro Zeitraum</div>
			</div>
			<div class="value">
			<?php 
			echo "<div><input class='input' name='selbstbeteiligungVK' type='number' step='0.01' value='" . $fahrzeug['selbstbeteiligung_vk'] . "'/><span>€</span></div>";
			echo "<div><input class='input' name='kfzSteuer' type='number' step='0.01' value='" . $fahrzeug['kfz_steuer'] . "'/><span>€</span></div>";
			?>
			<select name="dropDownVersicherungZeitraum" id="dropDownVersicherungZeitraum" class='input'>
				<?php 
    				foreach($allData['versicherungZeitraum'] as $versicherungZeitraum){
    				    $selected = '';
    				    if($fahrzeug['versicherung_zeitraum_id'] == $versicherungZeitraum['versicherung_zeitraum_id']) $selected = 'selected';
    				    echo "<option value='" . $versicherungZeitraum['versicherung_zeitraum_id'] . "' " . $selected . ">" . $versicherungZeitraum['versicherung_zeitraum_name'] . "</option>"; 
    				}
				?>
            </select>
			</div>
		</div>
	</div>
	<div id="viewAnhaenge" style="display: none;">
		<ul class="overflow">
    		<?php 
			 $ordner = 'Anhaenge/' . $fahrzeug['kfz_id'] . '/';
			 if(file_exists($ordner)){
    			 $dateien = scandir($ordner);
    			 foreach ($dateien as $datei) {
    			     // . und .. Dateien werden ignoriert
    			     if ($datei !== '.' && $datei !== '..') {?>
						<li class="listFile" id="anhaengeList">
                            <input class="deleteFileAnhaenge" id="deleteFileAnhaenge" type="checkbox" name="deleteFilesAnhaenge[]" value="<?php echo $datei; ?>">
                            <a href="<?php echo $ordner . '/' . $datei; ?>" target="_blank"><?php echo $datei; ?></a> <!-- "_blank" -> der Link wird in einem neuen Tab geöffnet -->
                        </li>  
    			<?php }
    			 }    			     
			 }
            ?>
		 </ul>
		
		<div class="anlageButtons">
			<input name="hiddenKFZ_IDAnhaenge" value="<?php echo $fahrzeug['kfz_id']?>" type="hidden">
			<input id="dateienAnhaenge" name="fileAnhaenge" type="file">
			<button id="addFileAnhaenge" class="Buttons" type="submit" disabled>Hinzufügen</button>
			<button id="submitAnhaenge" class="Buttons" type="submit" disabled>Entfernen</button>
		</div>				
	</div>
	<table id="viewHistorie" class=table style="display: none;">
		<tr>
			<th class="tableHeader">Überlassung</th>
			<th class="tableHeader">Kennzeichen</th>
			<th class="tableHeader">Übergabedatum</th>
			<th class="tableHeader">Rückgabedatum</th>
			<th class="tableHeader">Nachname</th>
			<th class="tableHeader">Vorname</th>
			<th class="tableHeader">KST</th>
		</tr>
		<tr>
			<td>Test</td>
			<td>Test</td>
			<td>Test</td>
			<td>Test</td>
			<td>Test</td>
			<td>Test</td>
			<td>Test</td>
		</tr>
	</table>
	<div id="viewSchaeden" style="display: none;">
		<ul class="overflow">
    		<?php 
    		if($fahrzeug['kfz_id']){
    			 $ordner = 'Schaeden/' . $fahrzeug['kfz_id'] . '/';
    			 if(file_exists($ordner)){
        			 $dateien = scandir($ordner);
        			 foreach ($dateien as $datei) {
        			     // . und .. Dateien werden ignoriert
        			     if ($datei !== '.' && $datei !== '..') {?>
    						<li class="listFile" id="schaedenList">
                                <input class="deleteFileSchaeden" id="deleteFileSchaeden" type="checkbox" name="deleteFilesSchaeden[]" value="<?php echo $datei; ?>">
                                <a href="<?php echo $ordner . '/' . $datei; ?>" target="_blank"><?php echo $datei; ?></a>
                            </li>  
        			<?php }
        			 }
    			 }
    		}
            ?>
		 </ul>
		<div class="anlageButtons">
			<input name="hiddenKFZ_IDSchaeden" value="<?php echo $fahrzeug['kfz_id']?>" type="hidden">
			<input id="dateienSchaeden" name="fileSchaeden" type="file">
			<button id="addFileSchaeden" class="Buttons" type="submit" disabled>Hinzufügen</button>
			<button id="submitSchaeden" class="Buttons" type="submit" disabled>Entfernen</button>
		</div>
	</div>
	<table id="viewNotizen" class="table" style="display: none;">
		<tr>
			<th class="tableHeader" id="headDatum">Datum</th>
			<th class="tableHeader" id="headNotiz">Notiz</th>
    		<th class="tableHeader" id="headerNoteButton">
    			<input class="Buttons" type="button" id="newNoteButton" value='Neue Notiz'>
    		</th>
		</tr>
		<tr id="newNoteLine">
    		<td hidden><input class='newNote' name='hidden_kfz_id' value='<?php echo $fahrzeug['kfz_id']?>'></td>
    		<td><input class='newNote' name='noteDate' id='noteDate' type="date"></td>
    		<td colspan='2'><input class='newNote' id='newNote' name='noteText'></td>
		</tr>
		<?php 
		if(array_key_exists("notizen", $fahrzeug)){
    		foreach ($fahrzeug['notizen'] as $notiz){
    		    echo "<tr class='kfzNotizen'>";
     		    echo "<td hidden><input class='note' name='notizen_kfz_id[]' value='" . $notiz['notizen_kfz_id'] . "'></td>";
     		    echo "<td hidden><input class='note' name='notizen_id[]' value='" . $notiz['notizen_id'] . "'></td>";
    		    echo "<td><input class='note' name='notizen_datum[]' type='date' value='" . $notiz['datum'] . "'></td>";
    		    echo "<td colspan='2'><input id='textNote' class='note' name='notizen_text[]' value='" . $notiz['notizen_text'] . "'></td>";
    		    echo "</tr>";
    		}		    
		}

		?>
	</table>
	</form>
</div>


	<script>
	
		$(document).ready(function(){
			
			var modus = <?php echo json_encode($modus); ?>;
			
			if (modus=='insert') {
        		$(".input, .note").prop("disabled", false);	
        		$("#change").hide();
				$("#newCar").hide();
				$("#delete").hide();
                $("#cancel").show();
                $("#save").show();
                $("#newNoteButton").show();	
                $('#myForm').attr('action', 'fahrzeugeVerwalten/insertSaveButton');
    		}else if(modus=='change'){			
        		$(".input, .note").prop("disabled", false);$("#change").hide();
				$("#newCar").hide();
				$("#delete").hide();
                $("#cancel").show();
                $("#save").show();
                $("#newNoteButton").show();
                $('#myForm').attr('action', 'fahrzeugeVerwalten/changeSaveButton');
    		} else {
        		$(".input, .note").prop("disabled", true);
    		}
			
			
			// Die Funktion wird ausgeführt wenn ein Kennzeichen aus der Suchleiste ausgewählt wird
			$('#dropDownInput').on('input', function() {
				
				// Das ausgewählte Kennzeichen
				var inputValue = $(this).val();
    
                // Es wird über jedes Kennzeichen (<option> Element) geloopt.
                var optionID = $('#dropDownFahrzeuge option').filter(function() {
                
                	// Ist das Kennzechen gefunden wird die ID zurückgegeben und der Variablen optionID zugewiesen.
					return $(this).val() === inputValue;
                }).attr('id');       
               
              // Der Controller kann nicht über die <form> aufgerufen werden, da es keine vorherige Aktion gibt, die die action vordefiniert.
              var controllerUrl = "FahrzeugeVerwalten?modus=show&kfz_id=" + optionID;
              window.location = controllerUrl;
                
            });
                
			$('#arrowRight').click(function() {
								
				var kfzID = $('#inputKFZ_ID').val();                
                
                if (kfzID) {
                  // Construct the URL for the controller with multiple parameters
                  var controllerUrl = "FahrzeugeVerwalten?modus=next&kfz_id=" + kfzID;
                  
                  // Redirect the browser to the controller URL
                  window.location = controllerUrl;
                }
                });
               
			$('#arrowLeft').click(function() {
								
				var kfzID = $('#inputKFZ_ID').val();                
                
                if (kfzID) {
                  // Construct the URL for the controller with multiple parameters
                  var controllerUrl = "FahrzeugeVerwalten?modus=former&kfz_id=" + kfzID;
                  
                  // Redirect the browser to the controller URL
                  window.location = controllerUrl;
                }
                });

			// Das Register wächselt auf die Ansicht Leasing
			$('#buttonLeasing').click(function() {
							
				// Alle Views bis auf Leasing werden verborgen
				$("#viewVersicherung, #viewAnhaenge, #viewHistorie, #viewNotizen, #viewSchaeden").hide();
                
                // Die Leasing-View wird angezeigt
                $("#viewLeasing").show();
                
                // Alle Buttons außer Leasing bekommen wieder das gleiche Layout
                $("#buttonVersicherung, #buttonAnhaenge, #buttonHistorie, #buttonNotizen, #buttonSchaeden").css({
                    "border": "thin solid #084c94",
                    "background-color": "#e7f2fe"
                });
                
                /* Durch die andere Hintergrundfarbe und die fehlende bottom-line des Buttons ist schnell erkennbar,
					welches Register ausgewählt ist.*/
                $("#buttonLeasing").css({
                    "background-color": "white",
                    "border-bottom": "none"
                });
                
                });
                
			$('#buttonVersicherung').click(function() {
								
				$("#viewLeasing, #viewAnhaenge, #viewHistorie, #viewNotizen, #viewSchaeden").hide();
                
                $("#viewVersicherung").show();
                
                $("#buttonLeasing, #buttonVersicherung, #buttonAnhaenge, #buttonHistorie, #buttonNotizen, #buttonSchaeden").css({
                    "border": "thin solid #084c94",
                    "background-color": "#e7f2fe"
                });
                
                $("#buttonVersicherung").css({
                    "background-color": "white",
                    "border-bottom": "none"
                });
                
                });
                
			$('#buttonAnhaenge').click(function() {
								
				$("#viewLeasing, #viewVersicherung, #viewHistorie, #viewNotizen, #viewSchaeden").hide();
                
                $("#viewAnhaenge").show();
                
                $("#buttonLeasing, #buttonVersicherung, #buttonAnhaenge, #buttonHistorie, #buttonNotizen, #buttonSchaeden").css({
                    "border": "thin solid #084c94",
                    "background-color": "#e7f2fe"
                });
                
                $("#buttonAnhaenge").css({
                    "background-color": "white",
                    "border-bottom": "none"
                });
                
                });
                
			$('#buttonHistorie').click(function() {
								
				$("#viewLeasing, #viewVersicherung, #viewAnhaenge, #viewHistorie, #viewNotizen, #viewSchaeden").hide();
                
                $("#viewHistorie").show();
                
                $("#buttonLeasing, #buttonVersicherung, #buttonAnhaenge, #buttonNotizen, #buttonSchaeden").css({
                    "border": "thin solid #084c94",
                    "background-color": "#e7f2fe"
                });
                
                $("#buttonHistorie").css({
                    "background-color": "white",
                    "border-bottom": "none"
                });
                
                });
                
			$('#buttonNotizen').click(function() {
								
				$("#viewLeasing, #viewVersicherung, #viewAnhaenge, #viewHistorie, #viewNotizen, #viewSchaeden").hide();
                
                $("#viewNotizen").show();
                
                $("#buttonLeasing, #buttonVersicherung, #buttonAnhaenge, #buttonHistorie, #buttonSchaeden").css({
                    "border": "thin solid #084c94",
                    "background-color": "#e7f2fe"
                });
                
                $("#buttonNotizen").css({
                    "background-color": "white",
                    "border-bottom": "none"
                });
                
                });
                
			$('#buttonSchaeden').click(function() {
								
				$("#viewLeasing, #viewVersicherung, #viewAnhaenge, #viewHistorie, #viewNotizen").hide();
                
                $("#viewSchaeden").show();
                
                $("#buttonLeasing, #buttonVersicherung, #buttonAnhaenge, #buttonHistorie, #buttonNotizen, #buttonSchaeden").css({
                    "border": "thin solid #084c94",
                    "background-color": "#e7f2fe"
                });
                
                $("#buttonSchaeden").css({
                    "background-color": "white",
                    "border-bottom": "none"
                });
                
                });
                
			$('#change').click(function() {
							
				// Alle Inputfelder außer der kfz_id sollen bearbeitbar sein
				$(".input:not(#inputKFZ_ID), .note").prop("disabled", false);
				// Die Funktion muss getriggert werden, da es sein kann, dass die Seite neu geladen wurde und ein Hersteller bereits ausgewählt ist.
				$("#dropDownHersteller").trigger('change');
				
				$("#change").hide();
				$("#newCar").hide();
				$("#delete").hide();
                $("#cancel").show();
                $("#save").show();
                $("#newNoteButton").show();
                
                $('#myForm').attr('action', 'fahrzeugeVerwalten/changeSaveButton');
                
                });

			$('#cancel').click(function() {
                
                var kfzID = $('#inputKFZ_ID').val();
                
				if(modus == 'show' || modus == 'change'){
                  	var controllerUrl = "FahrzeugeVerwalten?modus=show&kfz_id=" + kfzID;
                  	window.location = controllerUrl; 
				} else {
                    var controllerUrl = "FahrzeugeVerwalten";
                  	window.location = controllerUrl;
				}
                });
               
			$('#save').click(function() {
				// Alle Felder werden entblockt, damit die Werte aus dem Controller gelesen werden können. 				
				$(".input, .note").prop("disabled", false);
				                
                });
                		
			$('#fahrzeugButton').change(function(){
                this.value = +this.checked;
            	});
            
            $('#newCar').click(function() {
				
				// 				                  	
				$('#buttonLeasing').click();
						
				// Alle Input-Felder werden editierbar und geleert		                  				
				$(".input, .note").prop("disabled", false);
				$(".input, .note, .kfz_id").val('');
				
				// Beim Anlegen eines neuen Fahrzeugs können noch keine Anhänge, Schäden oder Notizen mitgegeben werden
				// Daher werden die Buttons aus der Registerleiste blockiert
				$("#buttonAnhaenge").prop("disabled", true);
				$("#buttonNotizen").prop("disabled", true);
				$("#buttonSchaeden").prop("disabled", true);

				// Buttons werden angezeigt und versteckt
				$("#newCar").hide();
				$("#change").hide();
				$("#delete").hide();
				$(".kfzNotizen").hide();
				$("#save").show();
                $("#cancel").show();
                $("#newNoteButton").show();
                
                // Ändern der action in der <form>
                $('#myForm').attr('action', 'fahrzeugeVerwalten/insertSaveButton');
                
                });
                
			$('#delete').click(function() {
								                  				
				$("#newCar").hide();
				$("#change").hide();
				$("#delete").hide();
				$("#save").hide();
                $("#cancel").show();
                $("#deleteCar").show();
                
                $('#myForm').attr('action', 'fahrzeugeVerwalten/deleteSaveButton');
                
                });
                
			$('#deleteCar').click(function() {
    			$(".input, .note").prop("disabled", false);
                
                });
            
            $('#newNoteButton').click(function() {
								
				$("#newCar").hide();
				$("#change").hide();
				$("#newNoteLine").show();
				$("#save").show();
                $("#cancel").show();
                $("#newNoteButton").hide();
                
                if ($('#inputKFZ_ID').val()) {
    				$('#myForm').attr('action', 'fahrzeugeVerwalten/insertNoteButton');
                } else {
    				$('#myForm').attr('action', 'fahrzeugeVerwalten/insertSaveButton');                	
                }
                          
                                
                });
                
			$('#dateienAnhaenge').click(function() {
								
    			$('#myForm').attr('action', 'fahrzeugeVerwalten/addFileAnhaenge');
    			$('#myForm').attr('enctype','multipart/form-data');              	
                
                $('#addFileAnhaenge').prop('disabled', false);
                });
                
			$('#dateienSchaeden').click(function() {
								
    			$('#myForm').attr('action', 'fahrzeugeVerwalten/addFileSchaeden');
    			$('#myForm').attr('enctype','multipart/form-data');              	
                
                $('#addFileSchaeden').prop('disabled', false);
                });
                
			$('.deleteFileAnhaenge').change(function() {
				
				if ($('#anhaengeList input[type="checkbox"]:checked').length > 0) {
    				$('#submitAnhaenge').prop('disabled', false);
        			$('#myForm').attr('action', 'fahrzeugeVerwalten/deleteFileAnhaenge');     
                } else {
                    $('#submitAnhaenge').prop('disabled', true);
    				$('#myForm').attr('action', 'fahrzeugeVerwalten');
                }
                
                });
                
			$('.deleteFileSchaeden').change(function() {
				
				if ($('#schaedenList input[type="checkbox"]:checked').length > 0) {   
    				$('#submitSchaeden').prop('disabled', false);
        			$('#myForm').attr('action', 'fahrzeugeVerwalten/deleteFileSchaeden');
                } else {
                    $('#submitSchaeden').prop('disabled', true);
    				$('#myForm').attr('action', 'fahrzeugeVerwalten');
                }
				
                
                });
            
            // Es werden nur die Modelle zum richtigen Hersteller im Dropdown angezeigt
			$('#dropDownHersteller').on('change', function () {
                var selectedHersteller = $(this).val(); // Gewählter Hersteller
        		
        		// Bei gleicher herstellerID wird das Modell in dem Dropdown angezeigt
                $('#dropDownModell option').each(function () {
                    var herstellerId = $(this).data('hersteller');
                    if (herstellerId == selectedHersteller) {
                        $(this).show();
                    } else {
                        $(this).hide();
                    }
                });
        
            });
			
		});
	</script>
