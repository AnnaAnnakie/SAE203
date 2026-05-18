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

function saveOrUpdateQuiz($quizId, $quizName, $creatorId, $questionsData): string
{
    $db = getPDOConnection();

    try {
        // Préparation temporaire de l'envoie d'infos à la BD
        $db->beginTransaction();
        if ($quizId) {
            $quiz = $db->prepare("UPDATE sae203_quiz SET name = ? WHERE id = ?");
            $quiz->execute([$quizName, $quizId]);
        } else {
            // Préparation du quiz
            $quiz = $db->prepare("INSERT INTO sae203_quiz (name, creator) VALUES (?, ?)");
            // On "rempli" les ?
            $quiz->execute([$quizName, $creatorId]);
            // Récupération de l'id pour l'ajouter plus tard en clef étrangère
            $quizId = $db->lastInsertId();
        }

        // Listes pour traquer ce qu'on garde pour de supprimer le reste
        $keptQuestionIds = [];
        $keptAnswerIds = [];

        foreach ($questionsData as $question) {
            $questionId = isset($question['id']) ? (int)$question['id'] : null;
            $questionText = $question['text'];

            if ($questionId) {
                // La question existe
                $questionQuery = $db->prepare("UPDATE sae203_question SET question = ? WHERE id = ? AND quiz = ?");
                $questionQuery->execute([$questionText, $questionId, $quizId]);
                $keptQuestionIds[] = $questionId;
            } else {
                // Nouvelle question
                $questionQuery = $db->prepare("INSERT INTO sae203_question (quiz, question) VALUES (?, ?)");
                $questionQuery->execute([$quizId, $questionText]);
                $questionId = $db->lastInsertId();
                $keptQuestionIds[] = $questionId;
            }

            if (isset($question['answers']) && is_array($question['answers'])) {
                foreach ($question['answers'] as $reponse) {
                    $reponseId = isset($reponse['id']) ? (int)$reponse['id'] : null;
                    $reponseText = $reponse['text'];
                    $isCorrect = isset($reponse['correct']) ? 1 : 0;

                    // La réponse existe
                    if ($reponseId) {
                        $reponseQuery = $db->prepare("UPDATE sae203_reponse SET content = ?, bonne_reponse = ? WHERE id = ? AND question = ?");
                        $reponseQuery->execute([$reponseText, $isCorrect, $reponseId, $questionId]);
                        $keptAnswerIds[] = $reponseId;
                    } else {
                        // Nouvelle réponse
                        $reponseQuery = $db->prepare("INSERT INTO sae203_reponse (question, content, bonne_reponse) VALUES (?, ?, ?)");
                        $reponseQuery->execute([$questionId, $reponseText, $isCorrect]);
                        $keptAnswerIds[] = $db->lastInsertId();
                    }
                }
            }
        }

        if ($quizId) {
            // Supprimer les réponses qui ne sont plus dans le formulaire
            if (!empty($keptAnswerIds)) {
                /*
                On a besoin d'un nombre variable de ? pour preparer la query
                On crée une liste de longeur $keptAnswerIds avec que des ? et on en fait un string séparé par des virgules
                */
                $inReponseClause = implode(',', array_fill(0, sizeof($keptAnswerIds), '?'));
                $deleteReponseQuery = $db->prepare("DELETE FROM sae203_reponse WHERE question IN (SELECT id FROM sae203_question WHERE quiz = ?) AND id NOT IN ($inReponseClause)");
                $deleteReponseQuery->execute(array_merge([$quizId], $keptAnswerIds));
            } else {
                $deleteReponseQuery = $db->prepare("DELETE FROM sae203_reponse WHERE question IN (SELECT id FROM sae203_question WHERE quiz = ?)");
                $deleteReponseQuery->execute([$quizId]);
            }

            // Supprimer les questions qui ne sont plus dans le formulaire
            if (!empty($keptQuestionIds)) {
                $inQuestionClause = implode(',', array_fill(0, sizeof($keptQuestionIds), '?'));
                $deleteQuestionQuery = $db->prepare("DELETE FROM sae203_question WHERE quiz = ? AND id NOT IN ($inQuestionClause)");
                $deleteQuestionQuery->execute(array_merge([$quizId], $keptQuestionIds));
            } else {
                $deleteQuestionQuery = $db->prepare("DELETE FROM sae203_question WHERE quiz = ?");
                $deleteQuestionQuery->execute([$quizId]);
            }
        }

        // On envoie les infos à la BD
        $db->commit();
        return "OK";


    } catch (PDOException $event) {
        // On annule tout !
        $db->rollBack();
        return "Erreur : " . $event->getMessage();
    }
}