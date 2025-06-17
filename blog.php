<?php
	// Variables d'affichage
	$strH1		= "Blog";
	$strPar		= "Page affichant tous les articles, avec une zone de recherche sur les articles";
	
	// Variables de fonctionnement
	$strPage 	= "blog";
	
	include("_partial/header.php");
	
	//var_dump($_POST);
	
	$strKeywords	= $_POST['keywords']??"";
	$intPeriod		= $_POST['period']??0;
	$strDate		= $_POST['date']??"";
	$strStartDate	= $_POST['startdate']??"";
	$strEndDate		= $_POST['enddate']??"";
	$intAuthor		= $_POST['author']??0;
	
	// Inclure le fichier de connexion PDO
	/*require("connexion.php");
	
	// Ecrire la requête comme dans PHPMyAdmin
	$strQuery		= "SELECT article_title, article_img, article_content, article_createdate,
							CONCAT(user_name, ' ', user_firstname) AS article_author
						FROM articles
							INNER JOIN users ON article_creator = user_id
						ORDER BY article_createdate DESC 
						";
	// On execute la requête et on demande tous les résultats
	$arrArticles	= $db->query($strQuery)->fetchAll();*/
	
	require_once("models/article_model.php"); // le fichier model des articles
	$objModelArticle	= new ArticleModel(); // instancier
	$arrArticles		= $objModelArticle->findAll(); // Récupérer les articles
	
?>	
<div class="row mb-2">
	<form name="formSearch" method="post" action="#">
		<fieldset>
			<legend>Rechercher des articles</legend>
			<p>
				<label class="form-label" for="keywords">Mots clés</label>
				<input class="form-control" id="keywords" type="text" name="keywords" value="<?php echo $strKeywords; ?>" />
			</p>
			<p>	
				<input type="radio" name="period" <?php echo ($intPeriod == 0)?"checked":""; ?> value="0" onclick="changePeriod()" /> Par date exacte
				<input type="radio" name="period" <?php echo ($intPeriod == 1)?"checked":""; ?> value="1" onclick="changePeriod()" /> Par période
			</p>
			<p id="uniquedate">
				<label for="date">Date</label>
				<input id="date" type="date" name="date" value="<?php echo $strDate; ?>" />
			</p>
			<p id="period">
				<label for="startdate">Date de début</label>
				<input id="startdate" type="date" name="startdate" value="<?php echo $strStartDate; ?>" />
				<label for="enddate">Date de fin</label>
				<input id="enddate" type="date" name="enddate" value="<?php echo $strEndDate; ?>"/>
			</p>
			<p>
				<label for="author">Auteur</label>
				<select id="author" name="author" >
					<option <?php echo ($intAuthor == 0)?"selected":""; ?> value="0">--</option>
					<option <?php echo ($intAuthor == 1)?"selected":""; ?> value="1">christel</option>
					<option <?php echo ($intAuthor == 2)?"selected":""; ?> value="2">test</option>
				</select>
			</p>
			<p><input type="submit" value="Rechercher" /> <input type="reset" value="Réinitialiser" /></p>
		</fieldset>
	</form>

<?php
		// Parcourir le tableau des articles
		foreach($arrArticles as $arrDetArticle){
			// Traitement avant affichage
			$objDate 	= new DateTimeImmutable($arrDetArticle['article_createdate']);
			$strDate 	= $objDate->format('d/m/Y');
			$strSummary	= substr($arrDetArticle['article_content'], 0, 50).'...';
			// Affichage d'un article
			include("_partial/article.php");
		}
	?>			
</div>
<?php
	include("_partial/footer.php");
?>
