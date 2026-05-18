<?php
session_start();
require_once $_SERVER["DOCUMENT_ROOT"] . "/sae203/php/utils.php";


?>

<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Réglement</title>
    <?php require_once $_SERVER["DOCUMENT_ROOT"] . "/sae203/includes/load-css.php"; ?>
</head>
<body>
<?php require_once $_SERVER["DOCUMENT_ROOT"] . "/sae203/includes/header.php"; ?>

<main class="legal-container">
    <div class="legal-content">
        <section class="legal-section">
            <h1>Règlement de Quiz Zest</h1>
            <p class="lead">Bienvenue sur Quiz Zest ! Ici, on aime le challenge et la bonne humeur. Pour que l'expérience reste croustillante et juste pour tout le monde, voici les quelques règles d'or à suivre.</p>

            <h2>1. Fair-play & Esprit Zest</h2>
            <p>Le but est de tester tes connaissances, pas tes compétences en recherche Google ultra-rapide ! De toute façon c'est pas comme s'il y avait un chrono. Joue le jeu à la loyale.</p>

            <h2>2. Vos super pouvoirs (Création de Quiz)</h2>
            <p>En tant que créateur de contenu, tu as le droit de mettre une bonne dose de fun, d'insérer des questions insolites ou des pièges machiavéliques. Cependant, assure-toi que tes questions restent respectueuses, sans propos offensants ou discriminatoires.</p>

            <h2>3. Score et Récompenses</h2>
            <ul>
                <li>Chaque bonne réponse te rapproche du sommet du classement.</li>
                <li>Pas de panique, les mauvaises réponses ne t'enlèvent pas de points, alors tente ta chance !</li>
                <li>Le respect des règles garantit le maintien de ton score au tableau d'honneur.</li>
            </ul>
        </section>
    </div>
</main>

<?php require_once $_SERVER["DOCUMENT_ROOT"] . "/sae203/includes/footer.php"; ?>
</body>
<?php require_once $_SERVER["DOCUMENT_ROOT"] . "/sae203/includes/load-js.php"; ?>
</html>