<?php
    session_start();
   
   function findPositionToInsertEntry($newEntry, $currentLeaderboardData){
        $positionToInsert = 0;
        if(!empty($currentLeaderboardData)){
            foreach($currentLeaderboardData as $entry){
                if($entry['points'] >= $newEntry[0]['points']){
                    $positionToInsert++;
                }
                else{
                    break;
                }
            }
        } 
        return $positionToInsert;
   }
    
    if($_SERVER['REQUEST_METHOD'] === "POST" && basename($_SERVER['HTTP_REFERER']) === "pairs.php"){
        $post =json_decode(file_get_contents('php://input'), true);
        
        if(!empty($post) && array_key_exists("levelScores", $post) && array_key_exists("totalTimes", $post) ){
            /*
            lists for levels 1 to 5 and then a list for overall score.
            [
                [ //Level one leaderboard
                    {
                        points: numPoints,
                        username : username1,
                        time: timeToComplete
                    },
                    {
                        ...
                    }
                ],
                [ //Level 2 leaderboard
                    ...
                ],etc
            ]
            */
            //5 levels so there will be five elements in the "levelPoints" and "totalTimes" fields.
            $leaderboardData = json_decode(file_get_contents('./data/leaderboard.json'), true);
            for($i = 0;$i<5;$i++){
                $newEntry = array(array(
                    "points" => $post["levelScores"][$i],
                    "username" => $_SESSION['username'],
                    "time" => round($post["totalTimes"][$i],2)
                ));
                
                $positionToInsert = findPositionToInsertEntry($newEntry, $leaderboardData[$i]);
                array_splice($leaderboardData[$i], $positionToInsert, 0, $newEntry);
                
            }
            $totalTime = array_sum($post["totalTimes"]);
            $totalPoints = array_sum($post["levelScores"]);
            $newEntry = array(array(
                "points" => $totalPoints,
                "username" => $_SESSION['username'],
                "time" => round($totalTime,2)
            ));
            $positionToInsert = findPositionToInsertEntry($newEntry, $leaderboardData[5]);
            array_splice($leaderboardData[5], $positionToInsert, 0, $newEntry);
      
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
        
        
        <div id="main">
            <img src="./images/arcade.jpg" alt="Arcade" id="arcadeImage">

            <div id="contentDiv">
                <h1>Leaderboard</h1>
        
                <button id="btn0" class="btn">Level 1</button>
                <button id="btn1" class="btn">Level 2</button>
                <button id="btn2" class="btn">Level 3</button>
                <button id="btn3" class="btn">Level 4</button>
                <button id="btn4" class="btn">Level 5</button>
                <button id="btn5" class="btn">Overall</button>
                
                
                <table id="leaderboardTable">
                    <tr class='leaderboardHeadings'>
                        <th>Position</th>
                        <th>Score</th>
                        <th>Username</th>
                        <th>Time</th>
                    </tr>
                
                
                    <?php //populating the table with entries.
                        $leaderboardData = json_decode(file_get_contents('./data/leaderboard.json'), true);
                        
                        if(!empty($leaderboardData[5])){
                            foreach($leaderboardData[5] as $position => $entry){
                                $actualPosition = $position + 1;
                                echo    "<tr>
                                        <td>{$actualPosition}</td>
                                        <td>{$entry['points']}</td>
                                        <td>{$entry['username']}</td>
                                        <td>{$entry['time']}</td>
                                    </tr>";
                            }
                        }
                        
                    ?>
                </table>
            </div>
        </div>
        <script>
            function clearTable(table){
                let index = 1;
                while(table.children.length > 1){ //Removes all rows in the table apart from first row (heading row).
                    table.children[index].remove();
                }
            }
            function changeTableDisplay(level){
                let table = document.getElementById('leaderboardTable').getElementsByTagName('tbody')[0];
                clearTable(table);
                fetch('./data/leaderboard.json')
                .then((response) => response.json())
                .then((leaderboardData) => {
                    let levelData = leaderboardData[level]
                    for(let i = 0;i<levelData.length;i++){
                        let row =document.createElement("tr");
                        let tdPosition = document.createElement("td");
                        tdPosition.innerHTML =i+1;
                        row.append(tdPosition);
                        for(let value of Object.values(levelData[i])){
                            let td = document.createElement("td");
                            td.innerHTML = value;
                            row.append(td);
                        }
                        
                        table.append(row);
                        
                    }
                });
            }
            for(let i=0;i<6;i++){
                let btn = document.getElementById(`btn${i}`);
                btn.level = i;
                btn.addEventListener("click", function(){changeTableDisplay(i)});
            }
            
        </script>
    </body>
</html>