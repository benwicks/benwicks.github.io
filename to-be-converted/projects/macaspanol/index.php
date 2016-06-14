<!--
http://www.omniglot.com/language/phrases/spanish.php
http://character-code.com/spanish-html-codes.php
-->
<!DOCTYPE html>
<html xmlns="http://www.w3.org/1999/xhtml" lang="en">
<head>
<title>Macaspa&ntilde;ol</title>
<?php include('header.php'); ?>
<script type="text/javascript">
var divPhrase;
var running = false;
var timeoutID = -1;
var msg = new SpeechSynthesisUtterance();
var language = "en-US";
var recentlyUsedMessages = [0];
var lastUsedMessage = 0;
var messages_en = ["Welcome!",
	"How are you doing?",
	"I'm fine, thanks. And you?",
	"Long time, no see.",
	"What is your name?",
	"My name is...",
	"Where are you from?",
	"I am from...",
	"Pleased to meet you!",
	"Good morning!",
	"Good afternoon.",
	"Good night",
	"Goodbye"];
var messages_es_html = ["&#161;Bienvenido!",
	"&#191;C&#243;mo est&#225;s?",
	"Bien gracias, &#191;y usted?",
	"&#161;Cu&#225;nto tiempo! &#161;Tanto tiempo sin verte!",
	"&#191;C&#243;mo se llama Usted?",
	"Me llamo...",
	"&#191;De d&#243;nde es usted?",
	"Soy de...",
	"Mucho gusto encantado",
	"Buenos d&#237;as",
	"Buenas tardes",
	"Buenas noches",
	"Hasta luego"];
var messages_es_text = ["Bienvenido!",
	"Como estas?",
	"Bien gracias, y usted?",
	"Cuanto tiempo! Tanto tiempo sin verte!",
	"Como se llama Usted?",
	"Me llamo...",
	"De donde es usted?",
	"Soy de...",
	"Mucho gusto encantado",
	"Buenos dias",
	"Buenas tardes",
	"Buenas noches",
	"Hasta luego"];

window.addEventListener("load", windowLoad, false);
function windowLoad(e) {
	initSpeech();
	divPhrase = document.getElementById("divPhrase");
	if (<? echo USER_IS_MOBILE; ?>) {
		divPhrase.innerHTML = "Tap the screen to begin.";
		window.addEventListener("touchstart", touchStartListener);
	} else {
		divPhrase.innerHTML = "Press the spacebar to begin.";
		$(window).keypress(function(e) {
			if ((e.keyCode==0||e.keyCode==32)) {
				if (running) {
					clearTimeout(timeoutID);
					divPhrase.innerHTML("Press the spacebar to resume.");
				} else {
					beginSpeaking();
				}
				running = !running;
			}
		});
	}
	divPhrase.addEventListener("webkitAnimationEnd", function() {
		if (running) {
			this.className = "";
			language = (language==="en-US"?"es-ES":"en-US");
			msg.lang = language;
			if (language==="en-US") {
				timeoutID = setTimeout(function() {startAnimationAgain_en();}, 4000);
			} else {
				timeoutID = setTimeout(function() {startAnimationAgain_sp();}, 2000);
			}
		} else {
			divPhrase.className = "";
		}
	});
}

function initSpeech() {
	var voices = window.speechSynthesis.getVoices();
	console.log("Voices:\t"+voices);
	msg.voice = voices[0];
	msg.voiceURI = "native";
	msg.volume = 1; // 0 to 1
	msg.rate = 1; // 0.1 to 10
	msg.pitch = 1; //0 to 2
	msg.lang = language;
	msg.text = messages_en[0];
	msg.onend = function(e) {
		console.log("Finished in "+e.elapsedTime+" seconds.");
	};
}

function beginSpeaking() {
	divPhrase.className = "animateMe";
	divPhrase.innerHTML = messages_en[lastUsedMessage];
	console.log("Set innerHTML to "+messages_en[lastUsedMessage]);
}

var touchStartListener = function(e) {
	e.preventDefault();
	window.removeEventListener("touchstart", touchStartListener);
	running = true;
	console.log("Begin speaking!");
	speechSynthesis.speak(msg);
	return false;
	beginSpeaking();
	var touch = e.touches[0];
	
};

function startAnimationAgain_en() {
	/* var rnd = Math.floor(Math.random()*messages_en.length);
	if (recentlyUsedMessages.length === messages_en.length) {
		recentlyUsedMessages = [];
		console.log("Cleared recently used messages array.");
	} else if (recentlyUsedMessages.indexOf(rnd) >= 0) {
		while (recentlyUsedMessages.indexOf(rnd) >= 0) {
			rnd = Math.floor(Math.random()*messages_en.length);
		}
	}
	recentlyUsedMessages.push(rnd);
	var rnd = lastUsedMessage;*/
	lastUsedMessage += 1
	if (lastUsedMessage===messages_en.length) {
		// TODO: Start something else?
		lastUsedMessage = 0;
		window.close();
		divPhrase.innerHTML = "All done.";
		divPhrase.className = "";
		return false;
	}
	
	divPhrase.innerHTML = messages_en[lastUsedMessage];
	divPhrase.className = "animateMe";
	msg.text = messages_en[lastUsedMessage];
	speechSynthesis.speak(msg);
}

function startAnimationAgain_sp() {
	// var lastWord = messages_es[recentlyUsedMessages[recentlyUsedMessages.length-1]];
	divPhrase.innerHTML = messages_es_html[lastUsedMessage];
	divPhrase.className = "animateMe";	
	msg.text = messages_es_text[lastUsedMessage];
	speechSynthesis.speak(msg);
}
</script>
<style type="text/css">
html, body {
	height:100%;
	width:100%;
	margin:0;
}
body {
	display:table;
	vertical-align:middle;
	text-align:center;
}
#divPhrase {
	display:table-cell;
	vertical-align:middle;
	font-size:<? echo (USER_IS_MOBILE?"1em":"3em");?>;
	font-family: "Verdana", sans-serif;
}
div.animateMe {
	-webkit-animation: animatePhrase 2s;
	-animation: animatePhrase 2s;
}
@-webkit-keyframes animatePhrase {
	0% {-webkit-transform: scale(0,0); opacity: 0;}
	50% {-webkit-transform: scale(1.2,1.2); opacity: 0.5;}
	100% {-webkit-transform: scale(1,1); opacity: 1;}
}
@keyframes animatePhrase {
	0% {transform: scale(0,0);}
	50% {transform: scale(1.2,1.2);}
	100% {transform: scale(1,1);}
}
</style>
</head>
<body>
<div id="divPhrase" class="animateMe">
Welcome!
</div>
</body>
</html>
