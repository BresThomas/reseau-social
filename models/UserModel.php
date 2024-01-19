<?php

namespace Model;

use PDO;
use PDOException;

ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);
class UserModel
{


    public function connectDB()
    {
        try {
            $db = new PDO('mysql:host=linserv-info-01.campus.unice.fr;dbname=bt200994_reseau-social;charset=utf8', 'bt200994', 'bt200994');
            $db->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
            return $db;
        } catch (PDOException $e) {
            die('Erreur de connexion à la base de données : ' . $e->getMessage());
        }
    }

    public function checkEmailExists($email)
    {
        $query = "SELECT * FROM users WHERE email = :email";
        $stmt = $this->connectDB()->prepare($query);
        $stmt->bindParam(':email', $email, PDO::PARAM_STR);
        $stmt->execute();

        $result = $stmt->fetch(PDO::FETCH_ASSOC);

        return $result !== false;
    }

    public function checkUsernameExists($username)
    {
        $err = "err_user";
        $query = "SELECT * FROM users WHERE username = :username or username = :err";
        $stmt = $this->connectDB()->prepare($query);
        $stmt->bindParam(':username', $username, PDO::PARAM_STR);
        $stmt->bindParam(':err', $err, PDO::PARAM_STR);

        $stmt->execute();

        $result = $stmt->fetch(PDO::FETCH_ASSOC);

        return $result !== false;
    }

    public function createUser($email, $username, $password)
    {
        if ($this->checkEmailExists($email)) {

            session_start();
            $_SESSION['error_message'] = "Email déjà utilisé";
            $_SESSION['utilisateur_connecte'] = false;

            header('Location: ../');
            exit;
        } elseif ($this->checkUsernameExists($username)) {
            session_start();
            $_SESSION['error_message'] = "Username déjà utilisé";
            $_SESSION['utilisateur_connecte'] = false;

            header('Location: ../');
            exit;
        } else {
            $uniqid = uniqid(true);
            $codeInscription = strtoupper(substr($uniqid, -5));

            if (isset($codeInscription)) {
                // require 'vendor/autoload.php';

                // $mail = new PHPMailer(true);
            } else {

                session_start();
                $_SESSION["error_message"] = "Erreur connexion a la base de données";
                $_SESSION['utilisateur_connecte'] = false;

                header('Location: ../');
                exit;
            }
        }
    }

    public function resetPwd($email)
    {
        if (!$this->checkEmailExists($email)) {
            session_start();
            $_SESSION["error_message"] = "Email inexistant, créé un compte !";
            $_SESSION['utilisateur_connecte'] = false;

            header('Location: ../');

            exit;
        }

        $uniqid = uniqid(true);
        $code = strtoupper(substr($uniqid, -5));

        $query = "UPDATE users SET codeMDPOublie = :code WHERE email = :email";
        $stmt = $this->connectDB()->prepare($query);
        $stmt->bindParam(':code', $code, PDO::PARAM_STR);
        $stmt->bindParam(':email', $email, PDO::PARAM_STR);

        if ($stmt->execute()) {
            // require 'vendor/autoload.php';

        } else {

            session_start();
            $_SESSION["error_message"] = "Erreur connexion a la base de données";
            header('Location: ../');
            exit;
        }
    }
    public function checkLogin($username, $password)
    {
        $query = "SELECT * FROM users WHERE (email = :email OR username = :username)";
        $stmt = $this->connectDB()->prepare($query);
        $stmt->bindParam(':email', $username, PDO::PARAM_STR);
        $stmt->bindParam(':username', $username, PDO::PARAM_STR);
        $stmt->execute();

        if (!$stmt->execute()) {
            $errorInfo = $stmt->errorInfo();
            print_r($errorInfo);
        }
        $user = $stmt->fetch(PDO::FETCH_ASSOC);



        if ($user && password_verify($password, $user['mdp'])) {
            session_start();
            $_SESSION['utilisateur_connecte'] = true;
            $_SESSION['email'] = $user['email'];
            $_SESSION['username'] = $user['username'];

            $query = "UPDATE users SET last_connexion = datetime('now') WHERE email = :email";
            $stmt = $this->connectDB()->prepare($query);
            $stmt->bindParam(':email', $user['email'], PDO::PARAM_STR);
            $stmt->execute();

            header('Location: ../views/dashboard.php');
            exit;
        } else {
            session_start();
            $_SESSION['error_message'] = "Identifiant ou mot de passe incorrect";
            $_SESSION['utilisateur_connecte'] = false;


            header('Location: ../');
            exit;
        }
    }

    public function checkCodeVerif($email, $code)
    {
        session_start();
        include('message_ESI.php');

        $query = "SELECT codeMDPOublie FROM users WHERE (email = :email )";
        $stmt = $this->connectDB()->prepare($query);
        $stmt->bindParam(':email', $email, PDO::PARAM_STR);
        $stmt->execute();

        $row = $stmt->fetch(PDO::FETCH_ASSOC);

        if ($row) {
            $codeBD = $row['codeMDPOublie'];
            if ($codeBD === strtoupper($code)) {
                $_SESSION['codeCheck'] = true;

                header('Location: ../views/ChangementMDP.php');
                exit();
            } else {

                $_SESSION["error_message"] = "Code vérif erroné !";
                header('Location: ../views/codeVerif.php');
                exit;
            }

        }
    }

    public function checkCodeVerifInscription($email, $password, $username, $code)
    {
        $pp = 'Images/pdp/Default.png';

        if ($_SESSION["codeInscription"] == strtoupper($code)) {

            $hashed_password = password_hash($password, PASSWORD_DEFAULT);
            $query = "INSERT INTO users (username, email, mdp, first_connexion, pp) VALUES (:username, :email, :hashed_password, DATETIME('now'), :pp)";
            $stmt = $this->connectDB()->prepare($query);
            $stmt->bindParam(':username', $username, PDO::PARAM_STR);
            $stmt->bindParam(':email', $email, PDO::PARAM_STR);
            $stmt->bindParam(':hashed_password', $hashed_password, PDO::PARAM_STR);
            $stmt->bindParam(':pp', $pp, PDO::PARAM_STR);
            $stmt->execute();

            $this->checkLogin($email, $password);
            exit();

        } else {

            session_start();
            $_SESSION["error_message"] = "Code erroné !";
            header("Location: ../views/codeVerifInscription.php");
            exit;
        }


    }


    public function changeMDP($newMDP, $confirmNewMDP, $email)
    {

        if ($newMDP == $confirmNewMDP) {

            $hashed_password = password_hash($newMDP, PASSWORD_DEFAULT);
            $query = "UPDATE users SET mdp = :newMDP WHERE email = :email";
            $stmt = $this->connectDB()->prepare($query);
            $stmt->bindParam(':newMDP', $hashed_password, PDO::PARAM_STR);
            $stmt->bindParam(':email', $email, PDO::PARAM_STR);
            $stmt->execute();


            $this->checkLogin($email, $newMDP);
            session_start();
            $_SESSION["success_message"] = "Mot de passe modifié avec success";
            header('Location: ../');
            exit;
        } else {
            session_start();
            $_SESSION["error_message"] = "Les mots de passes ne coorespondent pas !";
            header('Location: ChangementMDP.php');
            exit;
        }

    }

    public function isModerator($email)
    {
        $query = "SELECT * FROM users WHERE (email = :email OR username = :email)";
        $stmt = $this->connectDB()->prepare($query);
        $stmt->bindParam(':email', $email);
        $stmt->execute();
        $user_data = $stmt->fetch();

        session_start();
        $_SESSION['is_moderator'] = $user_data['is_moderator'];


    }

    public function getUserData($username)
    {
        $query = "SELECT * FROM users WHERE username = :username";
        $stmt = $this->connectDB()->prepare($query);
        $stmt->bindParam(':username', $username, PDO::PARAM_STR);
        $stmt->execute();

        $user_data = $stmt->fetch(PDO::FETCH_ASSOC);


        return $user_data;
    }


    public function ChangeUserInfo($newPP, $newDesc, $user, )
    {
        if (isset($_FILES['newPP']) && $_FILES['newPP']['error'] == 0) {
            $uploadDir = 'Images/pdp/';

            if (!is_dir($uploadDir)) {
                mkdir($uploadDir, 0755, true);
            }

            $imageFileName = uniqid() . '_' . basename($_FILES['newPP']['name']);
            $ppPath = $uploadDir . $imageFileName;

            if (move_uploaded_file($_FILES['newPP']['tmp_name'], $ppPath)) {
                $query = "UPDATE users SET pp = :newPP, description = :newDesc WHERE username = :user";
                $stmt = $this->connectDB()->prepare($query);
                $stmt->bindParam(':newPP', $ppPath, PDO::PARAM_STR);
                $stmt->bindParam(':newDesc', $newDesc, PDO::PARAM_STR);
                $stmt->bindParam(':user', $user, PDO::PARAM_STR);
                $stmt->execute();
            }
        } else {
            $query = "UPDATE users SET description = :newDesc WHERE username = :user";
            $stmt = $this->connectDB()->prepare($query);
            $stmt->bindParam(':newDesc', $newDesc, PDO::PARAM_STR);
            $stmt->bindParam(':user', $user, PDO::PARAM_STR);
            $stmt->execute();
        }

        header("Location: ../views/profil.php");
    }

}