<?php
require('../src/connect.php');
$pseudo = htmlspecialchars($_POST['user']);
$Code = htmlspecialchars($_COOKIE['secretCode']);

$req = $db->prepare("SELECT * FROM user WHERE secret_code = ?");
$req->execute(array($Code));

while ($user = $req->fetch()) {
    $id_profile = $user['id_profile'];

    $req = $db->prepare("SELECT * FROM profile$id_profile WHERE pseudo = ?");
    $req->execute(array($pseudo));

    while ($user = $req->fetch()) {
        $url = $user['url'];
?>


<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Netflix - Streaming Page</title>
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
            <form action="../index.php" id="logo">
                <input type="image" src="../img/logo.png" alt="Submit">
                <div id="logoutDiv">
                    <?php
                        echo('<img src="'.$url.'" alt="profile_img" id="profile_img">');}}
                    ?>
                    <br>
                    <a id="logout" href="../logout.php">Déconnexion</a>
                </div>
            </form>
        </header>
        <h2 id="animeH2">Animés -</h2>
        <div class="anime_section">
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
        </div>
        <h2>Films -</h2>
        <div class="film_section">
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
                <div class="Les Minions 2">
                    <input type="image" src="main-imgs/Minions.jfif" value="Submit" class="animeCase">
                    <input type="hidden" name="id" value="8">
                    <input type="hidden" name="user" value="<?php echo($pseudo); ?>">
                </div>
            </form>
        </div>
    </div>

    <script src="../Netflix/js/coupleOfCuckoos.js"></script>
    <script src="../Netflix/js/fadeEffect.js"></script>
</body>
</html>