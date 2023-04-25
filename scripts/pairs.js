document.addEventListener('DOMContentLoaded', function(){
    document.getElementById("playPairsbtn").addEventListener("click", PlayGame);
});


function setupGameArea(numberOfCards, canMatch3Or4Cards){
    //Using a few arrays of potential attributes for the colour, eyes, and mouth
    let colours = ["green", "red", "yellow"]; //initialise these arrays with array literal notation.
    let eyes = ["closed", "laughing", "long", "normal", "rolling", "winking"];
    let mouths = ["open", "sad", "smiling", "straight", "surprise", "teeth"];

    var table = document.getElementById('cardTable');
    //generate a list of pairs of images, then select an image at random and add it to to the table row.
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
        
            var contains = cards.some(elem => {
            return (card.colour === elem.colour && card.eyes === elem.eyes && card.mouth === elem.mouth);
        });
        
        if(!contains){//Makes sure there aren't already the same pair of cards in the cards array.
            let numCardsToMatch = 2;
            let differenceInNumOfCards = numberOfCards - cards.length;
            if(differenceInNumOfCards <= 4 && canMatch3Or4Cards){
                numCardsToMatch = differenceInNumOfCards;
            }
            else if(canMatch3Or4Cards){ // as if it is 5, a 4 card match shouldn't be added as it would leave a singular card as a match.
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
    // console.log(cards);
    cards.forEach(card => console.log(card.numCardsToMatch));
    //Adds the list of cards to the table in a random way.
    let numberOfRows = Math.ceil(numberOfCards / 5) ;
    // THIS MAKES THE TABLE CELLS ADD TO THE TABLE IN ROWS OF 5.
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
    console.log(table);
    
}
const Game = {//Variables needed for the pairs game. They are declared in an object so they are not global variables.
    clicks:0,
    matchedCells:[],
    cardsTurnedOver : [], //This will have a maximum of 3 cells in it at a time (for matching pairs - clickedCells.length == number of cards to match + 1).
    potentialMatch : true,
    previousBestScore : 0,
    points:0,
    startTime:0,
    levelFinished : false,
    timerInterval : null,
    currentSecond : 0,
    totalTimes:[],
    startNumberOfCards : 6,
    currentLevel : 4, //Levels start from zero (so the number of cards in the first level is equal to the number of cards declared in 'startNumberOfCards'),
    levelScores : []

}

function cardClicked(event){ //==================== CHECK IF CARDS ARE ADDED (pushed) to the Game.cardsClicked array properly and that the display properties are the correct value.
    clickedCard = event.currentTarget.id;
    //make Game.cardsTurnedOver to be the id of the cards clicked.
    console.log(!Game.cardsTurnedOver.includes(clickedCard));

    if(!Game.cardsTurnedOver.includes(clickedCard)){
        if(!Game.potentialMatch){
            Game.cardsTurnedOver.forEach(cardId => {//iterating over the cells that are currently turned over and flipping them back over.
                /*let imgs = cellElement.children;//each cell element has 3 children (all img elements)
                for(let i =0;i<3;i++){
                    imgs[i].classList.remove('showCard');
                    imgs[i].classList.add('hideCard');
                }*/
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
                    //console.log(Game.cardsTurnedOver[i].children[0]);
                    let card = document.getElementById(Game.cardsTurnedOver[i]);
                    if(card.children[0].children[1].children[j].src !== document.getElementById(Game.cardsTurnedOver[0]).children[0].children[1].children[j].src){
                        isPotentialMatch = false;
                        break;
                    }
                }
                if(!isPotentialMatch){ //NOT SURE IF NEEDED
                    break;
                }
                
            }
            if(isPotentialMatch){
                if(parseInt(Game.cardsTurnedOver[0].slice(-1)) === Game.cardsTurnedOver.length) { //A complete match has occurred / last digit of the id is the number of occurrences.
                    //Can take any cell element in cardsTurnedOver as they are all the same card and will have the same length.
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
                if(Game.points > 5){
                    Game.points -= 1;
                }
                else{
                    Game.points = 0;
                }
                updatePoints();
            }
            if(Game.levelFinished){
                let banner = document.createElement('div');
                banner.id = "winBanner";
                let bannerHeading = document.createElement('h1');
                bannerHeading.innerHTML = `You won with ${Game.points} points!`;

                let bannerTiming = document.createElement('h1');
                let totalTime = ((Date.now() - Game.startTime)/1000).toFixed(2);
                Game.totalTimes.push(totalTime);
                bannerTiming.innerHTML = `Completed in: ${totalTime} seconds`
                banner.append(bannerHeading);
                banner.append(bannerTiming);

                document.getElementById('gameAreaDiv').appendChild(banner);
                document.getElementById("cardTable").classList.add("hide");
                let playPairsBtn = document.getElementById("playPairsbtn");
                clearInterval(Game.timerInterval);

                //Create a submit score button here
                //=====================  THIS WILL HAPPEN AT THE END OF THE GAME - NOT AT THE END OF THE LEVEL  ============================================
                
                //Add level score to the Game.levelScores array
                Game.levelScores.push(Game.points);
                if(Game.currentLevel === 4){ //the 5th level has been completed
                    // ===================  NEED TO REMOVE THE 'submit button' FROM SHOWING AFTER THE 'play again' button has been clicked  ====================================
                    playPairsBtn.innerHTML = "Play again"; //"Play again";
                    let submitScoreBtn = document.createElement('button');
                    submitScoreBtn.innerHTML = 'Submit Score';
                    submitScoreBtn.id = 'submitScoreButton';
                    submitScoreBtn.classList = 'buttons btn btn-success';
                    submitScoreBtn.addEventListener("click", postScoreToLeaderboard);
                    document.getElementById('buttonDiv').append(submitScoreBtn);
                    
                } else{
                    playPairsBtn.innerHTML = "Next Level";
                    ++Game.currentLevel;
                }
                playPairsBtn.removeEventListener('click', PlayGame);
                playPairsBtn.addEventListener('click', resetGame);
                playPairsBtn.style.display = 'block';
            }
        }
    }


    
}
function postScoreToLeaderboard(){
    console.log("HELLO");
    //Make a post request here with the relevant details.
    fetch('./leaderboard.php', {
        method:'POST',
        headers:{
            'Accept' : 'application/json',
            'Content-Type' : 'application/json'
        },
        body: JSON.stringify({
            'levelScores':Game.levelScores,//Game.points,
            'totalTimes':Game.totalTimes
        })
    }).then(response => console.log(response.json()));
    //Then remove the event listener from the submit score button (the the same score isn't submitted more than once).
    let submitScoreBtn = document.getElementById('submitScoreButton');
    if(submitScoreBtn !== null){
        submitScoreBtn.removeEventListener('click', postScoreToLeaderboard);
    }
    
    
}

function resetGame(event){
    //Remove the previous cells of the table (empty the table), then call PlayGame() to setup the board again.

    let banner = document.getElementById('winBanner');
    banner.remove();
    let table = document.getElementById('cardTable');
    table.classList.remove('hide');
    table.classList.add('showTable');

    table.replaceChildren(); //removes all children from the table element.
    //Reset all of the Game object properties here:
    
    //if the submit score button exists, remove it from the DOM.
    let submitScoreBtn = document.getElementById('submitScoreButton');
    if(submitScoreBtn !== null){
        submitScoreBtn.remove();
    }
    if(Game.currentLevel === 4){
        Game.currentLevel = 0;
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
function setupGameForLevel(){
    //Here will be the checks for if the entire game is finished and not just the level (e.g. if(Game.currentLevel === 4){ do something at the end of the})
    setupGameArea(Game.startNumberOfCards + Game.currentLevel * 2, true);
}
function PlayGame(event){
    //Add the points and timer heading.
    //Get and set the previous best score for the current level to the Game object.
    
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
        }) //look into validating the JSON file - check if it's empty etc.
        .catch((error) => console.log(error));

    if(document.getElementById('pointsHeading') === null && document.getElementById('timerHeading') === null){
        let pointsHeading = document.createElement('h1');
        let timerHeading = document.createElement('h1');
        pointsHeading.id = "pointsHeading";

        
        timerHeading.id = "timerHeading";
        
        let mainDiv = document.getElementById('main');
        mainDiv.append(pointsHeading);
        mainDiv.append(timerHeading);
    }
    timerHeading.innerHTML = "Timer: 0";
    pointsHeading.innerHTML = "Points: 0";

    event.currentTarget.style.display = "none"; //hiding the 'play pairs' button.
    
    let totalNumberOfCards = Game.startNumberOfCards + 2 * Game.currentLevel;
    setupGameArea(totalNumberOfCards, Game.currentLevel >= 2);
    
    
    
    console.log(document.getElementById('cardTable').children);
    
    let rows = document.getElementById('cardTable').children;
    for(let i =0;i<rows.length;i++){
        let cells = rows[i].children;
        for(let j=0;j<cells.length;j++){
            cells[j].children[0].addEventListener('click', cardClicked);
        }
    }

    Game.startTime = Date.now();
    Game.timerInterval = setInterval(updateTimer,10);
    
}
function updateTimer(){
    let timer = document.getElementById("timerHeading");
    let timeDiffMs = Date.now() - Game.startTime;
    let ms = timeDiffMs % 1000;
    let s = Math.floor(timeDiffMs/1000) % 60;
    if(s != Game.currentSecond){
        Game.currentSecond = s;
        if(Game.points - 1 >= 0 && s % 2 === 0){
            Game.points -= 1;
            updatePoints();
        }
        
    }

    let m = Math.floor(timeDiffMs/60000) % 60;
    timer.innerHTML = `Time: ${String(m).padStart(2,'0')}:${String(s).padStart(2,'0')}:${ms}`;
}
function updatePoints(){
    let pointsHeading = document.getElementById('pointsHeading');
    pointsHeading.innerHTML = `Points: ${Game.points}`;
    let gameAreaDiv = document.getElementById('gameAreaDiv');
    let divColour = window.getComputedStyle(gameAreaDiv, null).getPropertyValue("background-color");
    
    console.log("divColour");
    console.log(divColour);
    console.log(divColour === "rgb(128, 128, 128)"); //FIND A BETTER WAY TO COMPARE THE COLOURS
    
    if(divColour === "rgb(255, 215, 0)" && Game.points < Game.previousBestScore){
        gameAreaDiv.style.setProperty("background-color", "#808080");
    }
    else if(divColour === "rgb(128, 128, 128)" && Game.points > Game.previousBestScore){
        gameAreaDiv.style.setProperty("background-color", "#FFD700");
    }
}