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
    <title>Modifier le Profil - Nexa</title>
    <link href="../css/modifier_profil.css" rel="stylesheet" type="text/css">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="description" content="Nexa !">
    <meta name="author" content="Henricy Limosani Safran Amettler Zoppi Bedos">
    <link href="https://fonts.googleapis.com/css2?family=Roboto:wght@400;700&display=swap" rel="stylesheet">
    <link rel="icon" href="../Images/Logos/Logo_Nexa-smaller.png">
</head>

<body class="modif-profil">

    <?php include('header.php'); ?>


    <main>
        <section class="profile">
            <div class="profile-info">
                <img src="../<?php echo $user_data['pp']; ?>" alt="Photo de profil" class="pp pp-hover">
                <h1>
                    <?php echo $user; ?>
                </h1>
                <p>Description ou informations sur l'utilisateur</p>
            </div>
            <div class="profile-edit">
                <h2>Modifier le Profil</h2>
                <form action="../User/ChangeUserInfo" method="post" enctype="multipart/form-data">
                    <label for="newPP">Nouvelle Photo de Profil</label>
                    <input type="file" id="newPP" name="newPP">

                    <label for="newDesc">Nouvelle Description</label>
                    <textarea id="newDesc" name="newDesc"><?php echo $user_data['description']; ?></textarea>

                    <input type="submit" value="Enregistrer les Modifications">
                </form>
            </div>
        </section>
    </main>
    <?php include('footer.php'); ?>

    <!-- Autres éléments de votre page, scripts, etc. -->
</body>

</html>