<?php 

if (isset($_COOKIE['auth']) && !isset($_SESSION['connect'])) {
    $secret = htmlspecialchars($_COOKIE['auth']);

    require('src/connect.php');

    $req = $db->prepare("SELECT count(*) as numberAccount FROM user WHERE secret = ?");
    $req->execute(array($secret));

    while($user = $req->fetch()) {
        if($user['numberAccount'] == 1) {
           $reqUser = $db->prepare("SELECT * FROM users WHERE secret = ?");
           $reqUser->execute(array($secret)); 

           while($userAccount = $req->fetch()) {
                $_SESSION['connect'] = 1;
			    $_SESSION['email']   = $userAccount['email'];
           }
        }
    }
}

?>