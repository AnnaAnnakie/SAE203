<?php
session_start();
require_once $_SERVER["DOCUMENT_ROOT"] . "/sae203/php/utils.php";

if (!isset($_SESSION['user'])) {
    header("Location: login.php");
    exit;
}

if (isset($_GET["quizId"]) && is_numeric($_GET["quizId"])) {
    $id = $_GET["quizId"];
    $query = "SELECT * FROM sae203_quiz WHERE id = $id";
    $quiz = getInfoDataBase($query);
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $quizName = $_POST['name'];
    $creatorId = 1;
    $questions = $_POST['questions'];

    $result = saveQuiz($quizName, $creatorId, $questions);
    if ($result === "OK") {
        echo "Le quiz a bien été enregistré !";
    } else {
        echo $result;
    }
}
?>

<!DOCTYPE html>
<html>
<head>
    <title>Edition</title>
    <?php require_once $_SERVER["DOCUMENT_ROOT"] . "/sae203/includes/load-css.php"; ?>
    <link rel="stylesheet" href="css/edit.css">
</head>
<body>
<?php require_once $_SERVER["DOCUMENT_ROOT"] . "/sae203/includes/header.php"; ?>

<main class="quiz-container">
    <h1><?php
        if (isset($quiz)) {
            echo "Édition - " . $quiz[0]['name'];
        } else {
            echo "Création de Quiz";
        } ?></h1>

    <form action="" method="post" id="edit-quiz">
        <div class="form-group main-title">
            <label for="name">Nom du Quiz</label>
            <input type="text" name="name" id="name" placeholder="Ex: Culture Générale #1" required>
        </div>

        <div id="questions-container">

            <div class="question-card" id="q1" data-qindex="1">
                <div class="question-header">
                    <h3>Question N°1</h3>
                    <button type="button" class="delete-question-btn" title="Supprimer la question">
                        Supprimer la question
                    </button>
                </div>

                <div class="form-group">
                    <input type="text" name="questions[1][text]" class="question-input"
                           placeholder="Votre question trop géniale"
                           required>
                </div>

                <!-- Création d'une base de réponses -->
                <div class="answers-section">
                    <h4>Réponses possibles</h4>
                    <div class="answers-list">
                        <div class="answer-item">
                            <input type="text" name="questions[1][answers][1][text]" placeholder="Réponse 1" required>
                            <label class="checkbox-container">
                                <input type="checkbox" name="questions[1][answers][1][correct]" value="1">
                                <span class="checkmark"></span> Bonne réponse
                            </label>
                            <button type="button" class="delete-answer-btn">&times;</button>
                        </div>
                        <div class="answer-item">
                            <input type="text" name="questions[1][answers][2][text]" placeholder="Réponse 2" required>
                            <label class="checkbox-container">
                                <input type="checkbox" name="questions[1][answers][2][correct]" value="1">
                                <span class="checkmark"></span> Bonne réponse
                            </label>
                            <button type="button" class="delete-answer-btn">&times;</button>
                        </div>
                    </div>
                    <button type="button" class="add-answer-btn">+ Ajouter une réponse</button>
                </div>
            </div>

        </div>

        <div class="form-actions">
            <button type="button" id="add-question-btn">Ajouter une question</button>
            <button type="submit" id="submit-btn">Enregistrer le quiz</button>
        </div>
    </form>
</main>

<?php require_once $_SERVER["DOCUMENT_ROOT"] . "/sae203/includes/footer.php"; ?>
</body>
<?php require_once $_SERVER["DOCUMENT_ROOT"] . "/sae203/includes/load-js.php"; ?>
<script src="js/edit-quiz.js"></script>
</html>