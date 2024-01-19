<?php

//namespace ctrl;
use Model\CategorieModel;


require_once $_SERVER['DOCUMENT_ROOT'] . '/models/CategorieModel.php';



ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);
class CategorieController
{
    public function ajouterCategorie()
    {
        if (isset($_SERVER['REQUEST_METHOD']) && $_SERVER['REQUEST_METHOD'] == "POST") {
            $nom_categorie = $_POST['nom_categorie'];
            $libelle = $_POST['libelle'];

            $categorieModel = new CategorieModel();
            $categorieModel->ajouterCategorie($nom_categorie, $libelle);
        }
    }

    public function removeCategorie()
    {
        if (isset($_SERVER['REQUEST_METHOD']) && $_SERVER['REQUEST_METHOD'] == "POST") {
            $nom_categorie = $_POST['nom_categorie'];
            if ($nom_categorie == 'Divers') {
                session_start();
                $_SESSION['error_message'] = "Vous ne pouvez pas supprimer la categorie divers";
                header("Location: ../views/dashboard.php");
            } else {
                $categorieModel = new CategorieModel();
                $categorieModel->removeCategorie($nom_categorie);
            }
        }
    }


    public function getCategorieAll()
    {
        $categorieModel = new CategorieModel();
        $result = $categorieModel->getCategorieAll();
        return $result;
    }

    public function getLibelleCategorie($categorie)
    {

        $categorieModel = new CategorieModel();
        $libelle = $categorieModel->getLibelleCategorie($categorie);

        return $libelle;
    }


}
