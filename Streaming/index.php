<?php
error_reporting(0);
require('../src/connect.php');

$pseudo = htmlspecialchars($_POST['user']);

if ($pseudo != '') {
    setcookie('pseudoUser', $pseudo, time()+3600*24, '/', '', false, false);
}

$Code = htmlspecialchars($_COOKIE['secretCode']);

$req = $db->prepare("SELECT * FROM user WHERE secret_code = ?");
$req->execute(array($Code));

while ($user = $req->fetch()) {
    $id_profile = $user['id_profile'];

    if (!empty($_POST['addList'])) {
        $pseudo = htmlspecialchars($_COOKIE['pseudoUser']);
    
        $list_id = htmlspecialchars($_POST['addList']);
    
        $req = $db->prepare("UPDATE profile$id_profile SET list = ? WHERE pseudo = ?");
        $req->execute(array($list_id, $pseudo));
    }

    $req = $db->prepare("SELECT * FROM profile$id_profile WHERE pseudo = ?");
    $req->execute(array($pseudo));

    while ($user = $req->fetch()) {
        $url = $user['url'];
        $watched = $user['watched'];
        $list = $user['list'];

        $req = $db->prepare("SELECT * FROM streaming WHERE id = ?");
        $req->execute(array($watched));

        while ($user = $req->fetch()) {
            $img = $user['img'];
        }
?>


<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Acceuil - LemonFlix</title>
    <link rel="stylesheet" href="streaming_style.css">
    <link rel="icon" type="image/pngn" href="../img/favicon.png">
</head>
<body>
    <dialog id="popup">
            <div class="closeBtn">
                <input type="image" alt="close" value="Submit" id="close">
            </div>
            <div class="coupleOfCuckoos">
                <h1>A Couple of Cuckoos</h1>
                <ul>
                    <!-- Choix de saisons
                    <li>
                        <select name="saison" id="saisonChoose">
                            <option>Saison 1</option>
                        </select>
                    </li> -->
                    <form action="player.php" method="post" class="streaming_form">
                        <li>
                            <input type="submit" value="Épisode 1">
                            <input type="hidden" name="id" value="1">
                            <input type="hidden" name="user" value="<?php echo($pseudo); ?>">
                        </li>
                    </form>
                    <form action="player.php" method="post" class="streaming_form">
                        <li>
                            <input type="submit" value="Épisode 2">
                            <input type="hidden" name="id" value="5">
                            <input type="hidden" name="user" value="<?php echo($pseudo); ?>">
                        </li>
                    </form>
                    <form action="player.php" method="post" class="streaming_form">
                        <li>
                            <input type="submit" value="Épisode 3">
                            <input type="hidden" name="id" value="7">
                            <input type="hidden" name="user" value="<?php echo($pseudo); ?>">
                        </li>
                        <li>
                            <img src="../img/arrow.svg" alt="arrow" id="arrow">
                        </li>
                    </form>
                </ul>
            </div>
    </dialog>
    <div id="parent_streaming">
        <header>
            <div class="header">
                <form action="../index.php" id="logo">
                    <ul>
                        <li id="imgHeader">
                            <input type="image" src="../img/LemonFlix.png" alt="Submit">
                        </li>
                        <li>
                            <div id="logoutDiv">
                                <?php
                                    echo('<img src="'.$url.'" alt="profile_img" id="profile_img">');
                                ?>
                                <br>
                                <a id="logout" href="../logout.php">Déconnexion</a>
                            </div>
                        </li>
                    </ul>
                </form>
            </div>
        </header>
        <?php 
            if (isset($watched)) {
                echo('
                    <h2 class="h2_margin">Reprendre avec le profile de <strong>'.$pseudo.'</strong> -</h2>
                    <div class="menu">
                        <form action="player.php" method="post" class="streaming_form">
                            <div class="watched">
                                <input type="image" src='.$img.' value="Submit" class="animeCase">
                                <input type="hidden" name="id" value='.$watched.'>
                                <input type="hidden" name="user" value="NULL">
                            </div>
                        </form>
                        <form action="index.php" method="post" class="addToList">
                            <input type="submit" value="Ajout" id="addList">
                            <input type="hidden" name="addList" value="'.$watched.'">
                        </form>
                    </div>');
            }
        ?>
        <h2>Animés -</h2>
        <div class="anime_section">
            <div class="anime_container">
                <form action="player.php" method="post" class="streaming_form">
                    <!-- A Couple of Cuckoos -->
                    <div class="coupleCuckoos">
                        <input type="image" src="main-imgs/coupleCuckoos.jpg" value="Submit" class="animeCase" id="coupleCuckoos">
                    </div>
                </form>
                <form action="player.php" method="post" class="streaming_form">
                    <!-- Jujutsu Kaisen 0 -->
                    <div class="jujutsuKaisen">
                        <input type="image" src="main-imgs/Jujutsu.png" value="Submit" class="animeCase">
                        <input type="hidden" name="id" value="2">
                        <input type="hidden" name="user" value="<?php echo($pseudo); ?>">        
                    </div>
                </form>
                <form action="player.php" method="post" class="streaming_form">
                    <!-- Sword Art Online -->
                    <div class="sao">
                        <input type="image" src="main-imgs/SAO.webp" value="Submit" class="animeCase">
                        <input type="hidden" name="id" value="6">
                        <input type="hidden" name="user" value="<?php echo($pseudo); ?>">
                    </div>
                </form>
                <form action="player.php" method="post" class="streaming_form">
                    <!-- Chainsaw Man -->
                    <div class="chainsawMan">
                        <input type="image" src="main-imgs/chainsawMan.jpg" value="Submit" class="animeCase">
                        <input type="hidden" name="id" value="9">
                        <input type="hidden" name="user" value="<?php echo($pseudo); ?>">
                    </div>
                </form>
                <form action="player.php" method="post" class="streaming_form">
                    <!-- Chainsaw Man -->
                    <div class="blueLock">
                        <input type="image" src="main-imgs/blueLock.jpg" value="Submit" class="animeCase" id="secondeOne">
                        <input type="hidden" name="id" value="12">
                        <input type="hidden" name="user" value="<?php echo($pseudo); ?>">
                    </div>
                </form>
                <form action="player.php" method="post" class="streaming_form">
                    <!-- My Hero academia -->
                    <div class="mha">
                        <input type="image" src="main-imgs/mha.png" value="Submit" class="animeCase" id="lastOne">
                        <input type="hidden" name="id" value="13">
                        <input type="hidden" name="user" value="<?php echo($pseudo); ?>">
                    </div>
                </form>
            </div>
            <img src="../img/arrow.svg" alt="arrow" class="button" id="d">
            <img src="../img/arrow.svg" alt="arrow" class="button" id="g">
        </div>
        <h2>Films -</h2>
        <div class="menu">
            <form action="player.php" method="post" class="streaming_form">
                <!-- Top Gun -->
                <div class="topGun">
                    <input type="image" src="main-imgs/topGun.jpg" value="Submit" class="animeCase">
                    <input type="hidden" name="id" value="3">
                    <input type="hidden" name="user" value="<?php echo($pseudo); ?>">
                </div>
            </form>
            <form action="player.php" method="post" class="streaming_form">
                <!-- Thor -->
                <div class="Thor">
                    <input type="image" src="main-imgs/Thor.jpg" value="Submit" class="animeCase">
                    <input type="hidden" name="id" value="4">
                    <input type="hidden" name="user" value="<?php echo($pseudo); ?>">
                </div>
            </form>
            <form action="player.php" method="post" class="streaming_form">
                <!-- les Minions -->
                <div class="Les Minions 2">
                    <input type="image" src="main-imgs/Minions.jfif" value="Submit" class="animeCase">
                    <input type="hidden" name="id" value="8">
                    <input type="hidden" name="user" value="<?php echo($pseudo); ?>">
                </div>
            </form>
        </div>
        
        <?php
                $req = $db->prepare("SELECT * FROM streaming WHERE id = ?");
                $req->execute(array($list));

                while ($user = $req->fetch()) {
                    $main_img = $user['img'];
                }

                if ($list != "") {
                    echo('
                    <h2>Ma Liste -</h2>
                    <div class="menu">
                        <form action="player.php" method="post" class="streaming_form">
                            <div class="maListe">
                                <input type="image" src="'.$main_img.'" value="Submit" class="animeCase">
                                <input type="hidden" name="id" value="'.$list.'">
                                <input type="hidden" name="user" value"'.$pseudo.'">
                            </div>
                        </form>
                    </div>
                    ');
                }

            }}
            ?>
        
    </div>

    <script src="../Netflix/js/coupleCuckoos.js"></script>
    <script src="../Netflix/js/fadeEffects.js"></script>
    <script src="../Netflix/js/Anime_carrousel.js"></script>
</body>
</html>