<?php
    session_start();
    if($_SESSION['loggedIn'] === true){
        echo "<button id='submitScoreButton' class='btn btn-success'>
                Submit Score
            </button>";
    }
?>