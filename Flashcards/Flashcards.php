<!DOCTYPE html>
<html>
    <head>
        <meta charset="utf-8">
        <title>Flashcard Game</title>
        <?php 
            $server = "localhost";
            $user = "root";
            $pass = "";
            $dbName = "flashcards";

            $conn = new mysqli($server, $user, $pass, $dbName);

            if ($conn->connect_error) {
                die("Connection failed: " . $conn->connect_error);
            }

            $query = "select answer, path from flashcards order by rand();";
            $result = $conn->query($query);

            $answers = array();
            $pictures = array();

            if ($result->num_rows > 0) {
                while($row = $result->fetch_assoc()) {
                    $answers[] = $row['answer'];
                    $pictures[] = $row['path'];
                }
            }

            $jsonAnswers = json_encode($answers);
            $jsonPictures = json_encode($pictures);

            $conn->close();
        ?>

        <style>
            
			
			
        </style>

        <script>
            var guessed = false;
            var correct = 0;
            var pictures = <?php echo $jsonPictures; ?>;
            var answers = <?php echo $jsonAnswers; ?>;
            var order = [0,1,2,3,4,5,6,7,8,9,10,11,12,13,14,15,16,17,18,19];
            var counter = 0;

            function start() {
				var nextButton = document.getElementById("next");
				nextButton.addEventListener("click", nextQuestion, false);
				var restartButton = document.getElementById("restart");
				restartButton.addEventListener("click", restart, false);
				shuffleArray(order);
				nextQuestion();
			}
			
			function restart() {
				counter = 0;
				correct = 0;
				
				var correctNum = document.getElementById("numberCorrect");
				correctNum.innerHTML = correct;
				
				shuffleArray(order);
				nextQuestion();
				
			}
			
			function shuffleArray(array) {
				for (var i = array.length -1; i > 0; i--) {
					const j = Math.floor(Math.random() * (i +1));
					
					const temp = array[i];
					array[i] = array[j];
					array[j] = temp;
				}
			}
			
			function nextQuestion () {
				guessed = false;
				var imageSrc = document.getElementById("source");
				imageSrc.setAttribute("src", pictures[order[counter]]);
				imageSrc.setAttribute("alt",answers[order[counter]]);
				
				var imgNum = document.getElementById("numberImg");
				imgNum.innerHTML = parseInt(counter + 1);
				
				setButton();
				
				if (counter < pictures.length)
					counter ++;
				else
					endGame();
			}
			
			function randomNum (num1, num2) {
				return (Math.floor(num1 + Math.random() * num2));
			}
			
			
			function setButton() {
				//Format answer button to remove image type path
				var rand = Math.floor(1 + Math.random() * 4);
				var answerButton = document.getElementById("button" + rand);
				
				answerButton.setAttribute("value",answers[order[counter]]);
				
				setOtherButtons(rand);
			}
			
			function setOtherButtons(randomInt) {
				/*someway to check if same number is generated - answer buttons should all be different*/
				var button1 = document.getElementById("button1");
				var button2 = document.getElementById("button2");
				var button3 = document.getElementById("button3");
				var button4 = document.getElementById("button4");
				
				var rand1 = randomNum(0,19);
				var rand2 = randomNum(0,19);
				var rand3 = randomNum(0,19);

				while (rand1 == order[counter]){
					rand1 = randomNum(0,19);
				}
				while (rand2 == order[counter] || rand2 == rand1){
					rand2 = randomNum(0,19);
				}
				while (rand3 == order[counter] || rand3 == rand1 || rand3 == rand2){
					rand3 = randomNum(0,19);
				}
								
				if (randomInt == 1) {
					button2.setAttribute("value", answers[rand1]);
					button3.setAttribute("value", answers[rand2]);
					button4.setAttribute("value", answers[rand3]);
				}
				else if (randomInt == 2) {
					button1.setAttribute("value", answers[rand1]);
					button3.setAttribute("value", answers[rand2]);
					button4.setAttribute("value", answers[rand3]);
				}
				else if (randomInt == 3) {
					button1.setAttribute("value", answers[rand1]);
					button2.setAttribute("value", answers[rand2]);
					button4.setAttribute("value", answers[rand3]);
				}
				else if (randomInt == 4) {
					button1.setAttribute("value", answers[rand1]);
					button2.setAttribute("value", answers[rand2]);
					button3.setAttribute("value", answers[rand3]);
				}
			}
					
			
			
			function checkAnswer(id) {
				if (guessed == false) {
					var check = document.getElementById(id).value;
					var correctNum = document.getElementById("numberCorrect");

					if (check == answers[order[counter-1]]) {
						window.alert("Correct!");
						correct ++;
						correctNum.innerHTML = correct;	
					}

					else
						window.alert("Nope!");
					
					guessed = true;
				}
			}
			
			function endGame() {
				window.alert("Good Job! You got a " + correct + " out of 20!");
				restart();
			}
			
			window.addEventListener("load", start, false);
		</script>
	</head>

	<body>
		
		<div class = "inner">
			<h1>Language Game</h1>
			<p>Let's test your language identification skills... Besides, you are a programmer, aren't you??</p>
			<p>Match the language shown with its correct name.</p>
			<hr>
			<table>
				<th>Image</th>
				<th>Choices</th>
				<tr>
					<td align = "center">
						<img id = "source" src = "">
					</td>

					<td align = "center">
						<form id = "answers">
							<input type = "button" onClick = "checkAnswer('button1')" value = "button 1" id = "button1">
							<input type = "button" onClick = "checkAnswer('button2')" value = "button 2" id = "button2">
							<input type = "button" onClick = "checkAnswer('button3')" value = "button 3" id = "button3">
							<input type = "button" onClick = "checkAnswer('button4')" value = "button 4" id = "button4">
						</form>
					</td>
				</tr>
				<th>Images Shown:<span id = "numberImg">1</span></th>
				<th>Correct Answers:<span id = "numberCorrect">0</span></th>
			</table>
			
			
			<input type = "button" id = "next" value = "Next">
			<input type = "button" id = "restart" value = "Restart">
		</div>


    </body>
</html>