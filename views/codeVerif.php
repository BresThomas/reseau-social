<?php
session_start();
$email = $_SESSION["email"];

if (!isset($_SESSION['codeSend'])) {
    header("Location: ../");
}

?>

<!DOCTYPE html>
<html lang="fr">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="description" content="Réinitialisation mot de passe">
    <meta name="author" content="Henricy Limosani Safran Amettler Zoppi Bedos">
    <link href="../css/codeVerif.css" rel="stylesheet" type="text/css">
    <link rel="icon" href="Images/Logos/Logo_Nexa-smaller.png">
    <title>Mot de passe oublié</title>
    <link rel="icon" href="../Images/Logos/Logo_Nexa-smaller.png">

</head>

<body class="codeVerif-body">

    <?php include('message_ESI.php'); ?>

    <section class="main">
        <section class="accueil form">
            <section class="head">
                <img class="logo-nexa" src="../Images/Logos/Logo_Nexa.png" alt="Logo Nexa">
            </section>
            <form method="post" action="../User/checkCodeVerif">
                <h3>Mot de passe oublié</h3>
                <div class="contentExplainForm">
                    <p>Entrez votre code de réinitialisation recu par email afin de pouvoir changer votre mot de passe.
                    </p>
                </div>
                <div class="field">
                    <label for="code">Code :</label>
                    <section class="field-email">
                        <input type="code" name="code" required>
                    </section>
                </div>

                <div class="inscription-connexion-button-container">
                    <input class="connexionButton button" type="submit" value="Envoyer le code de réinitialisation">
                </div>

            </form>
        </section>
    </section>
    <?php include('footer.php'); ?>
    <script src="../js/script.js"></script>
</body>

</html>