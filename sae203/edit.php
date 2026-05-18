<?php
session_start();
require_once $_SERVER["DOCUMENT_ROOT"] . "/sae203/php/utils.php";

if (!isset($_SESSION['user'])) {
    header("Location: login.php");
    exit;
}

$id = isset($_GET['quizId']) ? (int)$_GET['quizId'] : null;
$quizName = "";
$questionsHTML = "";
$isCreator = false;

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['action_delete'])) {
    if ($id) {
        $queryCheck = "SELECT creator FROM sae203_quiz WHERE id = $id";
        $checkResult = getInfoDataBase($queryCheck);

        if (!empty($checkResult) && $checkResult[0]['creator'] == $_SESSION['user']['id']) {
            $queryDeleteAnswers = "DELETE FROM sae203_reponse WHERE question IN (SELECT id FROM sae203_question WHERE quiz = $id)";
            getInfoDataBase($queryDeleteAnswers);

            $queryDeleteQuestions = "DELETE FROM sae203_question WHERE quiz = $id";
            getInfoDataBase($queryDeleteQuestions);

            $queryDeleteResults = "DELETE FROM sae203_resultat WHERE quiz = $id";
            getInfoDataBase($queryDeleteResults);

            $queryDeleteQuiz = "DELETE FROM sae203_quiz WHERE id = $id";
            getInfoDataBase($queryDeleteQuiz);

            header("Location: catalogue.php?deleted=success");
            exit;
        } else {
            $errorMsg = "Vous n'avez pas l'autorisation de supprimer ce quiz.";
        }
    }
}

if ($id) {
    $query = "SELECT * FROM sae203_quiz WHERE id = $id ";
    $quiz = getInfoDataBase($query);

    if (!empty($quiz)) {
        $quizName = $quiz[0]['name'];
        // On vérifie si l'utilisateur en session est le créateur
        $isCreator = ($quiz[0]['creator'] == $_SESSION['user']['id']);

        $query = "SELECT * FROM sae203_question WHERE quiz = $id ";
        $questions = getInfoDataBase($query);

        $questionIndex = 1;
        foreach ($questions as $question) {
            $questionId = $question['id'];
            $questionText = htmlspecialchars($question['question']);

            // Récupérer les réponses de chaque question
            $query = "SELECT * FROM sae203_reponse WHERE question = $questionId ";
            $reponses = getInfoDataBase($query);

            // Générer le HTML de la question pré existante
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
}

if ($_SERVER['REQUEST_METHOD'] === 'POST' && !isset($_POST['action_delete'])) {
    $quizName = $_POST['name'];
    $creatorId = $_SESSION['user']['user_id'];
    $questionsData = $_POST['questions'] ?? [];

    $result = saveOrUpdateQuiz($id, $quizName, $creatorId, $questionsData);
    if ($result === "OK") {
        header("Location: catalogue.php");
        exit;
    } else {
        $errorMsg = $result;
    }
}
?>

<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?= $id ? "Édition" : "Création" ?> | Quiz Zest</title>
    <?php require_once $_SERVER["DOCUMENT_ROOT"] . "/sae203/includes/load-css.php"; ?>
    <link rel="stylesheet" href="css/edit.css">
</head>
<body>
<?php require_once $_SERVER["DOCUMENT_ROOT"] . "/sae203/includes/header.php"; ?>

<main class="quiz-container">
    <h1><?= $id ? "Édition du Quiz" : "Création de Quiz" ?></h1>

    <?php if (isset($errorMsg)): ?>
        <div class="error-banner">
            <?= htmlspecialchars($errorMsg) ?>
        </div>
    <?php endif; ?>

    <form action="" method="post" id="edit-quiz">
        <div class="form-group main-title">
            <label for="name">Nom du Quiz</label>
            <input type="text" name="name" id="name" value="<?= htmlspecialchars($quizName) ?>"
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
                        <button type='button' class='delete-question-btn'>Supprimer la question</button>
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
            <div class="left-actions">
                <button type="button" id="add-question-btn" class="btn-secondary">Ajouter une question</button>
                <button type="submit" id="submit-btn" class="btn-primary">Enregistrer le quiz</button>
            </div>

            <?php if ($id && $isCreator): ?>
                <button type="submit" name="action_delete" id="delete-quiz-btn" class="btn-delete"
                        onclick="return confirm('⚠️ Êtes-vous sûr de vouloir supprimer définitivement ce quiz ? Cette action est irréversible.');">
                    <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none"
                         stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"
                         style="margin-right: 6px; vertical-align: text-bottom;">
                        <polyline points="3 6 5 6 21 6"></polyline>
                        <path d="M19 6v14a2 2 0 0 1-2 2H7a2 2 0 0 1-2-2V6m3 0V4a2 2 0 0 1 2-2h4a2 2 0 0 1 2 2v2"></path>
                        <line x1="10" y1="11" x2="10" y2="17"></line>
                        <line x1="14" y1="11" x2="14" y2="17"></line>
                    </svg>
                    Supprimer le quiz
                </button>
            <?php endif; ?>
        </div>
    </form>
</main>
<?php require_once $_SERVER["DOCUMENT_ROOT"] . "/sae203/includes/footer.php"; ?>
</body>
<?php require_once $_SERVER["DOCUMENT_ROOT"] . "/sae203/includes/load-js.php"; ?>
<script src="js/edit-quiz.js"></script>
</html>