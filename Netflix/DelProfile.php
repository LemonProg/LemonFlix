<?php
require('../src/connect.php');

    if(isset($_GET['pseudo'])) {
        $pseudo      = htmlspecialchars($_GET['pseudo']);
        $secret_code = htmlspecialchars($_COOKIE['secretCode']);

        $req = $db->prepare("SELECT * FROM user WHERE secret_code = ?");
	    $req->execute(array($secret_code));


        while($user = $req->fetch()) {
            $userProfile = $user['id_profile'];

            $req = $db->prepare("SELECT * FROM profile".$userProfile);
	        $req->execute();

            $req = $db->prepare("DELETE FROM profile$userProfile WHERE pseudo = ?");
            $req->execute(array($pseudo));

            header('location: DelProfile.php?success=1');
            exit();
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
    <link rel="stylesheet" href="styleDelProfil.css">
    <link rel="icon" type="image/pngn" href="../img/favicon.png">
</head>
<body>
    <header>
        <form action="../index.php" id="logo">
            <input type="image" src="../img/logo.png" alt="Submit">
            <div id="logoutDiv">
                <a id="logout" href="../logout.php">Déconnexion</a>
            </div>
        </form>
    </header>
    <div class="container">
        <h1>Suppression d'un  utilisateur</h1>
        <h2>Appuyiez pour supprimer :</h2>
        <?php

            if(isset($_GET['error'])) {
                if(isset($_GET['message'])) {
                    echo '<p class="alert">'.htmlspecialchars($_GET['message']).'</p>';
                }
            }else if (isset($_GET['success'])) {
                echo '<p class="success">Votre Profile a bien été a supprimé.</p>';
                echo '<p>Retour au <a href="main.php">Menu Principale</a>.</p>';
            }
            $Code = htmlspecialchars($_COOKIE['secretCode']);
            
            $req = $db->prepare("SELECT * FROM user WHERE secret_code = ?");
            $req->execute(array($Code));
            
            while ($user = $req->fetch()) {
                $req = $db->prepare("SELECT pseudo, age FROM profile".$user['id_profile']);
                $req->execute();
            
                while ($user = $req->fetch()) {
            
                    $pseudo = $user['pseudo'];
                    
                    echo('<a id="pseudo" href="DelProfile.php?pseudo='.$pseudo.'">'.$pseudo.'</a>');
                }
            }
        ?>
    </div>

    <footer>
        <p class="footer">&copy; Copyright 2022 – LemonFlix</p>
    </footer>
</body>
</html>

