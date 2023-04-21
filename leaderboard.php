<?php
    session_start();
    //Update the session variables for the leaderboard on the post request here.
    //SHOULD PROBABLY CHECK THE ORIGIN OF THE REQUEST
   
    if($_SERVER['REQUEST_METHOD'] === "POST" && basename($_SERVER['HTTP_REFERER']) === "pairs.php"){
        $post =json_decode(file_get_contents('php://input'), true);//true flag is to indicate if an associative array is returned or straight json.
        if(!empty($post)){
            
            // ==================================================================  JSON FILE IMPLEMENTATION  ==============================================================================
            //Get contents of the file
            /*
            Should be structured like:   ----position is implicit (the index of the entry)
            {
                position1 : {
                    points: numPoints,
                    username : username1,
                    time: timeToComplete
                },
                position2 :{
                    ...
                }
            }
            */
            $newEntry = array(array(
                "points" => $post["points"],
                "username" => $_SESSION['username'],
                "time" => $post["time"]
            ));
            
            $leaderboardData = json_decode(file_get_contents('./data/leaderboard.json'), true);
            //Insert the new entry into the correct position (so it remains ordered)
            $positionToInsert = 0;
            if(!empty($leaderboardData)){
                foreach($leaderboardData as $entry){
                    if($entry['points'] >= $newEntry[0]['points']){
                        $positionToInsert++;
                    }
                    else{
                        break;
                    }
                }
            }
            if(empty($leaderboardData)){
                $leaderboardData = $newEntry;
            }
            else{
                array_splice($leaderboardData, $positionToInsert, 0, $newEntry);
            }
            
            //Save the new json to the file
            $fp = fopen('./data/leaderboard.json', 'w');
            fwrite($fp, json_encode($leaderboardData,JSON_PRETTY_PRINT));
            fclose($fp);

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
        
        <img src="./images/arcade.jpg" alt="Arcade" id="arcadeImage">
        <div id="main">
        

            
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
                    $leaderboardData = json_decode(file_get_contents('./data/leaderboard.json'), true);
                    if(!empty($leaderboardData)){
                        foreach($leaderboardData as $position => $entry){
                            $actualPosition = $position + 1;
                            echo    "<tr>
                                    <td>{$actualPosition}</td>
                                    <td>{$entry['points']}</td>
                                    <td>{$entry['time']}</td>
                                    <td>{$entry['username']}</td>
                                </tr>";
                        }
                    }
                    
                ?>
            </table>
        </div>
    </body>
</html>