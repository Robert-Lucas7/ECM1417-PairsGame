<!-- Landing page-->
<?php
    session_start();
?>
<html>
    <head>
        <link rel="stylesheet" href="css/general.css">
        <link rel="stylesheet" href="css/index.css">
        <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.6.3/jquery.min.js"></script>
        <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-EVSTQN3/azprG1Anm3QDgpJLIm9Nao0Yz1ztcQTwFspd3yD65VohhpuuCOmLASjC" crossorigin="anonymous">
        <title>Pairs</title>

        <script>
            document.addEventListener("DOMContentLoaded", function(){
                let playBtn = document.getElementById("playBtn");
                if(playBtn !== null){
                    playBtn.addEventListener("click", function(){
                        location.href = "pairs.php";
                    });
                }
            });
        </script>
    </head>
    <body>
        <?php include("./navbar.php")?>
        <!--<div id="navbarDiv"></div>-->
        <div id="main">
            <img src="./images/arcade.jpg" alt="Arcade" id="arcadeImage">   
            <div class="center"> <!-- Find out how to make this div to be the size of the viewport - the height of the navbar-->
            <?php
                if(isset($_SESSION['loggedIn']) && $_SESSION['loggedIn'] === true){
                    echo    "<h1 id='heading'>Welcome to pairs!</h1> 
                                <button id='playBtn' class='btn btn-primary'>Click here to play</button>
                            </div>";
                } else{
                    echo    "<div class='d-flex flex-column center'>
                                <p>You're not using a registered session? <a href='registration.php'>Register now</a></p>
                            </div>";
                }
            ?>    
            
        </div>
    </body>
</html>
