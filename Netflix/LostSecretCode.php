<?php

if(!empty($_POST['email']) && !empty($_POST['password'])){
    require('../src/connect.php');

    $email 		= htmlspecialchars($_POST['email']);
	$password 	= htmlspecialchars($_POST['password']);

    $password = "aq1".sha1($password."123")."25";

    if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
		header('location: LostSecretCode.php?error=1&message=Votre adresse email est invalide.');
		exit();
	}

    $req = $db->prepare("SELECT * FROM user WHERE email = ?");
	$req->execute(array($email));

    while($user = $req->fetch()){
        if($password == $user['password']){
            header('location: LostSecretCode.php?success=1&message='.$user['secret_code']);
            echo ($user['secret_code']);
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
    <link rel="stylesheet" href="../styleLostCode.css">
    <title>Netflix - SecretCode Page</title>
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
        <form method="post" action="LostSecretCode.php">
            <h1>Code Secret Oublié</h1>
            <td>
                <p>Email</p>
                <input type="email" name="email" class="form">
            </td>
            <td>
                <p>Mot de Passe</p>
                <input type="password" name="password" class="form">
            </td>
            <br>
            <input type="submit" value="Envoyer" id="valBtn">
            <br>
            <td>
                <p id="TextCode">Votre Code Secret :</p>
                <?php 

                    if(isset($_GET['success'])) {
                        if(isset($_GET['message'])) {
                            echo ('<p class="code">'.htmlspecialchars($_GET['message']).'</p>');
                        }
                    }

                ?>
            </td>
        </form>
    </div>
    <footer>
        <p class="footer">&copy; Copyright 2022 – LemonFlix</p>
    </footer>
</body>
</html>
