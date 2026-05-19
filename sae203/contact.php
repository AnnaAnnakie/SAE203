<?php
session_start();
require_once $_SERVER["DOCUMENT_ROOT"] . "/sae203/php/utils.php";

// Optionnel : On récupère l'email de l'utilisateur connecté pour lui pré-remplir le champ
$userEmail = $_SESSION['user']['email'] ?? '';
?>

<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Contact | Quiz Zest</title>
    <?php require_once $_SERVER["DOCUMENT_ROOT"] . "/sae203/includes/load-css.php"; ?>
    <link rel="stylesheet" href="css/contact.css">
</head>
<body>
<?php require_once $_SERVER["DOCUMENT_ROOT"] . "/sae203/includes/header.php"; ?>

<main class="contact-container">
    <div class="contact-card">
        <div class="contact-header">
            <h1>Une question ? <span class="text-gradient">Contactez-nous</span></h1>
            <p>Une suggestion, un bug à signaler ou juste un mot doux ? Laissez-nous un message !</p>
        </div>

        <form action="https://formspree.io/f/xdajkvlg" method="POST" class="contact-form">
            <div class="form-group">
                <label for="email">Votre adresse e-mail</label>
                <input
                        type="email"
                        id="email"
                        name="email"
                        placeholder="exemple@domaine.com"
                        value="<?= htmlspecialchars($userEmail) ?>"
                        required
                >
            </div>

            <div class="form-group">
                <label for="message">Votre message</label>
                <textarea
                        id="message"
                        name="message"
                        rows="6"
                        placeholder="Écrivez votre message ici..."
                        required
                ></textarea>
            </div>

            <button type="submit" class="btn-submit">
                <span>Envoyer le message</span>
                <svg xmlns="http://www.w3.org/2000/svg" width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round">
                    <line x1="22" y1="2" x2="11" y2="13"></line>
                    <polygon points="22 2 15 22 11 13 2 9 22 2"></polygon>
                </svg>
            </button>
        </form>
    </div>
</main>

<?php require_once $_SERVER["DOCUMENT_ROOT"] . "/sae203/includes/footer.php"; ?>
</body>
<?php require_once $_SERVER["DOCUMENT_ROOT"] . "/sae203/includes/load-js.php"; ?>
</html>