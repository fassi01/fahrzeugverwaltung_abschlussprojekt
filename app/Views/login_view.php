<div class="loginContainer">
	<div class="loginWrapper">
		<h1>Fuhrparkmanagement-System <?php echo session()->get('enviornmentDe')?></h1>

		<div class="formLogin"> 
             
              
            <?php if(session()->getFlashdata('msgWarning')):?>
                    <div class="messsageBox">
				<div class="messageBoxWarning"><?= session()->getFlashdata('msgWarning') ?></div>
			</div>
            <?php endif;?>            
            
            <form action="<?php echo base_url('login/loginAuth');?>"
				method="post">

				<div class="formGroupFirst">

					<label class="inputLabel">E-Mail</label>
                    
                    <?php if(!session()->getFlashdata('msgErrorLoginEmail')):?> 
                          <input class="inputField" type="email"
						name="email" value="<?= session()->get('u_mail'); ?>">
                    <?php else:?>
                          <input class="inputFieldValError" type="email"
						name="email" value="<?= session()->get('u_mail'); ?>">
                    <?php endif;?>
                    
                    <?php if(session()->getFlashdata('msgErrorLoginEmail')):?>
                          <div class="inputValidationText"><?= session()->getFlashdata('msgErrorLoginEmail');?></div>
                    <?php endif;?>
            </div>

				<div class="formGroup">

					<label class="inputLabel">Passwort</label>
                    
                    <?php if(!session()->getFlashdata('msgErrorLoginPassword')):?>  
                          <input class="inputField" type="password"
						name="password">
                    <?php else:?>
                          <input class="inputFieldValError"
						type="password" name="password">
                    <?php endif;?>
                          
                    <?php if(session()->getFlashdata('msgErrorLoginPassword')):?>
                          <div class="inputValidationText"><?= session()->getFlashdata('msgErrorLoginPassword');?></div>
                    <?php endif;?>                  
             </div>

				<div class="formGroupButton">
					<input type="submit" class="loginButton" value="Login">
				</div>

				<!--                  
                 https://www.positronx.io/codeigniter-authentication-login-and-registration-tutorial/
                 https://www.youtube.com/results?search_query=codeigniter+4+login
                 
                 -->

			</form>
		</div>
	</div>
</div>
