<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Modifier profile - LemonFlix</title>
    <link rel="stylesheet" href="styleEdit.css">
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
                    $pseudo = htmlspecialchars($_POST['user']);
                    setcookie('user', $pseudo, time()+3600*24, '/', '', false, false);

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

    <h1>Modifier le profile</h1>
    <div class="profile">
        <?php 
        
            $req = $db->prepare("SELECT * FROM profile$id_profile WHERE pseudo = ?");
            $req->execute(array($pseudo));

            while ($user = $req->fetch()) {
                echo("<img src=".$user['url']." alt='url' id='pp'>");    
            }
        }
        ?>
        <div class="formu">
            <form action="EditProfiles.php" method="post">
                <input type="text" name="pseudo" class="input" placeholder="<?php echo($pseudo); ?>" required>
                <br>
                <input type="text" name="url" class="input" placeholder="Photo de profile" required>
                <div class="webhost">
                    <a href="https://online-hoster.000webhostapp.com/" class="website">(Site d'hébergement en ligne)</a>   
                </div>
                
                <input type="submit" value="Enregister" class="submit" id="save">
            </form>
        </div>
    </div>
    <form action="ExportProfile.php" id="exportForm" method='post'>
        <input type="submit" value="Exporter le profile" id="exportBtn" name="export">
    </form>
    
</body>
</html>

<?php
if(!empty($_POST['pseudo']) && (!empty($_POST['url']))) {
    require('../src/connect.php');

    $pseudo_form      = htmlspecialchars($_POST['pseudo']);
    $pseudo           = htmlspecialchars($_COOKIE['user']);
    $url              = htmlspecialchars($_POST['url']);
    $secret_code      = htmlspecialchars($_COOKIE['secretCode']);


    $req = $db->prepare("SELECT * FROM user WHERE secret_code = ?");
    $req->execute(array($secret_code));

    while($id_profile = $req->fetch()) {

        $id_profile = $id_profile['id_profile'];

        $req = $db->prepare("SELECT * FROM profile$id_profile WHERE pseudo = ?");
        $req->execute(array($pseudo));

        while($user = $req->fetch()) {
            $id = $user['id'];

            $req = $db->prepare("UPDATE profile$id_profile SET pseudo = ?, url = ? WHERE id = ?");
            $req->execute(array($pseudo_form, $url, $id));
            
            header('location: ModProfile.php');
            setcookie('user', '', time()-3600, '/', '', false, false);
            exit();
        }
    }
}
?>