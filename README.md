[Azure VM Link](ml-lab-4d78f073-aa49-4f0e-bce2-31e5254052c7.ukwest.cloudapp.azure.com:54267)

### Pairs.php
* the game will start with 6 cards displayed face down and 2 cards will be added for each level completed (there are 5 levels).
* In the first 2 levels, the user will only have to match pairs of cards. In levels 3 to 5, the user will have to match 2, 3, or 4 cards.
* If the highscore for a level is exceeded the background colour of the content div changes to gold.
* On completion of the game (when using a registered session),the user can post their results to the leaderboard on a button click.
* When a card is clicked it is rotated so it looks like it is being turned over.
* If the cards flipped over don't match they will be flipped over when the next card is clicked.
* There is a timer.
* Points are deducted for every 2 seconds that pass and every incorrect attempt at matching the cards.
### index.php
* Displays a message depending on if the user is using a registered session.
### registration.php
* sets relevant session variables after form submission.
* can configure features to assemble an emoji avatar.
* the username field is validated on each change and when the form is submitted. If the username is invalid a message will be displayed in red.
### leaderboard.php
* Leaderboards for the individual levels can be viewed by clicking the buttons at the top of the page (the overall leaderboard is displayed first).