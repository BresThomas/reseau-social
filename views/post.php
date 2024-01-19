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
?>

<!DOCTYPE html>
<html lang="fr">

<head>
    <meta charset="UTF-8">
    <title>Nexa - Réseau Social</title>
    <link rel="stylesheet" href="../css/post.css">
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="description" content="Nexa !">
    <meta name="author" content="Henricy Limosani Safran Amettler Zoppi Bedos">
    <link rel="icon" href="../Images/Logos/Logo_Nexa-smaller.png">
    <title>Nexa</title>
</head>

<body class="post-body">

    <?php include('message_ESI.php'); ?>



    <?php include('header.php'); ?>


    <main class="main-content">

        <section class="right-panel">


            <div class="afficherpost">
                <a href="profilViewer.php?user=<?php echo $post['user']; ?>">
                    <div class="post">
                        <div class="post-header">
                            <img src="../<?php echo $post['user_pp']; ?>" alt="Photo de profil de l'utilisateur"
                                class="post-pp post-pp-hover">
                            <h3 class="post-username">
                                <?php echo $post['user']; ?>
                            </h3>
                        </div>
                </a>
                <hr class="post-divider">
                <p class="post-content">
                    <?php echo $post['contenu']; ?>
                </p>
                <?php if (!empty($post['image'])): ?>
                    <img src="../<?php echo $post['image']; ?>" alt="Photo de profil" class="post-image" />
                <?php endif; ?>
                <div class="post-actions">
                    <button class="post-action like" data-id_post="<?php echo $post['id_post']; ?>" <?php echo $post['user_liked'] > 0 ? 'disabled' : ''; ?>>❤️
                        <?php echo $post['LikeCount']; ?>
                    </button>
                    <a id="post-link" class="post-action comment"
                        href="post.php?id=<?php echo $post['id_post']; ?>">💬</a>
                    <button class="post-action share" id="copy-link">🔗</button>
                    <?php if ($is_moderator == 1 || $post['user'] == $user): ?>
                        <a class="post-action delete" href="supprimerPost.php?id=<?php echo $post['id_post']; ?>">🗑</a>
                    <?php endif; ?>
                </div>
                <div class="comments-container" id="comments<?php echo $post['id_post']; ?>" style="display:none;">
                    <?php
                    $comments = $postCtrl->getComments($post['id_post']);
                    foreach ($comments as $comment):
                        ?>
                        <div class="comment">
                            <p class="comment-content">
                                <?php echo $comment['contenu']; ?>
                            </p>
                            <p class="comment-user">
                                <?php echo $comment['user']; ?>
                            </p>
                        </div>
                    <?php endforeach; ?>
                </div>
            </div>
        </section>
        <section class="comments">
            <h2>Commentaires</h2>
            <form action="../Post/sendComment" method="post">
                <textarea name="contenu" placeholder="Ajouter un commentaire"></textarea>
                <input type="submit" value="Publier">
            </form>
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