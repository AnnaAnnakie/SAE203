<?php
session_start();
require_once $_SERVER["DOCUMENT_ROOT"] . "/sae203/php/utils.php";

$quiz = isset($_GET['quizId']) ? (int)$_GET['quizId'] : 0;
$userId = isset($_SESSION['user']['id']) ? (int)$_SESSION['user']['id'] : null;

if ($quiz <= 0) {
    header("Location: catalogue.php");
    exit;
}

$query = "SELECT * FROM sae203_quiz WHERE id = $quiz";
$quizInfosResult = getInfoDataBase($query);

if (empty($quizInfosResult)) {
    header("Location: catalogue.php");
    exit;
}
$quizInfos = $quizInfosResult[0];

$query = "SELECT COUNT(*) as nb_questions FROM sae203_question WHERE quiz = $quiz";
$countResult = getInfoDataBase($query);
$hasQuestions = ($countResult[0]['nb_questions'] > 0);

$queryScores = "SELECT r.*, u.username, u.profilepic 
                FROM sae203_resultat r 
                JOIN sae203_user u ON r.user = u.id 
                WHERE r.quiz = $quiz 
                ORDER BY r.score DESC";
$allScores = getInfoDataBase($queryScores);

$top10 = [];
$userRank = null;
$userBestScore = null;

$rank = 1;
foreach ($allScores as $scoreRow) {
    // On garde uniquement les 10 premiers pour le leaderboard
    if ($rank <= 10) {
        $top10[] = [
                'rank' => $rank,
                'username' => $scoreRow['username'],
                'score' => $scoreRow['score'],
                'profilepic' => $scoreRow['profilepic']
        ];
    }

    // Le classement de l'utilisateur n'est cherché que s'il est connecté
    if ($userId && (int)$scoreRow['user'] === $userId) {
        if ($userRank === null) {
            $userRank = $rank;
            $userBestScore = $scoreRow['score'];
        }
    }
    $rank++;
}
?>

<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Quiz | <?= htmlspecialchars($quizInfos['name']) ?></title>
    <?php require_once $_SERVER["DOCUMENT_ROOT"] . "/sae203/includes/load-css.php"; ?>
    <link rel="stylesheet" href="css/quiz.css">
</head>
<body>
<?php require_once $_SERVER["DOCUMENT_ROOT"] . "/sae203/includes/header.php"; ?>

<main class="quiz-preview-container">

    <section class="quiz-hero-card">
        <span class="badge">Prêt pour le défi ?</span>
        <h1><?= htmlspecialchars($quizInfos['name']) ?></h1>
        <p class="quiz-description">
            Testez vos connaissances sur ce questionnaire. Attention, le classement prend en compte votre score final, soyez vif et précis !
        </p>

        <div class="cta-zone">
            <?php if ($hasQuestions): ?>
                <a href="questions.php?quizId=<?= $quiz ?>" class="btn btn-start">Commencer le Quiz ?</a>
            <?php else: ?>
                <div class="alert-empty">⚠️ Aucune question n'est disponible pour ce quiz actuellement.</div>
            <?php endif; ?>
        </div>
    </section>

    <section class="leaderboard-section">
        <div class="section-title">
            <h2>🏆 Classement <span class="text-gradient">Général</span></h2>
            <p>Les meilleurs scores en temps réel sur ce questionnaire</p>
        </div>

        <div class="leaderboard-box">
            <?php if (!empty($top10)): ?>
                <table class="leaderboard-table">
                    <thead>
                    <tr>
                        <th>Rang</th>
                        <th>Joueur</th>
                        <th>Score</th>
                    </tr>
                    </thead>
                    <tbody>
                    <?php foreach ($top10 as $player):
                        // Surlignage uniquement si l'utilisateur est connecté et qu'il s'agit de sa ligne
                        $isMe = ($userId && isset($_SESSION['user']['username']) && $player['username'] === $_SESSION['user']['username']);
                        ?>
                        <tr class="<?= $isMe ? 'row-current-user' : '' ?>">
                            <td class="rank-cell">
                                <?php if ($player['rank'] == 1): ?> 🥇
                                <?php elseif ($player['rank'] == 2): ?> 🥈
                                <?php elseif ($player['rank'] == 3): ?> 🥉
                                <?php else: ?> <?= $player['rank'] ?>
                                <?php endif; ?>
                            </td>
                            <td class="player-cell">
                                <div class="player-info">
                                    <?php if (!empty($player['profilepic'])): ?>
                                        <img src="data:image/jpeg;base64,<?= base64_encode($player['profilepic']) ?>" class="table-avatar" alt="Avatar">
                                    <?php else: ?>
                                        <img src="assets/defaultPP.jpg" class="table-avatar" alt="Avatar">
                                    <?php endif; ?>
                                    <span class="player-name"><?= htmlspecialchars($player['username']) ?><?= $isMe ? ' (Vous)' : '' ?></span>
                                </div>
                            </td>
                            <td class="score-cell"><strong><?= number_format($player['score'], 2) ?></strong> pts</td>
                        </tr>
                    <?php endforeach; ?>

                    <?php if ($userId && $userRank !== null && $userRank > 10): ?>
                        <tr class="separator-row"><td colspan="3">...</td></tr>
                        <tr class="row-current-user">
                            <td class="rank-cell"><?= $userRank ?></td>
                            <td class="player-cell">
                                <div class="player-info">
                                    <?php if (!empty($_SESSION['user']['profilepic'])): ?>
                                        <img src="data:image/jpeg;base64,<?= base64_encode($_SESSION['user']['profilepic']) ?>" class="table-avatar" alt="Avatar">
                                    <?php else: ?>
                                        <img src="assets/defaultPP.jpg" class="table-avatar" alt="Avatar">
                                    <?php endif; ?>
                                    <span class="player-name"><?= htmlspecialchars($_SESSION['user']['username']) ?> (Vous)</span>
                                </div>
                            </td>
                            <td class="score-cell"><strong><?= number_format($userBestScore, 2) ?></strong> pts</td>
                        </tr>
                    <?php endif; ?>
                    </tbody>
                </table>
            <?php else: ?>
                <div class="empty-leaderboard">
                    ✨ Soyez le premier à participer pour ouvrir le classement de ce quiz !
                </div>
            <?php endif; ?>
        </div>
    </section>

</main>

<?php require_once $_SERVER["DOCUMENT_ROOT"] . "/sae203/includes/footer.php"; ?>
</body>
<?php require_once $_SERVER["DOCUMENT_ROOT"] . "/sae203/includes/load-js.php"; ?>
</html>