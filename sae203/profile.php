<?php
session_start();
require_once $_SERVER["DOCUMENT_ROOT"] . "/sae203/php/utils.php";

if (!isset($_SESSION['user'])) {
    header("Location: /sae203/login.php");
    exit;
}

$username = $_SESSION['user']['username'];

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['action_update'])) {
    $newUsername = htmlspecialchars(trim($_POST['username']));
    $newPassword = $_POST['password'] ?? '';
    $userId = $_SESSION['user']['user_id'];

    if (!empty($newUsername)) {
        $newUsernameClean = addslashes($newUsername);

        // Est-ce que le nouveau pseudo est déjà pris ?
        $check = getInfoDataBase("SELECT id FROM sae203_user WHERE username = '$newUsernameClean' AND id != $userId");
        if (empty($check)) {
            // Mise à jour du pseudo
            getInfoDataBase("UPDATE sae203_user SET username = '$newUsernameClean' WHERE id = $userId");
            $_SESSION['user']['username'] = $newUsername;
            $username = $newUsername;

            // Si un nouveau mot de passe est tapé
            if (!empty($newPassword)) {
                $passwordHash = addslashes(password_hash($newPassword, PASSWORD_DEFAULT));
                getInfoDataBase("UPDATE sae203_user SET password = '$passwordHash' WHERE id = $userId");
            }
            $successMsg = "Profil mis à jour avec succès !";
        } else {
            $errorMsg = "Ce nom d'utilisateur est déjà utilisé.";
        }
    }
}

// Traitement de l'avatar
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['action_avatar'])) {
    $userId = $_SESSION['user']['id'];
    $avatarData = $_POST['avatar_base64'] ?? '';

    if (!empty($avatarData)) {
        // Extraction des données brutes de l'image base64 envoyée par Cropper.js
        $avatarData = str_replace('data:image/jpeg;base64,', '', $avatarData);
        $avatarData = str_replace(' ', '+', $avatarData);
        $binaryImage = base64_decode($avatarData);

        $hexImage = bin2hex($binaryImage);
        getInfoDataBase("UPDATE sae203_user SET profilepic = 0x$hexImage WHERE id = $userId");
        $successMsg = "Photo de profil mise à jour !";
    }
}

// Supprimer un compte
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['action_delete_account'])) {
    $userId = $_SESSION['user']['id'];
    $queryUser = "SELECT admin FROM sae203_user WHERE id = $userId";
    $userData = getInfoDataBase($queryUser)[0];

    // Un admin n'a pas le droit de s'auto-supprimer ici
    if (!$userData['admin']) {
        // Nettoyage des résultats de quiz liés à l'utilisateur
        getInfoDataBase("DELETE FROM sae203_resultat WHERE user = $userId");
        // Suppression de l'utilisateur
        getInfoDataBase("DELETE FROM sae203_user WHERE id = $userId");

        // Destruction de la session et retour à la case départ
        session_destroy();
        header("Location: /sae203/login.php?deleted=account");
        exit;
    } else {
        $errorMsg = "Un compte administrateur ne peut pas être supprimé depuis cette interface.";
    }
}

$query = "SELECT * FROM sae203_user WHERE username = '" . addslashes($username) . "'";
$user = getInfoDataBase($query)[0];
?>

<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Mon Profil | Quiz Zest</title>
    <?php require_once $_SERVER["DOCUMENT_ROOT"] . "/sae203/includes/load-css.php"; ?>
    <link rel="stylesheet" href="vendor/lib.css">
    <link rel="stylesheet" href="css/profile.css">
</head>
<body>
<?php require_once $_SERVER["DOCUMENT_ROOT"] . "/sae203/includes/header.php"; ?>

<main class="profile-main">

    <?php if (isset($successMsg)): ?>
        <div class="banner success-banner"><?= $successMsg ?></div>
    <?php endif; ?>
    <?php if (isset($errorMsg)): ?>
        <div class="banner error-banner"><?= $errorMsg ?></div>
    <?php endif; ?>

    <div class="profile-grid">

        <div class="profile-sidebar card">
            <div class="avatar-wrapper">
                <?php if (!empty($user['profilepic'])): ?>
                    <img id="current-avatar" src="data:image/jpeg;base64,<?= base64_encode($user['profilepic']) ?>"
                         alt="Photo de profil">
                <?php else: ?>
                    <img id="current-avatar" src="assets/defaultPP.jpg" alt="Photo de profil par défaut">
                <?php endif; ?>

                <label for="input-avatar" class="upload-label" title="Changer de photo">
                    <svg xmlns="http://www.w3.org/2000/svg" width="18" height="18" viewBox="0 0 24 24" fill="none"
                         stroke="currentColor" stroke-width="2.5">
                        <path d="M23 19a2 2 0 0 1-2 2H3a2 2 0 0 1-2-2V8a2 2 0 0 1 2-2h4l2-3h6l2 3h4a2 2 0 0 1 2 2z"/>
                        <circle cx="12" cy="13" r="4"/>
                    </svg>
                </label>
                <input type="file" id="input-avatar" accept="image/jpeg, image/png" style="display:none;">
            </div>

            <h2><?= htmlspecialchars($user['username']) ?></h2>
            <span class="role-badge <?= $user['admin'] ? 'admin' : 'player' ?>">
                <?= $user['admin'] ? 'Modérateur / Admin' : 'Joueur Zest' ?>
            </span>

            <div class="sidebar-actions">
                <a href="logout.php" class="btn btn-logout">Se déconnecter</a>

                <?php if ($user['admin']): ?>
                    <a href="admin/reset.php" class="btn btn-reset">RESET SYSTEM</a>
                <?php else: ?>
                    <form method="post" id="deleteAccountForm">
                        <button type="submit" name="action_delete_account" class="btn btn-danger-link">Supprimer mon
                            compte
                        </button>
                    </form>
                <?php endif; ?>
            </div>
        </div>

        <div class="profile-content card">
            <h3>Paramètres du compte</h3>
            <form method="post" class="profile-form">
                <div class="form-group">
                    <label for="username">Nom d'utilisateur</label>
                    <input type="text" name="username" id="username" value="<?= htmlspecialchars($user['username']) ?>"
                           required>
                </div>

                <div class="form-group">
                    <label for="email">Adresse Email (Non modifiable)</label>
                    <input type="email" id="email" value="<?= htmlspecialchars($user['email']) ?>" disabled>
                </div>

                <div class="form-group">
                    <label for="password">Nouveau mot de passe</label>
                    <input type="password" name="password" id="password" placeholder="Laisser vide pour ne pas changer">
                </div>

                <button type="submit" name="update_profile" class="btn btn-primary">Enregistrer les modifications
                </button>
            </form>
        </div>
    </div>

    <div id="cropper-modal" class="modal-overlay hidden">
        <div class="modal-box">
            <h3>Ajuster votre photo</h3>
            <div class="crop-area">
                <img id="image-to-crop" src="" alt="Aperçu">
            </div>
            <div class="modal-buttons">
                <button type="button" id="btn-cancel-crop" class="btn btn-secondary">Annuler</button>
                <button type="button" id="btn-validate-crop" class="btn btn-primary">Valider le recadrage</button>
            </div>
        </div>
    </div>

    <form method="post" id="avatarForm" style="display:none;">
        <input type="hidden" name="action_avatar" value="1">
        <input type="hidden" name="avatar_base64" id="avatar_base64_input">
    </form>
</main>

<?php require_once $_SERVER["DOCUMENT_ROOT"] . "/sae203/includes/footer.php"; ?>

<?php require_once $_SERVER["DOCUMENT_ROOT"] . "/sae203/includes/load-js.php"; ?>
<script src="vendor/lib.js"></script>
<script src="js/profile.js"></script>
</body>
</html>