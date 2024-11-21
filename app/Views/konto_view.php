<?php
if (! session()->get('formErrors')) {
    $formErrors = array(
        '0' => ''
    );
} else {
    $formErrors = session()->get('formErrors');
}
?>


<div class="baseHeader">
	<h1>Mein Konto</h1>
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

	<div class="viewHeader">Kontoinformationen</div>
	<div class="viewTable">
		<div class="left">
			<div class="accountInfo">
				<div class="text">Vorname</div>
				<input class="inputAccount" disabled value="<?= session()->get('u_firstName'); ?>"></input>
				<div class="text">Nachname</div>
				<input class="inputAccount" disabled value="<?= session()->get('u_lastName'); ?>"></input>
				<div class="text">E-Mail</div>
				<input class="inputAccount" disabled value="<?= session()->get('u_mail'); ?>"></input>
			</div>
		</div>
		<div class="right">
			<form action="<?php echo base_url('Konto/changePassword')?>"
				method="post">
				<div class="password">

    				<div class="text">Neues Passwort</div>
    				<?php if(!array_key_exists('newPassword', $formErrors)):?>
    					<input class="inputField" name="newPassword" type="password"></input>
    				<?php else:?>
    					<input class="inputFieldValError" name="newPassword"
						type="password"></input>
    				<?php endif;?>
    				<?php if(array_key_exists('newPassword', $formErrors)):?>
                          <div class="inputValidationText"><?= $formErrors['newPassword'];?></div>
                    <?php endif;?>
    				
    				<div class="text">Passwort bestätigen</div>
    				<?php if(!array_key_exists('confirmPassword', $formErrors)):?>
    					<input class="inputField" name="confirmPassword"
						type="password"></input>
    				<?php else:?>
    					<input class="inputFieldValError" name="confirmPassword"
						type="password"></input>
    				<?php endif;?>
    				<?php if(array_key_exists('confirmPassword', $formErrors)):?>
                          <div class="inputValidationText"><?= $formErrors['confirmPassword'];?></div>
                    <?php endif;?>
					<div class="formGroupButton">
						<input type="submit" class="changePassword"
							value="Passwort ändern">
					</div>
				</div>
			</form>
		</div>
	</div>
	<script>
		function changePassword () {
			
			const oldPassword = document.getElementById('oldPassword').value;
			const newPassword = document.getElementById('newPassword').value;
			const confirmPassword = document.getElementById('confirmPassword').value;
			
			console.log(oldPassword, newPassword, confirmPassword);
			
		}
	</script>
</div>