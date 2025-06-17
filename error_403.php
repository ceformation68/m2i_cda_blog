<?php
	// Variables d'affichage
	$strH1		= "Vous n'êtes pas autorisé";
	$strPar		= "Page d'erreur";
	
	// Variables de fonctionnement
	$strPage 	= "error_403";
	
	include("_partial/header.php");
?>

<div class="row g-5">
	<div class="col-md-12">
		<p>Vous n'êtes pas autorisé à consulter cette page.</p>
		<p>Vous pouvez vous connecter en <a href="login.php">suivant ce lien</a></p>
		<p>Ou vous pouvez <a href="create_account.php">créer un compte</a></p>
	</div>
</div>
<?php
	include("_partial/footer.php");
?>