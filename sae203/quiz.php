<?php
session_start();
require_once $_SERVER["DOCUMENT_ROOT"] . "/sae203/php/utils.php";

if (!isset($_SESSION['user'])) {
    header("Location: /sae203/login.php");
    exit;
}

$quiz = $_GET['quizId'];
$query = "SELECT * FROM sae203_quiz WHERE id = '$quiz'";
$quizInfos = getInfoDataBase($query)[0];

$query = "SELECT * FROM sae203_question WHERE quiz = $quiz ";
$questions = getInfoDataBase($query);
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
    <h1>quiz <?= $quizInfos['name'] ?></h1>
    <?php if (isset($questions[0])){
        echo "<a href='questions.php?quizId=<?= $quiz[0] ?>'>Commencer</a>";
     }else{
        echo "<p> Aucune question disponible</p>";
    }  ?>

</main>

<?php require_once $_SERVER["DOCUMENT_ROOT"] . "/sae203/includes/footer.php"; ?>
</body>
<?php require_once $_SERVER["DOCUMENT_ROOT"] . "/sae203/includes/load-js.php"; ?>
</html>
