<nav class="nav nav-underline justify-content-between">
	<a class="nav-item nav-link link-body-emphasis <?php if ($strPage == "index"){ echo "active"; } ?>" href="index.php">Accueil</a>
	<a class="nav-item nav-link link-body-emphasis <?php if ($strPage == "about"){ echo "active"; } ?>" href="index.php?ctrl=page&action=about">A propos</a>
	<a class="nav-item nav-link link-body-emphasis <?php if ($strPage == "blog"){ echo "active"; } ?>" href="index.php?ctrl=article&action=blog">Blog</a>
	<a class="nav-item nav-link link-body-emphasis <?php if ($strPage == "contact"){ echo "active"; } ?>" href="index.php?ctrl=page&action=contact">Contact</a>
</nav>