let timerRun = false;
let startTime;
let elapsedTime = 0;
let timerInterval;
let moveCount = 0;

function swapTiles(cell1, cell2) {
	var temp = document.getElementById(cell1).className;
	document.getElementById(cell1).className = document.getElementById(cell2).className;
	document.getElementById(cell2).className = temp;
	
	addMoveCount();
	
	setTimeout(() => {Win()}, 1000);
}

function swapTilesNoCount(cell1, cell2) {
	var temp = document.getElementById(cell1).className;
	document.getElementById(cell1).className = document.getElementById(cell2).className;
	document.getElementById(cell2).className = temp;
}

function addMoveCount() {
	moveCount++;
	document.getElementById('moveCountDisplay').textContent = moveCount;
}

function startNewGame() {
	moveCount = 0;
	document.getElementById('moveCountDisplay').textContent = moveCount; 

    elapsedTime = 0; 
    document.getElementById('timerDisplay').textContent = 0; 

    clearInterval(timerInterval); 
    timerRun = false; 

    shuffle(); 
    startTimer();
}

function startSimpleGame() {
	resetTiles();
	
	moveCount = 0;
	document.getElementById('moveCountDisplay').textContent = moveCount;
	
	elapsedTime = 0;
	document.getElementById('timerDisplay').textContent = 0; 

    clearInterval(timerInterval); 
    timerRun = false
	
	shuffle2();
	startTimer();
	
}


function shuffle() {
	for (var row = 1; row <= 4; row++) {
		for (var column = 1; column <= 4; column++) {
			var row2=Math.floor(Math.random()*4+1);
			var column2=Math.floor(Math.random()*4+1);
			swapTilesNoCount("cell"+row+column, "cell"+row2+column2);
		}
	}
}

function shuffle2() {
	var randomNumber = Math.floor(Math.random()*2+1);
	if(randomNumber == 1) {
		swapTilesNoCount("cell"+34, "cell"+44);
	}	
	else{
		swapTilesNoCount("cell"+43, "cell"+44);
	}
}

function clickTile(row, column) {
	var cell = document.getElementById("cell"+row+column);
	var tile = cell.className;
	
	if(tile!="tile16") {
		if(column < 4) {
			if(document.getElementById("cell"+row+(column+1)).className=="tile16") {
				swapTiles("cell"+row+column, "cell"+row+(column+1));
				return;
			}
		}
		if(column > 1) {
			if(document.getElementById("cell"+row+(column-1)).className=="tile16") {
				swapTiles("cell"+row+column, "cell"+row+(column-1));
				return;
			}
		}
		if(row > 1) {
			if(document.getElementById("cell"+(row-1)+column).className=="tile16") {
				swapTiles("cell"+row+column, "cell"+(row-1)+column);
				return;
			}
		}
		if(row < 4) {
			if(document.getElementById("cell"+(row+1)+column).className=="tile16") {
				swapTiles("cell"+row+column, "cell"+(row+1)+column);
				return;				
			}
		}
	}
}

function startTimer() {
	if (!timerRun) {
		timerRun = true;
		startTime = Date.now() - elapsedTime;
		timerInterval = setInterval(updateTimer, 1000);
	}
}

function updateTimer() {
	elapsedTime = Date.now() - startTime;
	displayTime();
}

function displayTime() {
	let seconds = Math.floor(elapsedTime/1000);
	document.getElementById('timerDisplay').textContent = seconds;
}

function Win() {
	if(document.getElementById("cell11").className=="tile1" &&
	document.getElementById("cell12").className=="tile2" &&
	document.getElementById("cell13").className=="tile3" &&
	document.getElementById("cell14").className=="tile4" &&
	document.getElementById("cell21").className=="tile5" &&
	document.getElementById("cell22").className=="tile6" &&
	document.getElementById("cell23").className=="tile7" &&
	document.getElementById("cell24").className=="tile8" &&
	document.getElementById("cell31").className=="tile9" &&
	document.getElementById("cell32").className=="tile10" &&
	document.getElementById("cell33").className=="tile11" &&
	document.getElementById("cell34").className=="tile12" &&
	document.getElementById("cell41").className=="tile13" &&
	document.getElementById("cell42").className=="tile14" &&
	document.getElementById("cell43").className=="tile15" &&
	document.getElementById("cell44").className=="tile16") {
		clearInterval(timerInterval);
		timerRun = false;
		let seconds = Math.floor(elapsedTime / 500);
		window.alert("Congratulations!! \nTime spent on current game in seconds: "+ seconds +" \nNumber of moves: " + moveCount + "\nWould you like to play again?")
		window.location.reload();
	}
}

function resetTiles() {
	document.getElementById('cell11').className = 'tile1';
    document.getElementById('cell12').className = 'tile2';
    document.getElementById('cell13').className = 'tile3';
    document.getElementById('cell14').className = 'tile4';
    document.getElementById('cell21').className = 'tile5';
    document.getElementById('cell22').className = 'tile6';
    document.getElementById('cell23').className = 'tile7';
    document.getElementById('cell24').className = 'tile8';
    document.getElementById('cell31').className = 'tile9';
    document.getElementById('cell32').className = 'tile10';
    document.getElementById('cell33').className = 'tile11';
    document.getElementById('cell34').className = 'tile12';
    document.getElementById('cell41').className = 'tile13';
    document.getElementById('cell42').className = 'tile14';
    document.getElementById('cell43').className = 'tile15';
    document.getElementById('cell44').className = 'tile16';

    moveCount = 0;
    document.getElementById('moveCountDisplay').textContent = moveCount;

    elapsedTime = 0;
    document.getElementById('timerDisplay').textContent = elapsedTime;

    clearInterval(timerInterval);
    timerRun = false;
}
