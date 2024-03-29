<?php
    session_start();
?>
<html>

<head>
    <link rel="stylesheet" href="css/pairs.css">
    <link rel="stylesheet" href="css/general.css">
    <script src="./scripts/pairs.js"></script>
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.6.3/jquery.min.js"></script>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-EVSTQN3/azprG1Anm3QDgpJLIm9Nao0Yz1ztcQTwFspd3yD65VohhpuuCOmLASjC" crossorigin="anonymous">

    
</head>

<body>
    <?php include("navbar.php")?>
    <div id="main">
        
        <img src="./images/arcade.jpg" alt="Arcade" id="arcadeImage">
        
        <div id="gameAreaDiv">
            <div id="centeredContent">
                <button id="playPairsbtn" class="btn">Start the game</button>
            </div>
            
        </div>
    </div>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/js/bootstrap.bundle.min.js" integrity="sha384-MrcW6ZMFYlzcLA8Nl+NtUVF0sA7MsXsP1UyJoMp4YLEuNSfAP+JcXn/tWtIaxVXM" crossorigin="anonymous"></script>

</body>

</html>