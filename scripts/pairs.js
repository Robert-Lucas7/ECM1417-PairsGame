document.addEventListener('DOMContentLoaded', function(){
    document.getElementById("playPairsbtn").addEventListener("click", PlayGame);
});
function setupGameArea(numberOfCards, canMatch3Or4Cards){
    let colours = ["green", "red", "yellow"]; 
    let eyes = ["closed", "laughing", "long", "normal", "rolling", "winking"];
    let mouths = ["open", "sad", "smiling", "straight", "surprise", "teeth"];

    var table = document.getElementById('cardTable');
    //Generates a list of 'card' objects with the attributes being the images of the skin, eyes, and mouth.
    let cards = [];
    while(cards.length < numberOfCards){
        let rndColourIndex = Math.floor(Math.random() * colours.length);
        let rndEyeIndex = Math.floor(Math.random() * eyes.length);
        let rndMouthIndex = Math.floor(Math.random() * mouths.length);

        let card = {
            colour:`./images/emoji assets/skin/${colours[rndColourIndex]}.png`, 
            eyes:`./images/emoji assets/eyes/${eyes[rndEyeIndex]}.png`, 
            mouth:`./images/emoji assets/mouth/${mouths[rndMouthIndex]}.png`
        };
        
        var contains = cards.some(elem => { //a boolean flag if the cards array already contains the card just produced.
            return (card.colour === elem.colour && card.eyes === elem.eyes && card.mouth === elem.mouth);
        });
        
        if(!contains){//Makes sure there aren't already the same pair of cards in the cards array.
            let numCardsToMatch = 2;
            let differenceInNumOfCards = numberOfCards - cards.length;
            if(differenceInNumOfCards <= 4 && canMatch3Or4Cards){
                numCardsToMatch = differenceInNumOfCards;
            }
            else if(canMatch3Or4Cards){ // if there are 5 cards remaining, a 4 card match shouldn't be added as it would leave a singular card as a set of cards to match.
                if(differenceInNumOfCards > 5){
                    numCardsToMatch = Math.floor( Math.random() * (4 - 2 + 1)) + 2; //Gets a random number from: 2,3
                } else{ //if there are 5 cards remaining, then choose 2 or 3 cards to match.
                    numCardsToMatch = Math.floor( Math.random() * (3 - 2 + 1)) + 2;
                }
                
            }
            card.numCardsToMatch = numCardsToMatch;
            for(let j = 0;j<numCardsToMatch;j++){
                cards.push(card);
            }
        }
    }
    //Adds the list of cards to the table in a random way.
    let numberOfRows = Math.ceil(numberOfCards / 5) ;
    // adds the cards to the table in rows of 5.
    for(let i=0;i<numberOfRows;i++){
        let row = document.createElement("tr");
        let j =0;
        while(j < 5 && numberOfCards - i*5 - j > 0){
            
            let rndNum = Math.floor(Math.random() * cards.length);
            let rndCard = cards[rndNum];
            cards.splice(rndNum, 1); //removes the card at index rndNum from cards array.
            let td = document.createElement("td");
            let card = document.createElement("div");
            card.id = `card${i}${j}${rndCard.numCardsToMatch}`;
            card.classList.add("flip-card");
            card.innerHTML = `<div class="flip-card-inner">
                                <div class="flip-card-front">

                                </div>
                                <div class="flip-card-back">
                                    <img src="${rndCard.colour}" alt="Avatar" class="eyesOrMouthCard">
                                    <img src="${rndCard.eyes}" alt="Avatar" class="eyesOrMouthCard">
                                    <img src="${rndCard.mouth}" alt="Avatar" class="eyesOrMouthCard">
                                </div>
                            </div>`;
            td.append(card);
            row.append(td);
            j++;
        }
        table.append(row);
    }
    
}
const Game = {//Variables needed for the pairs game.
    clicks:0,
    matchedCells:[],
    cardsTurnedOver : [], 
    potentialMatch : true,
    previousBestScore : 0,
    points:0,
    startTime:0,
    levelFinished : false,
    timerInterval : null,
    currentSecond : 0,
    totalTimes:[],
    startNumberOfCards : 6,
    currentLevel : 0, //Levels start from zero (so the number of cards in the first level is equal to the number of cards declared in 'startNumberOfCards'),
    gameFinished : false,
    levelScores : []

}

function cardClicked(event){
    clickedCard = event.currentTarget.id;

    if(!Game.cardsTurnedOver.includes(clickedCard)){
        if(!Game.potentialMatch){
            Game.cardsTurnedOver.forEach(cardId => {//iterating over the cards that are currently turned over and flipping them back over.
                document.getElementById(cardId).children[0].classList.remove("rotate");
            });
            Game.cardsTurnedOver = [];
            Game.potentialMatch = true;
        }
        Game.cardsTurnedOver.push(clickedCard);
        document.getElementById(clickedCard).children[0].classList.add("rotate");
        if(Game.cardsTurnedOver.length > 1){
            let isPotentialMatch = true;
            for(let i=1;i<Game.cardsTurnedOver.length;i++){
                for(let j=0;j<3;j++){
                    let card = document.getElementById(Game.cardsTurnedOver[i]);
                    if(card.children[0].children[1].children[j].src !== document.getElementById(Game.cardsTurnedOver[0]).children[0].children[1].children[j].src){
                        isPotentialMatch = false;
                        break;
                    }
                }
                if(!isPotentialMatch){
                    break;
                }
                
            }
            if(isPotentialMatch){
                if(parseInt(Game.cardsTurnedOver[0].slice(-1)) === Game.cardsTurnedOver.length) { //A complete match has occurred (the last digit of the id is the number of occurrences of that card)
                    Game.cardsTurnedOver.forEach(cardId => {
                        document.getElementById(cardId).removeEventListener('click', cardClicked)
                    });
                    Game.points += 20;
                    updatePoints();
                    Game.matchedCells.push.apply(Game.matchedCells, Game.cardsTurnedOver);
                    Game.cardsTurnedOver = [];

                    //Check for if the level has finished
                    if(Game.matchedCells.length === Game.startNumberOfCards + 2 * Game.currentLevel){
                        Game.levelFinished = true;
                    }
                }
                Game.potentialMatch = true;
            } else{
                Game.potentialMatch = false;
                if(Game.points > 2){ // 2 point penalty for an incorrect attempt.
                    Game.points -= 2;
                }
                else{
                    Game.points = 0;
                }
                updatePoints();
            }
            if(Game.levelFinished){
                let gameAreaDiv = document.getElementById("gameAreaDiv");
                let centeredContent = document.getElementById("centeredContent");

                let bannerHeading = document.createElement('h1');
                bannerHeading.innerHTML = `You won with ${Game.points} points!`;

                let bannerTiming = document.createElement('h1');
                let totalTime = ((Date.now() - Game.startTime)/1000).toFixed(2);

                bannerHeading.classList.add("endOfLevelHeadings");
                bannerTiming.classList.add("endOfLevelHeadings");

                Game.totalTimes.push(totalTime);
                bannerTiming.innerHTML = `Completed in: ${totalTime} seconds`
                
                centeredContent.prepend(bannerTiming);
                centeredContent.prepend(bannerHeading);
                
                gameAreaDiv.classList.add("winBannerAnimation");
                gameAreaDiv.style.setProperty("background-color", "#89CFF0");

                let table = document.getElementById("cardTable");
                table.classList.add("hide");
                table.classList.remove("showTable");
                let playPairsBtn = document.getElementById("playPairsbtn");
                playPairsBtn.classList.add("winBannerAnimation");
                
                clearInterval(Game.timerInterval);
               
                Game.levelScores.push(Game.points);
                if(Game.currentLevel === 4){ //the 5th level has been completed
                    playPairsBtn.innerHTML = "Play again";

                    fetch('./submitScoreLoggedIn.php', { //returns html string for the 'submit score' button if the user is using a registered session, otherwise it returns nothing.
                        method:'GET',
                        headers:{
                            'Accept' : 'application/json',
                            'Content-Type' : 'application/json'
                        }
                    }).then(response => response.text())
                    .then(data => {
                        if(data !== ""){
                            let wrapper = document.createElement("div");
                            wrapper.innerHTML = data;
                            let submitScoreBtn = wrapper.firstChild;
                            submitScoreBtn.addEventListener('click', postScoreToLeaderboard);
                            playPairsBtn.after(submitScoreBtn);
                        }
                    });
                    Game.gameFinished = true;
                    
                } else{
                    playPairsBtn.innerHTML = "Next Level";
                    ++Game.currentLevel;
                }
                playPairsBtn.removeEventListener('click', PlayGame);
                playPairsBtn.addEventListener('click', resetGame);
                playPairsBtn.style.display = 'inline-block';
            }
        }
    }


    
}
function postScoreToLeaderboard(){
    fetch('./leaderboard.php', {
        method:'POST',
        headers:{
            'Accept' : 'application/json',
            'Content-Type' : 'application/json'
        },
        body: JSON.stringify({
            'levelScores':Game.levelScores,
            'totalTimes':Game.totalTimes
        })
    });
    let submitScoreBtn = document.getElementById('submitScoreButton');
    if(submitScoreBtn !== null){ //If the submit score button exists, remove the onclick event so the score is only posted to the scoreboard once.
        submitScoreBtn.removeEventListener('click', postScoreToLeaderboard);
    }
}

function resetGame(event){
    //Remove the previous cells of the table (empty the table), then call PlayGame() to setup the board again.
    let bannerHeadings = document.getElementsByClassName("endOfLevelHeadings");
    while(bannerHeadings.length > 0){
        bannerHeadings[0].remove();
    }
    let table = document.getElementById('cardTable');
    table.classList.remove('hide');
    table.classList.add('showTable');

    table.replaceChildren();
    //if the submit score button exists, remove it from the DOM.
    let submitScoreBtn = document.getElementById('submitScoreButton');
    if(submitScoreBtn !== null){
        submitScoreBtn.remove();
    }
    //Reset all of the Game object properties
    if(Game.gameFinished === true){
        Game.currentLevel = 0;
        Game.gameFinished = false;
    }
    
    Game.clicks = 0;
    Game.clickedCells = [];
    Game.matchedCells = [];
    Game.points = 0;
    Game.wasMatch = false;
    Game.startTime = Date.now();
    Game.levelFinished = false;
    
    PlayGame(event);
}
function PlayGame(event){
    if(document.getElementById('cardTable') === null){//if table doesn't exist in the DOM then create it - on the first occurence.
        let table = document.createElement('table');
        table.id = 'cardTable';
        document.getElementById('gameAreaDiv').append(table);
    }
    fetch('./data/leaderboard.json')
        .then((response) => response.json())
        .then((leaderboardData) => {
            let gameAreaDiv = document.getElementById('gameAreaDiv');
            if(leaderboardData[Game.currentLevel].length > 0){
                Game.previousBestScore = leaderboardData[Game.currentLevel][0]['points'];
                gameAreaDiv.style.setProperty('background-color', '#808080');
            } else{
                Game.previousBestScore = -1;
                gameAreaDiv.style.setProperty('background-color', '#FFD700');
            }
        });

    if(document.getElementById('pointsHeading') === null && document.getElementById('timerHeading') === null){
        let pointsHeading = document.createElement('h1');
        let timerHeading = document.createElement('h1');
        let headingDiv = document.createElement('div');
        headingDiv.classList = "headings ";
        pointsHeading.id = "pointsHeading";
        timerHeading.id = "timerHeading";
        
        let mainDiv = document.getElementById('main');
        let gameAreaDiv = document.getElementById('gameAreaDiv');

        headingDiv.append(pointsHeading);
        headingDiv.append(timerHeading);
        mainDiv.insertBefore(headingDiv, gameAreaDiv);
        
        
    }
    timerHeading.innerHTML = "Timer: 0";
    pointsHeading.innerHTML = "Points: 0";

    event.currentTarget.style.display = "none"; //hiding the 'play pairs' button.
    
    let totalNumberOfCards = Game.startNumberOfCards + 2 * Game.currentLevel;
    setupGameArea(totalNumberOfCards, Game.currentLevel >= 2);
    
    let rows = document.getElementById('cardTable').children;
    for(let i =0;i<rows.length;i++){
        let cells = rows[i].children;
        for(let j=0;j<cells.length;j++){
            cells[j].children[0].addEventListener('click', cardClicked);
        }
    }

    Game.startTime = Date.now();
    Game.timerInterval = setInterval(updateTimer,100);
    
}
function updateTimer(){
    let timer = document.getElementById("timerHeading");
    let timeDiffMs = Date.now() - Game.startTime;
    let ms = timeDiffMs % 1000;
    let s = Math.floor(timeDiffMs/1000) % 60;
    if(s != Game.currentSecond){
        Game.currentSecond = s;
        if(Game.points - 1 >= 0 && s % 2 === 0){ //remove a point every 2 seconds if the points score will still be positive.
            Game.points -= 1;
            updatePoints();
        }
        
    }

    let m = Math.floor(timeDiffMs/60000) % 60;
    timer.innerHTML = `Time: ${String(m).padStart(2,'0')}:${String(s).padStart(2,'0')}.${Math.floor(ms/100)}`;
}
function updatePoints(){
    let pointsHeading = document.getElementById('pointsHeading');
    pointsHeading.innerHTML = `Points: ${Game.points}`;
    setGameAreaBackgroundColour(false);
    
}
function setGameAreaBackgroundColour(setStraightToGrey){
    let gameAreaDiv = document.getElementById('gameAreaDiv');
    let divColour = window.getComputedStyle(gameAreaDiv, null).getPropertyValue("background-color");
    if(!setStraightToGrey){
        if(divColour === "rgb(255, 215, 0)" && Game.points < Game.previousBestScore){
            gameAreaDiv.style.setProperty("background-color", "#808080");
        }
        else if(divColour === "rgb(128, 128, 128)" && Game.points > Game.previousBestScore){
            gameAreaDiv.style.setProperty("background-color", "#FFD700");
        }
    }
    else{
        gameAreaDiv.style.setProperty("background-color", "#FFD700");
    }
}