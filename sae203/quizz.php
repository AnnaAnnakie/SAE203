<?php
require_once $_SERVER["DOCUMENT_ROOT"] . "/sae203/php/utils.php";

$quizz = $_GET['quizzId'];
$query = "SELECT * FROM sae203_quizz WHERE id = '$quizz'";
$quizzInfos = getInfoDataBase($query)[0];
?>

<!DOCTYPE html>
<html>
<head>
    <title>Question</title>
    <?php require_once $_SERVER["DOCUMENT_ROOT"] . "/sae203/includes/load-css.php"; ?>
</head>
<body>
<?php require_once $_SERVER["DOCUMENT_ROOT"] . "/sae203/includes/header.php"; ?>

<main>
    <h1>Quizz <?= $quizzInfos['name'] ?></h1>
    <a href="questions.php?quizzId=<?= $quizz ?>">Commencer</a>
</main>

<?php require_once $_SERVER["DOCUMENT_ROOT"] . "/sae203/includes/footer.php"; ?>
</body>
<?php require_once $_SERVER["DOCUMENT_ROOT"] . "/sae203/includes/load-js.php"; ?>
</html>
