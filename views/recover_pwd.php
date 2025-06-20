<?php
	/* Affichage des erreurs */
	if (count($arrErrors) == 0){
?>
	<form method="post">
		<p>
			<label for="pwd">Nouveau mot de passe</label>
			<input name="pwd" id="pwd" class="form-control 
				<?php if(isset($arrErrors['pwd'])){ echo 'is-invalid'; } ?>" type="password" >
		</p>
		<p>
			<label for="confirm_pwd">Confirmation du mot de passe</label>
			<input name="confirm_pwd" id="confirm_pwd" class="form-control 
				<?php if(isset($arrErrors['confirm_pwd'])){ echo 'is-invalid'; } ?>" type="password" >
		</p>
		<p>
			<input class="form-control btn btn-primary" type="submit" >
		</p>

	</form>	
		
		
<?php		
	}
?>
