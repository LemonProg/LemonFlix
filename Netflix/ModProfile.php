<?php 
session_start();
if (!isset($_SESSION['connect'])) {
    error_reporting(0);
    header("location: ../index.php");
} else {
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>LemonFlix</title>
    <link rel="stylesheet" href="stylesModProfils.css">
    <link rel="icon" type="image/pngn" href="../img/favicon.png">
</head>
<body>
    <header>
        <form action="../index.php" id="logo">
            <input type="image" src="../img/LemonFlix.png" alt="Submit">
            <?php
                require('../src/connect.php');
                $Code = htmlspecialchars($_COOKIE['secretCode']);
                
                $req = $db->prepare("SELECT * FROM user WHERE secret_code = ?");
                $req->execute(array($Code));

                while ($user = $req->fetch()) {
                    $id_profile = $user['id_profile'];
                    echo('<p id="email">'.$user["email"].'</p>');
                }
                ?> 
            <div id="logoutDiv">
                <a id="logout" href="../logout.php">Déconnexion</a>
            </div>
        </form>
    </header>
    <div class="containerParent">
        <h1>Gestion des profils :</h1>
        <?php
            $req = $db->prepare("SELECT * FROM profile$id_profile");
            $req->execute();
        
            while ($user = $req->fetch()) {
                $pseudo = $user['pseudo'];
                $url = $user['url'];

                echo('
                <div class="container">
                    <form action="EditProfiles.php" method="post">
                        <input type="image" src="'.$url.'" id="pp" value="Submit" name="image">
                        <img src="../img/pencil.svg" alt="edit" class="top">
                        <input type="hidden" name="user" value="'.$pseudo.'">
                        <p id="pseudo">'.$pseudo.'</p>
                    </form>
                </div>
                ');
            }

            $req = $db->prepare("SELECT count(*) from profile$id_profile");
            $req->execute();
            
            while ($user = $req->fetch()) {
                $count = $user[0];

                if ($count < 5) {
                    echo('
                    <div class="container ');
                    if (empty($pseudo)) {
                        echo('ajustMargin');
                    } 
            echo('">
                        <form action="AddProfile.php" id="addForm">
                            <input type="image" class="addBtn" value="Submit">
                            <p id="pseudo">Ajouter un profil</p>
                        </form>
                    </div>
                    ');
                }
        }
        ?>
        <br>
        <form action="main.php">
            <input type="submit" value="Terminé" id="backBtn">
        </form>
    </div>
</body>
</html>
<?php  } ?>
