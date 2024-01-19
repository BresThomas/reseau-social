<?php
session_start();
$user = isset($_SESSION['username']) ? $_SESSION['username'] : 'err_user';

if ($_SESSION['username'] == 'err_user' || $_SESSION['utilisateur_connecte'] !== true || !isset($_SESSION['utilisateur_connecte'])) {
    header("Location: ../");
}

require_once('../ctrl/UserController.php');
$userCtrl = new UserController();
$user_data = $userCtrl->getUser($user);



require_once('../ctrl/PostController.php');
if (isset($_GET['id'])) {
    $postId = $_GET['id'];
} else {
    header('dashboard.php');
}
$_SESSION['post_id'] = $postId;
$postCtrl = new PostController();
$post_fetch = $postCtrl->getPost($postId);
$post = $post_fetch;

require_once('../ctrl/CategorieController.php');
$categorieCtrl = new CategorieController();
$categorie_fetch = $categorieCtrl->getCategorieAll();
$categorieLeftPannel = $categorie_fetch;
$categorieRightPannel = $categorie_fetch;

$comments = $postCtrl->getComments($postId);

$is_moderator = $user_data['is_moderator'];

if ($is_moderator == 0) {
    if ($user != $post['user']) {
        $_SESSION['error_message'] = "Vous n'avez pas le droit d'ètre là";
        header("Location: ../views/dashboard.php");
    }
}
?>

<!DOCTYPE html>
<html lang="fr">

<head>
    <meta charset="UTF-8">
    <title>Nexa - Réseau Social</title>
    <link rel="stylesheet" href="../css/supprimerPost.css">
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="description" content="Nexa !">
    <meta name="author" content="Henricy Limosani Safran Amettler Zoppi Bedos">
    <link rel="icon" href="../Images/Logos/Logo_Nexa-smaller.png">
    <title>Nexa</title>
</head>

<body>

    <?php include('message_ESI.php'); ?>



    <?php include('header.php'); ?>


    <main class="main-content">

        <section class="right-panel">


            <div class="post">
                <div class="post-header">
                    <img src="../<?php echo $post['user_pp']; ?>" alt="Photo de profil de l'utilisateur"
                        class="post-pp post-pp-hover">
                    <h3 class="post-username">
                        <?php echo $post['user']; ?>
                    </h3>
                </div>
                <hr class="post-divider">
                <a href="post.php?id=<?php echo $post['id_post']; ?>">
                    <p class="post-content">
                        <?php echo $post['contenu']; ?>
                    </p>
                    <?php if (!empty($post['image'])): ?>
                        <img src="../<?php echo $post['image']; ?>" alt="Photo de profil" class="post-image" />
                    <?php endif; ?>
                </a>
            </div>
            <div class="suppform">
                <h1> Voulez vous vraiment supprimer ce post ainsi que tous ses commentaires ? </h1>
                <form method="post" action="../Post/supprimerPost">
                    <label for="oui">Oui</label>
                    <input type="radio" id="oui" name="choix" value="oui">

                    <input type="hidden" value=<?php echo $post['id_post']; ?> name=id_post>
                    <label for="non">Non</label>
                    <input type="radio" id="non" name="choix" value="non">

                    <input type="submit" value="Soumettre">
                </form>
            </div>
        </section>
        <section class="comments">
            <h2>Commentaires</h2>
            <?php foreach ($comments as $comment): ?>
                <div class="post">
                    <div class="post-header">
                        <img src="../<?php echo $comment['user_pp']; ?>"
                            alt="Photo de profil de l'utilisateur qui a commenté" class="post-pp post-pp-hover">
                        <h3 class="post-username">
                            <?php echo $comment['user']; ?>
                        </h3>
                    </div>
                    <p class="post-content">
                        <?php echo $comment['contenu']; ?>
                    </p>

                </div>
            <?php endforeach; ?>
        </section>
    </main>

    <script src="../js/script_dash.js"></script>
    <?php include('footer.php'); ?>

</body>

</html>