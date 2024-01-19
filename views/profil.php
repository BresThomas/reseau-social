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
$postCtrl = new PostController();
$posts = $postCtrl->getAllPostsUser($user);

$userCtrl->getUser($user);

$is_moderator = $user_data['is_moderator'];
?>

<!DOCTYPE html>
<html lang="fr">

<head>
    <meta charset="UTF-8">
    <title>Nexa - Réseau Social</title>
    <link href="../css/profil.css" rel="stylesheet" type="text/css">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="description" content="Nexa !">
    <meta name="author" content="Henricy Limosani Safran Amettler Zoppi Bedos">
    <link href="https://fonts.googleapis.com/css2?family=Roboto:wght@400;700&display=swap" rel="stylesheet">
    <link rel="icon" href="../Images/Logos/Logo_Nexa-smaller.png">
    <title>Nexa</title>
</head>




<body class="profil-body">

    <?php include('message_ESI.php'); ?>


    <?php include('header.php'); ?>


    <body>
        <main>
            <section class="profil">
                <div class="profil-info">
                    <img src="../<?php echo $user_data['pp']; ?>" alt="Photo de profil" class="pp pp-hover">
                    <h1>
                        <?php echo $user; ?>
                    </h1>
                    <?php if ($is_moderator == 1): ?>
                        <h4>Modérateur</h4>
                    <?php endif; ?>
                    <p>
                        Créé le :
                        <?php echo $user_data['first_connexion']; ?>
                    </p>
                    <p>
                        <?php echo $user_data['description']; ?>
                    </p>
                    <a href="modifier_profil.php">Modifier le profil</a>
                </div>
                <div class="profil-posts">
                    <h2>Publications récentes</h2>

                    <?php include('afficherpost.php'); ?>
                </div>
            </section>
        </main>
    </body>

    <script src="../js/script_dash.js"></script>

</html>