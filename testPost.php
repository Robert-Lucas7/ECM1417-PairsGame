<?php
    session_start(); //start session so the session variables are accessible.
    $post =json_decode(file_get_contents('php://input'), true);//true flag is to indicate if an associative array is returned or straight json.
    //echo $post->points; //UNSURE WHAT THIS ARROW OPERATOR IS
    $string = "";
    
    echo $post["points"];
    $_SESSION["pointsss"] = $post->points;
    
?>