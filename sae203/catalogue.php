<?php
session_start();
require_once $_SERVER["DOCUMENT_ROOT"] . "/sae203/php/utils.php";

if(!isset($_SESSION['user'])){
    header("Location: /sae203/login.php");
    exit;
}

$query = "SELECT * FROM sae203_quiz";
$result = getInfoDataBase($query);
?>

<!DOCTYPE html>
<html>
<head>
    <title>Quizs</title>
    <?php require_once $_SERVER["DOCUMENT_ROOT"] . "/sae203/includes/load-css.php"; ?>
    <link rel="stylesheet" href="css/quizList.css">
</head>
<body>
<?php require_once $_SERVER["DOCUMENT_ROOT"] . "/sae203/includes/header.php"; ?>
<main>
    <h1>Liste des quiz</h1>
    <a href="edit.php"><span>+</span>Créer un quiz </a>
    <div id="container">
        <?php

        foreach ($result as $quiz) {
            $creatorId = $quiz['creator'];
            $query = "SELECT username FROM sae203_user WHERE id = '$creatorId'";
            $user = getInfoDataBase($query);
            echo <<< EOD
            <div class='quiz-card'>
                <h3>{$quiz["name"]}</h3>
            EOD;
            if ($user[0]['username'] == $_SESSION['user']['username']) {
                echo "<a href='edit.php?quizId=" . $quiz['id']. "'>";
                echo "<img src='assets/edit.svg' alt='edit-icon' width='20px' />";
                echo "</a>";
            }

            echo <<< EOD
                
                <p>Par <span>{$user[0]['username']}</span></p>
                <a href="/sae203/quiz.php?quizId={$quiz["id"]}">Découvrir --></a>
            </div>
            EOD;
        }
        ?>
    </div>
</main>
<?php require_once $_SERVER["DOCUMENT_ROOT"] . "/sae203/includes/footer.php"; ?>
</body>
<?php require_once $_SERVER["DOCUMENT_ROOT"] . "/sae203/includes/load-js.php"; ?>
</html>