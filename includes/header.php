<header>
    <a href="index.php">Blog AFPA</a> 
    <ul class="header-menu">
        <li class=<?= $_SERVER['REQUEST_URI'] === '/add-article.php' ? 'active' : '' ?>>
           <a href="add-article.php">Créer un article</a>
        </li>
    </ul>
</header>