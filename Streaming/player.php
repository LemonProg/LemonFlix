<?php 
session_start();
if (!isset($_SESSION['connect'])) {
    error_reporting(0);
    header("location: ../index.php");
} else {
?>

<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>LemonFlix</title>
    <link rel="stylesheet" href="player_style.css">
    <link rel="icon" type="image/pngn" href="../img/favicon.png">
</head>
<body>
    <div class="parent">
        <?php
            require('../src/connect.php');
            $Code = htmlspecialchars($_COOKIE['secretCode']);

            $req = $db->prepare("SELECT * FROM user WHERE secret_code = ?");
            $req->execute(array($Code));

            while ($user = $req->fetch()) {
                $id_profile = $user['id_profile'];
                $id_ep = htmlspecialchars($_POST['id']);
                $pseudo = htmlspecialchars($_POST['user']);

                $req = $db->prepare("UPDATE profile$id_profile SET watched = ? WHERE pseudo = ?");
                $req->execute(array($id_ep, $pseudo));

                $req = $db->prepare("SELECT * FROM streaming WHERE id = ?");
                $req->execute(array($id_ep));

                while ($user = $req->fetch()) {
                    $url = $user['url'];
                    ?>
                    <iframe src='<?php echo($url); }} ?>' id='player' scrolling='no' frameborder='0' allowfullscreen></iframe>

    </div>
</body>
</html>

<?php }?>