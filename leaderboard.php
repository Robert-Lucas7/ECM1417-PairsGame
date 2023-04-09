<?php
    session_start();

    //Update the session variables for the leaderboard on the post request here.
    //SHOULD PROBABLY CHECK THE ORIGIN OF THE REQUEST
   
    if($_SERVER['REQUEST_METHOD'] === "POST" && basename($_SERVER['HTTP_REFERER']) === "pairs.php"){
        $post =json_decode(file_get_contents('php://input'), true);//true flag is to indicate if an associative array is returned or straight json.
        if(!empty($post)){
            //Arrays are always ordered - as they are an 'ordered map'
            /*Leaderboard structure is: - STRUCTURE IS CHANGING TO SEQUENTIAL INTEGER INDEXES as associative arrays cannot have duplicate keys (and points may be the same)
            [1stPoint] => array(
                "username" => "username1",
                "time" => "time1"
            ),
            [2ndPoint] => etc.
            */
            //If there isn't currently any leaderboard entries then create a structure for it...
            if(!isset($_SESSION['leaderboard'])){ //LOOK INTO isset() vs empty()
                $_SESSION['leaderboard'] = array(array(
                    "points" => $post["points"],
                    "username" => $_SESSION['username'],
                    "time" => $post["time"]
                ));
            } else{// If the leaderboard already exists, then find the new scores position in the array and insert it there.
                $newEntry = array(array(
                    "points" => $post["points"],
                    "username" => $_SESSION['username'],
                    "time" => $post["time"]
                ));
                $indexToInsert = 0;
                foreach($_SESSION['leaderboard'] as $entry){
                    if($entry["points"] >= $newEntry[0]["points"]){
                        $indexToInsert++;
                    }
                    else{
                        break;
                    }
                }
                array_splice($_SESSION['leaderboard'], $indexToInsert, 0, $newEntry);
            }
        }
    }
    
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
            <!-- MAKE THE ACTUAL LEADERBOARD HERE-->
            <table id="leaderboardTable">
                <tr class='leaderboardHeadings'>
                    <th>Position</th>
                    <th>Score</th>
                    <th>Time</th>
                    <th>Username</th>
                </tr>
            
            
                <?php  //needs to be php as the leadboard is stored server-side.
                    
                    foreach($_SESSION['leaderboard'] as $position => $entry){
                        $actualPosition = $position +1;
                        echo    "<tr>
                                    <td>{$actualPosition}</td>
                                    <td>{$entry['points']}</td>
                                    <td>{$entry['time']}</td>
                                    <td>{$entry['username']}</td>
                                </tr>";
                    }
                    
                ?>
            </table>
        </div>
    </body>
</html>