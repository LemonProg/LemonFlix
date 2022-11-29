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
    <link rel="stylesheet" href="styles.css">
    <link rel="icon" type="image/pngn" href="../img/favicon.png">
</head>
<body>
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
    
    <div class="anime_section">
        <h2>Animés -</h2>
        <form action="player.php" method="post" id="anime_form">
            <input type="image" src="main-imgs/main.jpg" value="Submit">
            <input type="hidden" name="id" value="1">
        </form>
        
    </div>
    
    <script src="../Netflix/js/fade.js"></script>
</body>
</html>