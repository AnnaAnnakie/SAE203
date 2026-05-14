<?php
session_start();
require_once $_SERVER["DOCUMENT_ROOT"] . "/sae203/admin/config.php";
require_once $_SERVER["DOCUMENT_ROOT"] . "/sae203/php/connexionFunc.php";

if (isset($_SESSION['user'])) {
    header('Location: index.php');
    exit;
}

$connectInfo = null;
$errorMessageLogin = null;
$errorMessageSignup = null;

if ($_SERVER['REQUEST_METHOD'] === 'POST' ) {
    if (isset($_POST["login"])){
        $username = $_POST['userID'] ?? '';
        $password = $_POST['passwordLogin'] ?? '';

        $connectInfo = connectUser($username, $password);

        if ($connectInfo == "OK") {

            session_regenerate_id(true);

            $_SESSION['user'] = [
                    'id' => 1,
                    'username' => $username
            ];

            header('Location: catalogue.php');
            exit;
        }else{
            $errorMessageLogin = $connectInfo;
        }
    }else{
        $email = $_POST['email'] ?? '';
        $username = $_POST['newUsername'] ?? '';
        $password = $_POST['password'] ?? '';

        $connectInfo = createUser($email, $username, $password);

        if ($connectInfo == "OK") {
            session_regenerate_id(true);

            $_SESSION['user'] = [
                    'id' => 1,
                    'username' => $username
            ];

            header('Location: index.php');
            exit;
        }else{
            $errorMessageSignup = $connectInfo;
        }
    }

}

?>
<!DOCTYPE html>
<html>
<head>
    <title>Connexion</title>
    <?php require_once $_SERVER["DOCUMENT_ROOT"] . "/sae203/includes/load-css.php"; ?>
    <link rel="stylesheet" href="css/login.css">
</head>
    <body>
        <?php require_once $_SERVER["DOCUMENT_ROOT"] . "/sae203/includes/header.php"; ?>

        <main>
            <h1>Connexion</h1>

            <div id="forms">
                <form method="post" class="form" id="connexionForm">
                    <label for="userID">Nom d'utilisateur / Email</label>
                    <input type="text" name="userID" id="userID" required>
                    <label for="passwordLogin">Mot de passe</label>
                    <input type="password" name="passwordLogin"  id="passwordLogin" required>

                    <?php if ($errorMessageLogin): ?>
                        <p class="error"><?= $errorMessageLogin ?></p>
                    <?php endif; ?>

                    <button type="submit" name="login">Se connecter</button>
                </form>
                <hr>

                <form method="post" class="form" id="signUpForm">
                    <label for="email">Email</label>
                    <input type="email" name="email" id="email">
                    <label for="newUsername">Nom d'utilisateur</label>
                    <input type="text" name="newUsername" id="newUsername" required>
                    <label for="password">Mot de passe</label>
                    <input type="password" name="password" id="password" required>

                    <?php if ($errorMessageSignup): ?>
                        <p class="error"><?= $errorMessageSignup ?></p>
                    <?php endif; ?>

                    <button type="submit" name="signup">S'inscrire</button>
                </form>
            </div>
        </main>

        <?php require_once $_SERVER["DOCUMENT_ROOT"] . "/sae203/includes/footer.php"; ?>
    </body>
    <?php require_once $_SERVER["DOCUMENT_ROOT"] . "/sae203/includes/load-js.php"; ?>
</html>
