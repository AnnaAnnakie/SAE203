<?php
session_start();
require_once $_SERVER["DOCUMENT_ROOT"] . "/sae203/php/utils.php";

if (!isset($_SESSION['user'])) {
    header("Location: login.php");
    exit;
}

$username = $_SESSION['user']['username'];
$query = "SELECT * FROM sae203_user WHERE username = '$username'";
$user = getInfoDataBase($query)[0];
?>

<!DOCTYPE html>
<html>
<head>
    <title>Accueil</title>
    <?php require_once $_SERVER["DOCUMENT_ROOT"] . "/sae203/includes/load-css.php"; ?>
</head>
<body>
<?php require_once $_SERVER["DOCUMENT_ROOT"] . "/sae203/includes/header.php"; ?>
<main>
    <div>
        <?php
        if (isset($user['profilepic'])) {
            $imageData = $user['profilepic'];

            $base64 = base64_encode($imageData);

            echo '<img src="data:image/jpeg;base64,' . $base64 . '" alt="Photo de profil" />';
        }else{
            echo "<img src='assets/defaultPP.jpg' alt='Photo de profil' />";
        }
        ?>
        <h1><?= $user['username'] ?></h1>
    </div>
    <a href="logout.php" id="logout">Se déconnecter</a>
</main>

<?php require_once $_SERVER["DOCUMENT_ROOT"] . "/sae203/includes/footer.php"; ?>
</body>
<?php require_once $_SERVER["DOCUMENT_ROOT"] . "/sae203/includes/load-js.php"; ?>
</html>