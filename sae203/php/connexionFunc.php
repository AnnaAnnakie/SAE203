<?php

require_once $_SERVER["DOCUMENT_ROOT"] . "/sae203/php/utils.php";
function connectUser($userId, $password): string
{
    // Permet d'éviter des problèmes avec les caractères spéciaux ' " \ NUL qui pourraient créer un conflit avec la BD
    $userIdClean = addslashes($userId);

    $query = "SELECT * FROM sae203_user WHERE username = '$userIdClean' OR email = '$userIdClean'";
    $result = getInfoDataBase($query);
    if (empty($result)) {
        return "Mauvais identifiant";
    } else {
        // Vérification du mot de passe
        $userRow = $result[0];
        $hashedPassword = $userRow['password'];

        if (!password_verify($password, $hashedPassword)) {
            return "Mauvais mot de passe";
        } else { // Si les identifiants sont bons
            return "OK";
        }
    }

}

function createUser($email, $username, $password): string
{

    $usernameClean = addslashes($username);
    $emailClean = addslashes($email);

    $query = "SELECT * FROM sae203_user WHERE username = '$usernameClean'";
    $result = getInfoDataBase($query);
    if (!empty($result)) {
        return "Nom d'utilisateur existant";
    }

    $query = "SELECT * FROM sae203_user WHERE email = '$emailClean'";
    $result = getInfoDataBase($query);
    if (!empty($result)) {
        return "Email existant";
    }

    $passwordHash = password_hash($password, PASSWORD_DEFAULT);
    $passwordHashClean = addslashes($passwordHash);

    $query = "INSERT INTO sae203_user (username, email, password, admin) VALUES ('$usernameClean', '$emailClean', '$passwordHashClean', 0)";
    getInfoDataBase($query);

    return "OK";
}