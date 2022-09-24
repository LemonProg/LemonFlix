<?php
    if(!empty($_POST['pseudo']) && (!empty($_POST['age']))) {
        require('../src/connect.php');

        $pseudo      = htmlspecialchars($_POST['pseudo']);
        $age         = htmlspecialchars($_POST['age']);
        $url         = htmlspecialchars($_POST['url']);
        $secret_code = htmlspecialchars($_COOKIE['secretCode']);


        if(is_int($age))  {
            header("location: AddProfile.php?error=1&message=Votre age n'est pas valide.");
            exit();
        }else if($age <= 0) {
            header("location: AddProfile.php?error=1&message=Votre age n'est pas valide.");
            exit();
        }

        

        $req = $db->prepare("SELECT * FROM user WHERE secret_code = ?");
	    $req->execute(array($secret_code));

        while($id_profile = $req->fetch()) {

            $req = $db->prepare("INSERT INTO profile".$id_profile['id_profile']."(pseudo, age, url) VALUES(?,?,?)");
            $req->execute(array($pseudo, $age, $url));
            
            header('location: AddProfile.php?success=1');
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
    <title>Netflix - AddProfile Page</title>
    <link rel="stylesheet" href="styleAddProfils.css">
    <link rel="icon" type="image/pngn" href="../img/favicon.png">
</head>
<body>
    <header>
        <form action="../index.php" id="logo">
            <input type="image" src="../img/logo.png" alt="Submit">
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
            <h1>Ajout d'un  utilisateur</h1>
            <?php

                if(isset($_GET['error'])) {
                    if(isset($_GET['message'])) {
                        echo '<p class="alert">'.htmlspecialchars($_GET['message']).'</p>';
                    }
                }else if (isset($_GET['success'])) {
                    echo '<p class="success">Votre Profile a bien été ajouter.</p>';
                    echo '<p>Retour au <a href="main.php">Menu Principale</a>.</p>';
                }

            ?>
            <td>
                <p class="prenom">Prénom</p>
                <input type="text" name="pseudo" class="form" required>
            </td>
            <td>
                <p>Age</p>
                <input type="number" name="age" class="form" required>
            </td>
            <td>
                <p>Photo de Profile</p>
                <input type="url" name="url" class="form" required>
            </td>
            <br>
            <input type="submit" value="Ajouter" id="valBtn">
        </form>
    </div>

    <footer>
        <p class="footer">&copy; Copyright 2022 – LemonFlix</p>
    </footer>
</body>
</html>

