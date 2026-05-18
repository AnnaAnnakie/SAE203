<?php
session_start();
require_once $_SERVER["DOCUMENT_ROOT"] . "/sae203/php/utils.php";

if (isset($_SESSION['user'])) {
    header("Location: catalogue.php");
    exit;
}

?>

<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Accueil</title>
    <?php require_once $_SERVER["DOCUMENT_ROOT"] . "/sae203/includes/load-css.php"; ?>
    <link rel="stylesheet" href="css/home.css">
</head>
<body>
<?php require_once $_SERVER["DOCUMENT_ROOT"] . "/sae203/includes/header.php"; ?>
<main>
    <section class="hero-section">
        <div class="hero-container">
            <div class="hero-content">
                <span class="badge">🚀 SAÉ 203 — Plateforme interactive</span>
                <h1>Réveillez votre cerveau, <br><span class="text-gradient">relevez le défi.</span></h1>
                <p class="hero-subtitle">
                    Rejoignez la communauté Zest. Testez vos compétences, débloquez des badges uniques et grimpez
                    au sommet du classement général en temps réel.
                </p>

                <div class="hero-cta-group">
                    <?php if (isset($_SESSION['user'])): ?>
                        <a href="catalogue.php" class="btn btn-primary">Découvrir le Catalogue 🎯</a>
                    <?php else: ?>
                        <a href="login.php" class="btn btn-primary">Commencer l'Aventure ⚡</a>
                        <a href="#discover" class="btn btn-secondary">En savoir plus</a>
                    <?php endif; ?>
                </div>
            </div>

            <div class="hero-visual">
                <div class="floating-card card-1">🧠 Intégration</div>
                <div class="floating-card card-2">🏆 Top 1 : Vous ?</div>
                <div class="floating-card card-3">💡 100% Interactif</div>
                <div class="glow-sphere"></div>
            </div>
        </div>
    </section>

    <section id="discover" class="features-section">
        <div class="section-header">
            <h2>Pourquoi choisir <span class="text-gradient">Zest</span> ?</h2>
            <p>Une expérience pensée pour l'apprentissage et le fun.</p>
        </div>

        <div class="features-grid">
            <div class="feature-card">
                <div class="feature-icon">🎯</div>
                <h3>Thématiques Variées</h3>
                <p>Du développement web à la culture G, explorez des questionnaires créés sur-mesure pour votre
                    formation.</p>
            </div>

            <div class="feature-card">
                <div class="feature-icon">⏱️</div>
                <h3>Classement Dynamique</h3>
                <p>Chaque seconde compte. Vos scores sont enregistrés instantanément pour vous mesurer aux
                    meilleurs.</p>
            </div>

            <div class="feature-card">
                <div class="feature-icon">🔒</div>
                <h3>Espace Sécurisé</h3>
                <p>Vos progressions sont sauvegardées de manière chiffrée sur nos serveurs. Jouez l'esprit
                    tranquille.</p>
            </div>
        </div>
    </section>

</main>

<?php require_once $_SERVER["DOCUMENT_ROOT"] . "/sae203/includes/footer.php"; ?>
</body>
<?php require_once $_SERVER["DOCUMENT_ROOT"] . "/sae203/includes/load-js.php"; ?>
</html>