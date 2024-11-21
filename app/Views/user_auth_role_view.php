<?php 

use App\Libraries\SiteAuth;

if (isset($_GET['modus'])){
    $modus = $_GET['modus'];
}else{
    $modus = '';
}

if (isset($_GET['action'])){
    $action = $_GET['action'];
}else{
    $action = '';
}

if (isset($_GET['authRoleId'])){
    $authRoleId = $_GET['authRoleId'];
}else{
    $authRoleId = '';
}

if (!session()->get('formErrors')){
    $formErrors = array('0' => '');
}else{
    $formErrors = session()->get('formErrors');
}

?>


<div class=baseHeader>
	<?php if($modus=='show') : ?>
		<div class="authGroupImg"><h1>&nbsp;&nbsp;&nbsp;Berechtigungsrolle</h1></div>
	<?php elseif ($modus=='change') : ?>
		<div class="authGroupImg"><h1>&nbsp;&nbsp;&nbsp;Berechtigungsrolle ändern</h1></div>
	<?php elseif ($modus=='insert') : ?>
		<div class="authGroupImg"><h1>&nbsp;&nbsp;&nbsp;Berechtigungsrolle anlegen</h1></div>
	<?php endif; ?>				
</div>

<?php if(session()->getFlashdata('msgOK')):?>
	<div class="messsageBox">
		<div class="messageBoxOk"><?= session()->getFlashdata('msgOK') ?></div>
	</div>                
<?php endif;?>

<?php if(session()->getFlashdata('msgWarning')):?>
	<div class="messsageBox">
		<div class="messageBoxWarning"><?= session()->getFlashdata('msgWarning') ?></div>
	</div>                
<?php endif;?>            

<?php if(session()->getFlashdata('msgError')):?>
	<div class="messsageBox">
		<div class="messageBoxError"><?= session()->getFlashdata('msgError') ?></div>
	</div>                
<?php endif;?>  

<div class="mainView">
	
    <div class="viewContainer">                
        
    	<div class="viewWrapper">
    	
            
            
            <?php if(SiteAuth::getPermissionLvl('MANAGE_USER') > '1') : ?>
        		<?php if($modus=='show') : ?>
        			<form autocomplete="off" action="<?php echo base_url('UserAuthRole/changeButton');?>" method="post">
    			
    			<?php elseif ($modus=='change' && $action=='changeButton') : ?>	
    				<form autocomplete="off" action="<?php echo base_url('UserAuthRole/changeSaveButton');?>" method="post">
    			<?php elseif ($modus=='change' && $action=='changeSaveButton') : ?>	
    				<form autocomplete="off" action="<?php echo base_url('UserAuthRole/changeSaveButton');?>" method="post">				
    			<?php elseif ($modus=='insert') : ?>
    				<form autocomplete="off" action="<?php echo base_url('UserAuthRole/saveButton');?>" method="post">						
    			<?php endif; ?>
			<?php endif; ?>	            
            
            
            <div class="viewBodyUserAuthRole">
            
            	<div class="viewButtons">
            		<div class="viewButtonsLeft">
            			<input type="button" value="Zurück" class="backNavButton">
            		</div>
            		
            		<?php if(SiteAuth::getPermissionLvl('MANAGE_USER') > '1') : ?>
            		<div class="viewButtonsRight">            			
                		<?php if($modus=='show') : ?>            			
    						<input type="submit" value="Ändern" class="userAuthRoleChangeButton">    						
    						<input type="button" value="Löschen" class="userAuthRoleDeleteButton">    						
    					<?php elseif ($modus=='change' && $action=='changeButton') : ?>
    						<input type="submit" value="Änderungen speichern" class="userAuthRoleChangeSaveButton">
    						<input type="button" value="Löschen" class="userAuthRoleDeleteButton">    							
    					<?php elseif ($modus=='change' && $action=='changeSaveButton') : ?>
    						<input type="submit" value="Änderungen speichern" class="userAuthRoleChangeSaveButton">    						
    						<input type="button" value="Löschen" class="userAuthRoleDeleteButton">    													
    					<?php elseif ($modus=='insert') : ?>
    						<input type="submit" value="Speichern" class="userAuthRoleSaveButton">							
    					<?php endif; ?>    				
					</div>
					<?php else : ?>
					<div style="clear:both;"></div>
					<?php endif; ?>	
					
            	</div>
            	
        		<div class="inputAboveTable">
                    <label class="inputLabelAboveTable">Berechtigungsrolle</label> 
                    <?php if($modus=='show') : ?>
                   		<input class="inputFieldAboveTableReadOnly" type="text" name="authRoleDesc" value="<?= $authRoleDesc ?>" placeholder="* Pflichtfeld" readonly >
                   	<?php elseif ($modus=='change' || $modus=='insert') : ?>	
                   		<?php if(!array_key_exists('authRoleDesc', $formErrors)):?> 
                   			<input class="inputFieldAboveTable" type="text" name="authRoleDesc" value="<?= $authRoleDesc ?>" placeholder="* Pflichtfeld">
                   		<?php else: ?>
                   			<input class="inputFieldAboveTableValError" type="text" name="authRoleDesc" value="<?= $authRoleDesc ?>" placeholder="* Pflichtfeld">
                   			<div class="inputFieldAboveTableValErrorText"><?= $formErrors['authRoleDesc']; ?></div>
                   		<?php endif; ?>		
                   	<?php endif; ?>	
                   	<input class="inputFieldAboveTable" type="hidden" name="authRoleId" value="<?= $authRoleId ?>" >
               	</div>
            	
            
    			<div class="tableView">
    				
    				<table class="styled-table">
    					<thead>
    						<tr>
    							<th>Berechtigungsname</th>
    							<th>keine Berechtigung</th>
    							<th>nur anzeigen</th>
    							<th>anzeigen & ändern</th>
    						</tr>
    					</thead>
    					<tbody id="tableBody">
    					</tbody>
    				</table>
    			
    			</div>            

			</div>
			
			<?php if(SiteAuth::getPermissionLvl('MANAGE_USER') > '1') : ?>
			</form>   
			<?php endif; ?>         
            
            
       </div>
       
      
              
	</div>
	    
</div>

<script>
	$(document).ready(function(){		
	
		// ***********************************************************************************************
		// Start-initialization
		// ***********************************************************************************************
		
		// Get tableView-Array from PHP-Controller		
		var jsonObj = [];				
		var table = <?php echo json_encode($tableView); ?>;
		
		for ( var i = 0, l = table.length; i < l; i++ ) {
			item = {};
			item ["p_id"] = table[i]['p_id'];
			item ["p_description"] = table[i]['p_description'];
			item ["gp_level"] = table[i]['gp_level'];
			
			jsonObj.push(item);			
		}
		
		
		// write Json Objekt 'table' to html-table
		
		var row = "";
		var columnCounter = 0;
		var rowCounter = 0;
		var modus = <?php echo json_encode($modus); ?>;
		
		$.each(jsonObj, function (key, val) {							
			
			$.removeData(row);
			columnCounter = 0;
			rowCounter = rowCounter + 1;
			
			
			$.each(val, function (key2, val2) {
			
				if (key2 == 'p_id'){
					p_id = val2; 
				}
				
				if (key2 == 'p_description'){
					p_desc = val2; 
				}					
			
				if (key2 == 'gp_level'){
					
					gp_level = val2;				
				
    				columnCounter = columnCounter + 1;
    				
    				if (columnCounter == 1){
    					if (rowCounter % 2 > 0){
    						// odd-ungerade row					
    						row = "<tr class='tableTr tableTr2Odd'>";
    					}else{
    						// even-gerade row
    						row = "<tr class='tableTr tableTr2Even'>";
    					}
    				}
    			
    				row = row + "<td>" + p_desc + "</td>";
    				
    				if (gp_level == 0){
    					if (modus == 'show'){
    						row = row + "<td class='inpRadioList'><input type='radio' onclick='javascript: return false;' name='" + p_id + "' value='0' checked></td>";
    					}else{
    						row = row + "<td class='inpRadioList'><input type='radio' name='" + p_id + "' value='0' checked></td>";
    					}	
    				}else{
    					
    					if (modus == 'show'){    				
    						row = row + "<td class='inpRadioList'><input type='radio' onclick='javascript: return false;' name='" + p_id + "' value='0'></td>";
    					}else{
    						row = row + "<td class='inpRadioList'><input type='radio' name='" + p_id + "' value='0'></td>";
    					}	
    				}
    				
    				
    				if (gp_level == 1){
    					if (modus == 'show'){
    						row = row + "<td class='inpRadioList'><input type='radio' onclick='javascript: return false;' name='" + p_id + "' value='1' checked></td>";
    					}else{
    						row = row + "<td class='inpRadioList'><input type='radio' name='" + p_id + "' value='1' checked></td>";
    					}	
    				}else{
    					
    					if (modus == 'show'){    				
    						row = row + "<td class='inpRadioList'><input type='radio' onclick='javascript: return false;' name='" + p_id + "' value='1'></td>";
    					}else{
    						row = row + "<td class='inpRadioList'><input type='radio' name='" + p_id + "' value='1'></td>";
    					}	
    				}
    				
    				
    				if (gp_level == 2){
    					if (modus == 'show'){
    						row = row + "<td class='inpRadioList'><input type='radio' onclick='javascript: return false;' name='" + p_id + "' value='2' checked></td>";
    					}else{
    						row = row + "<td class='inpRadioList'><input type='radio' name='" + p_id + "' value='2' checked></td>";
    					}	
    				}else{
    					if (modus == 'show'){
    						row = row + "<td class='inpRadioList'><input type='radio' onclick='javascript: return false;' name='" + p_id + "' value='2'></td>";
    					}else{
    						row = row + "<td class='inpRadioList'><input type='radio' name='" + p_id + "' value='2'></td>";
    					}
    				}
    				
    				   				    				
    				

				}
			});
						
			row = row + "</tr>";
			$("#tableBody").append(row);					
				
		});		
		
		$(document).on('click', '.backNavButton', function(){
			var backNavUrl = <?php echo json_encode(session()->get('backNavUserAuthRole')); ?>;
			window.location = backNavUrl;
		});
		
		$(document).on('click', '.userAuthRoleDeleteButton', function(){
		
            $.confirm({
                title: 'Benutzerrolle löschen?',
                content: 'Wollen Sie wirklich die Benutzerrolle löschen? Dadurch können Webuser Ihre Berechtigungen verlieren!!!',
                draggable: false,
                boxWidth: '30%',
                useBootstrap: false,
                buttons: {
                    Ja: function () {
                    	var authRoleId = <?php echo json_encode($authRoleId); ?>; 
                        window.location = '/UserAuthRole/deleteButton?authRoleId=' + authRoleId; 
                    },
                    Nein: function () {
                        
                    }
                }
            });	
		
		});						
		
		
		if (modus == 'show'){			
            $('input[type=radio]').each(function(){            	
                this.attr('onclick','return false;');
            });
		}
		

		
		
	});
</script>	



       