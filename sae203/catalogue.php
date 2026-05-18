<?php
session_start();
require_once $_SERVER["DOCUMENT_ROOT"] . "/sae203/php/utils.php";

if (!isset($_SESSION['user'])) {
    header("Location: /sae203/login.php");
    exit;
}

$query = "SELECT q.*, u.username AS creator_name 
          FROM sae203_quiz q 
          JOIN sae203_user u ON q.creator = u.id";
$result = getInfoDataBase($query);
?>

<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Catalogue des Quizs | Quiz Zest</title>
    <?php require_once $_SERVER["DOCUMENT_ROOT"] . "/sae203/includes/load-css.php"; ?>
    <link rel="stylesheet" href="css/quizList.css">
</head>
<body>
<?php require_once $_SERVER["DOCUMENT_ROOT"] . "/sae203/includes/header.php"; ?>

<main>
    <div class="catalog-header">
        <div>
            <h1>Explorez les Quizs</h1>
            <p class="subtitle">Prêt à tester vos connaissances et marquer le meilleur score ?</p>
        </div>
        <a href="edit.php" class="btn-create">
            <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 24 24" fill="none"
                 stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round">
                <line x1="12" y1="5" x2="12" y2="19"></line>
                <line x1="5" y1="12" x2="19" y2="12"></line>
            </svg>
            Créer un quiz
        </a>
    </div>

    <div id="container">
        <?php
        if (empty($result)): ?>
            <div class="empty-state">
                <p>Aucun quiz n'est disponible pour le moment. Soyez le premier à en créer un !</p>
            </div>
        <?php else:
            foreach ($result as $quiz) {
                $isCreator = ($quiz['creator_name'] === $_SESSION['user']['username']);
                ?>
                <div class="quiz-card">
                    <div class="card-top">
                        <span class="quiz-badge">Quiz</span>
                        <?php if ($isCreator): ?>
                            <a href="edit.php?quizId=<?= $quiz['id'] ?>" class="edit-btn" title="Modifier ce quiz">
                                <img src="assets/edit.svg" alt="edit-icon" width="18px"/>
                            </a>
                        <?php endif; ?>
                    </div>

                    <h3><?= $quiz["name"] ?></h3>

                    <div class="card-bottom">
                        <p class="author">Par <span><?= $quiz['creator_name'] ?></span></p>
                        <a href="/sae203/quiz.php?quizId=<?= $quiz['id'] ?>" class="btn-discover">
                            Découvrir
                            <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24"
                                 fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round"
                                 stroke-linejoin="round">
                                <line x1="5" y1="12" x2="19" y2="12"></line>
                                <polyline points="12 5 19 12 12 19"></polyline>
                            </svg>
                        </a>
                    </div>
                </div>
                <?php
            }
        endif;
        ?>
    </div>
</main>

<?php require_once $_SERVER["DOCUMENT_ROOT"] . "/sae203/includes/footer.php"; ?>
</body>
<?php require_once $_SERVER["DOCUMENT_ROOT"] . "/sae203/includes/load-js.php"; ?>
</html>