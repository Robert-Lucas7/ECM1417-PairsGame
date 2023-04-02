<html>

<head>
    <script>

        //Get the children of each li element in ul.
        //use a switch statement to see what the current page is (e.g. pairs.html - this will be pairs.php)
        //============ SEE IF THERE IS A MORE EFFICIENT WAY TO DO THIS ================
        document.addEventListener("DOMContentLoaded", function(){
            let currentPage = location.pathname.split("/").splice(-1)[0];
            let listElements = document.getElementsByTagName("li"); //can use the name of each element to retrieve the correct one, then the child's (anchor tag) classList will be modified (adding active).
            
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
                }else {//remove 'active' from classList
                    listElements[i].children[0].classList.remove('active');
                }
            }
        });
        
        
        //==================== GETTING DETAILS ABOUT THE EMOJI HERE (type of skin, eyes, mouth) ============================
        
    </script>
</head>
<!--Instead of two lists use a flexbox with 2 columns-->
<div role="navigation" class="navbar navbar-expand navbar-dark">
    <ul class="navbar-nav me-auto">
        <li name="home" class="nav-item">
            <a href="index.php" class="nav-link active">Home</a>
        </li>
        <?php
            if($_SESSION['loggedIn'] === true){
                echo    "<li name='memory' class='nav-item'>
                            <a href='pairs.php' class='nav-link'>Play Pairs</a>
                        </li>";
            } else{
                "<li name='memory' class='nav-item'>
                            <a href='pairs.php' class='nav-link disabled'>Play Pairs</a>
                        </li>";
            }
        ?>

        <!-- Only one of these options should show depending on if the user is already registered. Check here using php (server-side) to see if user is logged in-->
        <!-- For now v-->
    </ul>
    <ul class="navbar-nav ms-auto">
        <?php
            if($_SESSION['loggedIn'] === true){
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
        //NEED TO GET THE AVATAR DETAILS FROM PHP SESSION STORAGE
        if($_SESSION['loggedIn'] === true){//As the user is 'loggedIn' there will be json data stored in the php SESSION describing the avatar.
            $avatarArr = json_decode($_SESSION['avatar'], true);
            //print_r($avatarArr);
            
            //print_r(array_keys($avatarArr));
            echo    sprintf("<div id='emojiDiv'>
                        <img src='./images/emoji assets/skin/%s.png' id='navbarEmoji' class='nav-item emoji'>
                        <img src='./images/emoji assets/eyes/%s.png' class='nav-item emoji'>
                        <img src='./images/emoji assets/mouth/%s.png' class='nav-item emoji'>
                    </div>", $avatarArr["colour"],$avatarArr["eyes"],$avatarArr["mouth"] );
            
        }
        //echo "<script>console.log('" . "heelo"  . "');</script>";
    ?>

    
</div>

</html>