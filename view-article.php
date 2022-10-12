<?php
$filename = './data/data.json';
$_GET = filter_input_array(INPUT_GET, FILTER_VALIDATE_INT);
$id = $_GET['id'] ?? '';

if ($id) {
    $articles = json_decode(file_get_contents($filename), true) ?? [];
    if (count($articles)) {
        $articleIndex = array_search($id, array_column($articles, 'id'));
        $article_title = $articles[$articleIndex]['title'];
        $article_description = $articles[$articleIndex]['description'];
        $article_category = $articles[$articleIndex]['category'];
        $article_img = $articles[$articleIndex]['imgPath'];
        $article_id = $articles[$articleIndex]['id'];
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <?php require_once './includes/head.php';
        echo VIEW_TITLE;
    ?>
    <link rel="stylesheet" href="./assets/css/view-article.css">
</head>
<body>
    <div class="container">
    <?php require_once './includes/header.php' ?>
        <div class="content">
            <div class="block p-20 form-container">
                <div class="flex">
                    <div class="article-title">
                        <h1><?= $article_title?></h1>
                    </div>
                    <div class="article-description">
                        <p><?= $article_description?></p>
                    </div>
                    <div class="article-img">
                        <img src="<?= $article_img?>" alt="Image Article">
                    </div>
                    <div class="article-button">
                        <a href="remove-article.php?id=<?= $article_id?>"><button class="btn btn-danger">Supprimer</button></a>
                        <a href="update-article.php?id=<?= $article_id?>"><button class="btn btn-primary">Modifier</button></a>
                    </div>
                </div>
            </div>
        </div>
        <?php require_once './includes/footer.php' ?>  
    </div>
</body>
</html>