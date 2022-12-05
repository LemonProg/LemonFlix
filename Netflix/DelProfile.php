<?php
require('../src/connect.php');

if(isset($_POST['pseudo'])) {
    $pseudo      = htmlspecialchars($_POST['pseudo']);
    $secret_code = htmlspecialchars($_COOKIE['secretCode']);

    $req = $db->prepare("SELECT * FROM user WHERE secret_code = ?");
    $req->execute(array($secret_code));


    while($user = $req->fetch()) {
        $userProfile = $user['id_profile'];

        $req = $db->prepare("SELECT * FROM profile".$userProfile);
        $req->execute();

        while($user = $req->fetch()) {
            
            if ($pseudo == $user['pseudo']) {
                $req = $db->prepare("DELETE FROM profile$userProfile WHERE pseudo = ?");
                $req->execute(array($pseudo));

                header('location: DelProfile.php?success=1');
                exit();
            }else {
                header('location: DelProfile.php?error=1');
            }
        }

        
        }
        
    }
    
?>


<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Netflix - Delete Profile</title>
    <link rel="stylesheet" href="styleDelProfiles.css">
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
                    echo('<p id="email">'.$user["email"].'</p>');
                }
                ?> 
            <div id="logoutDiv">
                <a id="logout" href="../logout.php">Déconnexion</a>
            </div>
        </form>
    </header>
    <div class="container">
        <h1>Suppression d'un  utilisateur</h1>
        <?php
            $Code = htmlspecialchars($_COOKIE['secretCode']);
            
            $req = $db->prepare("SELECT * FROM user WHERE secret_code = ?");
            $req->execute(array($Code));
            
            while ($user = $req->fetch()) {
                $req = $db->prepare("SELECT * FROM profile".$user['id_profile']);
                $req->execute();
            
                while ($user = $req->fetch()) {
            
                    $pseudo = $user['pseudo'];
                    $url = $user['url'];

                    echo('
                    <div class="containerImage">
                        <img id="pp" src="'.$url.'">
                        <p id="pseudo">'.$pseudo.'</p>
                    </div>
                    ');
                }
            }
        ?>
        <form action="DelProfile.php" method="post" id="pseudo_form">
            <input type="text" name="pseudo" id="pseudo_input" placeholder="Veuillez inscrire un Utilisateur à supprimer : " required>
            <input type="submit" value="Valider" id="pseudo_submit">
        </form>
    </div>

    <?php 
        if(isset($_GET['error'])) {
            echo "<script src='js/input_error.js'></script>";
        }
        if (isset($_GET['success'])) {
            echo "<script src='js/input_success.js'></script>";
        }
    ?>

    <footer>
        <p class="footer">&copy; Copyright 2022 – LemonFlix</p>
    </footer>
</body>
</html>

