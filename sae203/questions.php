<?php
session_start();
require_once $_SERVER["DOCUMENT_ROOT"] . "/sae203/php/utils.php";

if(!isset($_SESSION['user'])){
    header("Location: /sae203/login.php");
    exit;
}

if (empty($_GET["quizzId"])) {
    header("Location: index.php");
    exit();
} else {
    $quizzId = $_GET['quizzId'];

    $questionsTab = getInfoDataBase("SELECT * FROM sae203_question WHERE quizz = $quizzId");

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
<html>
<head>
    <title></title>
    <?php require_once $_SERVER["DOCUMENT_ROOT"] . "/sae203/includes/load-css.php"; ?>
    <link rel="stylesheet" href="css/quizz.css">
</head>
<body>
<?php require_once $_SERVER["DOCUMENT_ROOT"] . "/sae203/includes/header.php"; ?>
<main>
    <fieldset>
        <legend id="question-title"></legend>

        <div id="reponses">
        </div>
        <div id="btns">
            <button id="prev-btn">ANNULER</button>
            <button id="submit-btn">VALIDER</button>
            <button id="next-btn" hidden>SUIVANTE</button>
        </div>
    </fieldset>
</main>
<?php require_once $_SERVER["DOCUMENT_ROOT"] . "/sae203/includes/footer.php"; ?>
</body>
<script>
    const dataset = <?php echo json_encode($fullQuizData); ?>;
    console.log(dataset);
</script>
<script src="js/questions.js"></script>
<?php require_once $_SERVER["DOCUMENT_ROOT"] . "/sae203/includes/load-js.php"; ?>
</html>