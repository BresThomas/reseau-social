<?php

namespace Model;

use PDO;
use PDOException;

class CategorieModel
{

    public function connectDB()
    {

        // $path = __DIR__ . "/../db/db_nexa.sqlite";
        try {
            $db = new PDO('mysql:host=linserv-info-01.campus.unice.fr;dbname=bt200994_reseau-social;charset=utf8', 'bt200994', 'bt200994');
            $db->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
            return $db;
        } catch (PDOException $e) {
            die('Erreur de connexion à la base de données : ' . $e->getMessage());
            session_start();
            $_SESSION["error_message"] = "Connexion impossible a la DB !";
            header('Location: ../index.php');
            exit;
        }
    }
    public function ajouterCategorie($nom_categorie, $libelle)
    {
        session_start();

        try {


            $query = "INSERT INTO categorie (nom_categorie, libelle) VALUES (:nom_categorie, :libelle)";
            $stmt = $this->connectDB()->prepare($query);
            $stmt->bindParam(':nom_categorie', $nom_categorie, PDO::PARAM_STR);
            $stmt->bindParam(':libelle', $libelle, PDO::PARAM_STR);

            if ($stmt->execute()) {
                header("Location: ../views/dashboard.php");
                exit;
            } else {
                $_SESSION['error_message'] = "Erreur lors de l'ajout de la catégorie dans la base de données";
            }
        } catch (PDOException $e) {
            $_SESSION['error_message'] = "Erreur lors de l'ajout de la catégorie";
            error_log($e->getMessage());
            exit;
        }
    }

    public function removeCategorie($nom_categorie)
    {
        session_start();

        try {
            $updateQuery = "UPDATE Post SET categorie = 'Divers' WHERE categorie = :nom_categorie";
            $stmtUpdate = $this->connectDB()->prepare($updateQuery);
            $stmtUpdate->bindParam(':nom_categorie', $nom_categorie, PDO::PARAM_STR);
            $stmtUpdate->execute();

            $deleteQuery = "DELETE FROM categorie WHERE nom_categorie = :nom_categorie";
            $stmtDelete = $this->connectDB()->prepare($deleteQuery);
            $stmtDelete->bindParam(':nom_categorie', $nom_categorie, PDO::PARAM_STR);

            if ($stmtDelete->execute()) {
                header("Location: ../views/dashboard.php");
                exit;
            } else {
                $_SESSION['error_message'] = "Erreur lors de la suppression de la catégorie de la base de données";
            }
        } catch (PDOException $e) {
            $_SESSION['error_message'] = "Erreur lors de la suppression de la catégorie";
            error_log($e->getMessage());
            exit;
        }
    }


    public function getCategorieAll()
    {
        try {
            $db = $this->connectDB();

            $query = "SELECT * FROM categorie";
            $stmt = $db->query($query);

            $categories = $stmt->fetchAll(PDO::FETCH_ASSOC);

            return $categories;
        } catch (PDOException $e) {
            error_log("Erreur lors de la récupération de toutes les catégories : " . $e->getMessage());
            return [];
        }
    }

    public function getLibelleCategorie($categorie)
    {
        $query = "SELECT libelle FROM Categorie WHERE nom_categorie = :categorie";
        $stmt = $this->connectDB()->prepare($query);
        $stmt->bindParam(':categorie', $categorie);
        $stmt->execute();

        $result = $stmt->fetch(PDO::FETCH_ASSOC);

        return $result['libelle'];
    }

}
