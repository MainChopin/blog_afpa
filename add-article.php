<?php
require_once "errors.php";
$filename = "./data/data.json";
$errors = [
    'title' => '',
    'img' => '',
    'category' => '',
    'description' => '',
];
$articles = [];
$article_title = '';
$article_category = '';
$article_description = '';

if (file_exists($filename)) {
    $data = file_get_contents($filename);
    $articles = json_decode($data, true) ?? [];
}

if ($_SERVER["REQUEST_METHOD"] === "POST") {
    $_POST = filter_input_array(INPUT_POST, [
        'title' => [
            'filter' => FILTER_SANITIZE_FULL_SPECIAL_CHARS,
            'flags' => FILTER_FLAG_NO_ENCODE_QUOTES | FILTER_FLAG_STRIP_BACKTICK
        ],

        'category' => [
            'filter' => FILTER_SANITIZE_FULL_SPECIAL_CHARS,
            'flags' => FILTER_FLAG_NO_ENCODE_QUOTES | FILTER_FLAG_STRIP_BACKTICK
        ],

        'description' => [
            'filter' => FILTER_SANITIZE_FULL_SPECIAL_CHARS,
            'flags' => FILTER_FLAG_NO_ENCODE_QUOTES | FILTER_FLAG_STRIP_BACKTICK
        ]
    ]);

    

    $article_title = $_POST['title'] ?? '';
    $article_category = $_POST['category'] ?? '';
    $article_description = $_POST['description'] ?? '';
    
    if(!$article_title){
        $errors['title'] = ERROR_REQUIRED_TITLE;
    }elseif(mb_strlen($article_title) < 7 ){
        $errors['title'] = ERROR_TOO_SHORT_TITLE;
    }elseif(mb_strlen($article_title) > 75){
        $errors['title'] = ERROR_TOO_LONG_TITLE;
    }

    if(!$article_description){
        $errors['description'] = ERROR_REQUIRED_DESCRIPTION;
    }elseif(mb_strlen($article_description) < 7 ){
        $errors['description'] = ERROR_TOO_SHORT_DESCRIPTION;
    }elseif(mb_strlen($article_description) > 500){
        $errors['description'] = ERROR_TOO_LONG_DESCRIPTION;
    }

    if ($_FILES["img"]["name"]) {
        $target_dir = "img/";
        $target_file = $target_dir . basename($_FILES["img"]["name"]);
        $imageFileType = strtolower(pathinfo($target_file,PATHINFO_EXTENSION));
    
        if (file_exists($target_file)) {
            $errors['img'] = ERROR_EXIST_IMG;
        }
    
        if($imageFileType != "jpg" && $imageFileType != "png" && $imageFileType != "jpeg"
        && $imageFileType != "gif" ) {
        $errors['img'] = ERROR_FORMAT_IMG;
        }
    
        
    }else{
        $errors['img'] = ERROR_REQUIRED_IMG;
    }

    if (empty(array_filter($errors, fn ($e) => $e !== ''))) {
        
        move_uploaded_file($_FILES["img"]["tmp_name"], $target_file);
        
        $articles = [...$articles, [
            'title' => $article_title,
            'category' => $article_category,
            'description' => $article_description,
            'imgPath' => $target_file,
            'id' => time(),
        ]];
        file_put_contents($filename, json_encode($articles));
        $article_title = '';
        $article_category = '';
        $article_description = '';
    }
    

}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <?php require_once './includes/head.php';
        echo ADD_ARTICLE_TITLE;
    ?>
    <link rel="stylesheet" href="./assets/css/add-article.css">
</head>
<body>    
    <div class="container">
        <?php require_once './includes/header.php' ?>
        <div class="content">
            <div class="block p-20 form-container">
                <h1>Ecrire un article</h1>
                <form enctype="multipart/form-data" action="" method="POST">
                    <div class="form-control">
                        <label for="title">Titre</label>
                        <input type="text" name="title" value="<?= $article_title ? $article_title : "" ?>">
                        <?php if($errors['title'] !== "") : ?>
                            <p class="text-danger"><?= $errors['title']?></p>
                        <?php endif;?>
                    </div>
                    <div class="form-control">
                        <label for="img">Image</label>
                        <input type="file" name="img" id="img">
                        <?php if($errors['img'] !== "") : ?>
                            <p class="text-danger"><?= $errors['img']?></p>
                        <?php endif;?>
                    </div>
                    <div class="form-control">
                        <label for="category">Cat??gorie</label>
                        <select name="category">
                            <option value="nature" <?php if($article_category === "nature") echo 'selected="selected"'?>>Nature</option>
                            <option value="technologie" <?php if($article_category === "technologie") echo 'selected="selected"'?>>Technologie</option>
                            <option value="politique" <?php if($article_category === "politique") echo 'selected="selected"'?>>Politique</option>
                        </select>
                        <?php if($errors['category'] !== "") : ?>
                            <p class="text-danger"><?= $errors['category']?></p>
                        <?php endif;?>
                    </div>
                    <div class="form-control">                
                        <label for="description">Description</label>
                        <textarea name="description"><?= $article_description ? $article_description : "" ?></textarea>
                        <?php if($errors['description'] !== "") : ?>
                            <p class="text-danger"><?= $errors['description']?></p>
                        <?php endif;?>
                    </div> 
                    <div class="form-action">
                        <button class="btn-return btn" type="button"><a href="index.php">Annuler</a></button>
                        <button class="btn-primary btn" type="submit">Sauvegarder</button>
                    </div>                   
                </form>
            </div>
        </div>
        <?php require_once './includes/footer.php' ?>
    </div>
</body>
</html>