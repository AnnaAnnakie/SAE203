<?php
session_start();
require_once $_SERVER["DOCUMENT_ROOT"] . "/sae203/php/utils.php";

if(!isset($_SESSION['user'])){
    header("Location: /sae203/login.php");
    exit;
}

?>

<!DOCTYPE html>
<html>
<head>
    <title>Résultat</title>
    <?php require_once $_SERVER["DOCUMENT_ROOT"] . "/sae203/includes/load-css.php"; ?>
</head>
<body>
<?php require_once $_SERVER["DOCUMENT_ROOT"] . "/sae203/includes/header.php"; ?>

<main>

</main>

<?php require_once $_SERVER["DOCUMENT_ROOT"] . "/sae203/includes/footer.php"; ?>
</body>
<?php require_once $_SERVER["DOCUMENT_ROOT"] . "/sae203/includes/load-js.php"; ?>
</html>