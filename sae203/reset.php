<?php
session_start();
require_once $_SERVER["DOCUMENT_ROOT"] . "/sae203/php/utils.php";
$sql_file = 'init.sql';

try {
    $pdo = getPDOConnection();
    $sql_content = file_get_contents($sql_file);
    $pdo->exec($sql_content);

    header('Location: ../index.php');
    exit;

} catch (PDOException $e) {
    die("Erreur lors de la reinitialisation : " . $e->getMessage());
}