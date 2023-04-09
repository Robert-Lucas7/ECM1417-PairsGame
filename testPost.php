<?php
    session_start();
    $post =json_decode(file_get_contents('php://input'));
    //echo $post->points; //UNSURE WHAT THIS ARROW OPERATOR IS
    $_SESSION["points"] = "$post->points";
    
?>