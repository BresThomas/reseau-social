<?php
session_start();
$user = isset($_SESSION['username']) ? $_SESSION['username'] : 'err_user';

if ($_SESSION['username'] == 'err_user' || $_SESSION['utilisateur_connecte'] !== true || !isset($_SESSION['utilisateur_connecte'])) {
    header("Location: ../");
}
// Après que l'utilisateur se soit connecté avec succès

require_once('../ctrl/UserController.php');
$userCtrl = new UserController();
$user_data = $userCtrl->getUser($user);

//     Partie commentée pour récupérer les messages

require_once('../ctrl/PostController.php');
if (isset($_GET['category'])) {
    $categorie = $_GET['category'];
    $postCtrl = new PostController();
    $posts = $postCtrl->getPostsByCategory($categorie, $user);

    require_once('../ctrl/CategorieController.php');
    $categorieCtrl = new CategorieController();
    $categorieLibelle = $categorieCtrl->getLibelleCategorie($categorie);

}

//elseif (isset($_GET['scearch'])) {
//    $contenu = $_GET['scearch'];
//    $postCtrl = new PostController();
//    $posts = $postCtrl->getPostsByContenu($contenu, $user);
//}
//elseif (isset($_GET['search'])) {
//    $searchTerm = $_GET['search'];
//    $postCtrl = new PostController();
//    $posts = $postCtrl->getPostsByContenu(); // Fournir le terme de recherche et l'utilisateur
//}

else {
    $postCtrl = new PostController();
    $posts = $postCtrl->getPostsAll($user);
    $categorieLibelle = "Toutes les catégories";
}



require_once('../ctrl/CategorieController.php');
$categorieCtrl = new CategorieController();
$categorie_fetch = $categorieCtrl->getCategorieAll();

$categorieLeftPannel = $categorie_fetch;
$categorieRightPannel = $categorie_fetch;



$is_moderator = $user_data['is_moderator'];
?>
<!DOCTYPE html>
<html lang="fr">

<head>
    <meta charset="UTF-8">
    <title>Nexa - Réseau Social</title>
    <link rel="stylesheet" href="../css/dashboard.css">
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="description" content="Nexa !">
    <meta name="author" content="Henricy Limosani Safran Amettler Zoppi Bedos">
    <link rel="icon" href="../Images/Logos/Logo_Nexa-smaller.png">
    <title>Nexa</title>
</head>

<body class="dashboard-body">

    <?php include('message_ESI.php'); ?>

    <?php include('header.php'); ?>

    <main class="main-content">
        <section class="left-panel">
            <?php if ($is_moderator == 1): ?>
                <h2 class="section-title">Ajouter une catégorie</h2>
                <form id="addCategoryForm" class="category-form" action="../Categorie/ajouterCategorie" method="post">
                    <input type="text" name="nom_categorie" id="newCategory" class="category-input"
                        placeholder="Nouvelle catégorie" required>
                    <input type="text" name="libelle" id="newLibelle" class="category-input"
                        placeholder="Libellé de la catégorie" required>
                    <button type="submit" class="category-button dashboard-button">Ajouter</button>
                </form>
            <?php endif; ?>
            <h2 class="section-title">Catégories</h2>
            <ul id="categoryList" class="category-list">
                <?php
                foreach ($categorieLeftPannel as $categorie): ?>
                    <a href="dashboard.php?category=<?php echo $categorie['nom_categorie']; ?>">
                        <h3 class="category-item">
                            <?php echo $categorie['nom_categorie']; ?>
                        </h3>
                    </a>
                <?php endforeach; ?>
            </ul>

            <?php if ($is_moderator == 1): ?>
                <h2 class="section-title">Supprimer une catégorie</h2>
                <form id="removeCategoryForm" class="category-form" action="../Categorie/removeCategorie" method="post">
                    <input type="text" name="nom_categorie" id="newCategory" class="category-input"
                        placeholder="Catégorie à supprimer" required>
                    <button type="submit" class="category-button dashboard-button">Supprimer</button>
                </form>
            <?php endif; ?>
        </section>


        <section class="right-panel">
            <button id="ouvrirPublication" class="dashboard-button">Nouvelle publication</button>

            <h3>
                <?php echo $categorieLibelle; ?>
            </h3>

            <div id="modal" class="modal">
                <div class="modal-content">
                    <form action="../Post/sendPost" method="post" enctype="multipart/form-data">
                        <div class="input-container">
                            <label for="contenu">Contenu :</label>
                            <textarea id="contenu" name="contenu"></textarea>
                        </div>
                        <div class="input-container">
                            <label for="image">Image :</label>
                            <input type="file" id="image" name="image">
                        </div>
                        <div class="input-container">
                            <label for="CategorieSelect">Choisissez une catégorie :</label>
                            <select id="categorieSelect" name="categorie">
                                <?php
                                foreach ($categorieRightPannel as $category): ?>
                                    <option value="<?php echo $category['nom_categorie']; ?>">
                                        <?php echo $category['nom_categorie']; ?>
                                    </option>
                                <?php endforeach;
                                ?>
                            </select>

                        </div>
                        <input type="submit" value="Publier">
                    </form>
                </div>
            </div>


            <?php include('afficherpost.php'); ?>


        </section>
    </main>

    <script src="../js/script_dash.js"></script>

    <script>

    // function searchCategory() {
    //     var input, filter, ul, li, a, i, txtValue;
    //     input = document.getElementById("searchInput");
    //     filter = input.value.toUpperCase();
    //     ul = document.getElementById("categoryList");
    //     li = ul.getElementsByTagName("a");
    //     for (i = 0; i < li.length; i++) {
    //         a = li[i].getElementsByTagName("h3")[0];
    //         txtValue = a.textContent || a.innerText;
    //         if (txtValue.toUpperCase().indexOf(filter) > -1) {
    //             li[i].style.display = "";
    //             console.log("ok");
    //         } else {
    //             li[i].style.display = "none";
    //             console.log("no");
    //         }
    //     }
    // }

    // function searchPost() {
    //     var input, filter, ul, li, a, i, txtValue;
    //     input = document.getElementById("searchInput");
    //     filter = input.value.toUpperCase();
    //     ul = document.getElementsByClassName("post");
    //     li = ul;
    //     for (i = 0; i < li.length; i++) {
    //         a = li[i].getElementsByTagName("h3")[0];
    //         txtValue = a.textContent || a.innerText;
    //         if (txtValue.toUpperCase().indexOf(filter) > -1) {
    //             li[i].style.display = "";
    //             console.log("ok");
    //         } else {
    //             li[i].style.display = "none";
    //             console.log("no");
    //         }
    //     }
    // }

    </script>

<?php include('footer.php'); ?>

</body>

</html>