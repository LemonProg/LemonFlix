<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Netflix - Profile Page</title>
    <link rel="stylesheet" href="styleProfile.css">
    <link rel="icon" type="image/pngn" href="../img/favicon.png">
</head>
<body>
    <header>
        <form action="../index.php" id="logo">
            <input type="image" src="../img/LemonFlix.png" alt="Submit">
            <div id="logoutDiv">
            <?php
                require('../src/connect.php');
                $Code = htmlspecialchars($_COOKIE['secretCode']);
                
                $req = $db->prepare("SELECT * FROM user WHERE secret_code = ?");
                $req->execute(array($Code));

                while ($user = $req->fetch()) {
                    echo('<p id="email">'.$user["email"].'</p>');
                    $id_profile = $user['id_profile'];

                ?>  
                <a id="logout" href="../logout.php">Déconnexion</a>
            </div>
        </form>
    </header>
    <div class="container">
        <?php
            $req = $db->prepare("SELECT * FROM profile$id_profile");
            $req->execute();

            echo('<a id="createProfile" href="AddProfile.php">Créer un profile</a> <br>');

            while ($profile = $req->fetch()) {
                $id = $profile['id'];

                if ($id != "") {
                    echo('
                        <div class="DeleteDiv">
                            <a id="deleteProfile" href="DelProfile.php">Supprimer un profile</a> 
                        </div>
                        <br>
                        <div class="ModifyDiv">
                            <a id="modifyProfile" href="ModProfile.php">Modifier un profile</a> 
                        </div>
                    ');
                }
            }
        }
        ?>
        
    </div>
    <footer>
        <p class="footer">&copy; Copyright 2022 – LemonFlix</p>
    </footer>
</body>
</html>

