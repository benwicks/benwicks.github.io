<html>
<head>
<title>Sound</title>
<script src="../../jquery.js" type="text/javascript"></script>
<script>
var context = new webkitAudioContext(); // Create audio container
oscillator = context.createOscillator(); // Create sound source
oscillator.connect(context.destination); // Connect sound to output
oscillator.noteOn(0); // Play instantly

$(document).click(function(e) {
	console.log("Clicked at: "+e.pageX+", "+e.pageY);
	changeFrequency(e.pageX);
});

function changeFrequency(newFrequency) {
	oscillator.frequency.value = newFrequency;
}
</script>		
</head>
<body>

</body>
</html>
