<?php
session_start();
$user = isset($_SESSION['username']) ? $_SESSION['username'] : 'err_user';

if ($_SESSION['username'] == 'err_user' || $_SESSION['utilisateur_connecte'] !== true || !isset($_SESSION['utilisateur_connecte'])) {
    header("Location: ../");
}

$userViewed = $_GET['user'];
if ($userViewed == $user) {
    header('Location: profil.php');
}
require_once('../ctrl/UserController.php');
$userCtrl = new UserController();
$user_data = $userCtrl->getUser($user);
$userViwed_data = $userCtrl->getUser($userViewed);

require_once('../ctrl/PostController.php');
$postCtrl = new PostController();
$posts = $postCtrl->getAllPostsUser($userViewed);

$userCtrl->getUser($userViewed);

$is_moderator = $user_data['is_moderator'];

?>

<!DOCTYPE html>
<html lang="fr">

<head>
    <meta charset="UTF-8">
    <title>Nexa - Réseau Social</title>
    <link rel="stylesheet" href="../css/profilViewer.css">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="description" content="Nexa !">
    <meta name="author" content="Henricy Limosani Safran Amettler Zoppi Bedos">
    <link href="https://fonts.googleapis.com/css2?family=Roboto:wght@400;700&display=swap" rel="stylesheet">
    <link rel="icon" href="../Images/Logos/Logo_Nexa-smaller.png">
    <title>Nexa</title>
</head>

<?php include('message_ESI.php'); ?>

<?php include('header.php'); ?>


<body class="profilViewer-body">
    <main>
        <section class="profile">
            <div class="profile-info">
                <img src="../<?php echo $userViwed_data['pp']; ?>" alt="Photo de profil" class="pp pp-hover">
                <h1>
                    <?php echo $userViewed; ?>
                </h1>
                <?php if ($userViwed_data['is_moderator'] == 1): ?>
                    <h4>Modérateur</h4>
                <?php endif; ?>

                <p>
                    Dernière connexion :
                    <?php echo $user_data['last_connexion']; ?>
                </p>

                <p>
                    <?php echo $userViwed_data['description']; ?>
                </p>
            </div>
            <div class="profile-posts">
                <h2>Publications récentes</h2>

                <?php include('afficherpost.php'); ?>
            </div>
        </section>
    </main>
    <?php include('footer.php'); ?>

</body>

</html>