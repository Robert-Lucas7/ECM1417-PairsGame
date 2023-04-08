<?php
    session_start();
    
    if(empty($_SESSION['loggedIn']) || $_SESSION['loggedIn'] === false){//if the user is not logged in, redirect to 'index.php'. Unsure if the second condition is relevant as the user shouldn't have access to the session as it is stored server-side. Better option would be to disable the button not the link.
        header('Location: index.php');
    }
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
        <div id="gameAreaDiv" class="center">
            
            <table id="cardTable">

            </table>
            <div id="buttonDiv">
                <button id="playPairsbtn" class="btn btn-primary buttons">Play Pairs</button>
            </div>
            
        </div>
    </div>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/js/bootstrap.bundle.min.js" integrity="sha384-MrcW6ZMFYlzcLA8Nl+NtUVF0sA7MsXsP1UyJoMp4YLEuNSfAP+JcXn/tWtIaxVXM" crossorigin="anonymous"></script>

</body>

</html>