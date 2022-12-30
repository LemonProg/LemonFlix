<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Supprimer profile - LemonFlix</title>
    <link rel="stylesheet" href="styleDelProfils.css">
    <link rel="icon" type="image/pngn" href="../img/favicon.png">
</head>
<body>
<?php
require('../src/connect.php');

if(!empty($_POST['username'])) {
    $username      = htmlspecialchars($_POST['username']);
    $secret_code = htmlspecialchars($_COOKIE['secretCode']);

    echo("<h1>$username</h1>");

    $req = $db->prepare("SELECT * FROM user WHERE secret_code = ?");
    $req->execute(array($secret_code));


    while($user = $req->fetch()) {
        $userProfile = $user['id_profile'];

        $req = $db->prepare("SELECT * FROM profile".$userProfile);
        $req->execute();

        while($user = $req->fetch()) {
            
            if ($username == $user['pseudo']) {
                $req = $db->prepare("DELETE FROM profile$userProfile WHERE pseudo = ?");
                $req->execute(array($username));

                header('location: main.php');
                exit();
            }else {
                header('location: DelProfile.php?error=1');
            }
        }

        
        }
        
}
    
?>
</body>
</html>

