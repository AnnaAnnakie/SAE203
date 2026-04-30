<?php
require_once $_SERVER["DOCUMENT_ROOT"] . "/sae203/php/utils.php";
if (empty($_GET["quizzId"])) {
    header("Location: index.php");
    exit();
}else{
    $quizzId = $_GET['quizzId'];

    $queryQuestions = "SELECT * FROM `sae203_question` ques join marchean.sae203_quizz quizz on ques.quizz = quizz.id where quizz.id = $quizzId ";
    $questions = getInfoDataBase($queryQuestions);

    $questionId = 1;
    $queryReponses = "SELECT * FROM sae203_reponse where question = $questionId ";
    $reponses = getInfoDataBase($queryReponses);
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
            <legend><?=$questions[0]["question"] ?></legend>

            <div class="reponses">
                <?php
                foreach ($reponses as $reponse) {
                    echo "<div>";
                    echo "<label for='" .$reponse["id"]."'>".$reponse["content"]."</label>";
                    echo "<input type='checkbox' id='". $reponse['id']."' class='checkbox'>";
                    echo "<img src='assets/check-mark.svg' alt='check'>";
                    echo "</div>";
                }
                ?>
            </div>
            <button>VALIDER</button>
        </fieldset>
    </main>
    <?php require_once $_SERVER["DOCUMENT_ROOT"] . "/sae203/includes/footer.php"; ?>
    </body>
<?php require_once $_SERVER["DOCUMENT_ROOT"] . "/sae203/includes/load-js.php"; ?>
    </html><?php
