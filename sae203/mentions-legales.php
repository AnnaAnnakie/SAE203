<?php
session_start();
require_once $_SERVER["DOCUMENT_ROOT"] . "/sae203/php/utils.php";


?>

<!DOCTYPE html>
<html>
<head>
    <title>Mentions légales</title>
    <?php require_once $_SERVER["DOCUMENT_ROOT"] . "/sae203/includes/load-css.php"; ?>
</head>
<body>
<?php require_once $_SERVER["DOCUMENT_ROOT"] . "/sae203/includes/header.php"; ?>

<main class="legal-container">
    <div class="legal-content">
        <section class="legal-section">
            <h1>Mentions Légales</h1>

            <h2>1. Édition du site</h2>
            <p>Le site <strong>Quiz Zest</strong> est une application web développée dans le cadre de la SAÉ 203 à l'<strong>IUT1 de Grenoble</strong>.</p>
            <p><strong>Conception et développement :</strong> Anaïs Marchetti.</p>
            <p>Le code source du projet est ouvert et accessible publiquement sur GitHub : <a href="https://github.com/AnnaAnnakie/SAE203" target="_blank" style="color: var(--blue-bell); text-decoration: underline;">Dépôt GitHub du Projet</a>.</p>

            <h2>2. Hébergement</h2>
            <p>Ce site est un projet universitaire à but pédagogique. Il est hébergé localement ou sur l'infrastructure réseau mise à disposition par l'IUT1 de Grenoble.</p>

            <h2>3. Propriété intellectuelle</h2>
            <p>L'interface graphique, les éléments de marque (comme le logo <code>zest.svg</code>) et le code informatique sont la propriété exclusive d'Anaïs Marchetti. Le projet étant réalisé dans un cadre d'études, les contenus partagés le sont à des fins strictement éducatives.</p>

            <h2>4. Protection des données (RGPD)</h2>
            <p>Conformément à l'éthique du web, Quiz Zest ne collecte que le strict minimum pour fonctionner : un pseudonyme, un email et un mot de passe (haché, évidemment !). Aucune de ces informations ne sera jamais vendue, transmise à des tiers ou utilisée pour t'envoyer du spam.</p>
        </section>
    </div>
</main>

<?php require_once $_SERVER["DOCUMENT_ROOT"] . "/sae203/includes/footer.php"; ?>
</body>
<?php require_once $_SERVER["DOCUMENT_ROOT"] . "/sae203/includes/load-js.php"; ?>
</html>