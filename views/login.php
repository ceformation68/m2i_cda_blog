<form method="post">
	<p>
		<label for="mail">Mail</label>
		<input name="mail" value="<?php echo $strMail; ?>" id="mail" class="form-control 
			<?php if(isset($arrErrors['mail'])){ echo 'is-invalid'; } ?>" type="text" >
	</p>
	<p>
		<label for="pwd">Mot de passe</label>
		<input name="pwd" id="pwd" class="form-control 
			<?php if(isset($arrErrors['pwd'])){ echo 'is-invalid'; } ?>" type="password" >
	</p>
	<p>
		<input class="form-control btn btn-primary" type="submit" >
	</p>
</form>

<a href="index.php?ctrl=user&action=forgot_pwd">Mot de passe oublié</a>
