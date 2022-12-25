<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>LemonFlix</title>
    <link rel="stylesheet" href="styleModProfils.css">
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

                ?> 
            <div id="logoutDiv">
                <a id="logout" href="../logout.php">Déconnexion</a>
            </div>
        </form>
    </header>
    <div class="containerParent">
        <?php
            $req = $db->prepare("SELECT * FROM profile".$user['id_profile']);
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
                        <p id="pseudo" name="image">'.$pseudo.'</p>
                    </form>
                </div>
                ');
            }
        }
        ?>
        <br>
        <form action="main.php">
            <input type="submit" value="Retour" id="backBtn">
        </form>
    </div>
    <footer>
        <p class="footer">&copy; Copyright 2022 – LemonFlix</p>
    </footer>
</body>
</html>

