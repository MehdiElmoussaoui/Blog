<?php
include 'inc/header.php';
$bdd = new PDO('mysql:host=localhost;dbname=articles;charset=utf8', 'root', '');
$mode_edition = 0;

if (isset($_GET['edit']) AND !empty($_GET['edit'])){
    $mode_edition = 1;
    $edit_id = htmlspecialchars($_GET['edit']);
    $edit_article = $bdd->prepare('SELECT * FROM articles WHERE id = ?');
    $edit_article->execute(array($edit_id));

    if($edit_article->rowCount() == 1){
        $edit_article = $edit_article->fetch();
    }else{
        die('Erreure ! l\'article n\'existe pas...');
    }
}


if (isset($_POST['article_titre'], $_POST['article_contenus'])){
    if (!empty($_POST['article_titre']) AND !empty($_POST['article_contenus'])){
        $article_titre = htmlspecialchars($_POST['article_titre']);
        $article_contenus = htmlspecialchars($_POST['article_contenus']);

        if($mode_edition == 0){

            $ins = $bdd->prepare('INSERT INTO articles (titre, contenus, date_time_at) VALUES (?, ?, NOW())');
            $ins->execute(array($article_titre, $article_contenus));
            $last_id = $bdd->lastInsertId();

            if(isset($_FILES['miniature']) AND !empty($_FILES['miniature']['name'])) {
                if (exif_imagetype($_FILES['miniature']['tmp_name']) == 2) {
                    $chemin = 'miniature/'. $last_id .'.jpg';
                    move_uploaded_file($_FILES['miniature']['tmp_name'], $chemin);
                } else {
                    $message = 'Votre message doit être au format .jpg';
                }
            }
            $message = "Votre article a bien été envoyé <a href='index.php'>Le voir</a>";
        }else{
            $update = $bdd->prepare('UPDATE articles SET titre = ?, contenus = ?, date_time_edition = NOW() WHERE id = ?');
            $update->execute(array($article_titre, $article_contenus, $edit_id));
            header('Location: http://localhost/Articles/article.php?id='.$edit_id);
            $message = "Votre article a bien été mis a jour ! <a href='index.php'>Le voir</a>";
        }




    }else{
        $message = 'Veuillez remplir tous les champs';
    }
}
?>

<!DOCTYPE html>
<html>
    <head>
        <title>Rédaction / Edition</title>
        <meta charset="UTF-8">
    </head>
    <body>
    <div class="container" style="padding-top: 100px">
        <form method="POST" enctype="multipart/form-data">
            <input type="text" name="article_titre" placeholder="Titre de l'article" <?php if($mode_edition == 1) {?>value="<?= $edit_article['titre']?><?php } ?>" style="width: 500px"><br><br>
            <textarea name="article_contenus" placeholder="Contenus de l'article" style="width: 500px; height: 200px;"><?php if($mode_edition == 1) {?><?= $edit_article['contenus']?><?php } ?></textarea><br>

            <?php if ($mode_edition == 0) {?>
               <br>
                <label class="custom-file">
                <input class="custom-file-input" type="file" name="miniature" />
                <span class="custom-file-control"></span>
                </label>
            <?php } ?>
            <br><br><input type="submit" value="Crée l'article" class="btn btn-dark"><br>
        </form>
        <br />
        <?php if(isset($message)){ echo $message; }?>
    </div>
    </body>
</html>
