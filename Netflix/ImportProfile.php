<?php
require('../src/connect.php');
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Netflix - Profile Page</title>
    <link rel="stylesheet" href="stylesImport.css">
    <link rel="icon" type="image/pngn" href="../img/favicon.png">
</head>
<body>
    <header>
        <form action="../index.php" id="logo">
            <input type="image" src="../img/LemonFlix.png" alt="Submit">
            <div id="logoutDiv">
                <?php
                    $Code = htmlspecialchars($_COOKIE['secretCode']);

                    $req = $db->prepare("SELECT * FROM user WHERE secret_code = ?");
                    $req->execute(array($Code));

                    while ($user = $req->fetch()) {
                        echo('<p id="email">'.$user["email"].'</p>');
                        $id_profile = $user['id_profile'];
                    }
                ?>
                <a id="logout" href="../logout.php">Déconnexion</a>
            </div>
        </form>
    </header>
    <h1>Importer un profile</h1>
    <div class="importDiv">
        <form action="ImportProfile.php" method="post" id="importForm">
                <div class="input">
                    <input type="text" name="import_code" id="codeInput" placeholder="Code : " required>
                    <input type="submit" value="Vérifier" id="submitImport">
                </div>
        </form>
    </div>
    <?php
        if(!empty($_POST['import_code'])) {
            echo("<span>-------------------------------------</span>");
            $codeImport = $_POST['import_code'];

            $req = $db->prepare("SELECT * FROM saved_profils WHERE code = ?");
            $req->execute(array($codeImport));

            while ($user = $req->fetch()) {
                $imported_pseudo = htmlspecialchars($user['pseudo']);
                $imported_url = htmlspecialchars($user['url']);
                $imported_watched = htmlspecialchars($user['watched']);

                setcookie('imported_pseudo', $imported_pseudo, time()+3600*24, '/', '', false, false);
                setcookie('imported_url', $imported_url, time()+3600*24, '/', '', false, false);
                setcookie('imported_watched', $imported_watched, time()+3600*24, '/', '', false, false);
                
                echo("<div class='import_info'><h2>$imported_pseudo</h2>");
                echo("<div class='div_img'><img src=".$imported_url." alt='profilePicture' id='pp'></div></div>");
                echo('
                <div class="confirmDiv">
                    <form action="ImportProfile.php" method="post" id="confirmImport">
                        <input type="submit" value="Confirmer" class="bottomBtn" name="confirm">
                    </form>
                    <form action="AddProfile.php" method="post" id="cancelImport">
                        <input type="submit" value="Annuler" class="bottomBtn" id="cancel">
                    </form>
                </div>
                ');

            }

        }
        if(!empty($_POST['confirm'])) {
            $imported_pseudo = htmlspecialchars($_COOKIE['imported_pseudo']);
            $imported_url = htmlspecialchars($_COOKIE['imported_url']);
            $imported_watched = htmlspecialchars($_COOKIE['imported_watched']);

            $req = $db->prepare("SELECT count(*) as numberPseudo FROM profile$id_profile WHERE pseudo = ?");
            $req->execute(array($imported_pseudo));

            while($email_verification = $req->fetch()){

                if($email_verification['numberPseudo'] != 0){

                    header('location: ImportProfile.php?error=1&message=Ce prénom est déjà utiliser.');
                    exit();

                }
            }

            if (!empty($imported_watched)) {
                $req = $db->prepare("INSERT INTO profile$id_profile(pseudo, url, watched, imported) VALUES(?,?,?,?)");
                $req->execute(array($imported_pseudo, $imported_url, $imported_watched, "true"));
            } else{
                $req = $db->prepare("INSERT INTO profile$id_profile(pseudo, url, imported) VALUES(?,?,?)");
                $req->execute(array($imported_pseudo, $imported_url, "true"));
            }


            setcookie('imported_pseudo', "", time()-3600, '/', '', false, false);
            setcookie('imported_url', "", time()-3600, '/', '', false, false);
            setcookie('imported_watched', "", time()-3600, '/', '', false, false);

            header('location: main.php');
            exit();
        }

        if(isset($_GET['error'])) {
            if(isset($_GET['message'])) {
                echo '<p class="alert">'.htmlspecialchars($_GET['message']).'</p>';
            }
        }
    ?>
</body>
</html>
