<?php

require_once $_SERVER["DOCUMENT_ROOT"] . "/sae203/php/utils.php";
function connectUser($userId, $password) : string{

    $query = "SELECT * FROM sae203_user where username = '$userId' or email = '$userId' ";
    $result = getInfoDataBase($query);
    if (empty($result)) {
        return "Mauvais identifiant";
    }else{
        // Vérification du mot de passe
        $query = "SELECT * FROM sae203_user where (username = '$userId' or email = '$userId') and password = '$password' "; //TODO : ADD HASHING SYSTEM
        $result = getInfoDataBase($query);
        if (empty($result)) {
            return "Mauvais mot de passe";
        }else{ // Si les identifiants sont bons
            return "OK";
        }
    }

}

function createUser($email, $username, $password) :string{
    $query = "SELECT * FROM sae203_user where username= '$username'";
    $result = getInfoDataBase($query);
    if(!empty($result)){
        return "Nom d'utilisateur existant";
    }else{
        $query = "SELECT * FROM sae203_user where email= '$email'";
        $result = getInfoDataBase($query);

        if (!empty($result)){
            return "Email existant";
        }else{
            $query = "INSERT INTO sae203_user (username, email, password, admin) VALUES ('$username', '$email', '$password', 0)";
            getInfoDataBase($query);
            return "OK";
        }
    }
}