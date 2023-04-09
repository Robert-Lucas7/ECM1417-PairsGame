<?php
    session_start();
    
    if(!empty($_POST['username']) && !empty($_POST['avatarColour']) && !empty($_POST['avatarMouth']) && !empty($_POST['avatarEyes'])){
        //Validate here (server-side) - if valid redirect to 'play pairs' page
        //Username validation
        $username = $_POST['username'];
        $validUsername = true;
        if(strlen($username) > 30){
            $validUsername = false;
        }
        if(preg_match("/[!@#%&\*\(\)\+={}\[\]\-;:\"'<>\?\/]+/", $username) === 1){
            $validUsername = false;
        }
        //avatarColour and backgroundColour validation.
        $validAvatarColourSelected = false;
        $validAvatarEyesSelected = false;
        $validAvatarMouthSelected = false;
        $validColours = array("green","red","yellow");
        
       
        $validEyes = array("closed", "laughing", "long", "normal", "rolling", "winking");
        $validMouth = array("open","sad", "smiling", "straight", "surprise","teeth");
        if(in_array($_POST['avatarColour'], $validColours)){
            $validAvatarColourSelected = true;
        }
        if(in_array($_POST['avatarEyes'], $validEyes)){
            $validAvatarEyesSelected = true;
        }
        if(in_array($_POST['avatarMouth'], $validMouth)){
            $validAvatarMouthSelected = true;
        }
        if($validUsername && $validAvatarEyesSelected && $validAvatarColourSelected && $validAvatarMouthSelected){
            //Set session variables - 'loggedIn' and 'username' - if session storage can be modified client-side then a hash of the username and salt is needed??
            
            $_SESSION['loggedIn'] = true;
            $_SESSION['username'] = $username;
            $_SESSION['avatar'] = json_encode(array("colour"=>$_POST["avatarColour"], "eyes"=>$_POST["avatarEyes"], "mouth"=>$_POST["avatarMouth"]));
            //setcookie("avatar", sprintf('{"colour":"%s","eyes":"%s","mouth":"%s"}',$_POST["avatarColour"],$_POST["avatarEyes"],$_POST["avatarMouth"] ));//$_POST["avatarColour"], $_POST["avatarEyes"], $_POST["avatarMouth"])));
            //echo print_r($_SESSION);
            header('Location: index.php');
        }
    }

?>
<html>
    <head>
        <link rel="stylesheet" href="css/registration.css">
        <link rel="stylesheet" href="css/general.css">
        <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.6.3/jquery.min.js"></script>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-EVSTQN3/azprG1Anm3QDgpJLIm9Nao0Yz1ztcQTwFspd3yD65VohhpuuCOmLASjC" crossorigin="anonymous">
        <script>
            
            document.addEventListener("DOMContentLoaded", function(){
                document.getElementById("avatarColour").addEventListener("change", function(event){
                    //console.log(event.currentTarget.value);
                    //Change base image here.
                    document.getElementById("avatarColourImg").src = `./images/emoji assets/skin/${event.currentTarget.value}.png`
                });
                document.getElementById("avatarMouth").addEventListener("change", function(event){
                    console.log(event.currentTarget.value);
                    document.getElementById("avatarMouthImg").src = `./images/emoji assets/mouth/${event.currentTarget.value}.png`
                });
                document.getElementById("avatarEyes").addEventListener("change", function(event){
                    console.log(event.currentTarget.value);
                    document.getElementById("avatarEyesImg").src = `./images/emoji assets/eyes/${event.currentTarget.value}.png`
                });
                document.getElementById("usernameInput").addEventListener("input",ValidateUsername);
            });
            function ValidateUsername(){
                var invalidCharactersText = document.getElementById("validateInvalidCharactersText");
                var tooManyCharactersText = document.getElementById("validateTooManyCharacters");
                var username = document.getElementById("usernameInput").value;
                const specialCharacterRegex = /[!@#%&\*\(\)\+={}\[\]\-;:"'<>\?\/]+/; //This will match the first special character in the string (if present)
                var validUsername = true;
            
                if(username.length > 30){
                    tooManyCharactersText.style.setProperty('display', 'block');
                    validUsername = false;
                }
                else{
                    tooManyCharactersText.style.setProperty('display', 'none');
                }
                if(username.match(specialCharacterRegex)){
                    invalidCharactersText.style.setProperty('display', 'block');
                    validUsername = false;
                }
                else{
                    invalidCharactersText.style.setProperty('display', 'none');
                }
                return validUsername;
            }
            
        </script>
    </head>
    <body>
        
        <?php include("navbar.php")?>
        <div id="main">
            <!--<img src="./images/arcade.jpg" alt="Arcade" id="arcadeImage">-->
            <div id="headingAndForm">
                <h1>Register here</h1>
                <form id="registrationForm" onsubmit="return ValidateUsername()" method="post" action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]);?>"><!--the action submits the form data to the same page, htmlspecialchars() prevents XSS attacks, $_SERVER["PHP_SELF"] gets the name of the currently executing script.-->
                    <label for="username">Username: </label>
                    <input type="text" name="username" placeholder="Enter username here..." id="usernameInput" class="form-control"><br>
                    
                    <ul class="usernameValidation">
                        <li id="validateInvalidCharactersText">Invalid characters are in the username</li>
                        <li id="validateTooManyCharacters">Username is too long</li>
                    </ul>
                    
                    
                    <label for="avatarColour">Avatar colour: </label>
                    <select name="avatarColour" id="avatarColour" class="form-select">
                        <option value="green">Green</option>
                        <option value="red">Red</option>
                        <option value="yellow">Yellow</option>
                    </select><br>
                    


                    <label for="avatarEyes">Avatar Eyes: </label>
                    <select name="avatarEyes" id="avatarEyes" class="form-select">
                        <option value="closed">Closed</option>
                        <option value="laughing">Laughing</option>
                        <option value="long">Long</option>
                        <option value="normal">Normal</option>
                        <option value="rolling">Rolling</option>
                        <option value="winking">Winking</option>
                    </select><br>

                    <label for="avatarMouth">Avatar Mouth: </label>
                    <select name="avatarMouth" id="avatarMouth" class="form-select">
                        <option value="open">Open</option>
                        <option value="sad">Sad</option>
                        <option value="smiling">Smiling</option>
                        <option value="straight">Straight</option>
                        <option value="surprise">Surprise</option>
                        <option value="teeth">Teeth</option>
                    </select><br>

                    
                    <input type="submit" value="Submit" class="btn btn-success">
                    <input type="reset" value="Reset" class="btn btn-danger">
                </form>
                
                <img class="avatarImages" id="avatarColourImg" src="./images/emoji assets/skin/green.png">
                <img class="avatarImages"id="avatarMouthImg" src="./images/emoji assets/mouth/open.png">
                <img class="avatarImages" id="avatarEyesImg" src="./images/emoji assets/eyes/closed.png">
            </div>
        </div>
    </body>
</html>