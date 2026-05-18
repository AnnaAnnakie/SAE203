<?php
session_start();
require_once $_SERVER["DOCUMENT_ROOT"] . "/sae203/php/utils.php";

if(!isset($_SESSION['user'])){
    header("Location: /sae203/login.php");
    exit;
}

if (empty($_GET["quizId"])) {
    header("Location: index.php");
    exit();
} else {
    $quizId = $_GET['quizId'];

    $quizInfo = getInfoDataBase("SELECT name FROM sae203_quiz WHERE id = $quizId");
    $quizTitle = !empty($quizInfo) ? $quizInfo[0]['name'] : "Quiz";

    $questionsTab = getInfoDataBase("SELECT * FROM sae203_question WHERE quiz = $quizId");
    $fullQuizData = [];

    foreach ($questionsTab as $question) {
        $qId = $question['id'];
        $reponsesTab = getInfoDataBase("SELECT * FROM sae203_reponse WHERE question = $qId");

        $fullQuizData[] = [
                "id" => $qId,
                "intitule" => $question['question'],
                "reponses" => $reponsesTab
        ];
    }
}
?>

<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <title><?= $quizTitle ?></title>
    <?php require_once $_SERVER["DOCUMENT_ROOT"] . "/sae203/includes/load-css.php"; ?>
    <link rel="stylesheet" href="css/quiz.css">
</head>
<body>
<?php require_once $_SERVER["DOCUMENT_ROOT"] . "/sae203/includes/header.php"; ?>
<main>
    <div id="score-counter">
        <span class="score-label">Score :</span>
        <span id="current-score">0.00</span> pts
    </div>

    <fieldset>
        <legend id="question-title"></legend>

        <div id="reponses"></div>

        <div id="btns">
            <button id="prev-btn" disabled>RETOUR</button>
            <button id="submit-btn">VALIDER</button>
            <button id="next-btn" hidden>SUIVANTE</button>
        </div>
    </fieldset>

    <form id="finish-form" action="resultat.php?quizId=<?= $quizId ?>" method="POST" style="display:none;">
        <input type="hidden" name="user_answers" id="user-answers-input">
    </form>
</main>
<?php require_once $_SERVER["DOCUMENT_ROOT"] . "/sae203/includes/footer.php"; ?>
<script>
    const dataset = <?php echo json_encode($fullQuizData); ?>;
</script>
<script src="js/questions.js"></script>
</body>
<?php require_once $_SERVER["DOCUMENT_ROOT"] . "/sae203/includes/load-js.php"; ?>
</html>