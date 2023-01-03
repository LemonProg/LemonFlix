<?php
if(!empty($_POST['name'])) {
    $search = $_POST['name'];
}

$db = new PDO('mysql:host=localhost;dbname=lemonflix;charset=utf8','root', '');
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Barre de recherche</title>
    <link rel="stylesheet" href="style.css">
</head>
<body>
    <h1>Recherche : </h1>
    <?php

    function adaptCut($animeName) {
        if (strlen($animeName) <= 6) {
            return -3;
        } 
        if (strlen($animeName) <= 9) {
            return -6;
        } 
        if (strlen($animeName) <= 14) {
            return -7;
        }
        if (strlen($animeName) <= 16) {
            return -11;
        }
        if (strlen($animeName) > 16) {
            return -13;
        }
    }

    $req = $db->prepare("SELECT * FROM streaming");
    $req->execute();

    while($user = $req->fetch()){
        $animes = $user['name'];
    
        $animes = strtolower($animes);
        $search = strtolower($search);

        $animes_cut = substr($animes,0, adaptCut($animes));

        if(str_starts_with($search, $animes_cut)) {
            echo("<h1>Résulat : $animes</h1>");
        }
    }
    ?>

    <form action="index.php" method="post" id="form">
        <input type="search" name="name" id="search">
    </form>

    <script src="script.js"></script>
</body>
</html>