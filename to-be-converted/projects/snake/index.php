<html>
<head>
<title>SNAKE</title>
<style>
body {margin:0;}
</style>
<script type="text/javascript">
var canvasGrid = null;
var canvasSnake = null;
var canvasMsg = null;
var gameRunning = false;
var gameOver = false;
var canvasWidth = 0;
var canvasHeight = 0;
var sessionHighScore = 0;
var sessionName = "";
var postID = "";
var ip = "";
var isTouchDevice = false;
var xSquares = 0;
var ySquares = 0;
var clearDist = 0;
var gridOffset = 30;
var lineWidth = 3;
var cornerRad = 40;
var msgWidth = 400;
var smallFontSize = "18pt";
var largeFontSize = "28pt";
var xOffset = 0;
var yOffset = 0;
var snake = [];
var fruitLoc =[];
var timeToWait = 300;
var turnSnd = null;
var gameOverSnd = null;
var pauseSnd = null;
var resumeSnd = null;
// The direction can be either 0, 1, 2, or 3
var direction = 1;
var refreshIntervalId = 0;
var lastTimeout = 0;
var scoreToBeat = <?php $con=mysqli_connect("database2014.db.9953790.hostedresource.com","database2014","Neri988!","database2014");
if (mysqli_connect_errno()) {
	echo "alert(\"Failed to connect to MySQL: " . mysqli_connect_error()."\");";
	return;
}
$query = "SELECT score FROM snake_scores ORDER BY score ASC LIMIT 1";
$result = mysqli_query($con,$query);
if( ! mysqli_num_rows($result)) {
	echo "0";
} else {
	$row = mysqli_fetch_array($result);
	echo $row['score'];
}
mysqli_close($con);?>;
var soundsOn = true;
// Touch screen variables
var lastTouchDown = null;

window.onload = function() {
	turnSnd = document.getElementById("turnSnd");
	gameOverSnd = document.getElementById("gameOverSnd");
	pauseSnd = document.getElementById("pauseSnd");
	resumeSnd = document.getElementById("resumeSnd");

	if (navigator.userAgent.indexOf("Chrome") > -1) {
		soundsOn = true;
	} else {
		console.log(navigator.userAgent);
		soundsOn = false;
	}

	canvasGrid = document.getElementById("canvasGrid");
	canvasSnake = document.getElementById("canvasSnake");
	canvasMsg = document.getElementById("canvasMsg");

	if ('ontouchstart' in window || 'onmsgesturechange' in window) {
		isTouchDevice = true;
		canvasMsg.addEventListener("touchstart",touchDown);
		canvasMsg.addEventListener("touchend",touchUp);
		gridOffset *= 3;
		// cornerRad *= 3;
		msgWidth = 600;
		smallFontSize = "32pt";
		largeFontSize = "44pt";
	}
	clearDist = gridOffset-lineWidth;
	// var scaleFactor = backingScale(canvas.getContext("2d"));
 	// if (scaleFactor > 1) {
		// canvas.width = canvas.width * scaleFactor;
		// canvas.height = canvas.height * scaleFactor;
		// // update the context for the new canvas scale
		// var ctx = canvas.getContext("2d");
		// gridOffset *= scaleFactor;
	// }
	// TODO: Update this!
	postID = "<?php $f = fopen('keys.txt','r'); echo trim(fgets($f)); fclose($f); ?>";
	ip = navigator.userAgent;
	gameOver = true;
	gameRunning = false;
	// start_game();
	resize_canvases();
	draw_grid();
	show_message("Welcome to Snake","to begin",false);
};
document.onkeydown = function(event) {
	if (!event)
		event = window.event;
	var code = event.keyCode;
	var handled = false;
	if (event.charCode && code == 0)
		code = event.charCode;
	switch(code) {
		// The snake should not be able to do a 180 after it is > 1 in length
		case 37:
			// Key left.
			if (!gameRunning || direction == 1 && snake.length > 1) {
				break;
			}
			direction = 3;
			handled = true;
			break;
		case 38:
			// Key up.
			if (!gameRunning || direction == 2 && snake.length > 1) {
				break;
			}
			direction = 0;
			handled = true;
			break;
		case 39:
			// Key right.
			if (!gameRunning || direction == 3 && snake.length > 1) {
				break;
			}
			direction = 1;
			handled = true;
			break;
		case 40:
			// Key down.
			if (!gameRunning || direction == 0 && snake.length > 1) {
				break;
			}
			direction = 2;
			handled = true;
			break;
		case 83:
			// Letter s
			event.preventDefault();
			soundsOn = !soundsOn;
		case 32:
			// Spacebar
			event.preventDefault();
			flipGameRunning();
			break;
	}
	if (handled) {
		event.preventDefault();
		// Play a sound
		if (soundsOn) {
			turnSnd.currentTime = 0;
			turnSnd.play();
		}
		clearInterval(refreshIntervalId);
		update_game();
		refreshIntervalId = setInterval(function() {
			update_game();
		},timeToWait);
	}
};
function touchDown(e) {
	e.preventDefault();
	lastTouchDown = {x:e.touches[0].pageX, y:e.touches[0].pageY};
}
function touchUp(e) {
	if (!e) {
		e = event;
	}
	e.preventDefault();
	
	var xChange = Math.abs(lastTouchDown.x - e.changedTouches[0].pageX);
	var yChange = Math.abs(lastTouchDown.y - e.changedTouches[0].pageY);
	if (xChange > yChange) {
		if (lastTouchDown.x > e.changedTouches[0].pageX) {
			// Swipe left
			if (direction == 1 && snake.length > 1) {
				return;
			}
			direction = 3;
		} else {
			// Swipe right
			if (direction == 3 && snake.length > 1) {
				return;
			}
			direction = 1;
		}
		clearInterval(refreshIntervalId);
		update_game();
		refreshIntervalId = setInterval(function() {
			update_game();
		},timeToWait);
	} else if (yChange > xChange) {
		if (lastTouchDown.y > e.changedTouches[0].pageY) {
			// Swipe up
			if (direction == 2 && snake.length > 1) {
				return;
			}
			direction = 0;
		} else {
			// Swipe down
			if (direction == 0 && snake.length > 1) {
				return;
			}
			direction = 2;
		}
		clearInterval(refreshIntervalId);
		update_game();
		refreshIntervalId = setInterval(function() {
			update_game();
		},timeToWait);
	} else if (xChange == yChange) {
		flipGameRunning();
	}
}
function flipGameRunning() {
	// Clear the message that may be above the game canvas
	canvasMsg.getContext("2d").clearRect(0,0,canvasWidth,canvasHeight);

	gameRunning = !gameRunning;
	if (!gameRunning) {
		clearInterval(refreshIntervalId);
		console.log("Game Paused.");
		if (soundsOn) {
			pauseSnd.currentTime = 0;
			pauseSnd.play();
		}
		show_message("Game Paused","to resume",false);
	} else if (gameOver) {
		// Re-start the game
		canvasSnake.getContext("2d").clearRect(0,0,canvasWidth,canvasHeight);
		clearTimeout(lastTimeout);
		fruitLoc = generateRandomLocation();
		start_game();
		if (soundsOn) {
			resumeSnd.currentTime = 0;
			resumeSnd.play();
		}
	} else {
		canvasSnake.getContext("2d").clearRect(0,0,canvasWidth,canvasHeight);
		update_game();
		refreshIntervalId = setInterval(function() {
			update_game();
		},timeToWait);
		console.log("Game Resumed.");
		
		draw_grid();
		draw_snake();
		draw_fruit();
		if (soundsOn) {
			resumeSnd.currentTime = 0;
			resumeSnd.play();
		}
	}
}
function start_game() {
	gameRunning = true;
	gameOver = false;
	// TODO: Set a timer between lives
	// to record how long the life lasted

	// Re-set variables
	timeToWait = 300;
	// TODO: Don't let this be random. Get it from the user?
	direction = Math.floor(Math.random() * 4);

	draw_grid();
	init_snake();
	draw_snake();
	draw_fruit();
	refreshIntervalId = setInterval(function() {
		update_game();
	},timeToWait);
}
function init_snake() {
	snake = [];
	// Center snake head on grid
	snake.push([Math.ceil(xSquares/2), Math.ceil(ySquares/2)]);
}
function update_game() {
	if (!gameRunning) {
		clearInterval(refreshIntervalId);
		console.log("Trying to clear the interval ("+refreshIntervalId+")");
		return false;
	}

	ctx = canvasSnake.getContext("2d");

	// Clear the last tail of the snake
	var startX = ((snake[snake.length-1][0]-1)*gridOffset)+xOffset+(lineWidth/2);
	var startY = ((snake[snake.length-1][1]-1)*gridOffset)+yOffset+(lineWidth/2);
	ctx.clearRect(startX, startY, clearDist, clearDist);

	// Move entire snake in correct direction
	if (snake.length > 1) {
		// Remove the last position
		snake.pop();
		var newX = 0;
		var newY = 0;
		switch (direction) {
		case 0:
			newX = snake[0][0];
			newY = snake[0][1] - 1;
			break;
		case 1:
			newX = snake[0][0] + 1;
			newY = snake[0][1];
			break;
		case 2:
			newX = snake[0][0];
			newY = snake[0][1] + 1;
			break;
		case 3:
			newX = snake[0][0] - 1;
			newY = snake[0][1];
			break;
		}
		// Add new first position
		snake.unshift([newX,newY]);
	} else {
		switch (direction) {
		case 0:
			snake[0][1] -= 1;
			break;
		case 1:
			snake[0][0] += 1;
			break;
		case 2:
			snake[0][1] += 1;
			break;
		case 3:
			snake[0][0] -= 1;
			break;
		}
	}

	// Check that the snake did not exit the boundaries
	if (snake[0][0] < 1 || snake[0][0] > xSquares || snake[0][1] < 1 || snake[0][1] > ySquares) {
		flashHead(5);
		gameRunning = false;
		gameOver = true;
		if (soundsOn) {
			gameOverSnd.currentTime = 0;
			gameOverSnd.play();
		}
		clearInterval(refreshIntervalId);
		draw_snake();
		show_message("Game Over","to play again", true);
		check_score();
		return false;
	}

	// Check that the snake did not run into itself
	var len = snake.length;
	for (var i = 1; i < len; i++) {
		if (snake[0][0] == snake[i][0] && snake[0][1] == snake[i][1]) {
			flashHead(5);
			gameRunning = false;
			gameOver = true;
			if (soundsOn) {
				gameOverSnd.currentTime = 0;
				gameOverSnd.play();
			}
			clearInterval(refreshIntervalId);
			show_message("Game Over","to play again", true);
			check_score();
			return false;
		}
	}

	// Re-draw the snake
	draw_snake();
}
function flashHead(numTimes) {
	var fillDist = gridOffset-(2*lineWidth);
	var startX = ((snake[0][0]-1)*gridOffset)+xOffset+(lineWidth);
	var startY = ((snake[0][1]-1)*gridOffset)+yOffset+(lineWidth);
	ctx.clearRect(startX,startY,fillDist,fillDist);
	function fill() {
		ctx.fillStyle = "#FF0000";
		ctx.fillRect(startX,startY,fillDist,fillDist);
	};
	function clear() {
		ctx.clearRect(startX,startY,fillDist,fillDist);
	};
	for (var i = 0; i < numTimes; i++) {
		sleep(500*i,clear);
		sleep(250+(500*i),fill);
	}
}
function sleep(millis, callback) {
    lastTimeout = setTimeout(function()
            { callback(); }
    , millis);
}
function resize_canvases() {
	if (canvasSnake.width != window.innerWidth) {
		canvasSnake.width  = window.innerWidth;
	}
	if (canvasSnake.height != window.innerHeight) {
		canvasSnake.height = window.innerHeight;
	}
	if (canvasMsg.width != window.innerWidth) {
		canvasMsg.width  = window.innerWidth;
	}
	if (canvasMsg.height != window.innerHeight) {
		canvasMsg.height = window.innerHeight;
	}
	if (canvasGrid.width != window.innerWidth) {
		canvasGrid.width  = window.innerWidth;
	}
	if (canvasGrid.height != window.innerHeight) {
		canvasGrid.height = window.innerHeight;
	}
	canvasWidth = canvasSnake.width;
	canvasHeight = canvasSnake.height;
}
function backingScale(context) {
    if ('devicePixelRatio' in window) {
        if (window.devicePixelRatio > 1) {
            return window.devicePixelRatio;
        }
    }
    return 1;
}
function draw_grid() {
	var ctx = canvasGrid.getContext("2d");
	ctx.clearRect(0,0,canvasWidth,canvasHeight);
	ctx.lineWidth = lineWidth;
	xOffset = (canvasWidth % gridOffset)/2;
	yOffset = (canvasHeight % gridOffset)/2;
	//Vertical lines
	for (var i = xOffset; i < canvasWidth; i += gridOffset) {
		ctx.beginPath();
		ctx.moveTo(i,0);
		ctx.lineTo(i,canvasHeight);
		ctx.stroke();
		ctx.closePath();
	}
	//Horizontal Lines
	for (var i = yOffset; i < canvasHeight; i+= gridOffset) {
		ctx.beginPath();
		ctx.moveTo(0,i);
		ctx.lineTo(canvasWidth,i);
		ctx.stroke();
		ctx.closePath();
	}
	xSquares = ((canvasWidth - (2*xOffset))/gridOffset);
	ySquares = ((canvasHeight - (2*yOffset))/gridOffset);
}
function show_message(msg,msg2,showScore) {
	var ctx = canvasMsg.getContext("2d");
	ctx.clearRect(0,0,canvasWidth, canvasHeight);
	ctx.beginPath();
	if (msgWidth >= canvasMsg.width) {
		msgWidth = canvasMsg.width*.95;
	}
	var msgHeight = msgWidth*.75;
	if (msgHeight >= canvasMsg.height) {
		msgHeight = canvasMsg.height*.95;
	}
	ctx.moveTo((canvasMsg.width/2)-(msgWidth/2)+cornerRad, (canvasMsg.height/2)-(msgHeight/2));
	ctx.lineTo((canvasMsg.width/2)+(msgWidth/2)-cornerRad, (canvasMsg.height/2)-(msgHeight/2));
	ctx.quadraticCurveTo((canvasMsg.width/2)+(msgWidth/2), (canvasMsg.height/2)-(msgHeight/2), (canvasMsg.width/2)+(msgWidth/2), (canvasMsg.height/2)-(msgHeight/2)+cornerRad);
	ctx.lineTo((canvasMsg.width/2)+(msgWidth/2), (canvasMsg.height/2)+(msgHeight/2)-cornerRad);
	ctx.quadraticCurveTo((canvasMsg.width/2)+(msgWidth/2), (canvasMsg.height/2)+(msgHeight/2), (canvasMsg.width/2)+(msgWidth/2)-cornerRad, (canvasMsg.height/2)+(msgHeight/2));
	ctx.lineTo((canvasMsg.width/2)-(msgWidth/2)+cornerRad, (canvasMsg.height/2)+(msgHeight/2));
	ctx.quadraticCurveTo((canvasMsg.width/2)-(msgWidth/2), (canvasMsg.height/2)+(msgHeight/2), (canvasMsg.width/2)-(msgWidth/2), (canvasMsg.height/2)+(msgHeight/2)-cornerRad);
	ctx.lineTo((canvasMsg.width/2)-(msgWidth/2), (canvasMsg.height/2)-(msgHeight/2)+cornerRad);
	ctx.quadraticCurveTo((canvasMsg.width/2)-(msgWidth/2), (canvasMsg.height/2)-(msgHeight/2), (canvasMsg.width/2)-(msgWidth/2)+cornerRad, (canvasMsg.height/2)-(msgHeight/2));
	ctx.fillStyle = "rgba(0,0,0,0.8)";
	ctx.fill();
	ctx.closePath();
	// High score business
	var highScoreMsg = "Move: arrow keys | Pause: spacebar";
	if (isTouchDevice) {
		highScoreMsg = "Move: swipe | Pause: tap";
	}
	if (showScore) {
		highScoreMsg = "Your score: "+snake.length+" pts";
		if (snake.length > sessionHighScore) {
			sessionHighScore = snake.length;
			highScoreMsg = "New personal best: "+sessionHighScore+" pts";
		}
		if (snake.length == 1) {
			highScoreMsg = highScoreMsg.substring(0,highScoreMsg.length-1);
		}
	} else if (!gameOver) {
		highScoreMsg = "Current score: "+snake.length+" pts";
		if (snake.length == 1) {
			highScoreMsg = highScoreMsg.substring(0,highScoreMsg.length-1);
		}
	}
	// Text
	ctx.fillStyle = "#FFFFFF";
	ctx.font = largeFontSize+" Calibri";
	ctx.textAlign = "center";
	ctx.fillText(msg,canvasMsg.width/2,canvasMsg.height/2-(msgHeight/5));
	ctx.font = smallFontSize+" Calibri";
	if (isTouchDevice) {
		msg2Start = "Tap the screen ";
	} else {
		msg2Start = "Press spacebar ";
	}
	ctx.fillText(msg2Start+msg2,canvasMsg.width/2,canvasMsg.height/2);
	ctx.fillText(highScoreMsg,canvasMsg.width/2,canvasMsg.height/2+(msgHeight/5));
}
function check_score() {
	var xmlhttp = new XMLHttpRequest();
	// TODO: Keep track of scoreToBeat (and get it from checkScore.php)
	xmlhttp.onreadystatechange=function() {
		if (xmlhttp.readyState==4 && xmlhttp.status==200) {
			var isOnLeaderboard = (xmlhttp.responseText === 'true');
			if (isOnLeaderboard) {
				if (sessionName.length == 0) {
					sessionName = prompt("You got a new top 10 high score of "+sessionHighScore+"!\nPlease enter your name","");
					if (sessionName.length == 0) {
						sessionName = 'Anonymous';
					}
				} else {
					alert("Congratulations, "+sessionName+"! You got a new top 10 high score of "+sessionHighScore+"!");
				}
				console.log(sessionName+" got a new top 10 high score of "+sessionHighScore);
				// Submit the high score!
				var xmlhttp2 = new XMLHttpRequest();
				xmlhttp2.onreadystatechange=function() {
					if (xmlhttp2.readyState==4 && xmlhttp2.status==200) {
						var response = xmlhttp2.responseText.trim();
						if (response === 'true') {
							var xmlhttp3 = new XMLHttpRequest();
							xmlhttp3.onreadystatechange=function() {
								if (xmlhttp3.readyState==4) {
									console.log("Response3: "+xmlhttp3.responseText.trim());
									var json = JSON.parse(xmlhttp3.responseText);
									var topScores = [];
									for (var counter = 0; counter < json.highscores.length; counter++) {
										var hs = json.highscores[counter];
										if (hs.user.length > 10) {
											hs.user = hs.user.substring(0,8)+"...";
										}
										topScores[counter] = hs.score+"   "+hs.user+" ("+hs.time+")";
										if (counter > 9) {
											break;
										}
									}
									print_leaderboard(topScores);
								}
							};
							xmlhttp3.open("GET","getHighScores.php",true);
							xmlhttp3.send();
						}
					}
				};
				xmlhttp2.open("POST","submitScore.php",true);
				xmlhttp2.setRequestHeader("Content-type","application/x-www-form-urlencoded");
				xmlhttp2.send("score="+sessionHighScore+"&postID="+postID+"&name="+sessionName+"&ip="+ip);
			}
		}
	};
	xmlhttp.open("POST","checkScore.php",true);
	xmlhttp.setRequestHeader("Content-type","application/x-www-form-urlencoded");
	xmlhttp.send("score="+sessionHighScore+"&postID="+postID);
}
function print_leaderboard(scores) {
	var ctx = canvasMsg.getContext("2d");
	ctx.clearRect(0,0,canvasWidth, canvasHeight);
	ctx.beginPath();
	if (msgWidth >= canvasMsg.width) {
		msgWidth = canvasMsg.width*.95;
	}
	var msgHeight = msgWidth*1.2;
	if (msgHeight >= canvasMsg.height) {
		msgHeight = canvasMsg.height*.95;
	}
	ctx.moveTo((canvasMsg.width/2)-(msgWidth/2)+cornerRad, (canvasMsg.height/2)-(msgHeight/2));
	ctx.lineTo((canvasMsg.width/2)+(msgWidth/2)-cornerRad, (canvasMsg.height/2)-(msgHeight/2));
	ctx.quadraticCurveTo((canvasMsg.width/2)+(msgWidth/2), (canvasMsg.height/2)-(msgHeight/2), (canvasMsg.width/2)+(msgWidth/2), (canvasMsg.height/2)-(msgHeight/2)+cornerRad);
	ctx.lineTo((canvasMsg.width/2)+(msgWidth/2), (canvasMsg.height/2)+(msgHeight/2)-cornerRad);
	ctx.quadraticCurveTo((canvasMsg.width/2)+(msgWidth/2), (canvasMsg.height/2)+(msgHeight/2), (canvasMsg.width/2)+(msgWidth/2)-cornerRad, (canvasMsg.height/2)+(msgHeight/2));
	ctx.lineTo((canvasMsg.width/2)-(msgWidth/2)+cornerRad, (canvasMsg.height/2)+(msgHeight/2));
	ctx.quadraticCurveTo((canvasMsg.width/2)-(msgWidth/2), (canvasMsg.height/2)+(msgHeight/2), (canvasMsg.width/2)-(msgWidth/2), (canvasMsg.height/2)+(msgHeight/2)-cornerRad);
	ctx.lineTo((canvasMsg.width/2)-(msgWidth/2), (canvasMsg.height/2)-(msgHeight/2)+cornerRad);
	ctx.quadraticCurveTo((canvasMsg.width/2)-(msgWidth/2), (canvasMsg.height/2)-(msgHeight/2), (canvasMsg.width/2)-(msgWidth/2)+cornerRad, (canvasMsg.height/2)-(msgHeight/2));
	ctx.fillStyle = "rgba(0,0,0,0.8)";
	ctx.fill();
	ctx.closePath();
	// High score business
	ctx.fillStyle = "#FFFFFF";
	ctx.textAlign = "center";
	ctx.font = largeFontSize+" Calibri";
	ctx.fillText("High Scores",canvasMsg.width/2, (canvasMsg.height/2)-(msgHeight/2)+(1.5*cornerRad));
	ctx.font = smallFontSize+" Calibri";
	var counter = 1;
	for (s in scores) {
		ctx.fillText(scores[s],canvasMsg.width/2,(canvasMsg.height/2)-(msgHeight/2)+(2*cornerRad)+(counter*30));
		counter += 1;
	}
	ctx.fillStyle = "#eeeeee";
	var msg = "(Press spacebar to continue)";
	if (isTouchDevice) {
		msg = "(Tap to continue)";
	}
	ctx.fillText(msg,canvasMsg.width/2,(canvasMsg.height/2)-(msgHeight/2)+(2*cornerRad)+(counter*30));
}
function draw_snake() {
	// Check to see if the snake ate the fruit
	if (snake[0][0] == fruitLoc[0] && snake[0][1] == fruitLoc[1]) {
		timeToWait *= .85;
		if (timeToWait < 5) {
			timeToWait = 5;
		}
		var newX = snake[snake.length-1][0];
		var newY = snake[snake.length-1][1];
		if (snake.length == 1) {
			switch (direction) {
			case 0:
				newY += 1;
				break;
			case 1:
				newX -= 1;
				break;
			case 2:
				newY -= 1;
				break;
			case 3:
				newX += 1;
				break;
			}
		} else {
			var prevX = snake[snake.length-2][0];
			var prevY = snake[snake.length-2][1];
			newX = newX + (newX - prevX);
			newY = newY + (newY - prevY);
		}
		snake.push([newX,newY]);
		fruitLoc = generateRandomLocation();
		draw_fruit();
	}
	var ctx = canvasSnake.getContext("2d");
	ctx.fillStyle = "#FF0000";
	var arrayLength = snake.length;

	// Fill in the snake
	// TODO: Perhaps have the snake fade from red at one end to orange at the other to show which end is head
	for (var i = 0; i < arrayLength; i++) {
		var startX = ((snake[i][0]-1)*gridOffset)+xOffset+(lineWidth);
		var startY = ((snake[i][1]-1)*gridOffset)+yOffset+(lineWidth);
		var fillDist = gridOffset-(2*lineWidth);
		ctx.fillRect(startX,startY,fillDist,fillDist);
	}
}
function draw_fruit() {
	if (fruitLoc.length == 0) {
		fruitLoc = generateRandomLocation();
	}
	var ctx = canvasSnake.getContext("2d");
	ctx.fillStyle = "#0000FF";
	ctx.beginPath();
	var x = ((fruitLoc[0]-0.5)*gridOffset)+xOffset;
	var y = ((fruitLoc[1]-0.5)*gridOffset)+yOffset;
	var r = (gridOffset/2) - (lineWidth/2) - 2;
	ctx.arc(x,y,r,0,2*Math.PI);
	ctx.fill();
	ctx.closePath();
}
function generateRandomLocation() {
	var x = Math.floor(Math.random() * xSquares) + 1;
	var y = Math.floor(Math.random() * ySquares) + 1;
	var loc = [x,y];
	while (inSnake(loc)) {
		console.log("New fruit location ("+loc+") was in the snake body - trying again.");
		x = Math.floor(Math.random() * xSquares) + 1;
		y = Math.floor(Math.random() * ySquares) + 1;
		loc = [x,y];
	}
	console.log("New fruit location:\t"+loc);
	return loc;
}
function inSnake(loc) {
	var arrayLength = snake.length;
	for (var i = 0; i < arrayLength; i++) {
		// Check to be sure that the location is not the same as any snake body location
		if (snake[i][0] == loc[0] && snake[i][1] == loc[1]) {
			return true;
		}
	}
	return false;
}
</script>
</head>
<body onresize="resize_canvases();start_game();">
<canvas id="canvasGrid" style="background:#CCC;position:absolute;z=index:1;" >You browser does not support the canvas</canvas>
<canvas id="canvasSnake" style="position:absolute;z-index:2;" >Your browswer does not support the canvas</canvas>
<canvas id="canvasMsg" style="position:absolute;z-index:3;" >Your browswer does not support the canvas</canvas>
<audio id="turnSnd" hidden="true">
	<source src="turn.m4a">
	<source src="turn.wav">
	<source src="turn.ogg">
	<source src="turn.mp3">
</audio>
<audio id="gameOverSnd" hidden="true">
	<source src="game_over.m4a">
	<source src="game_over.wav">
	<source src="game_over.ogg">
	<source src="game_over.mp3">
</audio>
<audio id="pauseSnd" hidden="true">
	<source src="pause.m4a">
	<source src="pause.wav">
	<source src="pause.ogg">
	<source src="pause.mp3">
</audio>
<audio id="resumeSnd" hidden="true">
	<source src="resume.m4a">
	<source src="resume.wav">
	<source src="resume.ogg">
	<source src="resume.mp3">
</audio>
</body>
</html>
