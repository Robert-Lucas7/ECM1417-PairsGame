<?php
    session_start();

    //Update the session variables for the leaderboard on the post request here.
    
    
?>
<html>
    <head>
        <link rel="stylesheet" href="css/leaderboard.css">
        <link rel="stylesheet" href="css/general.css">
        <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.6.3/jquery.min.js"></script>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-EVSTQN3/azprG1Anm3QDgpJLIm9Nao0Yz1ztcQTwFspd3yD65VohhpuuCOmLASjC" crossorigin="anonymous">
    </head>
    <body>
        
        <?php include("navbar.php")?>
        
        <div id="main">
            <!--<img src="./images/arcade.jpg" alt="Arcade" id="arcadeImage">-->
            <h1>Leaderboard</h1>
            <?php
                echo var_dump($_SESSION);
            ?>
        </div>
    </body>
</html>