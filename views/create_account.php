<form method="post">
	<p>
		<label for="name">Nom</label>
		<input name="name" value="<?php echo $objUser->getName(); ?>" id="name" class="form-control 
			<?php if(isset($arrErrors['name'])){ echo 'is-invalid'; } ?>" type="text" >
	</p>
	<p>
		<label for="firstname">Prénom</label>
		<input name="firstname" value="<?php echo $objUser->getFirstname(); ?>" id="firstname" class="form-control 
			<?php if(isset($arrErrors['firstname'])){ echo 'is-invalid'; } ?>" type="text" >
	</p>
	<p>
		<label for="mail">Mail</label>
		<input name="mail" value="<?php echo $objUser->getMail(); ?>" id="mail" class="form-control 
			<?php if(isset($arrErrors['mail'])){ echo 'is-invalid'; } ?>" type="text" >
	</p>
	<p>
		<label for="pwd">Mot de passe</label>
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

