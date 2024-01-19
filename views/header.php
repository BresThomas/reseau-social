<link rel="stylesheet" href="../css/header.css">

<header class="header-body">
    <div class="Logo">
        <a href="dashboard.php"><img src="../Images/Logos/Logo_Nexa.png" alt="Logo Nexa" class="logo-img"></a>
    </div>

    <div class="user-icon">
        <i class="fas fa-user-circle"></i>
        <a href="profil.php" class="username-link"><img src="../<?php echo $user_data['pp']; ?>" alt="Profil"
                class="post-pp post-pp-hover"></a>
        <?php echo $user; ?>
    </div>

    <div class="logout">
        <form action="../User/logout" method="post">
            <input class='button' type="submit" name="logout" value="Se déconnecter">
        </form>
    </div>
</header>