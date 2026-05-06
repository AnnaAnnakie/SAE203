<?php
session_start();
require_once $_SERVER["DOCUMENT_ROOT"] . "/sae203/php/utils.php";

$query = "SELECT * FROM sae203_user where username = 'aaah' ";
$result = getInfoDataBase($query);
?>

<!DOCTYPE html>
<html>
    <head>
        <title>Accueil</title>
        <?php require_once $_SERVER["DOCUMENT_ROOT"] . "/sae203/includes/load-css.php"; ?>
    </head>
    <body>
        <?php require_once $_SERVER["DOCUMENT_ROOT"] . "/sae203/includes/header.php"; ?>


        <?php require_once $_SERVER["DOCUMENT_ROOT"] . "/sae203/includes/footer.php"; ?>
    </body>
    <?php require_once $_SERVER["DOCUMENT_ROOT"] . "/sae203/includes/load-js.php"; ?>
</html>