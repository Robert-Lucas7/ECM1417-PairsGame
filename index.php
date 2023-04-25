<!-- Landing page-->
<?php
session_start();
?>
<html>

<head>
    <link rel="stylesheet" href="css/general.css">
    <link rel="stylesheet" href="css/index.css">
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.6.3/jquery.min.js"></script>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/css/bootstrap.min.css" rel="stylesheet"
        integrity="sha384-EVSTQN3/azprG1Anm3QDgpJLIm9Nao0Yz1ztcQTwFspd3yD65VohhpuuCOmLASjC" crossorigin="anonymous">
    <title>Pairs</title>

    <script>
        document.addEventListener("DOMContentLoaded", function () {
            let playBtn = document.getElementById("playBtn");
            if (playBtn !== null) {
                playBtn.addEventListener("click", function () {
                    location.href = "pairs.php";
                });
            }
        });
    </script>


    <style>
        /* The flip card container - set the width and height to whatever you want. We have added the border property to demonstrate that the flip itself goes out of the box on hover (remove perspective if you don't want the 3D effect */
        .flip-card {
            width: 200px;
            height: 300px;
            border-radius: 20%;
        }

        /* This container is needed to position the front and back side */
        .flip-card-inner {
            position: relative;
            width: 100%;
            height: 100%;
            text-align: center;
            transition: transform 0.8s;
            transform-style: preserve-3d;
        }

        /* Do an horizontal flip when you move the mouse over the flip box container */
        
        .rotate{
            transform: rotateY(180deg);
        }
        /* Position the front and back side */
        .flip-card-front,
        .flip-card-back {
            position: absolute;
            width: 100%;
            height: 100%;
            -webkit-backface-visibility: hidden;
            /* Safari */
            backface-visibility: hidden;
        }

        .flip-card-front {
            background-color: white;
            border-radius: 20%;
        }

        /* Style the back side */
        .flip-card-back {

            background-color: blue;
            transform: rotateY(180deg);
            border-radius:20%;
        }
    </style>
    <script>
        function flipCard(event){
            let card = event.currentTarget;
            let inner = card.children[0];
            if(inner.classList.contains("rotate")){
                inner.classList.remove("rotate");
            } else{
                inner.classList.add("rotate");
            }
        }
    </script>
</head>

<body>
    <?php include("./navbar.php") ?>
    <!--<div id="navbarDiv"></div>-->
    <div id="main">
        <img src="./images/arcade.jpg" alt="Arcade" id="arcadeImage">
        <div class="center">
            <!-- Find out how to make this div to be the size of the viewport - the height of the navbar-->
            <?php
            if (isset($_SESSION['loggedIn']) && $_SESSION['loggedIn'] === true) {
                echo "<h1 id='heading'>Welcome to pairs!</h1> 
                                <button id='playBtn' class='btn btn-primary'>Click here to play</button>
                            </div>";
            } else {
                echo "<div class='d-flex flex-column center'>
                                <p>You're not using a registered session? <a href='registration.php'>Register now</a></p>
                            </div>";
            }
            ?>
            <!-- demo of card-->
            <!--<div class="" style="width:100px;height:200px;border-radius:20%;">
                <div class="flip-card-inner" style="position:relative;width:100%;height:100%;">
                    <div class="flip-card-front"style="position:absolute;">
                        <img src="./images/emoji assets/skin/green.png" style="width:100%; height:100%;position:absolute;inset:0;">
                        <img src="./images/emoji assets/eyes/closed.png" style="width:100%; height:100%;position:absolute;inset:0;">
                        <img src="./images/emoji assets/mouth/straight.png" style="width:100%; height:100%;position:absolute;inset:0;">
                    </div>
                    <div class="flip-card-back">
                    </div>
                </div>
            </div>-->
            <!-- END OF DEMO-->

            <table >
                <tr>
                    <td style="padding:50px;"> 
            <div class="flip-card" onclick="flipCard(event)">
                <div class="flip-card-inner">
                    <div class="flip-card-front">

                    </div>
                    <div class="flip-card-back" style="position:relative;height:100%;width:100%;">


                        <img src="./images/emoji assets/skin/green.png" alt="Avatar"
                            style="position:absolute;width:100%;height:100%;inset:0;">
                        <img src="./images/emoji assets/eyes/closed.png" alt="Avatar"
                            style="position:absolute;width:100%;height:100%;inset:0;">
                        <img src="./images/emoji assets/mouth/open.png" alt="Avatar"
                            style="position:absolute;width:100%;height:100%;inset:0;">
                    </div>

                </div>
            </div>
        </td>
        <td>
            <div class="flip-card" onclick="flipCard(event)">
                <div class="flip-card-inner">
                    <div class="flip-card-front">

                    </div>
                    <div class="flip-card-back" tyle="position:relative;height:100%;width:100%;">


                        <img src="./images/emoji assets/skin/green.png" alt="Avatar"
                            style="position:absolute;width:100%;height:100%;inset:0;">
                        <img src="./images/emoji assets/eyes/closed.png" alt="Avatar"
                            style="position:absolute;width:100%;height:100%;inset:0;">
                        <img src="./images/emoji assets/mouth/open.png" alt="Avatar"
                            style="position:absolute;width:100%;height:100%;inset:0;">
                    </div>

                </div>
            </div>
        </td>
        </tr>
        </table>
        </div>
</body>

</html>