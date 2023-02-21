<?php
session_start();
// error_reporting(0);
if (!isset($_SESSION['connect'])) {
    header("location: ../index.php");
} else {
require('../src/connect.php');

if (!empty($_POST['user'])) {
    header("location: index.php");
    $pseudo = htmlspecialchars($_POST['user']);
    if ($_POST['user'] != '') {
        setcookie('pseudoUser', $pseudo, time()+3600*24, '/', '', false, false);
    }
} else {
    $pseudo = htmlspecialchars($_COOKIE['pseudoUser']);
}

$Code = htmlspecialchars($_COOKIE['secretCode']);

$req = $db->prepare("SELECT * FROM user WHERE secret_code = ?");
$req->execute(array($Code));

while ($user = $req->fetch()) {
    $id_profile = $user['id_profile'];

    if (!empty($_POST['addList'])) {
        $pseudo = htmlspecialchars($_COOKIE['pseudoUser']);
    
        $list_id = htmlspecialchars($_POST['addList']);

        $req = $db->prepare("SELECT list FROM profile$id_profile WHERE pseudo = ?");
        $req->execute(array($pseudo));

        while ($list = $req->fetch()) {
            $listRecover = $list['list'];

            $arrayListDB = explode("/", $listRecover);

            if(!in_array($list_id, $arrayListDB)) {
                if(!empty($listRecover)) {
                    $finalPush = $listRecover."/".$list_id;

                    $req = $db->prepare("UPDATE profile$id_profile SET list = ? WHERE pseudo = ?");
                    $req->execute(array($finalPush, $pseudo));
                } else {
                    $req = $db->prepare("UPDATE profile$id_profile SET list = ? WHERE pseudo = ?");
                    $req->execute(array($list_id, $pseudo));
                }
            }
           
        }
    
    }

    if (!empty($_POST['delList'])) {
        $pseudo = htmlspecialchars($_COOKIE['pseudoUser']);
        $list_id = htmlspecialchars($_POST['delList']);

        $req = $db->prepare("SELECT list FROM profile$id_profile WHERE pseudo = ?");
        $req->execute(array($pseudo));

        while ($list = $req->fetch()) {
            $listRecover = $list['list'];

            $arrayDB = explode("/", $listRecover);

            if ($list_id == $arrayDB[0]) {
                $finalPush = str_replace($list_id.'/', '', $listRecover);
            } else {
                $finalPush = str_replace('/'.$list_id, '', $listRecover);
            }
            

            $req = $db->prepare("UPDATE profile$id_profile SET list = ? WHERE pseudo = ?");
            $req->execute(array($finalPush, $pseudo));


        }
    
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
    <link rel="stylesheet" href="streaming.css">
    <link rel="icon" type="image/pngn" href="../img/favicon.png">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/swiper@8/swiper-bundle.min.css"/>
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
                            <input type="submit" value="Épisode 1" class="pushBtnEP">
                            <input type="hidden" name="id" value="<?php echo($coupleCuckoos1); ?>">
                            <input type="hidden" name="user" value="<?php echo($pseudo); ?>">
                        </li>
                    </form>
                    <form action="player.php" method="post" class="streaming_form">
                        <li>
                            <input type="submit" value="Épisode 2" class="pushBtnEP">
                            <input type="hidden" name="id" value="<?php echo($coupleCuckoos2); ?>">
                            <input type="hidden" name="user" value="<?php echo($pseudo); ?>">
                        </li>
                    </form>
                    <form action="player.php" method="post" class="streaming_form">
                        <li>
                            <input type="submit" value="Épisode 3" class="pushBtnEP">
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
                            </form>
                            <form action="index.php" id="searchForm" method="post">
                                <!-- <input type="search" name="search" id="searchBar" placeholder="Chercher..."> -->
                                <div class="group">
                                    <svg class="icon" aria-hidden="true" viewBox="0 0 24 24"><g><path d="M21.53 20.47l-3.66-3.66C19.195 15.24 20 13.214 20 11c0-4.97-4.03-9-9-9s-9 4.03-9 9 4.03 9 9 9c2.215 0 4.24-.804 5.808-2.13l3.66 3.66c.147.146.34.22.53.22s.385-.073.53-.22c.295-.293.295-.767.002-1.06zM3.5 11c0-4.135 3.365-7.5 7.5-7.5s7.5 3.365 7.5 7.5-3.365 7.5-7.5 7.5-7.5-3.365-7.5-7.5z"></path></g></svg>
                                    <input placeholder="Rechercher" type="search" class="input" name="search" spellcheck="false" id="searchBar">
                                </div>
                            </form>
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
            </div>
        </header>
        <?php 
            function adaptCut($animeName) {
                if (strlen($animeName) <= 6) {
                    return -3;
                } 
                if (strlen($animeName) <= 9) {
                    return -6;
                } 
                if (strlen($animeName) <= 14) {
                    return -7;
                }
                if (strlen($animeName) <= 16) {
                    return -11;
                }
                if (strlen($animeName) > 16) {
                    return -13;
                }
            }
            
            if(!empty($_POST['search'])) {
                $search = htmlspecialchars($_POST['search']);
            
                $req = $db->prepare("SELECT * FROM streaming");
                $req->execute();
            
                while($user = $req->fetch()){
                    $animes = $user['name'];
            
                    $animes = strtolower($animes);
                    $search = strtolower($search);
            
                    $animes_cut = substr($animes,0, adaptCut($animes));
            
                    if(str_starts_with($search, $animes_cut)) {
                        $req = $db->prepare("SELECT * FROM streaming WHERE name = ?");
                        $req->execute(array($animes));
            
                        while($id = $req->fetch()){
                            $animeId = $id['id'];
                            $animeImg = $id['img'];

                            echo('
                            <h2 class="h2_margin">Recherche -</h2>
                            <div class="menu">
                                <form action="player.php" method="post" class="streaming_form">
                                    <div class="videos fixMargin">
                                        <div class="dropdown">
                                            <input type="image" src="'.$animeImg.'" value="Submit" class="animeCase">
                                            <input type="hidden" name="id" value="'.$animeId.'">
                                            <input type="hidden" name="user" value="'.$pseudo.'">
                                            </form>
                                            <div class="dropdown-content">
                                                <form action="index.php" method="post">
                                                    '); 
                                                        $req = $db->prepare("SELECT list FROM profile$id_profile WHERE pseudo = ?");
                                                        $req->execute(array($pseudo));
                
                                                        while ($user = $req->fetch()) {
                                                            $bddList = $user[0];
                                                            $arrayList = explode("/", $bddList);
                
                                                            if (in_array($animeId, $arrayList)) {
                                                                echo('
                                                                <input type="hidden" name="delList" value="'.$animeId.'">
                                                                <input type="submit" value="Supprimer de ma liste" id="list">');
                                                            } else {
                                                                echo('
                                                                <input type="hidden" name="addList" value="'.$animeId.'">
                                                                <input type="submit" value="Ajouter à ma liste" id="list">');
                                                            }
                                                        }
                
                                                    echo('
                                                    
                                                </form>
                                                <form action="player.php" method="post" class="streaming_form">
                                                    <input type="submit" value="Regarder" id="play">
                                                    <input type="hidden" name="id" value="'.$animeId.'">
                                                    <input type="hidden" name="user" value="'.$pseudo.'">
                                                </form>
                                            </div>
                                        </div>
                                    </div>
                                </form>    
                            </div>
                                
                            ');
                        }
                    }
                }
            }

            if (isset($watched)) {
                echo('
                <h2>Reprendre avec le profile de <strong>'.$pseudo.'</strong> -</h2>
                <div class="menu" id="watched_section">
                <div class="watched_container">
                    ');

                if(str_contains($watched, '/')) {
                    $arrayWatched = explode("/", $watched);
                    $count = 0;
                    foreach($arrayWatched as $id) {
                        $count++;
                        $req = $db->prepare("SELECT * FROM streaming WHERE id = ?");
                        $req->execute(array($id));
                        
                        while ($user = $req->fetch()) {
                            $img = $user['img'];
                        }
                        if (str_contains($id, "op")) {
                            $req = $db->prepare("SELECT * FROM onepiece WHERE ep = ?");
                            $req->execute(array($id));
                            while ($userOP = $req->fetch()) {
                                $img = $userOP['img'];
                            }     
                        }
                        echo('
                            <form action="player.php" method="post" class="streaming_form">
                                <div class="videos removeMargin">
                                    <div class="dropdown">
                                        <input type="image" src='.$img.' value="Submit" class="animeCase"'); 
                                        if ($count == 6) { 
                                            echo('id="lastOne_watched"'); 
                                        }
                                        if ($count == 5) { 
                                            echo('id="secondeOne_watched"'); 
                                        } 
                        echo('>
                                        <input type="hidden" name="id" value='.$id.'>
                                        <input type="hidden" name="user" value="NULL">
                                        </form>
                                        <div class="dropdown-content">
                                            <form action="index.php" method="post">
                                                ');
                                    $req = $db->prepare("SELECT list FROM profile$id_profile WHERE pseudo = ?");
                                    $req->execute(array($pseudo));

                                    while ($user = $req->fetch()) {
                                        $bddList = $user[0];
                                        $arrayList = explode("/", $bddList);

                                        if (in_array($id, $arrayList)) {
                                            echo('
                                            <input type="hidden" name="delList" value="'.$id.'">
                                            <input type="submit" value="Supprimer de ma liste" id="list">');
                                        } else {
                                            echo('
                                            <input type="hidden" name="addList" value="'.$id.'">
                                            <input type="submit" value="Ajouter à ma liste" id="list">');
                                        }
                                    }
                                            
                                    echo('</form>
                                            <form action="player.php" method="post" class="streaming_form">
                                                <input type="submit" value="Regarder" id="play">
                                                <input type="hidden" name="id" value='.$id.'>
                                                <input type="hidden" name="user" value="NULL">
                                            </form>
                                        </div>
                                    </div>
                                </div>
                            </form>
                            ');
                    }
                    echo('
                    </div>
                    <img src="../img/arrow.svg" alt="arrow" class="button" id="d_watched">
                    <img src="../img/arrow.svg" alt="arrow" class="button" id="g_watched">');
                } else {
                    if (str_contains($watched, "op")) {
                        $req = $db->prepare("SELECT * FROM onepiece WHERE ep = ?");
                        $req->execute(array($watched));
                        while ($userOP = $req->fetch()) {
                            $img = $userOP['img'];
                        }     
                    }
                    echo('
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
                                        $arrayList = explode("/", $bddList);

                                        if (in_array($watched, $arrayList)) {
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
                echo("</div>");
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
                                            $arrayList = explode("/", $bddList);

                                            if (in_array($coupleCuckoos1, $arrayList)) {
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

                                            if (in_array($jujutsuKaisen, $arrayList)) {
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

                                            if (in_array($sao, $arrayList)) {
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

                                            if (in_array($chainsaw, $arrayList)) {
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

                                            if (in_array($blueLock, $arrayList)) {
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

                                            if (in_array($mha, $arrayList)) {
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
                <form action="player.php" method="post" class="streaming_form">
                    <!-- One piece -->
                    <?php $op = "op_3"; ?>
                    <div class="videos">
                        <div class="dropdown">
                            <input type="image" src="https://i.pinimg.com/originals/ff/6e/b2/ff6eb2820802df2af1e20c85841eb907.jpg" value="Submit" class="animeCase" id="lastOne">
                            <input type="hidden" name="id" value="<?php echo($op); ?>">
                            <input type="hidden" name="user" value="<?php echo($pseudo); ?>">
                            </form>
                            <div class="dropdown-content">
                                <form action="index.php" method="post">
                                    <?php 
                                        $req = $db->prepare("SELECT list FROM profile$id_profile WHERE pseudo = ?");
                                        $req->execute(array($pseudo));

                                        while ($user = $req->fetch()) {
                                            $bddList = $user[0];

                                            if (in_array($op, $arrayList)) {
                                                echo('
                                                <input type="hidden" name="delList" value="'.$op.'">
                                                <input type="submit" value="Supprimer de ma liste" id="list">');
                                            } else {
                                                echo('
                                                <input type="hidden" name="addList" value="'.$op.'">
                                                <input type="submit" value="Ajouter à ma liste" id="list">');
                                            }
                                        }

                                    ?>
                                </form>
                                <form action="player.php" method="post" class="streaming_form">
                                    <input type="submit" value="Regarder" id="play">
                                    <input type="hidden" name="id" value="<?php echo($op); ?>">
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
            <?php
            $req = $db->prepare("SELECT * FROM streaming WHERE type = ?");
            $req->execute(array("movie"));

            $arrayId = array();
            $arrayURL = array();

            while ($streaming = $req->fetch()) {
                $animesId = $streaming['id'];
                $animeImg = $streaming['img'];

                array_push($arrayId, $animesId);
                array_push($arrayURL, $animeImg);
            }

            for ($i = 0; $i <= (count($arrayId) - 1); $i++) {
                echo('
                    <form action="player.php" method="post"
                    class="streaming_form">
                        <div class="videos">
                            <div class="dropdown">
                                <input type="image" src="'.$arrayURL[$i].'" value="Submit" class="animeCase">
                                <input type="hidden" name="id" value="'.$arrayId[$i].'">
                                <input type="hidden" name="user" value="'.$pseudo.'">
                    <div class="dropdown-content">
                    </form>
                    ');
                    $req = $db->prepare("SELECT list FROM profile$id_profile WHERE pseudo = ?");
                    $req->execute(array($pseudo));

                    while ($user = $req->fetch()) {
                        $bddList = $user[0];

                        if (in_array($arrayId[$i], $arrayList)) {
                            echo('
                            <form action="index.php" method="post">
                                <input type="hidden" name="delList" value="'.$arrayId[$i].'">
                                <input type="submit" value="Supprimer de ma liste" id="list">
                            </form>');  
                        } else {
                            echo('
                            <form action="index.php" method="post">
                                <input type="hidden" name="addList" value="'.$arrayId[$i].'">
                                <input type="submit" value="Ajouter à ma liste" id="list">
                            </form> ');  
                        }
                    }

                echo('
                    <form action="player.php" method="post" class="streaming_form">
                        <input type="submit" value="Regarder" id="play">
                        <input type="hidden" name="id" value="'.$arrayId[$i].'">
                        <input type="hidden" name="user" value="'.$pseudo.'">
                    </form>
                    </div>
                    </div>
                    </div>');
            } ?>
            </div>
        </div>
        
        <?php
                $req = $db->prepare("SELECT * FROM streaming WHERE id = ?");
                $req->execute(array($list));

                while ($user = $req->fetch()) {
                    $main_img = $user['img'];
                }
                if ($list != "") {
                    echo('<h2>Ma Liste -</h2>
                        <div class="menu">');
                    if(str_contains($list, '/')) {
                        $arrayList = explode("/", $list);
                        foreach($arrayList as $id) {
                            $req = $db->prepare("SELECT * FROM streaming WHERE id = ?");
                            $req->execute(array($id));

                            while ($user = $req->fetch()) {
                                $img = $user['img'];
                            }
                            if (str_contains($id, "op")) {
                                $req = $db->prepare("SELECT * FROM onepiece WHERE ep = ?");
                                $req->execute(array($id));
                                while ($userOP = $req->fetch()) {
                                    $img = $userOP['img'];
                                }     
                            }
                            echo('
                                <form action="player.php" method="post" class="streaming_form">
                                    <div class="videos removeMargin">
                                        <div class="dropdown">
                                            <input type="image" src="'.$img.'" value="Submit" class="animeCase">
                                            <input type="hidden" name="id" value="'.$id.'">
                                            <input type="hidden" name="user" value"'.$pseudo.'">
                                            </form>
                                            <div class="dropdown-content">
                                                <form action="index.php" method="post">
                                                    <input type="hidden" name="delList" value="'.$id.'">
                                                    <input type="submit" value="Supprimer de ma liste" id="list">
                                                </form>
                                                <form action="player.php" method="post" class="streaming_form">
                                                    <input type="submit" value="Regarder" id="play">
                                                    <input type="hidden" name="id" value="'.$id.'">
                                                    <input type="hidden" name="user" value="'.$pseudo.'">
                                                </form>
                                            </div>
                                        </div>
                                    </div>
                                </form>
                            ');
                        }
                        echo("</div>");
                    
                    } else {
                        echo('
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
                }

            }} 
            ?>
        
    </div>

    <script src="../Netflix/js/watched_carrou.js"></script>
    <script src="../Netflix/js/coupleCuckoosMenu.js"></script>
    <script src="../Netflix/js/fadeEffects.js"></script>
    <script src="../Netflix/js/carrousel_anime.js"></script>
    <script src="../Netflix/js/searchScript.js"></script>
</body>
</html>
<?php } ?>