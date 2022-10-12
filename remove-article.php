<?php

$filename = './data/data.json';
$_GET = filter_input_array(INPUT_GET, FILTER_VALIDATE_INT);
$id = $_GET['id'] ?? '';

if ($id) {
    $articles = json_decode(file_get_contents($filename), true) ?? [];
    if (count($articles)) {
        $articleIndex = array_search($id, array_column($articles, 'id'));
        array_splice($articles, $articleIndex, 1);
        file_put_contents($filename, json_encode($articles));
    }
}
header('Location: index.php');

?>