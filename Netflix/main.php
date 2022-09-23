<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Netflix - Main Page</title>
    <link rel="stylesheet" href="styleMain.css">
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
    <h1>Qui est-ce?</h1>
    
    <form id="FormPro">
        <tr>
            <div class="profiles">
                <?php
                    require('../src/connect.php');

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
                            <div class="container">
                                <img src='.$url.'>
                                <p id="pseudo">'.$pseudo.'</p>                     
                            </div>
                            ');
                            // 
                        }
                    }
                ?>
            </div>
        </tr>
    </form>
    <footer>
        <div id="divProfiles">
            <a id="addProfiles"href="profiles.php">Gérer les profils</a>
        </div>
        <p class="footer">&copy; Copyright 2022 – LemonFlix</p>
    </footer>
</body>
</html>
