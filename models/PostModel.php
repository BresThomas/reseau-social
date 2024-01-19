<?php

namespace Model;

use PDO;
use PDOException;

class PostModel
{

    public function connectDB()
    {

        $path = __DIR__ . "/../db/db_nexa.sqlite";
        try {
            $db = new PDO('sqlite:' . $path);
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

    public function getPostsAll($user)
    {
        $query = "SELECT Post.*, (SELECT COUNT(*) FROM Likes WHERE Likes.post_id = Post.id_post) as LikeCount, (SELECT COUNT(*) FROM Likes WHERE Likes.post_id = Post.id_post AND Likes.user = :user) as user_liked, Users.pp AS user_pp FROM Post LEFT JOIN Users ON Post.user = Users.username WHERE id_pere IS NULL ORDER BY Time DESC";
        $stmt = $this->connectDB()->prepare($query);
        $stmt->bindParam(':user', $user);
        $stmt->execute();
        $posts = $stmt->fetchAll();

        return $posts;
    }


    public function getPost($id_post)
    {
        $query = "SELECT Post.*, (SELECT COUNT(*) FROM Likes WHERE Likes.post_id = Post.id_post) as LikeCount, (SELECT COUNT(*) FROM Likes WHERE Likes.post_id = Post.id_post AND Likes.user = :user) as user_liked, Users.pp AS user_pp FROM Post LEFT JOIN Users ON Post.user = Users.username WHERE Post.id_post = :id_post";
        $stmt = $this->connectDB()->prepare($query);
        $stmt->bindParam(':id_post', $id_post, PDO::PARAM_INT);
        $stmt->execute();

        $result = $stmt->fetch(PDO::FETCH_ASSOC);

        return $result;
    }


    public function supprimerPost($id_post, $reponse)
    {
        if ($reponse == 'oui') {
            $query = "DELETE FROM Post WHERE id_post = :id_post OR id_pere = :id_post";
            $stmt = $this->connectDB()->prepare($query);
            $stmt->bindParam(':id_post', $id_post, PDO::PARAM_INT);
            $stmt->execute();
            header("Location: ../views/dashboard.php");
            $_SESSION["success_message"] = "Suppréssion confirmée";
        } else {
            $_SESSION["error_message"] = "Suppréssion annulé";
            header("Location: ../views/dashboard.php");
        }
    }

    public function getPostsByCategory($categorie, $user)
    {
        $query = "SELECT Post.*, 
                  (SELECT COUNT(*) FROM Likes WHERE Likes.post_id = Post.id_post) as LikeCount, 
                  (SELECT COUNT(*) FROM Likes WHERE Likes.post_id = Post.id_post AND Likes.user = :user) as user_liked, 
                  Users.pp AS user_pp 
                  FROM Post 
                  LEFT JOIN Users ON Post.user = Users.username 
                  WHERE categorie = :categorie 
                  AND id_pere IS NULL 
                  ORDER BY Time DESC";

        $stmt = $this->connectDB()->prepare($query);
        $stmt->bindParam(':categorie', $categorie, PDO::PARAM_STR);
        $stmt->bindParam(':user', $user, PDO::PARAM_STR);
        $stmt->execute();

        $results = $stmt->fetchAll(PDO::FETCH_ASSOC);

        return $results;
    }

    public function getPostsByContenu($contenu, $user)
    {
        $query = "SELECT Post.*, 
              (SELECT COUNT(*) FROM Likes WHERE Likes.post_id = Post.id_post) as LikeCount, 
              (SELECT COUNT(*) FROM Likes WHERE Likes.post_id = Post.id_post AND Likes.user = :user) as user_liked, 
              Users.pp AS user_pp 
              FROM Post 
              LEFT JOIN Users ON Post.user = Users.username 
              WHERE contenu LIKE :contenu 
              AND id_pere IS NULL 
              ORDER BY Time DESC";

        $contenu = '%' . $contenu . '%';

        $stmt = $this->connectDB()->prepare($query);
        $stmt->bindParam(':contenu', $contenu, PDO::PARAM_STR);
        $stmt->bindParam(':user', $user, PDO::PARAM_STR);
        $stmt->execute();

        $results = $stmt->fetchAll(PDO::FETCH_ASSOC);

        header("Location: ../views/dashboard.php?search=" . $contenu);
        exit();
    }



    public function getPostLikes($id_post)
    {
        $query = "SELECT COUNT(*) as LikeCount FROM Likes WHERE post_id = :id_post";
        $stmt = $this->connectDB()->prepare($query);
        $stmt->bindParam(':id_post', $id_post, PDO::PARAM_INT);
        $stmt->execute();

        $result = $stmt->fetch(PDO::FETCH_ASSOC);

        return $result['LikeCount'];
    }

    public function getPostUserLiked($id_post, $user)
    {
        $query = "SELECT COUNT(*) as user_liked FROM Likes WHERE post_id = :id_post AND user = :user";
        $stmt = $this->connectDB()->prepare($query);
        $stmt->bindParam(':id_post', $id_post, PDO::PARAM_INT);
        $stmt->bindParam(':user', $user, PDO::PARAM_STR);
        $stmt->execute();

        $result = $stmt->fetch(PDO::FETCH_ASSOC);

        return $result['user_liked'] == 1;
    }



    public function createPost($user, $contenu, $image, $categorie)
    {
        if (isset($_FILES['image']) && $_FILES['image']['error'] == 0) {
            $uploadDir = 'uploads/';

            if (!is_dir($uploadDir)) {
                mkdir($uploadDir, 0755, true);
            }
            $imageFileName = uniqid() . '_' . basename($_FILES['image']['name']);
            $imagePath = $uploadDir . $imageFileName;

            if (!move_uploaded_file($_FILES['image']['tmp_name'], $imagePath)) {
                $imagePath = null;
            }
        } else {
            $imagePath = null;
        }

        $query = "SELECT pp FROM users WHERE username = :username";
        $stmt = $this->connectDB()->prepare($query);
        $stmt->bindParam(':username', $user, PDO::PARAM_STR);
        $stmt->execute();
        $pp = $stmt->fetchColumn();


        $query = "INSERT INTO Post (user, contenu, image, Time, pp, categorie) VALUES (:username, :contenu, :image, datetime('now'), :pp, :categorie)";
        $stmt = $this->connectDB()->prepare($query);
        $stmt->bindParam(':username', $user, PDO::PARAM_STR);
        $stmt->bindParam(':contenu', $contenu, PDO::PARAM_STR);
        $stmt->bindParam(':image', $imagePath, PDO::PARAM_STR);
        $stmt->bindParam(':pp', $pp, PDO::PARAM_STR);
        $stmt->bindParam(':categorie', $categorie, PDO::PARAM_STR);
        $stmt->execute();

        header("Location: ../views/dashboard.php");
    }




    public function getAllPosts()
    {
        $query = "SELECT * FROM Post ORDER BY Time DESC";
        $stmt = $this->connectDB()->prepare($query);
        $stmt->execute();

        $result = $stmt->fetchAll(PDO::FETCH_ASSOC);

        return $result;
    }

    public function getAllPostsUser($user)
    {
        $query = "SELECT *, (SELECT COUNT(*) FROM Likes WHERE Likes.post_id = Post.id_post) as LikeCount, (SELECT COUNT(*) FROM Likes WHERE Likes.post_id = Post.id_post AND Likes.user = :user) as user_liked, Users.pp AS user_pp FROM Post LEFT JOIN Users ON Post.user = Users.username WHERE id_pere IS NULL AND user = :user ORDER BY Time DESC";
        $stmt = $this->connectDB()->prepare($query);
        $stmt->bindParam(':user', $user);
        $stmt->execute();

        $result = $stmt->fetchAll(PDO::FETCH_ASSOC);

        return $result;
    }

    public function getComments($id_pere)
    {
        $query = "SELECT Post.*, Users.pp AS user_pp FROM Post LEFT JOIN Users ON Post.user = Users.username WHERE Post.id_pere = :id_pere ORDER BY Post.Time DESC";
        $stmt = $this->connectDB()->prepare($query);
        $stmt->bindParam(':id_pere', $id_pere);
        $stmt->execute();

        $result = $stmt->fetchAll(PDO::FETCH_ASSOC);

        return $result;

    }

    public function commentPost($user, $contenu, $image, $id_pere)
    {
        if (isset($_FILES['image']) && $_FILES['image']['error'] == 0) {
            $uploadDir = 'uploads/';

            if (!is_dir($uploadDir)) {
                mkdir($uploadDir, 0755, true);
            }

            $imageFileName = uniqid() . '_' . basename($_FILES['image']['name']);
            $imagePath = $uploadDir . $imageFileName;

            if (!move_uploaded_file($_FILES['image']['tmp_name'], $imagePath)) {
                $imagePath = null;
            }
        } else {
            $imagePath = null;
        }

        $query = "SELECT pp FROM users WHERE username = :username";
        $stmt = $this->connectDB()->prepare($query);
        $stmt->bindParam(':username', $user, PDO::PARAM_STR);
        $stmt->execute();
        $pp = $stmt->fetchColumn();


        $query = "INSERT INTO Post (user, contenu, image, Time, pp, id_pere) VALUES (:username, :contenu, :image, datetime('now'), :pp, :id_pere)";
        $stmt = $this->connectDB()->prepare($query);
        $stmt->bindParam(':username', $user, PDO::PARAM_STR);
        $stmt->bindParam(':contenu', $contenu, PDO::PARAM_STR);
        $stmt->bindParam(':image', $imagePath, PDO::PARAM_STR);
        $stmt->bindParam(':pp', $pp, PDO::PARAM_STR);
        $stmt->bindParam(':id_pere', $id_pere, PDO::PARAM_STR);
        $stmt->execute();
        header("Location: ../views/post.php?id=" . $id_pere);


    }

    public function likePost($id_post, $user)
    {
        $query = "SELECT COUNT(*) as user_liked FROM Likes WHERE post_id = :id_post AND user = :user";
        $stmt = $this->connectDB()->prepare($query);
        $stmt->bindParam(':id_post', $id_post, PDO::PARAM_INT);
        $stmt->bindParam(':user', $user, PDO::PARAM_STR);
        $stmt->execute();

        $result = $stmt->fetch(PDO::FETCH_ASSOC);

        if ($result['user_liked'] == 1) {
            $query = "DELETE FROM Likes WHERE post_id = :id_post AND user = :user";
            $stmt = $this->connectDB()->prepare($query);
            $stmt->bindParam(':id_post', $id_post, PDO::PARAM_INT);
            $stmt->bindParam(':user', $user, PDO::PARAM_STR);
            $stmt->execute();

            $query = "UPDATE Post SET Like = Like - 1 WHERE id_post = :id_post";
            $stmt = $this->connectDB()->prepare($query);
            $stmt->bindParam(':id_post', $id_post, PDO::PARAM_INT);
            $stmt->execute();

            return json_encode(['success' => true, 'action' => 'unliked']);
        } else {
            $query = "INSERT INTO Likes (post_id, user) VALUES (:id_post, :user)";
            $stmt = $this->connectDB()->prepare($query);
            $stmt->bindParam(':id_post', $id_post, PDO::PARAM_INT);
            $stmt->bindParam(':user', $user, PDO::PARAM_STR);
            $stmt->execute();

            $query = "UPDATE Post SET Like = Like + 1 WHERE id_post = :id_post";
            $stmt = $this->connectDB()->prepare($query);
            $stmt->bindParam(':id_post', $id_post, PDO::PARAM_INT);
            $stmt->execute();

            return json_encode(['success' => true, 'action' => 'liked']);
        }
    }


}