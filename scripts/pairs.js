document.addEventListener('DOMContentLoaded', function(){
    document.getElementById("playPairsbtn").addEventListener("click", PlayGame);
});


function setupGameArea(){
    //Using a few arrays of potential attributes for the colour, eyes, and mouth
    let colours = ["green", "red", "yellow"]; //initialise these arrays with array literal notation.
    let eyes = ["closed", "laughing", "long", "normal", "rolling", "winking"];
    let mouths = ["open", "sad", "smiling", "straight", "surprise", "teeth"];

    var table = document.getElementById('cardTable');
    //generate a list of pairs of images, then select an image at random and add it to to the table row.
    //Generates a list of 'card' objects with the attributes being the images of the skin, eyes, and mouth.
    let cards = [];
    for(let i=0;i<5;i++){
        let rndColourIndex = Math.floor(Math.random() * colours.length);
        let rndEyeIndex = Math.floor(Math.random() * eyes.length);
        let rndMouthIndex = Math.floor(Math.random() * mouths.length);
        for(let j = 0;j<2;j++){
            var colourImg = document.createElement('img');
            var eyesImg = document.createElement('img');
            var mouthImg = document.createElement('img');
            eyesImg.classList = 'eyesOrMouthCard cardInGame';
            mouthImg.classList = 'eyesOrMouthCard cardInGame';
            colourImg.classList = 'eyesOrMouthCard cardInGame';//colourCard';
            
            colourImg.src = `./images/emoji assets/skin/${colours[rndColourIndex]}.png`;
            eyesImg.src = `./images/emoji assets/eyes/${eyes[rndEyeIndex]}.png`;
            mouthImg.src = `./images/emoji assets/mouth/${mouths[rndMouthIndex]}.png`;
            let card = {colour:colourImg, eyes:eyesImg, mouth:mouthImg};
            //======================== DUPLICATES CAN BE ADDED FOR SOME REASON, HAVE A LOOK AT THIS =======================================
            /*
            LOOK AT:*/
            var contains = cards.some(elem => {
                return card.colour === elem.colour && card.eyes === elem.eyes && card.mouth === elem.mouth;
            });
            //console.log(contains);
            
            if(!contains){//Makes sure there aren't already the same pair of cards in the cards array.
                cards.push(card);
            } else{
                j--;
            }
            
        }
    }
    //Adds the list of cards to the table in a random way.
    for(let i=0;i<2;i++){
        let row = document.createElement("tr");
        for(let j=0;j<5;j++){
            let td = document.createElement("td");
            td.id = `td${i}${j}`;
            td.style.position = 'relative';
            let rndNum = Math.floor(Math.random() * cards.length);
            let rndCard = cards[rndNum];
            cards.splice(rndNum, 1); //removes the card at index rndNum from cards array.
            rndCard.eyes.classList.add(`${td.id}`);
            rndCard.mouth.classList.add(`${td.id}`);
            rndCard.colour.classList.add(`${td.id}`);
            td.append(rndCard.colour);
            td.append(rndCard.eyes);
            td.append(rndCard.mouth);
            row.append(td);
        }
        table.append(row);
    }
}
const Game = {//Variables needed for the pairs game. They are declared in an object so they are not global variables.
    clicks:0,
    matchedCells:[],
    clickedCells : [], //This will have a maximum of 3 cells in it at a time (for matching pairs - clickedCells.length == number of cards to match + 1).
    wasMatch:false,
    points:0,
    startTime:0,
    gameFinished : true,
    timerInterval : null,
    currentSecond : 0,
    totalTime:null

}

function cardClicked(event){ //==================== CHECK IF CARDS ARE ADDED (pushed) to the Game.cardsClicked array properly and that the display properties are the correct value.
    clickedCard = event.currentTarget;
    console.log(Game.clickedCells);
    //Check if the card clicked is one of the last two cards clicked - nothing should happen if the "current card" has been clicked in the past 2 clicks.
    let cardNotClickedRecently = true;
    switch(Game.clickedCells.length){
        
        case 0:
            break;
        case 1:
        case 2:
            if(Game.clickedCells.includes(clickedCard)){
                cardNotClickedRecently = false;
            }
            break;
        case 3://Check the last 2 cards that were clicked.
            if(Game.clickedCells[2] === clickedCard || (Game.clickedCells[1] === clickedCard && Game.clicks % 2 === 0)){ 
                cardNotClickedRecently = false;
            }
            break;
        default:
            break;
    }
    //console.log(Game.clickedCells);
    if(cardNotClickedRecently){
        //Check the length of the Game.clickedCells array. If it is less than 3, just add the card. Else, use pop() and then add the card.
        if(Game.clickedCells.length >= 3){
            Game.clickedCells.shift();//Removes the first element from an array
        }
        Game.clickedCells.push(clickedCard);
        
        Game.clicks++;
        //Showing the Card
        let imgs = document.getElementsByClassName(clickedCard.id);
        for(let i=0;i<3;i++){
            imgs[i].classList.add('showCard');
            imgs[i].classList.remove('cardInGame');
        }
        
        console.log(Date.now() - Game.startTime);

        if(Game.clicks % 2 === 0 && Game.clickedCells.length > 1){
            Game.wasMatch = checkForMatch(clickedCard, imgs);//Game.matchedCells.every(elem => Game.clickedCells.includes(elem)) (for below - LOGIC DOESNT WORK FOR SOME REASON)
            if(Game.wasMatch === true && Game.matchedCells.length === 10){
                Game.gameFinished = true;//alert(`You have completed the game with ${Game.points} points`);
            }
        } 
        else if(Game.clickedCells.length === 3 && Game.clicks % 2 === 1 && !Game.wasMatch){// If the number of clicks is odd and it isn't the first click, then remove the previous two cards from displaying.
            //set the last 2 sets of card imgs to cardInGame class (will be 'hideCard').
            let previousCardImgs = document.getElementsByClassName(Game.clickedCells[Game.clickedCells.length - 2].id);
            let lastCardImgs = document.getElementsByClassName(Game.clickedCells[0].id);
            for(let i=0;i<3;i++){
                //Check if either are in this.matchedCells
                
                previousCardImgs[i].classList.add('cardInGame');//CardInGame should be called 'hideCard' this will be changed soon!
                previousCardImgs[i].classList.remove('showCard');
                
                lastCardImgs[i].classList.add('cardInGame');
                lastCardImgs[i].classList.remove('showCard');
                
            }
            if(Game.points - 5 >=0){
                Game.points -= 5;
                updatePoints();
            }
        }
        if(Game.gameFinished === true){//When the game is finished there should be a message displayed, the play button should reappear but with 'Play again' text on it.
            //alert(`You have completed the game with ${Game.points} points`);
            //Create a "You Won!" banner which displays the points won and time taken.
            let banner = document.createElement('div');
            banner.id = "winBanner";
            let bannerHeading = document.createElement('h1');
            bannerHeading.innerHTML = `You won with ${Game.points} points!`;

            let bannerTiming = document.createElement('h1');
            let totalTime = ((Date.now() - Game.startTime)/1000).toFixed(2);
            Game.totalTime = totalTime;
            bannerTiming.innerHTML = `Completed in: ${totalTime} seconds`
            banner.append(bannerHeading);
            banner.append(bannerTiming);

            document.getElementById('gameAreaDiv').appendChild(banner);
            document.getElementById("cardTable").classList.add("hide");
            let playPairsBtn = document.getElementById("playPairsbtn");
            playPairsBtn.innerHTML = "Play again";
            playPairsBtn.removeEventListener('click', PlayGame);
            playPairsBtn.addEventListener('click', resetGame);
            playPairsBtn.style.display = 'block';

            clearInterval(Game.timerInterval);

            //Create a submit score button here
            let submitScoreBtn = document.createElement('button');
            submitScoreBtn.innerHTML = 'Submit Score';
            submitScoreBtn.id = 'submitScoreButton';
            submitScoreBtn.classList = 'buttons btn btn-success';
            submitScoreBtn.addEventListener("click", postScoreToLeaderboard);
            document.getElementById('buttonDiv').append(submitScoreBtn);

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
            'points':Game.points,//Game.points,
            'time':Game.totalTime
        })
    }).then(response => console.log(response.json()));
    
}

function checkForMatch(currentClicked, currentImgs){
    let isMatch = true;
    for(let i=0;i<3;i++){ //If the images on both of the cards are the same, then there is a match.
        if(isMatch && currentImgs[i].src !== Game.clickedCells[Game.clickedCells.length - 2].children[i].src){
            isMatch = false;
            break;
        }
    }
    if(isMatch){//There is a match
        Game.matchedCells.push(Game.clickedCells[Game.clickedCells.length - 2]);
        Game.matchedCells.push(currentClicked);
        //Removing the event listener for clicking a table cell.
        currentClicked.removeEventListener('click', cardClicked);
        Game.clickedCells[Game.clickedCells.length - 2].removeEventListener('click', cardClicked);

        Game.points += 20; //20 points for a match, -1 for each second taken, 
        updatePoints();
        return true;
    }else{
        
        return false;
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
    
    Game.clicks = 0;
    Game.clickedCells = [];
    Game.matchedCells = [];
    Game.points = 0;
    Game.wasMatch = false;
    Game.startTime = Date.now();
    Game.gameFinished = false;
    
    PlayGame(event);
}
function PlayGame(event){
    //Add the points and timer heading.
    
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
    setupGameArea();
    console.log(document.getElementById('cardTable'));
    for(let i=0;i<2;i++){
        for(let j=0;j<5;j++){
            document.getElementById(`td${i}${j}`).addEventListener("click",cardClicked);
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
}