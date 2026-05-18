<?php
session_start();
require_once $_SERVER["DOCUMENT_ROOT"] . "/sae203/php/utils.php";

if (!isset($_SESSION['user'])) {
    header("Location: login.php");
    exit;
}

$id = isset($_GET['quizId']) ? (int)$_GET['quizId'] : null;

if (isset($_GET["quizId"]) && is_numeric($_GET["quizId"])) {
    $id = $_GET["quizId"];
    $query = "SELECT * FROM sae203_quiz WHERE id = $id ";
    $quiz = getInfoDataBase($query);
    $quizName = $quiz[0]['name'];

    // Récupérer les questions
    $query = "SELECT * FROM sae203_question WHERE quiz = $id ";
    $questions = getInfoDataBase($query);

    $questionIndex = 1;
    foreach ($questions as $question) {
        $questionId = $question['id'];
        $questionText = htmlspecialchars($question['question']);

        // Récupérer les réponses de chaque question
        $query = "SELECT * FROM sae203_reponse WHERE question = $questionId ";
        $reponses = getInfoDataBase($query);

        // Génèrer le HTML de la question pré existante
        $questionsHTML .= "
            <div class='question-card' id='q{$questionIndex}' data-qindex='{$questionIndex}'>
                <input type='hidden' name='questions[{$questionIndex}][id]' value='{$questionId}'>
                <div class='question-header'>
                    <h3>Question N°{$questionIndex}</h3>
                    <button type='button' class='delete-question-btn'>Supprimer la question</button>
                </div>
                <div class='form-group'>
                    <input type='text' name='questions[{$questionIndex}][text]' class='question-input' value='{$questionText}' required>
                </div>
                <div class='answers-section'>
                    <h4>Réponses possibles</h4>
                    <div class='answers-list'>";

        $reponseIndex = 1;
        foreach ($reponses as $reponse) {
            $reponseId = $reponse['id'];
            $reponseContent = htmlspecialchars($reponse['content']);
            $checked = $reponse['bonne_reponse'] ? "checked" : "";

            // Générer le HTML de la réponse pré existante
            $questionsHTML .= "
                        <div class='answer-item'>
                            <input type='hidden' name='questions[{$questionIndex}][answers][{$reponseIndex}][id]' value='{$reponseId}'>
                            <input type='text' name='questions[{$questionIndex}][answers][{$reponseIndex}][text]' value='{$reponseContent}' required>
                            <label class='checkbox-container'>
                                <input type='checkbox' name='questions[{$questionIndex}][answers][{$reponseIndex}][correct]' value='1' {$checked}>
                                <span class='checkmark'></span> Bonne réponse
                            </label>
                            <button type='button' class='delete-answer-btn'>&times;</button>
                        </div>";
            $reponseIndex++;
        }

        $questionsHTML .= "
                    </div>
                    <button type='button' class='add-answer-btn'>+ Ajouter une réponse</button>
                </div>
            </div>";
        $questionIndex++;
    }

}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $quizName = $_POST['name'];
    $creatorId = 1;
    $questionsData = isset($_POST['questions']) ? $_POST['questions'] : [];

    $result = saveOrUpdateQuiz($id, $quizName, $creatorId, $questionsData);
    if ($result === "OK") {
        header("Location: edit-quiz.php?quizId=" . ($quizId ?? $_GET['quizId'] ?? ""));
        exit;
    } else {
        echo "<div class='error'>$result</div>";
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
    <h1><?= $id ? "Édition du Quiz" : "Création de Quiz" ?></h1>

    <form action="" method="post" id="edit-quiz">
        <div class="form-group main-title">
            <label for="name">Nom du Quiz</label>
            <input type="text" name="name" id="name" value="<?= $quizName ?>"
                   placeholder="Ex: Culture Générale #1" required>
        </div>

        <div id="questions-container">
            <?php if (!empty($questionsHTML)): ?>
                <?= $questionsHTML ?>
            <?php else: ?>
                <!-- Modèle vide par défaut si c'est une création -->
                <div class="question-card" id="q1" data-qindex="1">
                    <div class="question-header">
                        <h3>Question N°1</h3>
                        <button type="button" class="delete-question-btn">Supprimer la question</button>
                    </div>
                    <div class="form-group">
                        <input type="text" name="questions[1][text]" class="question-input"
                               placeholder="Votre question..." required>
                    </div>
                    <div class="answers-section">
                        <h4>Réponses possibles</h4>
                        <div class="answers-list">
                            <div class="answer-item">
                                <input type="text" name="questions[1][answers][1][text]" placeholder="Réponse 1"
                                       required>
                                <label class="checkbox-container">
                                    <input type="checkbox" name="questions[1][answers][1][correct]" value="1">
                                    <span class="checkmark"></span> Bonne réponse
                                </label>
                                <button type="button" class="delete-answer-btn">&times;</button>
                            </div>
                        </div>
                        <button type="button" class="add-answer-btn">+ Ajouter une réponse</button>
                    </div>
                </div>
            <?php endif; ?>
        </div>

        <div class="form-actions">
            <button type="button" id="add-question-btn" class="btn-secondary">Ajouter une question</button>
            <button type="submit" id="submit-btn">Enregistrer le quiz</button>
        </div>
    </form>
</main>
<?php require_once $_SERVER["DOCUMENT_ROOT"] . "/sae203/includes/footer.php"; ?>
</body>
<?php require_once $_SERVER["DOCUMENT_ROOT"] . "/sae203/includes/load-js.php"; ?>
<script src="js/edit-quiz.js"></script>
</html>