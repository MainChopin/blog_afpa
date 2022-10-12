<?php
$filename = "./data/data.json";
$articles = [];

if (file_exists($filename)) {
    $data = file_get_contents($filename);
    $articles = json_decode($data, true) ?? [];
}
?>
<!DOCTYPE html>
<html lang="en">

<head>
    <?php require_once './includes/head.php';
        echo BLOG_TITLE;
    ?>
    <link rel="stylesheet" href="./assets/css/index.css">
</head>

<body>
    <div class="container">
        <?php require_once './includes/header.php' ?>   
        <div class="content">
            <div class="flex">
                <?php foreach ($articles as $article) :?>
                    <div class="p-50">

                        <div class="card">
                            <a href="">
                                <div class="card-img">
                                    <img src="<?= $article['imgPath']?>" alt="Image Article">
                                </div>
                                <div class="card-body">
                                    <div class="card-title">
                                        <h3><?= $article['title']?></h3>
                                    </div>
                                    <div class="card-button">
                                        <form action="" method="GET">
                                            <button type="submit" class="btn">Détails</button>
                                        </form>
                                    </div>
                                </div>
                            </a>
                        </div>
                    </div>
                <?php endforeach; ?>
            </div>
        </div>
        <?php require_once './includes/footer.php' ?>    
    </div>
</body>

</html>