<?php
require_once $_SERVER["DOCUMENT_ROOT"] . "/sae203/admin/config.php";

function getPDOConnection()
{
    try {
        return new PDO(DB, USER, PWD);
    } catch (PDOException $e) {
        die ("Failed: " . $e);
    }
}

function getInfoDataBase($query)
{
    $conn = getPDOConnection();
    $res = $conn->query($query);
    // Vérification de la requête
    if (!$res) die("Failed query: " . $query);
    $rows = $res->fetchAll();

    // Fermeture de la connection
    $conn = null;

    return $rows;
}

function saveQuiz($quizName, $creatorId, $questionsData): string
{
    $db = getPDOConnection();

    try {
        // Préparation temporaire de l'envoie d'infos à la BD
        $db->beginTransaction();

        // Préparation du quiz
        $quiz = $db->prepare("INSERT INTO sae203_quiz (name, creator) VALUES (?, ?)");
        // On "rempli" les ?
        $quiz->execute([$quizName, $creatorId]);
        // Récupération de l'id pour l'ajouter plus tard en clef étrangère
        $quizId = $db->lastInsertId();
        $question = $db->prepare("INSERT INTO sae203_question (quiz, question) VALUES (?, ?)");
        $reponse = $db->prepare("INSERT INTO sae203_reponse (question, content, bonne_reponse) VALUES (?, ?, ?)");

        foreach ($questionsData as $q) {
            $questionText = $q['text'];

            $question->execute([$quizId, $questionText]);
            $questionId = $db->lastInsertId();

            if (isset($q['answers']) && is_array($q['answers'])) {
                foreach ($q['answers'] as $a) {
                    $answerContent = $a['text'];
                    $isCorrect = isset($a['correct']) ? 1 : 0;

                    $reponse->execute([$questionId, $answerContent, $isCorrect]);
                }
            }
        }

        // On envoie les infos à la BD
        $db->commit();
        return "OK";

    } catch (PDOException $e) {
        // On annule tout !
        $db->rollBack();
        return "Erreur : " . $e->getMessage();
    }
}