<?php
function randLetter() {
    $int = rand(0,25);
    $a_z = "ABCDEFGHIJKLMNOPQRSTUVWXYZ";
    $rand_letter = $a_z[$int];
    return $rand_letter;
}

function randNumber() {
    $int = rand(0, 10);
    return $int;
}

$savePro_code = randLetter().randNumber().randLetter().randNumber();

    if(!empty($_POST['pseudo']) && (!empty($_POST['url']))) {
        require('../src/connect.php');

        $pseudo      = htmlspecialchars($_POST['pseudo']);
        $url         = htmlspecialchars($_POST['url']);
        $secret_code = htmlspecialchars($_COOKIE['secretCode']);


        $req = $db->prepare("SELECT * FROM user WHERE secret_code = ?");
	    $req->execute(array($secret_code));

        while($id_profile = $req->fetch()) {

            $id_profile = $id_profile['id_profile'];

            $req = $db->prepare("SELECT count(*) from profile$id_profile");
            $req->execute();
            
            while ($user = $req->fetch()) {
                $count = $user[0];

                if ($count < 5) {
                    $req = $db->prepare("SELECT count(*) as numberPseudo FROM profile$id_profile WHERE pseudo = ?");
                    $req->execute(array($pseudo));
        
                    while($email_verification = $req->fetch()){
        
                        if($email_verification['numberPseudo'] != 0){
        
                            header('location: AddProfile.php?error=1&message=Ce prénom est déjà utiliser.');
                            exit();
        
                        }
                    }
        
                    $req = $db->prepare("INSERT INTO profile$id_profile(pseudo, url) VALUES(?,?)");
                    $req->execute(array($pseudo, $url));
        
                    $req = $db->prepare("SELECT * FROM profile$id_profile ORDER BY id DESC LIMIT 1");
                    $req->execute();
        
                    while ($user = $req->fetch()) {
                        $id_user = $user['id'];
        
                        $req = $db->prepare("INSERT INTO saved_profils(id_user, code, pseudo, url) VALUES(?,?,?,?)");
                        $req->execute(array($id_user, $savePro_code, $pseudo, $url));
                        
                        header('location: main.php?success=1');
                        exit();
                    }
                } else {
                    header('location: AddProfile.php?error=1&message=Vous avez atteint le nombre maxial de profils.');
                    exit();
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
    <title>Ajout Profile - LemonFlix</title>
    <link rel="stylesheet" href="stylesAddProfils.css">
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
        <form method="post" action="AddProfile.php" id="Forumlaire">
            <h1>Ajouter un profil</h1>
            <?php

                if(isset($_GET['error'])) {
                    if(isset($_GET['message'])) {
                        echo '<p class="alert">'.htmlspecialchars($_GET['message']).'</p>';
                    }
                }

            ?>
            <td>
                <p class="prenom">Prénom</p>
                <input type="text" name="pseudo" class="form" required>
            </td>
            <td>
                <p>Photo de Profile</p>
                <input type="url" name="url" class="form" required>
                <br>
                <div id="websiteDiv">
                    <a href="https://online-hoster.000webhostapp.com/" id="website">Site d'hébergement en ligne</a>
                </div>
                
            </td>
            <input type="submit" value="Ajouter" id="valBtn">
        </form>
    </div>

    <footer>
        <p class="footer">&copy; Copyright 2022 – LemonFlix</p>
    </footer>
</body>
</html>

