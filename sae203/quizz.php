<?php
$query = "SELECT * FROM `sae203_user`";
?>

<!DOCTYPE html>
<html>
<head>
    <title></title>
    <?php require_once $_SERVER["DOCUMENT_ROOT"] . "/sae203/includes/load-css.php"; ?>
</head>
<body>
<?php require_once $_SERVER["DOCUMENT_ROOT"] . "/sae203/includes/header.php"; ?>

<p>This is an example of a simple HTML page with one paragraph.</p>

<?php
echo <<<HTML
                <p>
                    Lien vers la page <a href="/sae203/page_exemple.php">page_exemple.php</a> du dossier "php".
                </p>
            HTML;
?>

<?php require_once $_SERVER["DOCUMENT_ROOT"] . "/sae203/includes/footer.php"; ?>
</body>
<?php require_once $_SERVER["DOCUMENT_ROOT"] . "/sae203/includes/load-js.php"; ?>
</html>