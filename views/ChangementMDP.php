<?php
session_start();
if (!isset($_SESSION['codeCheck'])) {
    header("Location: codeVerif.php");
}

?>

<!DOCTYPE html>
<html lang="fr">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="description" content="Réinitialisation mot de passe">
    <meta name="author" content="Henricy Limosani Safran Amettler Zoppi Bedos">
    <link href="../css/ChangementMDP.css" rel="stylesheet" type="text/css">
    <link rel="icon" href="Images/Logos/Logo_Nexa-smaller.png">
    <title>Changement de mot de passe</title>
</head>

<body class="ChangementMDP-body">

    <?php include('message_ESI.php'); ?>

    <section class="main">
        <section class="accueil form">
            <section class="head">
                <img class="logo-nexa" src="../Images/Logos/Logo_Nexa.png" alt="Logo Nexa">
            </section>
            <form method="post" action="../User/changeMDP">
                <h3>Changez votre mot de passe</h3>
                <div class=contentExplainForm>
                    <p>Lorem ipsum dolor sit amet consectetur, adipisicing elit. Illo, provident eligendi. Consectetur
                        debitis possimus</p>
                </div>
                <div class="field">
                    <label for="confirmNewMDP">Veuillez entrez votre mot de passe :</label>
                    <section class="field-email">
                        <input type="password" name="newMDP" required>
                    </section>
                </div>

                <div class="field">
                    <label for="confirmNewMDP">Veuillez confirmez votre nouveau mot de passe :</label>
                    <section class="field-email">
                        <input type="password" name="confirmNewMDP" required>
                    </section>
                </div>
                <div class="inscription-connexion-button-container">
                    <input class="connexionButton button" type="submit" value="Changer de mot de passe">
                </div>
            </form>
        </section>
    </section>
    <?php include('footer.php'); ?>

</body>

</html>