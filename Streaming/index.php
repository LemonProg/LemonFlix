<?php
session_start();
if (!isset($_SESSION['connect'])) {
    error_reporting(0);
    header("location: ../index.php");
} else {
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

    if (!empty($_POST['delList'])) {
        $pseudo = htmlspecialchars($_COOKIE['pseudoUser']);
    
        $req = $db->prepare("UPDATE profile$id_profile SET list = ? WHERE pseudo = ?");
        $req->execute(array(NULL, $pseudo));
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
    <title>Accueil - LemonFlix</title>
    <link rel="stylesheet" href="streamingStyle.css">
    <link rel="icon" type="image/pngn" href="../img/favicon.png">
</head>
<body>
    <dialog id="popup">
            <div class="closeBtn">
                <input type="image" alt="close" value="Submit" id="close">
            </div>
            <div class="coupleOfCuckoos">
                <?php $coupleCuckoos1 = 1 ?>
                <?php $coupleCuckoos2 = 5 ?>
                <?php $coupleCuckoos3 = 7 ?>
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
                            <input type="hidden" name="id" value="<?php echo($coupleCuckoos1); ?>">
                            <input type="hidden" name="user" value="<?php echo($pseudo); ?>">
                        </li>
                    </form>
                    <form action="player.php" method="post" class="streaming_form">
                        <li>
                            <input type="submit" value="Épisode 2">
                            <input type="hidden" name="id" value="<?php echo($coupleCuckoos2); ?>">
                            <input type="hidden" name="user" value="<?php echo($pseudo); ?>">
                        </li>
                    </form>
                    <form action="player.php" method="post" class="streaming_form">
                        <li>
                            <input type="submit" value="Épisode 3">
                            <input type="hidden" name="id" value="<?php echo($coupleCuckoos3); ?>">
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
                    <div class="menu_reprendre">
                        <form action="player.php" method="post" class="streaming_form">
                            <div class="videos removeMargin">
                                <div class="dropdown">
                                    <input type="image" src='.$img.' value="Submit" class="animeCase">
                                    <input type="hidden" name="id" value='.$watched.'>
                                    <input type="hidden" name="user" value="NULL">
                                    </form>
                                    <div class="dropdown-content">
                                        <form action="index.php" method="post">
                                            ');
                                $req = $db->prepare("SELECT list FROM profile$id_profile WHERE pseudo = ?");
                                $req->execute(array($pseudo));

                                while ($user = $req->fetch()) {
                                    $bddList = $user[0];

                                    if ($watched == $bddList) {
                                        echo('
                                        <input type="hidden" name="delList" value="'.$watched.'">
                                        <input type="submit" value="Supprimer de ma liste" id="list">');
                                    } else {
                                        echo('
                                        <input type="hidden" name="addList" value="'.$watched.'">
                                        <input type="submit" value="Ajouter à ma liste" id="list">');
                                    }
                                }
                                        
                                echo('</form>
                                        <form action="player.php" method="post" class="streaming_form">
                                            <input type="submit" value="Regarder" id="play">
                                            <input type="hidden" name="id" value='.$watched.'>
                                            <input type="hidden" name="user" value="NULL">
                                        </form>
                                    </div>
                                </div>
                            </div>
                        </form>
                    </div>');
            }
        ?>
        <h2>Animés -</h2>
        <div class="anime_section">
            <div class="anime_container">
                <form action="player.php" method="post" class="streaming_form">
                    <!-- A Couple of Cuckoos -->
                    <div class="videos">
                        <div class="dropdown">
                            <input type="image" src="main-imgs/coupleCuckoos.jpg" value="Submit" class="animeCase" id="coupleCuckoos">
                            </form>
                            <div class="dropdown-content">
                                <form action="index.php" method="post">
                                    <?php 
                                        $req = $db->prepare("SELECT list FROM profile$id_profile WHERE pseudo = ?");
                                        $req->execute(array($pseudo));

                                        while ($user = $req->fetch()) {
                                            $bddList = $user[0];

                                            if ($coupleCuckoos1 == $bddList) {
                                                echo('
                                                <input type="hidden" name="delList" value="'.$coupleCuckoos1.'">
                                                <input type="submit" value="Supprimer de ma liste" id="list">');
                                            } else {
                                                echo('
                                                <input type="hidden" name="addList" value="'.$coupleCuckoos1.'">
                                                <input type="submit" value="Ajouter à ma liste" id="list">');
                                            }
                                        }

                                    ?>
                                </form>
                                <form action="player.php" method="post" class="streaming_form">
                                    <input type="submit" value="Regarder" id="play">
                                    <input type="hidden" name="id" value="<?php echo($coupleCuckoos1); ?>">
                                    <input type="hidden" name="user" value="<?php echo($pseudo); ?>">
                                </form>
                            </div>
                        </div>
                    </div>
                <form action="player.php" method="post" class="streaming_form">
                    <!-- Jujutsu Kaisen 0 -->
                    <?php $jujutsuKaisen = 2 ?>
                    <div class="videos">
                        <div class="dropdown">
                            <input type="image" src="main-imgs/Jujutsu.png" value="Submit" class="animeCase">
                            <input type="hidden" name="id" value="<?php echo($jujutsuKaisen); ?>">
                            <input type="hidden" name="user" value="<?php echo($pseudo); ?>">
                            </form>
                            <div class="dropdown-content">
                                <form action="index.php" method="post">
                                    <?php 
                                        $req = $db->prepare("SELECT list FROM profile$id_profile WHERE pseudo = ?");
                                        $req->execute(array($pseudo));

                                        while ($user = $req->fetch()) {
                                            $bddList = $user[0];

                                            if ($jujutsuKaisen == $bddList) {
                                                echo('
                                                <input type="hidden" name="delList" value="'.$jujutsuKaisen.'">
                                                <input type="submit" value="Supprimer de ma liste" id="list">');
                                            } else {
                                                echo('
                                                <input type="hidden" name="addList" value="'.$jujutsuKaisen.'">
                                                <input type="submit" value="Ajouter à ma liste" id="list">');
                                            }
                                        }

                                    ?>
                                    
                                </form>
                                <form action="player.php" method="post" class="streaming_form">
                                    <input type="submit" value="Regarder" id="play">
                                    <input type="hidden" name="id" value="<?php echo($jujutsuKaisen); ?>">
                                    <input type="hidden" name="user" value="<?php echo($pseudo); ?>">
                                </form>
                            </div>
                        </div>        
                    </div>
                </form>
                <form action="player.php" method="post" class="streaming_form">
                    <!-- Sword Art Online -->
                    <?php $sao = 6 ?>
                    <div class="videos">
                        <div class="dropdown">
                            <input type="image" src="main-imgs/SAO.webp" value="Submit" class="animeCase">
                            <input type="hidden" name="id" value="<?php echo($sao); ?>">
                            <input type="hidden" name="user" value="<?php echo($pseudo); ?>">
                            </form>
                            <div class="dropdown-content">
                                <form action="index.php" method="post">
                                <?php 
                                        $req = $db->prepare("SELECT list FROM profile$id_profile WHERE pseudo = ?");
                                        $req->execute(array($pseudo));

                                        while ($user = $req->fetch()) {
                                            $bddList = $user[0];

                                            if ($sao == $bddList) {
                                                echo('
                                                <input type="hidden" name="delList" value="'.$sao.'">
                                                <input type="submit" value="Supprimer de ma liste" id="list">');
                                            } else {
                                                echo('
                                                <input type="hidden" name="addList" value="'.$sao.'">
                                                <input type="submit" value="Ajouter à ma liste" id="list">');
                                            }
                                        }

                                    ?>
                                </form>
                                <form action="player.php" method="post" class="streaming_form">
                                    <input type="submit" value="Regarder" id="play">
                                    <input type="hidden" name="id" value="<?php echo($sao); ?>">
                                    <input type="hidden" name="user" value="<?php echo($pseudo); ?>">
                                </form>
                            </div>
                        </div>    
                    </div>
                </form>
                <form action="player.php" method="post" class="streaming_form">
                    <!-- Chainsaw Man -->
                    <?php $chainsaw = 9 ?>
                    <div class="videos">
                        <div class="dropdown">
                            <input type="image" src="main-imgs/chainsawMan.jpg" value="Submit" class="animeCase">
                            <input type="hidden" name="id" value="<?php echo($chainsaw); ?>">
                            <input type="hidden" name="user" value="<?php echo($pseudo); ?>">
                            </form>
                            <div class="dropdown-content">
                                <form action="index.php" method="post">
                                    <?php 
                                        $req = $db->prepare("SELECT list FROM profile$id_profile WHERE pseudo = ?");
                                        $req->execute(array($pseudo));

                                        while ($user = $req->fetch()) {
                                            $bddList = $user[0];

                                            if ($chainsaw == $bddList) {
                                                echo('
                                                <input type="hidden" name="delList" value="'.$chainsaw.'">
                                                <input type="submit" value="Supprimer de ma liste" id="list">');
                                            } else {
                                                echo('
                                                <input type="hidden" name="addList" value="'.$chainsaw.'">
                                                <input type="submit" value="Ajouter à ma liste" id="list">');
                                            }
                                        }

                                    ?>
                                </form>
                                <form action="player.php" method="post" class="streaming_form">
                                    <input type="submit" value="Regarder" id="play">
                                    <input type="hidden" name="id" value="<?php echo($chainsaw); ?>">
                                    <input type="hidden" name="user" value="<?php echo($pseudo); ?>">
                                </form>
                            </div>
                        </div>
                    </div>
                </form>
                <form action="player.php" method="post" class="streaming_form">
                    <!-- Blue Lock -->
                    <?php $blueLock = 12 ?>
                    <div class="videos">
                        <div class="dropdown">
                            <input type="image" src="main-imgs/blueLock.jpg" value="Submit" class="animeCase" id="secondeOne">
                            <input type="hidden" name="id" value="<?php echo($blueLock); ?>">
                            <input type="hidden" name="user" value="<?php echo($pseudo); ?>">
                            </form>
                            <div class="dropdown-content">
                                <form action="index.php" method="post">
                                    <?php 
                                        $req = $db->prepare("SELECT list FROM profile$id_profile WHERE pseudo = ?");
                                        $req->execute(array($pseudo));

                                        while ($user = $req->fetch()) {
                                            $bddList = $user[0];

                                            if ($blueLock == $bddList) {
                                                echo('
                                                <input type="hidden" name="delList" value="'.$blueLock.'">
                                                <input type="submit" value="Supprimer de ma liste" id="list">');
                                            } else {
                                                echo('
                                                <input type="hidden" name="addList" value="'.$blueLock.'">
                                                <input type="submit" value="Ajouter à ma liste" id="list">');
                                            }
                                        }

                                    ?>
                                </form>
                                <form action="player.php" method="post" class="streaming_form">
                                    <input type="submit" value="Regarder" id="play">
                                    <input type="hidden" name="id" value="<?php echo($blueLock); ?>">
                                    <input type="hidden" name="user" value="<?php echo($pseudo); ?>">
                                </form>
                            </div>
                        </div>
                    </div>
                </form>
                <form action="player.php" method="post" class="streaming_form">
                    <!-- My Hero academia -->
                    <?php $mha = 13 ?>
                    <div class="videos">
                        <div class="dropdown">
                            <input type="image" src="main-imgs/mha.png" value="Submit" class="animeCase" id="lastOne">
                            <input type="hidden" name="id" value="<?php echo($mha); ?>">
                            <input type="hidden" name="user" value="<?php echo($pseudo); ?>">
                            </form>
                            <div class="dropdown-content">
                                <form action="index.php" method="post">
                                    <?php 
                                        $req = $db->prepare("SELECT list FROM profile$id_profile WHERE pseudo = ?");
                                        $req->execute(array($pseudo));

                                        while ($user = $req->fetch()) {
                                            $bddList = $user[0];

                                            if ($mha == $bddList) {
                                                echo('
                                                <input type="hidden" name="delList" value="'.$mha.'">
                                                <input type="submit" value="Supprimer de ma liste" id="list">');
                                            } else {
                                                echo('
                                                <input type="hidden" name="addList" value="'.$mha.'">
                                                <input type="submit" value="Ajouter à ma liste" id="list">');
                                            }
                                        }

                                    ?>
                                </form>
                                <form action="player.php" method="post" class="streaming_form">
                                    <input type="submit" value="Regarder" id="play">
                                    <input type="hidden" name="id" value="<?php echo($mha); ?>">
                                    <input type="hidden" name="user" value="<?php echo($pseudo); ?>">
                                </form>
                            </div>
                        </div>
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
                <?php $topGun = 3 ?>
                <div class="videos">
                    <div class="dropdown">
                        <input type="image" src="main-imgs/topGun.jpg" value="Submit" class="animeCase">
                        <input type="hidden" name="id" value="<?php echo($topGun); ?>">
                        <input type="hidden" name="user" value="<?php echo($pseudo); ?>">
                            </form>
                            <div class="dropdown-content">
                                <form action="index.php" method="post">
                                    <?php 
                                        $req = $db->prepare("SELECT list FROM profile$id_profile WHERE pseudo = ?");
                                        $req->execute(array($pseudo));

                                        while ($user = $req->fetch()) {
                                            $bddList = $user[0];

                                            if ($topGun == $bddList) {
                                                echo('
                                                <input type="hidden" name="delList" value="'.$topGun.'">
                                                <input type="submit" value="Supprimer de ma liste" id="list">');
                                            } else {
                                                echo('
                                                <input type="hidden" name="addList" value="'.$topGun.'">
                                                <input type="submit" value="Ajouter à ma liste" id="list">');
                                            }
                                        }

                                    ?>
                                </form>
                                <form action="player.php" method="post" class="streaming_form">
                                    <input type="submit" value="Regarder" id="play">
                                    <input type="hidden" name="id" value="<?php echo($topGun); ?>">
                                    <input type="hidden" name="user" value="<?php echo($pseudo); ?>">
                                </form>
                            </div>
                    </div>
                </div>
            </form>
            <form action="player.php" method="post" class="streaming_form">
                <!-- Thor -->
                <?php $Thor = 4 ?>
                <div class="videos">
                    <div class="dropdown">
                        <input type="image" src="main-imgs/Thor.jpg" value="Submit" class="animeCase">
                        <input type="hidden" name="id" value="<?php echo($Thor); ?>">
                        <input type="hidden" name="user" value="<?php echo($pseudo); ?>">
                            </form>
                            <div class="dropdown-content">
                                <form action="index.php" method="post">
                                    <?php 
                                        $req = $db->prepare("SELECT list FROM profile$id_profile WHERE pseudo = ?");
                                        $req->execute(array($pseudo));

                                        while ($user = $req->fetch()) {
                                            $bddList = $user[0];

                                            if ($Thor == $bddList) {
                                                echo('
                                                <input type="hidden" name="delList" value="'.$Thor.'">
                                                <input type="submit" value="Supprimer de ma liste" id="list">');
                                            } else {
                                                echo('
                                                <input type="hidden" name="addList" value="'.$Thor.'">
                                                <input type="submit" value="Ajouter à ma liste" id="list">');
                                            }
                                        }

                                    ?>
                                </form>
                                <form action="player.php" method="post" class="streaming_form">
                                    <input type="submit" value="Regarder" id="play">
                                    <input type="hidden" name="id" value="<?php echo($Thor); ?>">
                                    <input type="hidden" name="user" value="<?php echo($pseudo); ?>">
                                </form>
                            </div>
                    </div>
                </div>
            </form>
            <form action="player.php" method="post" class="streaming_form">
                <!-- les Minions -->
                <?php $Minions = 8 ?>
                <div class="videos">
                    
                    <div class="dropdown">
                        <input type="image" src="main-imgs/Minions.jfif" value="Submit" class="animeCase">
                        <input type="hidden" name="id" value="<?php echo($Minions); ?>">
                        <input type="hidden" name="user" value="<?php echo($pseudo); ?>">
                            </form>
                            <div class="dropdown-content">
                                <form action="index.php" method="post">
                                    <?php 
                                        $req = $db->prepare("SELECT list FROM profile$id_profile WHERE pseudo = ?");
                                        $req->execute(array($pseudo));

                                        while ($user = $req->fetch()) {
                                            $bddList = $user[0];

                                            if ($Minions == $bddList) {
                                                echo('
                                                <input type="hidden" name="delList" value="'.$Minions.'">
                                                <input type="submit" value="Supprimer de ma liste" id="list">');
                                            } else {
                                                echo('
                                                <input type="hidden" name="addList" value="'.$Minions.'">
                                                <input type="submit" value="Ajouter à ma liste" id="list">');
                                            }
                                        }

                                    ?>
                                </form>
                                <form action="player.php" method="post" class="streaming_form">
                                    <input type="submit" value="Regarder" id="play">
                                    <input type="hidden" name="id" value="<?php echo($Minions); ?>">
                                    <input type="hidden" name="user" value="<?php echo($pseudo); ?>">
                                </form>
                            </div>
                    </div>
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
                            <div class="videos removeMargin">
                                
                                <div class="dropdown">
                                    <input type="image" src="'.$main_img.'" value="Submit" class="animeCase">
                                    <input type="hidden" name="id" value="'.$list.'">
                                    <input type="hidden" name="user" value"'.$pseudo.'">
                                    </form>
                                    <div class="dropdown-content">
                                        <form action="index.php" method="post">
                                            <input type="hidden" name="delList" value="'.$list.'">
                                            <input type="submit" value="Supprimer de ma liste" id="list">
                                        </form>
                                        <form action="player.php" method="post" class="streaming_form">
                                            <input type="submit" value="Regarder" id="play">
                                            <input type="hidden" name="id" value="'.$list.'">
                                            <input type="hidden" name="user" value="'.$pseudo.'">
                                        </form>
                                    </div>
                                </div>
                            </div>
                        </form>
                    </div>
                    ');
                }

            }} 
            ?>
        
    </div>

    <script src="../Netflix/js/coupleCuckoosMenu.js"></script>
    <script src="../Netflix/js/fadeEffect.js"></script>
    <script src="../Netflix/js/Anime_carrousel.js"></script>
</body>
</html>

<?php }?>