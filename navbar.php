
    <script>
        //use a switch statement to see what the current page is (e.g. pairs.html - this will be pairs.php)
        document.addEventListener("DOMContentLoaded", function(){
            let currentPage = location.pathname.split("/").splice(-1)[0];
            let listElements = document.getElementsByTagName("li");
            
            let currentName = "";
            switch (currentPage) {
                case "index.php":
                    currentName = "home";
                    break;
                case "pairs.php":
                    currentName = "memory";
                    break;
                case "leaderboard.php":
                    currentName = "leaderboard";
                    break;
                case "registration.php":
                    currentName = "register";
                    break;
                default:
                    break;
            }
            for(let i=0;i<3;i++){
                if(listElements[i].getAttribute("name") === currentName){
                    listElements[i].children[0].classList.add('active');
                }else {
                    listElements[i].children[0].classList.remove('active');
                }
            }
        });
    </script>

<nav role="navigation" class="navbar navbar-expand navbar-dark " id="navbar">
    <ul class="navbar-nav me-auto ">
        <li name="home" class="nav-item">
            <a href="index.php" class="nav-link active">Home</a>
        </li>
    </ul>
    <ul class="navbar-nav ms-auto ">
        <li name='memory' class='nav-item'>
            <a href='pairs.php' class='nav-link'>Play Pairs</a>
        </li>
        <?php
            if(!empty($_SESSION['loggedIn']) && $_SESSION['loggedIn'] === true){
                echo   "<li name='leaderboard' class='nav-item'>
                            <a href='leaderboard.php' class='nav-link'>Leaderboard</a>
                        </li>";
            }else{
                echo    "<li name='register' class='nav-item'>
                            <a href='registration.php' class='nav-link'>Register</a>
                        </li>";
            }
            
            
        ?>
    </ul>
    <?php
        if(!empty($_SESSION['loggedIn']) && $_SESSION['loggedIn'] === true){
            $avatarArr = json_decode($_SESSION['avatar'], true);

            echo    sprintf("<div id='emojiDiv'>
                        <img src='./images/emoji assets/skin/%s.png' id='navbarEmoji' class='nav-item emoji'>
                        <img src='./images/emoji assets/eyes/%s.png' class='nav-item emoji'>
                        <img src='./images/emoji assets/mouth/%s.png' class='nav-item emoji'>
                    </div>", $avatarArr["colour"],$avatarArr["eyes"],$avatarArr["mouth"] );
            
        }
    ?>

    
</nav>

