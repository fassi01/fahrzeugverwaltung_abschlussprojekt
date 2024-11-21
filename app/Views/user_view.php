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

if (isset($_GET['userId'])){
    $userId = $_GET['userId'];
}else{
    $userId = '';
}

if (!session()->get('formErrors')){
    $formErrors = array('0' => '');
}else{
    $formErrors = session()->get('formErrors');
}

?>

<div class="baseHeader">
	<?php if($modus=='show') : ?>
		<div class="userImg"><h1>&nbsp;&nbsp;&nbsp;Benutzer</h1></div>
	<?php elseif ($modus=='change') : ?>
		<div class="userImg"><h1>&nbsp;&nbsp;&nbsp;Benutzer ändern</h1></div>
	<?php elseif ($modus=='insert') : ?>
		<div class="userImg"><h1>&nbsp;&nbsp;&nbsp;Benutzer anlegen</h1></div>
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
        			<form autocomplete="off" action="<?php echo base_url('User/changeButton');?>" method="post">
    			
    			<?php elseif ($modus=='change' && $action=='changeButton') : ?>	
    				<form autocomplete="off" action="<?php echo base_url('User/changeSaveButton');?>" method="post">
    			<?php elseif ($modus=='change' && $action=='changeSaveButton') : ?>	
    				<form autocomplete="off" action="<?php echo base_url('User/changeSaveButton');?>" method="post">				
    			<?php elseif ($modus=='insert') : ?>
    				<form autocomplete="off" action="<?php echo base_url('User/saveButton');?>" method="post">						
    			<?php endif; ?>  
			<?php endif; ?>  
			
            
            <div class="viewBodyUser">
            
            	<div class="viewButtons">
            		<div class="viewButtonsLeft">
            			<input type="button" value="Zurück" class="backNavButton">
            		</div>
            		
            		<?php if(SiteAuth::getPermissionLvl('MANAGE_USER') > '1') : ?>
            		<div class="viewButtonsRight">	
                		<?php if($modus=='show') : ?>            			
    						<input type="submit" value="Ändern" class="userAuthRoleChangeButton">
    					<?php elseif ($modus=='change' && $action=='changeButton') : ?>
    						<input type="button" value="Berechtigungsrolle anlegen" class="authRoleListCreateRoleButton">
    						<input type="submit" value="Änderungen speichern" class="userAuthRoleChangeSaveButton">    						    						    							
    					<?php elseif ($modus=='change' && $action=='changeSaveButton') : ?>
    						<input type="button" value="Berechtigungsrolle anlegen" class="authRoleListCreateRoleButton">
    						<input type="submit" value="Änderungen speichern" class="userAuthRoleChangeSaveButton">    						
    					<?php elseif ($modus=='insert') : ?>
							<input type="button" value="Berechtigungsrolle anlegen" class="authRoleListCreateRoleButton">    					
    						<input type="submit" value="Speichern" class="userAuthRoleSaveButton">    													
    					<?php endif; ?>
					</div>
					<?php else : ?>
					<div style="clear:both;"></div>					
					<?php endif; ?>	
            	</div>
            
                       
                <div class="userInfoBlock">
                    <div class="viewHeaderH2">
                    	<h2>Kontoinformationen</h2>
                    </div>
                    
                    
                    <div class="userInfoBody">
                    	
                    	
                        <div class="inputUserBox">                                                
                            <label class="inputLabelUser">Benutzer ID:</label>
                            <input class="inputFieldUserReadOnly" type="text" name="userId" value="<?= esc($u_id) ?>" readonly >
                        </div>                    	   
                         
                         
                        <div class="inputUserBox">                                                
                            <label class="inputLabelUser">E-Mail/Loginuser:</label>
                            <?php if($modus=='show') : ?>
                            	<input class="inputFieldUserReadOnly" type="text" name="email" value="<?= esc($u_mail) ?>" readonly>
                            <?php elseif ($modus=='change' || $modus=='insert') : ?>
                                <?php if(!array_key_exists('u_mail', $formErrors)):?> 
                                	<input class="inputFieldUser" type="text" name="email" value="<?= esc($u_mail) ?>" >
                                <?php else:?>
                                	<input class="inputFieldUserValError" type="text" name="email" value="<?= esc($u_mail) ?>" >
                                	<div class="inputValidationUserText"><?= $formErrors['u_mail'];?></div>
                                <?php endif;?>
                            <?php endif;?>
                        </div></br>                         
                                                 
                        <div class="inputUserBox">                                                
                            <label class="inputLabelUser">Vorname:</label>
                            <?php if($modus=='show') : ?>
                            	<input class="inputFieldUserReadOnly" type="text" name="firstName" value="<?= esc($u_first_name) ?>" readonly >
                            <?php elseif ($modus=='change' || $modus=='insert') : ?>
                                <?php if(!array_key_exists('u_first_name', $formErrors)):?> 
                                	<input class="inputFieldUser" type="text" name="firstName" value="<?= esc($u_first_name) ?>" >
                                <?php else:?>
                                	<input class="inputFieldUserValError" type="text" name="firstName" value="<?= esc($u_first_name) ?>" >
                                	<div class="inputValidationUserText"><?= $formErrors['u_first_name']; ?></div>
                                <?php endif;?>
                           <?php endif;?>	     
                        </div>
                        
                        <div class="inputUserBox">                                                
                            <label class="inputLabelUser">Nachname:</label>
                            <?php if($modus=='show') : ?>
                            	<input class="inputFieldUserReadOnly" type="text" name="lastName" value="<?= esc($u_last_name) ?>" readonly>
                            <?php elseif ($modus=='change' || $modus=='insert') : ?>
                                <?php if(!array_key_exists('u_last_name', $formErrors)):?> 
                                	<input class="inputFieldUser" type="text" name="lastName" value="<?= esc($u_last_name) ?>" >
                                <?php else:?>
                                	<input class="inputFieldUserValError" type="text" name="lastName" value="<?= esc($u_last_name) ?>" >
                                	<div class="inputValidationUserText"><?= $formErrors['u_last_name'];?></div>
                                <?php endif;?>                            
							<?php endif;?>
                        </div>
                        
                        </br>
                        <div class="inputUserBox">                                                
                            <label class="inputLabelUser">Status:</label>
                            	<?php if($modus=='show') : ?>
                                <select class="userSelectOptionReadOnly" name="status" disabled="true">
                                <?php else:?>
                                	<?php if(!array_key_exists('u_status', $formErrors)):?>
                            			<select class="userSelectOption" name="status">
                            		<?php else:?>
                            			<select class="userSelectOptionValError" name="status">
                            		<?php endif;?>
                                <?php endif;?> 
                                	<option value="empty"></option>
                            		<option value="A" <?php if($u_status=='A'){echo 'selected';}?>>Aktiv</option>                                    	
                            		<option value="I" <?php if($u_status=='I'){echo 'selected';}?>>Inaktiv</option>
                                </select> 
                                
                                <?php if(array_key_exists('u_status', $formErrors)):?>
                                	<div class="inputValidationUserText"><?= $formErrors['u_status'];?></div>
                                <?php endif;?> 
                        </div>         
                        
                	</div>
                	     
                </div>
        
                <div class="userPasswordChangeBlock" >
                    <div class="viewHeaderH2">
                    	<h2>Passwort</h2>
                    </div>                    
                	<div class="userPasswordChangeBody">
 
                         <div class="inputUserBox">                                                
                            <label class="inputLabelUser">Neues Passwort:</label>
                            <?php if($modus=='show') : ?>
                            	<input class="inputFieldUserReadOnly" type="password" name="newPassword" value="" readonly>
                            <?php elseif ($modus=='change' || $modus=='insert') : ?>
                            	<?php if($modus=='insert' && array_key_exists('u_password', $formErrors) ) : ?>  
                            		<input class="inputFieldUserValError" type="password" name="newPassword" value="" >
                            		<div class="inputValidationUserText"><?= $formErrors['u_password'];?></div>
                            	<?php else:?>                               
                            		<input class="inputFieldUser" type="password" name="newPassword" value="" > 
                            	<?php endif;?>                               
                            <?php endif;?>
                        </div>                		
                	</div>                	
                </div>
                
                </br>                
                <div class="userAuthGroupBlock" >
                    <div class="viewHeaderH2">
                    	<h2>Berechtigungsrolle</h2>
                    </div>
                    <div class="userAuthGroupBody">
                        <div class="inputUserBox">                                                
                            <label class="inputLabelUser">Rolle:</label>
                            	<?php if($modus=='show') : ?>
                                <select class="userSelectOptionReadOnly" name="authRoleId" disabled="true">
                                <?php else:?>
                                	<?php if(!array_key_exists('u_authRoleId', $formErrors)):?>
                            			<select class="userSelectOption" name="authRoleId">
                            		<?php else:?>
                            			<select class="userSelectOptionValError" name="authRoleId">
                            		<?php endif;?>		
                                <?php endif;?> 
                                	<option value="empty"></option>
                                    <?php foreach($groupDropDown as $rowGroupDropDown): ?>
                                    	<?php if($rowGroupDropDown['g_id'] == $g_id) : ?>
                                    		<option value="<?= $rowGroupDropDown['g_id']; ?>" selected><?= $rowGroupDropDown['g_description']; ?></option>
                                    	<?php else:?>
                                    		<option value="<?= $rowGroupDropDown['g_id']; ?>"><?= $rowGroupDropDown['g_description']; ?></option>
                                    	<?php endif;?>
    									                                
                                    <?php endforeach; ?>
                                </select> 
                                
                                <?php if(array_key_exists('u_authRoleId', $formErrors)):?>
                                	<div class="inputValidationUserText"><?= $formErrors['u_authRoleId'];?></div>
                                <?php endif;?>
                        </div>
                    </div>                	
                </div>                
                
            </div>
            <?php if(SiteAuth::getPermissionLvl('MANAGE_USER') > '1') : ?>
            </form>
            <?php endif;?>
        </div>  
    </div>
</div>

<script>
	$(document).ready(function(){		
	
		// ***********************************************************************************************
		// Start-initialization
		// ***********************************************************************************************
		
				
		
	});

	$(document).on('click', '.backNavButton', function(){		
		window.location = '/UserList';
	});
	
	$(document).on('click', '.authRoleListCreateRoleButton', function(){
	
		var modus = <?php echo json_encode($modus); ?>;
		var userId = <?php echo json_encode($userId); ?>;		
		
		if (modus=='insert') {
			window.location = '/User/setBackLocationInsertAuthRoleFromUserInsert';			
		}else if(modus=='change'){			
			window.location = '/User/setBackLocationInsertAuthRoleFromUserChange?userId=' + userId ;
		}
		
		
	});	
	
	
	
	
</script>
