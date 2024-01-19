<!DOCTYPE html>
<html lang="fr">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="description" content="Réinitialisation mot de passe">
    <meta name="author" content="Henricy Limosani Safran Amettler Zoppi Bedos">
    <link href="../css/forgotPwd.css" rel="stylesheet" type="text/css">
    <link rel="icon" href="Images/Logos/Logo_Nexa-smaller.png">
    <title>Mot de passe oublié</title>
</head>

<body class="forgotPwd-body">

<?php include('message_ESI.php'); ?>

    <section class="main">
        <section class="accueil form">
            <section class="head">
                <img class="logo-nexa" src="../Images/Logos/Logo_Nexa.png" alt="Logo Nexa">
            </section>
            <form method="post" action="../User/forgotPwd">
                <h3>Mot de passe oublié</h3>
                <div class="contentExplainForm">
                    <p>Entrez votre adresse e-mail pour recevoir un code de réinitialisation.</p>
                </div>
                <div class="field">
                    <label for="email">Adresse e-mail :</label>
                    <section class="field-email">
                        <input type="email" name="email" required>
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