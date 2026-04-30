<?php
require_once $_SERVER["DOCUMENT_ROOT"] . "/sae203/admin/config.php";
function getInfoDataBase($query)
{

    try{
        $conn = new PDO(DB, USER, PWD);
        // Vérification de la connection
    } catch (PDOException $e){
        die ("Failed: ".$e);
    }
    $res = $conn->query($query);
    // Vérification de la requête
    if (!$res) die("Failed query: " . $query);
    $rows = $res->fetchAll();

    // Fermeture de la connection
    $conn = null;

    return $rows;
}