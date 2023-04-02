document.addEventListener('DOMContentLoaded', function(){
    setupGameArea();
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
            if(!cards.includes(card)){//Makes sure there aren't already the same pair of cards in the cards array.
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
const Game = {
    prevClicked : null, //Table cell that is clicked, each image has a class of the table cell's id.
    clicks:0,
    matchedCells:[]
}
function cardClicked(event){
    clickedCard = event.currentTarget;
    console.log(Game.clicks);
    console.log(Game.prevClicked);
    console.log(Game.matchedCells);
    Game.clicks++;
    //Showing the Card
    let imgs = document.getElementsByClassName(clickedCard.id);
    for(let i=0;i<3;i++){
        imgs[i].classList.add('showCard');
        imgs[i].classList.remove('cardInGame');
    }
    if(Game.clicks % 2 === 0 && Game.prevClicked !== null){
        checkForMatch(clickedCard, imgs);
    }
    Game.prevClicked = clickedCard;
}
function checkForMatch(currentClicked, currentImgs){
    let isMatch = true;
    for(let i=0;i<3;i++){ //If the images on both of the cards are the same, then there is a match.
        if(isMatch && currentImgs[i].src !== Game.prevClicked.children[i].src){
            isMatch = false;
        }
    }
    if(isMatch){//There is a match
        Game.matchedCells.push(Game.prevClicked);
        Game.matchedCells.push(currentClicked);
        //Removing the event listener for clicking a table cell.
        document.getElementById(currentClicked.id).removeEventListener('click', cardClicked);
        document.getElementById(Game.prevClicked.id).removeEventListener('click', cardClicked);
    }else{
        let prevImgs = document.getElementsByClassName(Game.prevClicked.id);
        setTimeout(function(){ // This is a cheap way to cheap the card to be displayed for a bit if there isn't a match. The previous 2 cards should be displayed until the next card is clicked.
            for(let i=0;i<3;i++){
                //Check if either are in this.matchedCells
                
                    currentImgs[i].classList.add('cardInGame');//CardInGame should be called 'hideCard' this will be changed soon!
                    currentImgs[i].classList.remove('showCard');
                
                    prevImgs[i].classList.add('cardInGame');
                    prevImgs[i].classList.remove('showCard');
                
            }
        }, 1000);
        
    }
}
/*, //cells that have already been matched
    getLastCellClicked(){
        return this.prevClicked;
    },
    click(event){
        clickedCell = event.currentTarget;
        //this.clicks+=1;
        console.log(clicks);
        console.log(this.clicks %2===0);
        console.log(this.prevClicked);
        //Show image in table cell that was clicked.
        let imgs = document.getElementsByClassName(clickedCell.id);
        for(let i=0;i<3;i++){
            imgs[i].classList.add('showCard');
            imgs[i].classList.remove('cardInGame');
                
        }
        if(this.clicks % 2 === 0 && this.prevClicked !== null){
            this.checkForMatch(clickedCell, imgs);
        }
        this.prevClicked = clickedCell;
        
    },
    checkForMatch(currentClicked, currentImgs){
        
    }
}*/
function PlayGame(e){
    e.currentTarget.style.display = "none";
    for(let i=0;i<2;i++){
        for(let j=0;j<5;j++){
            document.getElementById(`td${i}${j}`).addEventListener("click",cardClicked);
        }
    }
}