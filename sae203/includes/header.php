<?php

$ernest = $_SESSION['user']['username'] ?? null;
?>

<header>
    <a href="/sae203/">
        <img src="assets/zest.svg" alt="logo" width="150px">
    </a>
    <form action="" method="get" id="search-bar">
        <input type="text" class="input-group" name="query" placeholder="Rechercher...">
        <button type="submit">
            <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="#fff" class="bi bi-search"
                 viewBox="0 0 16 16">
                <path d="M11.742 10.344a6.5 6.5 0 1 0-1.397 1.398h-.001q.044.06.098.115l3.85 3.85a1 1 0 0 0 1.415-1.414l-3.85-3.85a1 1 0 0 0-.115-.1zM12 6.5a5.5 5.5 0 1 1-11 0 5.5 5.5 0 0 1 11 0"/>
            </svg>
        </button>
    </form>
    <nav>
        <?php if(!isset($_SESSION['user'])){ ?>
            <div id="connexion">
                <a href="/sae203/login.php" id="log-in">Se connecter</a>
            </div>
        <?php }else { ?>
            <div id="connected">
                <a href="/sae203/logout.php" id="logged"> <?=$ernest?></a>
            </div>
        <?php } ?>
    </nav>
</header>
