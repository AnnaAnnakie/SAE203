<?php
session_start();
require_once $_SERVER["DOCUMENT_ROOT"] . "/sae203/php/utils.php";

$query = "SELECT q.*, u.username AS creator_name 
          FROM sae203_quiz q 
          JOIN sae203_user u ON q.creator = u.id";
$result = getInfoDataBase($query);

$current_username = $_SESSION['user']['username'] ?? null;
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
        <?php if ($current_username): ?>
            <a href="edit.php" class="btn-create">
                <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 24 24" fill="none"
                     stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round">
                    <line x1="12" y1="5" x2="12" y2="19"></line>
                    <line x1="5" y1="12" x2="19" y2="12"></line>
                </svg>
                Créer un quiz
            </a>
        <?php endif; ?>
    </div>

    <div class="filter-bar">
        <div class="search-box">
            <input type="text" id="search-input" placeholder="Rechercher un quiz...">
        </div>
        <div class="filter-actions">
            <select id="sort-select">
                <option value="default">Trier par...</option>
                <option value="alpha-asc">Nom (A-Z)</option>
                <option value="alpha-desc">Nom (Z-A)</option>
                <option value="author-asc">Auteur (A-Z)</option>
            </select>

            <?php if ($current_username): ?>
                <label class="checkbox-filter">
                    <input type="checkbox" id="mine-checkbox">
                    Mes quiz uniquement
                </label>
            <?php endif; ?>
        </div>
    </div>

    <div id="container">
        <?php if (empty($result)): ?>
            <div class="empty-state">
                <p>Aucun quiz n'est disponible pour le moment. Soyez le premier à en créer un !</p>
            </div>
        <?php else: ?>
            <?php foreach ($result as $quiz):
                // Initialisation par défaut pour éviter l'erreur de variable indéfinie
                $isCreator = false;
                if ($current_username) {
                    $isCreator = ($quiz['creator_name'] === $current_username);
                }
                ?>
                <div class="quiz-card"
                     data-name="<?= htmlspecialchars(strtolower($quiz['name'])) ?>"
                     data-author="<?= htmlspecialchars(strtolower($quiz['creator_name'])) ?>"
                     data-mine="<?= $isCreator ? 'true' : 'false' ?>">

                    <div class="card-top">
                        <span class="quiz-badge">Quiz</span>
                        <?php if ($isCreator): ?>
                            <a href="edit.php?quizId=<?= $quiz['id'] ?>" class="edit-btn" title="Modifier ce quiz">
                                <img src="assets/edit.svg" alt="edit-icon" width="18px"/>
                            </a>
                        <?php endif; ?>
                    </div>

                    <h3><?= htmlspecialchars($quiz["name"]) ?></h3>

                    <div class="card-bottom">
                        <p class="author">Par <span><?= htmlspecialchars($quiz['creator_name']) ?></span></p>
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
            <?php endforeach; ?>
        <?php endif; ?>

        <div id="no-match-state" class="empty-state" style="display: none;">
            <p>Aucun quiz ne correspond à vos critères de recherche.</p>
        </div>
    </div>
</main>

<?php require_once $_SERVER["DOCUMENT_ROOT"] . "/sae203/includes/footer.php"; ?>
<script src="js/catalogue.js"></script>
</body>
<?php require_once $_SERVER["DOCUMENT_ROOT"] . "/sae203/includes/load-js.php"; ?>
</html>