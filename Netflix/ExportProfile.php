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
    <title>Exporter profile - LemonFlix</title>
    <link rel="stylesheet" href="stylesExportProfil.css">
    <link rel="icon" type="image/pngn" href="../img/favicon.png">
</head>
<body>
    <header>
        <form action="../index.php" id="logo">
            <input type="image" src="../img/LemonFlix.png" alt="Submit">
            <?php
                    require('../src/connect.php');
                    $Code = htmlspecialchars($_COOKIE['secretCode']);
                    $pseudo = htmlspecialchars($_COOKIE['user']);
                    
                    $req = $db->prepare("SELECT * FROM user WHERE secret_code = ?");
                    $req->execute(array($Code));
                    
                    while ($user = $req->fetch()) {
                        echo('<p id="email">'.$user["email"].'</p>');
                        $id_profile = $user['id_profile'];
                        
                        if(!empty($_POST['export'])) {
                            $req = $db->prepare("SELECT * FROM profile$id_profile WHERE pseudo = ?");
                            $req->execute(array($pseudo));
                            ?> 
            <div id="logoutDiv">
                <a id="logout" href="../logout.php">Déconnexion</a>
            </div>
        </form>
    </header>

    <h1>Exporter le profile : <?php echo($pseudo);?></h1>
    
<?php
while ($user = $req->fetch()) {
$url = $user['url'];
$watched = $user['watched'];
$id_user = $user['id'];
$imported = $user['imported'];

if ($imported == "true") {
    echo('<h2 class="cannotExport">Le profile "'.$pseudo.'" a été importé, il ne peut donc être exporter à nouveau</h2>');
}

$req = $db->prepare("SELECT * FROM saved_profils WHERE id_user = ?");
$req->execute(array($id_user));

while ($saved_user = $req->fetch()) {
    if ($saved_user['id_user'] == $id_user) {
        $savePro_code = $saved_user['code'];

        $req = $db->prepare("UPDATE saved_profils SET pseudo = ?, url = ?, watched = ? WHERE id_user = ?");
        $req->execute(array($pseudo, $url, $watched, $id_user));

        echo("<h2>Votre Code publique :</h2>");
        echo("<div class='codeDiv'><span>$savePro_code</span></div>");
    }
    }}}}
}
?>
</body>
</html>
