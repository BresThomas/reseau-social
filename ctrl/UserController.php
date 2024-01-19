<?php



require_once $_SERVER['DOCUMENT_ROOT'] . '/models/UserModel.php';


use Model\UserModel;

ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);
class UserController
{



    public function registerUser()
    {
        if (
            isset($_SERVER['REQUEST_METHOD']) &&
            $_SERVER['REQUEST_METHOD'] == "POST"
        ) {
            $email = $_POST["email"];
            $password = $_POST["mot_de_passe"];
            $username = $_POST["username"];
            session_start();
            $_SESSION["email"] = $email;
            $_SESSION["username"] = $username;
            $_SESSION["mot_de_passe"] = $password;

            $userModel = new UserModel();
            $userModel->createUser($email, $username, $password);
        }
    }

    public function loginUser()
    {
        if ($_SERVER['REQUEST_METHOD'] == 'POST') {
            $email = $_POST['email'];
            $password = $_POST['mot_de_passe'];

            $userModel = new UserModel();
            $userModel->checkLogin($email, $password);
            $userModel->isModerator($email);

        }
    }
    public function logout()
    {
        if (isset($_POST['logout'])) {
            session_start();
            session_destroy();
            header('Location: ../');
            exit;
        }
    }


    public function forgotPwd()
    {
        if (
            isset($_SERVER['REQUEST_METHOD']) &&
            $_SERVER["REQUEST_METHOD"] == "POST"
        ) {
            $email = $_POST["email"];
            session_start();

            $_SESSION["email"] = $email;

            $userModel = new UserModel();
            $userModel->resetPwd($email);
        }
    }


    public function checkCodeVerif()
    {
        if (
            isset($_SERVER['REQUEST_METHOD']) &&
            $_SERVER["REQUEST_METHOD"] == "POST"
        ) {

            session_start();

            $code = $_POST["code"];
            $email = $_SESSION["email"];

            $userModel = new UserModel();
            $userModel->checkCodeVerif($email, $code);
        }

    }

    public function checkCodeVerifInscription()
    {
        if (
            isset($_SERVER['REQUEST_METHOD']) &&
            $_SERVER["REQUEST_METHOD"] == "POST"
        ) {

            session_start();

            $code = $_POST["code"];
            $email = $_SESSION["email"];
            $username = $_SESSION["username"];
            $password = $_SESSION["mot_de_passe"];

            $userModel = new UserModel();
            $userModel->checkCodeVerifInscription($email, $password, $username, $code);
        }

    }

    public function changeMDP()
    {
        if (
            isset($_SERVER['REQUEST_METHOD']) &&
            $_SERVER["REQUEST_METHOD"] == "POST"
        ) {

            session_start();

            $newMDP = $_POST["newMDP"];
            $confirmNewMDP = $_POST["confirmNewMDP"];
            $email = $_SESSION["email"];

            $userModel = new UserModel();
            $userModel->changeMDP($newMDP, $confirmNewMDP, $email);
        }
    }

    public function getUser($email)
    {
        $userModel = new UserModel();
        $user = $userModel->getUserData($email);

        return $user;
    }

    public function ChangeUserInfo()
    {
        if (isset($_SERVER['REQUEST_METHOD']) && $_SERVER["REQUEST_METHOD"] == "POST") {
            $newPP = isset($_FILES['newPP']) ? $_FILES['newPP'] : null;

            session_start();
            $newDesc = $_POST["newDesc"];
            $user = $_SESSION["username"];

            $userModel = new UserModel();
            $userModel->ChangeUserInfo($newPP, $newDesc, $user);
        }
    }
}