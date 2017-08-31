<?php
include 'inc/header.php';
$bdd = new PDO('mysql:host=localhost;dbname=articles;charset=utf8', 'root', '');

if (isset($_GET['id']) AND !empty($_GET['id'])){
    $get_id = htmlspecialchars($_GET['id']);

    $article = $bdd->prepare('SELECT * FROM articles WHERE id = ?');
    $article->execute(array($get_id));

    if($article->rowCount() == 1){
        $article = $article->fetch();
        $titre = $article['titre'];
        $id = $article['id'];
        $contenus = $article['contenus'];

        $likes = $bdd->prepare('SELECT id FROM likes WHERE id_article = ?');
        $likes->execute(array($id));
        $likes->rowCount();

        $dislikes = $bdd->prepare('SELECT id FROM likes WHERE id_article = ?');
        $dislikes->execute(array($id));
        $dislikes->rowCount();
    } else {
        die('Cet article n\'existe pas ! <a href="index.php">Aller en cherche un</a>');
    }
} else {
    die('Erreure');
}

?>

<!DOCTYPE html>
<html>
<head>
    <title>Blog - Article</title>
    <meta charset="UTF-8">
</head>
<body>
<div class="container" style="padding-top: 100px">
    <h1><?= $titre ?></h1>
    <img src="miniature/<?= $id ?>.jpg" width="500px"><br /><br>
    <p><?= $contenus ?></p>
</div>
</body>
</html>
