<?php
	session_start();
	//var_dump($_SESSION);
?>

<!doctype html>
<html lang="fr" data-bs-theme="auto">
	<head>
		<meta charset="utf-8">
		<meta name="viewport" content="width=device-width, initial-scale=1">
		<meta name="description" content="Exercice de blog">
		<meta name="author" content="Mark Otto, Jacob Thornton, and Bootstrap contributors">
		<meta name="generator" content="Hugo 0.118.2">
		<title>Blog - <?php echo $strH1; ?></title>
		<link rel="canonical" href="https://getbootstrap.com/docs/5.3/examples/blog/">
		<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/@docsearch/css@3">
		
		<link href="assets/dist/css/bootstrap.min.css" rel="stylesheet">
    
		<link rel="stylesheet" href="https://use.fontawesome.com/releases/v5.3.1/css/all.css" integrity="sha384-mzrmE5qonljUremFsqc01SB46JvROS7bZs3IO2EmfFsd15uHvIt+Y8vEf7N7fWAU" crossorigin="anonymous">    
	
		<!-- Custom styles for this template -->
		<link href="https://fonts.googleapis.com/css?family=Playfair&#43;Display:700,900&amp;display=swap" rel="stylesheet">
		<!-- Custom styles for this template -->
		<link href="assets/css/blog.css" rel="stylesheet">
		<link href="assets/css/custom.css" rel="stylesheet">
		
		<?php if ($strPage == "blog"){ ?>
		<script src="assets/js/period.js"></script>
		<?php } ?>

	</head>
	<body>
		<div class="container">
			<header class="border-bottom lh-1 py-3">
				<div class="row flex-nowrap justify-content-between align-items-center">
					<div class="col-4 pt-1">
					</div>
					<div class="col-4 text-center">
						<a class="blog-header-logo text-body-emphasis text-decoration-none" href="#">Mon blog</a>
					</div>
					<div id="user" class="col-4 d-flex justify-content-end align-items-center">
						<?php
							if (isset($_SESSION['prenom']) && $_SESSION['prenom'] != '') {
						?>
						<a class="btn btn-sm" href="edit_account.php" title="Mon compte">
							Bonjour <?php echo($_SESSION['prenom']); ?>
						</a>
						| 
						<a class="btn btn-sm" href="logout.php" title="Se déconnecter">
							<i class="fas fa-sign-out-alt"></i>
						</a> 
						<?php } else { ?>
						<a class="btn btn-sm" href="create_account.php" title="Créer un compte">
							<i class="fas fa-user"></i>
						</a>
						| 
						<a class="btn btn-sm" href="login.php" title="Se connecter">
							<i class="fas fa-sign-in-alt"></i>
						</a>
						<?php } ?>						
					</div>
				</div>
			</header>

			<div class="nav-scroller py-1 mb-3 border-bottom">
				<?php
					include("views/_partial/nav.php");
				?>
			</div>
		</div>

		<main class="container">
			<div class="p-4 p-md-5 mb-4 rounded text-body-emphasis bg-body-secondary">
				<div class="col-lg-6 px-0">
					<h1 class="display-4 fst-italic"><?php echo $strH1; ?></h1>
					<p class="my-3"><?php echo $strPar; ?></p>
				</div>
			</div>
			
		<?php
			/* Affichage des messages en session */
			if (isset($_SESSION['message'])){
				echo "<div class='alert alert-success'>";
				echo "<p class='mb-0'>".$_SESSION['message']."</p>";
				echo "</div>";
				// On supprime le message après l'avoir affiché
				unset($_SESSION['message']);
			}
		?>