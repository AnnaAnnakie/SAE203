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

        $query = "SELECT id FROM sae203_user WHERE username = '$username'";
        $userId = getInfoDataBase($query)[0]['id'];

        if ($connectInfo == "OK") {
            session_regenerate_id(true);

            $_SESSION['user'] = [
                    'id' => 1,
                    'username' => $username,
                    'user_id' => $userId
            ];

            header('Location: index.php');
            exit;
        }else{
            $errorMessageSignup = $connectInfo;
        }
    }

}
// TODO : EMPECHER SI LE CHAMP EST PAS BON (contient des ')
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
                    <div class="password-container">
                        <input type="password" name="passwordLogin" id="passwordLogin" required>
                        <div class="toggle-password">
                            <!-- Ton SVG Oeil Ouvert (Visible par défaut) -->
                            <svg class="icon-eye-open" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                                <path d="M2 12s3-7 10-7 10 7 10 7-3 7-10 7-10-7-10-7Z"/>
                                <circle cx="12" cy="12" r="3"/>
                            </svg>
                            <!-- Ton SVG Oeil Fermé/Barré (Caché par défaut) -->
                            <svg class="icon-eye-close hidden" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                                <path d="M9.88 9.88a3 3 0 1 0 4.24 4.24"/>
                                <path d="M10.73 5.08A10.43 10.43 0 0 1 12 5c7 0 10 7 10 7a13.16 13.16 0 0 1-1.67 2.68"/>
                                <path d="M6.61 6.61A13.52 13.52 0 0 0 2 12s3 7 10 7a9.74 9.74 0 0 0 5.39-1.61"/>
                                <line x1="2" y1="2" x2="22" y2="22"/>
                            </svg>
                        </div>
                    </div>

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
                    <label for="passwordLogin">Mot de passe</label>
                    <div class="password-container">
                        <input type="password" name="passwordLogin" id="passwordLogin" required>
                        <div class="toggle-password">
                            <!-- Ton SVG Oeil Ouvert (Visible par défaut) -->
                            <svg class="icon-eye-open" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                                <path d="M2 12s3-7 10-7 10 7 10 7-3 7-10 7-10-7-10-7Z"/>
                                <circle cx="12" cy="12" r="3"/>
                            </svg>
                            <!-- Ton SVG Oeil Fermé/Barré (Caché par défaut) -->
                            <svg class="icon-eye-close hidden" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                                <path d="M9.88 9.88a3 3 0 1 0 4.24 4.24"/>
                                <path d="M10.73 5.08A10.43 10.43 0 0 1 12 5c7 0 10 7 10 7a13.16 13.16 0 0 1-1.67 2.68"/>
                                <path d="M6.61 6.61A13.52 13.52 0 0 0 2 12s3 7 10 7a9.74 9.74 0 0 0 5.39-1.61"/>
                                <line x1="2" y1="2" x2="22" y2="22"/>
                            </svg>
                        </div>
                    </div>

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
