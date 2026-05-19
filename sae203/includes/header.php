<?php
// On récupère le pseudo si l'utilisateur est connecté
$ernest = $_SESSION['user']['username'] ?? null;
?>

<header>
    <a href="/sae203/" class="logo-container">
        <img src="assets/zest.svg" alt="logo" width="150px">
    </a>

    <nav class="nav-menu">
        <ul class="nav-links">
            <li><a href="/sae203/catalogue.php" class="nav-item">Catalogue</a></li>
            <li><a href="/sae203/contact.php" class="nav-item">Contact</a></li>
        </ul>

        <div class="nav-auth">
            <?php if (!$ernest): ?>
                <div id="connexion">
                    <a href="/sae203/login.php" id="log-in" class="btn-auth">Se connecter</a>
                </div>
            <?php else: ?>
                <div id="connected" class="user-profile-badge">
                    <span class="online-indicator"></span>
                    <a href="/sae203/profile.php" id="logged"><?= htmlspecialchars($ernest) ?></a>
                </div>
            <?php endif; ?>
        </div>
    </nav>
</header>