<?php
session_start();
if (!empty($_POST['username']) && !empty($_POST['avatarColour']) && !empty($_POST['avatarMouth']) && !empty($_POST['avatarEyes'])) {
    //Validate here (server-side) - if valid redirect to 'play pairs' page
    //Username validation
    $username = $_POST['username'];
    $validUsername = true;
    if (strlen($username) > 30) {
        $validUsername = false;
    }
    if (preg_match("/[!@#%&\*\(\)\+={}\[\]\-;:\"'<>\?\/\$£\^]+/", $username) === 1) {
        $validUsername = false;
    } 
    //avatarColour and backgroundColour validation.
    $validAvatarColourSelected = false;
    $validAvatarEyesSelected = false;
    $validAvatarMouthSelected = false;
    $validColours = array("green", "red", "yellow");


    $validEyes = array("closed", "laughing", "long", "normal", "rolling", "winking");
    $validMouth = array("open", "sad", "smiling", "straight", "surprise", "teeth");
    if (in_array($_POST['avatarColour'], $validColours)) {
        $validAvatarColourSelected = true;
    }
    if (in_array($_POST['avatarEyes'], $validEyes)) {
        $validAvatarEyesSelected = true;
    }
    if (in_array($_POST['avatarMouth'], $validMouth)) {
        $validAvatarMouthSelected = true;
    }
    if ($validUsername && $validAvatarEyesSelected && $validAvatarColourSelected && $validAvatarMouthSelected) {
        //Set session variables - 'loggedIn' and 'username' - if session storage can be modified client-side then a hash of the username and salt is needed??

        $_SESSION['loggedIn'] = true;
        $_SESSION['username'] = $username;
        $_SESSION['avatar'] = json_encode(array("colour" => $_POST["avatarColour"], "eyes" => $_POST["avatarEyes"], "mouth" => $_POST["avatarMouth"]));
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
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/css/bootstrap.min.css" rel="stylesheet"
        integrity="sha384-EVSTQN3/azprG1Anm3QDgpJLIm9Nao0Yz1ztcQTwFspd3yD65VohhpuuCOmLASjC" crossorigin="anonymous">
    <script>

        document.addEventListener("DOMContentLoaded", function () {
            let avatarColourRadios = document.getElementsByName("avatarColour");
            for (let i = 0; i < avatarColourRadios.length; i++) {
                avatarColourRadios[i].addEventListener("change", function () {
                    document.getElementById("avatarColourImg").src = `./images/emoji assets/skin/${avatarColourRadios[i].value}.png`;
                });
            }
            let avatarEyesRadios = document.getElementsByName("avatarEyes");
            for (let i = 0; i < avatarEyesRadios.length; i++) {
                avatarEyesRadios[i].addEventListener("change", function () {
                    document.getElementById("avatarEyesImg").src = `./images/emoji assets/eyes/${avatarEyesRadios[i].value}.png`;
                });
            }
            let avatarMouthsRadios = document.getElementsByName("avatarMouth");
            for (let i = 0; i < avatarMouthsRadios.length; i++) {
                avatarMouthsRadios[i].addEventListener("change", function () {
                    document.getElementById("avatarMouthImg").src = `./images/emoji assets/mouth/${avatarMouthsRadios[i].value}.png`;
                });
            }
            document.getElementById("usernameInput").addEventListener("input", ValidateUsername);
        });
        function ValidateUsername() {
            var invalidCharactersText = document.getElementById("validateInvalidCharactersText");
            var tooManyCharactersText = document.getElementById("validateTooManyCharacters");
            var usernameInput = document.getElementById("usernameInput");
            const specialCharacterRegex = /[!@#%&\*\(\)\+={}\[\]\-;:"'<>\?\/£$^]+/; //This will match the first special character in the string (if present)
            var validUsername = true;

            if (usernameInput.value.length === 0 || usernameInput.value.length > 30 || usernameInput.value.match(specialCharacterRegex)) {
                if(usernameInput.classList.contains("is-valid")){
                    usernameInput.classList.remove("is-valid");
                    usernameInput.classList.add("is-invalid");
                }
                validUsername = false;
            }
            else {
                if(usernameInput.classList.contains("is-invalid")){
                    usernameInput.classList.remove("is-invalid");
                    usernameInput.classList.add("is-valid");
                }
            }
           
            return validUsername;
        }

    </script>
</head>

<body>
    <!-- user profile information should be stored in cookies-->
    <?php include("navbar.php") ?>
    <div id="main">
        <img src="./images/arcade.jpg" alt="Arcade" id="arcadeImage">
        <div class="container-fluid">
            <div class="row">
                <h1>Register here</h1>
            </div>
            <div class="row">
                <div class="col--7 col-md"><!--at small breakpoint the preview is stacked.-->

                    <form id="registrationForm" onsubmit="return ValidateUsername()" method="post"
                        action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>" class="needs-validation">
                        <!--the action submits the form data to the same page, htmlspecialchars() prevents XSS attacks, $_SERVER["PHP_SELF"] gets the name of the currently executing script.-->

                        <div class="form-group row">
                            <div class="col-md">
                                <label for="username" class="col-2 col-form-label">Username: </label>
                                <input name="username" type="text" placeholder="Enter username here..."
                                    id="usernameInput" class="form-control is-valid" required>
                                <div id="invalidCharactersFeedback" class="invalid-feedback">
                                    Invalid characters are in the username and/or the username is too long.
                                </div>
                            </div>
                        </div>

                        <ul class="usernameValidation">
                            <li id="validateInvalidCharactersText">Invalid characters are in the username</li>
                            <li id="validateTooManyCharacters">Username is too long</li>
                        </ul>
                        <div class="row">

                            <label>Avatar colour</label>

                        </div>
                        <div class="form-group row justify-content-center">


                            <div class="col-2">
                                <label>
                                    <input type="radio" name="avatarColour" value="green" checked>
                                    <img src="./images/emoji assets/skin/green.png">

                                </label>
                            </div>
                            <div class="col-2">
                                <label>
                                    <input type="radio" name="avatarColour" value="yellow">
                                    <img class="col" src="./images/emoji assets/skin/yellow.png">
                                </label>
                            </div>
                            <div class="col-2">
                                <label>
                                    <input type="radio" name="avatarColour" value="red">
                                    <img src="./images/emoji assets/skin/red.png">
                                </label>
                            </div>


                        </div>
                        <div class="row">
                            <label>Avatar Eyes</label>
                        </div>
                        <div class="form-group row justify-content-center">
                            <div class="col">
                                <label>

                                    <input type="radio" name="avatarEyes" value="closed" checked>
                                    <img src="./images/emoji assets/eyes/closed.png" class="eyesAndMouth">


                                </label>
                            </div>
                            <div class="col">
                                <label>
                                    <input type="radio" name="avatarEyes" value="laughing">
                                    <img src="./images/emoji assets/eyes/laughing.png" class="eyesAndMouth">

                                </label>
                            </div>
                            <div class="col">
                                <label>
                                    <input type="radio" name="avatarEyes" value="long">
                                    <img src="./images/emoji assets/eyes/long.png" class="eyesAndMouth">

                                </label>
                            </div>

                            <div class="col">
                                <label>
                                    <input type="radio" name="avatarEyes" value="normal">
                                    <img src="./images/emoji assets/eyes/normal.png" class="eyesAndMouth">

                                </label>
                            </div>
                            <div class="col">
                                <label>
                                    <input type="radio" name="avatarEyes" value="rolling">
                                    <img src="./images/emoji assets/eyes/rolling.png" class="eyesAndMouth">

                                </label>
                            </div>
                            <div class="col">
                                <label>
                                    <input type="radio" name="avatarEyes" value="winking">
                                    <img src="./images/emoji assets/eyes/winking.png" class="eyesAndMouth">

                                </label>
                            </div>

                        </div>
                        <div class="row">

                            <label>Avatar Mouths</label>

                        </div>
                        <div class="form-group row justify-content-center">
                            <div class="col">
                                <label>
                                    <input type="radio" name="avatarMouth" value="open" checked>
                                    <img src="./images/emoji assets/mouth/open.png" class="eyesAndMouth">
                                </label>
                            </div>
                            <div class="col">
                                <label>
                                    <input type="radio" name="avatarMouth" value="sad">
                                    <img src="./images/emoji assets/mouth/sad.png" class="eyesAndMouth">
                                </label>
                            </div>
                            <div class="col">
                                <label>
                                    <input type="radio" name="avatarMouth" value="smiling">
                                    <img src="./images/emoji assets/mouth/smiling.png" class="eyesAndMouth">
                                    <img>
                                </label>
                            </div>
                            <div class="col">
                                <label>
                                    <input type="radio" name="avatarMouth" value="straight">
                                    <img src="./images/emoji assets/mouth/straight.png" class="eyesAndMouth">
                                </label>
                            </div>
                            <div class="col">
                                <label>
                                    <input type="radio" name="avatarMouth" value="surprise">
                                    <img src="./images/emoji assets/mouth/surprise.png" class="eyesAndMouth">
                                </label>
                            </div>
                            <div class="col">
                                <label>
                                    <input type="radio" name="avatarMouth" value="teeth">
                                    <img src="./images/emoji assets/mouth/teeth.png" class="eyesAndMouth">
                                </label>
                            </div>


                        </div>
                        <div class="row" style="padding-top:1em;">
                            <div class="col text-end">
                                <input type="submit" value="Submit" class="btn btn-success">
                            </div>
                            <div class="col">
                                <input type="reset" value="Reset" class="btn btn-danger">
                            </div>
                        </div>
                    </form>


                </div>

                <div class="col" id="avatarPreview" style="padding:1;">
                    <!--padding is 1 as when it is 0 nothing is shown as the column has no predefined height.-->
                    <img class="avatarImages" id="avatarColourImg" src="./images/emoji assets/skin/green.png"
                        style="position:relative;display:block;">
                    <img class="avatarImages" id="avatarMouthImg" src="./images/emoji assets/mouth/open.png">
                    <img class="avatarImages" id="avatarEyesImg" src="./images/emoji assets/eyes/closed.png">
                </div>
            </div>
        </div>
    </div>
</body>

</html>