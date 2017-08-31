<?php
include 'inc/header.php';
$bdd = new PDO('mysql:host=localhost;dbname=articles;charset=utf8', 'root', '');

$articles = $bdd->query('SELECT * FROM articles ORDER BY date_time_at DESC');
?>

<!DOCTYPE html>
<html>
    <head>
        <title>Blog</title>
        <meta charset="UTF-8">
    </head>
    <body>

    <div class="container" style="padding-top: 100px;">
        <ul>
            <?php while($a = $articles->fetch()){ ?>
                <li>
                    <img src="miniature/<?= $a['id'];?>.jpg" width="100px"><br />
                    <a href="article.php?id=<?= $a['id']; ?>"><?= $a['titre'] ?></a> | <a href="redaction.php?edit=<?= $a['id']?>">Modifier</a> | <a href="supprimer.php?id=<?= $a['id']?>">Supprimer</a> </li><br>

            <?php } ?>

        </ul>
        <p>Envie soudaine d'écrire ? <a href="redaction.php">Ajoutez un article</a></p>
    </div>
    </body>
</html>
