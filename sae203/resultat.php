<?php
session_start();
require_once $_SERVER["DOCUMENT_ROOT"] . "/sae203/php/utils.php";

// 1. Récupération des infos de session si elles existent
$userId = $_SESSION['user']['user_id'] ?? null;
$userUsername = $_SESSION['user']['username'] ?? "Invité";

$quizId = $_GET['quizId'] ?? null;

if (!$quizId) {
    header("Location: index.php");
    exit();
}

$finalScore = 0;

// 2. LE CALCUL DU SCORE (Exécuté pour TOUT LE MONDE si le formulaire est soumis)
if ($_SERVER['REQUEST_METHOD'] === 'POST' && !empty($_POST['user_answers'])) {
    $userAnswers = json_decode($_POST['user_answers'], true);
    $questionsTab = getInfoDataBase("SELECT * FROM sae203_question WHERE quiz = $quizId");

    foreach ($questionsTab as $question) {
        $qId = $question['id'];
        $reponsesTab = getInfoDataBase("SELECT * FROM sae203_reponse WHERE question = $qId");

        $selectedIds = $userAnswers[$qId] ?? [];

        $totalBonnes = 0;
        $checkedBonnes = 0;
        $checkedMauvaises = 0;

        foreach ($reponsesTab as $reponse) {
            $isGood = ($reponse['bonne_reponse'] === 1 || $reponse['bonne_reponse'] === "1");
            if ($isGood) $totalBonnes++;

            if (in_array($reponse['id'], $selectedIds)) {
                if ($isGood) $checkedBonnes++;
                else $checkedMauvaises++;
            }
        }

        $questionScore = 0;
        if ($totalBonnes === 1) {
            if ($checkedBonnes === 1 && $checkedMauvaises === 0) {
                $questionScore = 1;
            }
        } else if ($totalBonnes > 1) {
            $valeur = 1 / $totalBonnes;
            $questionScore = ($checkedBonnes * $valeur) - ($checkedMauvaises * $valeur);
            if ($questionScore < 0) $questionScore = 0;
        }

        $finalScore += $questionScore;
    }

    $finalScore = round($finalScore, 2);

    // 3. SAUVEGARDE EN BDD (Uniquement si l'utilisateur est connecté)
    if ($userId) {
        $query = "
            INSERT INTO sae203_resultat (user, quiz, score) 
            VALUES ($userId, $quizId, $finalScore)
            ON DUPLICATE KEY UPDATE score = GREATEST(score, VALUES(score))
        ";
        getInfoDataBase($query);
    }
} else {
    // Si pas de POST (rechargement de page), on essaie de récupérer le dernier score en BDD pour le connecté
    if ($userId) {
        $lastRes = getInfoDataBase("
            SELECT score 
            FROM sae203_resultat 
            WHERE user = $userId AND quiz = $quizId 
            ORDER BY id DESC 
            LIMIT 1
        ");
        $finalScore = !empty($lastRes) ? $lastRes[0]['score'] : 0;
    }
}

// 4. RÉCUPÉRATION DU LEADERBOARD (Pour l'affichage global, mais le traitement du rang reste pour le connecté)
$leaderboard = getInfoDataBase("
    SELECT r.user, MAX(r.score) as max_score, u.username 
    FROM sae203_resultat r 
    JOIN sae203_user u ON r.user = u.id 
    WHERE r.quiz = $quizId 
    GROUP BY r.user 
    ORDER BY max_score DESC
");

$top10 = array_slice($leaderboard, 0, 10);

$userRank = 0;
if ($userId) {
    foreach ($leaderboard as $index => $row) {
        if ($row['user'] == $userId) {
            $userRank = $index + 1;
            break;
        }
    }
}
?>

<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Résultats</title>
    <?php require_once $_SERVER["DOCUMENT_ROOT"] . "/sae203/includes/load-css.php"; ?>
    <link rel="stylesheet" href="css/questions.css">
</head>
<body>
<?php require_once $_SERVER["DOCUMENT_ROOT"] . "/sae203/includes/header.php"; ?>

<main class="result-main">
    <div class="result-card">
        <h1>Quiz Terminé !</h1>
        <div class="score-box">
            <p>Ton score final :</p>
            <div class="big-score"><?= number_format($finalScore, 2) ?> pts</div>
        </div>

        <?php if (!$userId): ?>
            <div class="guest-notice">
                💡 <strong>Mode invité :</strong> Votre score n'est pas enregistré. <a href="login.php">Connectez-vous</a>
                pour rejoindre le classement !
            </div>
        <?php endif; ?>

        <div class="actions">
            <a href="catalogue.php" class="btn-primary">Retour à la liste des quiz</a>
        </div>
    </div>

    <div class="leaderboard-card">
        <h2>Classement du Top 10</h2>
        <table>
            <thead>
            <tr>
                <th>Rang</th>
                <th>Joueur</th>
                <th>Score Max</th>
            </tr>
            </thead>
            <tbody>
            <?php
            $userInTop10 = false;
            foreach ($top10 as $index => $row):
                $currentRank = $index + 1;
                $isMe = ($userId && $row['user'] == $userId);
                if ($isMe) $userInTop10 = true;
                ?>
                <tr class="<?= $isMe ? 'row-me' : '' ?>">
                    <td><strong>#<?= $currentRank ?></strong></td>
                    <td><?= htmlspecialchars($row['username']) ?> <?= $isMe ? ' (Moi)' : '' ?></td>
                    <td><?= number_format($row['max_score'], 2) ?> pts</td>
                </tr>
            <?php endforeach; ?>

            <?php if ($userId && !$userInTop10 && $userRank > 10): ?>
                <tr class="row-separator">
                    <td colspan="3">...</td>
                </tr>
                <tr class="row-me">
                    <td><strong>#<?= $userRank ?></strong></td>
                    <td><?= htmlspecialchars($userUsername) ?> (Moi)</td>
                    <td><?= number_format($finalScore, 2) ?> pts</td>
                </tr>
            <?php endif; ?>
            </tbody>
        </table>
    </div>
</main>

<?php require_once $_SERVER["DOCUMENT_ROOT"] . "/sae203/includes/footer.php"; ?>
</body>
</html>