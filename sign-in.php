<?php
session_start();
include('inc/dbconnect.php');
if(!isset($attempt))
	$attempt = 0;
?>
<link rel="icon" type="image/png" href="attachment/bg/logo.png"/>
<title>Validating..</title>
<script type="text/javascript" src="//code.jquery.com/jquery-1.9.1.js"></script>
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/jquery-confirm/3.3.2/jquery-confirm.min.css">
<script src="https://cdnjs.cloudflare.com/ajax/libs/jquery-confirm/3.3.2/jquery-confirm.min.js"></script>
<link rel="stylesheet" type="text/css" href="css/header.css">

<script type="text/javascript">

	function student(){
		$.confirm({
			boxWidth: '27%',
  			title: 'Successful authentication!',
		    content: 'You will be redirected to student homepage now.',
		    type: 'green',
		    typeAnimated: true,
		    useBootstrap: false,
		    buttons: {
		        ok: {
		        	boxWidth: '27%',
		            text: 'Okay',
		            btnClass: 'btn-green',		            
		            action: function(){
		            	window.location.replace('Student');
		            }
		        }
		    }
		});
	}

	function coordinator(){
		$.confirm({
			boxWidth: '27%',
  			title: 'Successful authentication!',
		    content: 'You will be redirected to lecturer-coordinator homepage now.',
		    type: 'green',
		    typeAnimated: true,
		    useBootstrap: false,
		    buttons: {
		        ok: {
		        	boxWidth: '27%',
		            text: 'Okay',
		            btnClass: 'btn-green',		            
		            action: function(){
		            	window.location.replace('Coordinator');
		            }
		        }
		    }
		});
	}

	function supervisor(){
		$.confirm({
			boxWidth: '27%',
  			title: 'Successful authentication!',
		    content: 'You will be redirected to lecturer-supervisor/evaluator homepage now.',
		    type: 'green',
		    typeAnimated: true,
		    useBootstrap: false,
		    buttons: {
		        ok: {
		        	boxWidth: '27%',
		            text: 'Okay',
		            btnClass: 'btn-green',		            
		            action: function(){
		            	window.location.replace('Supervisor-Evaluator');
		            }
		        }
		    }
		});
	}
</script>

<?php
if(isset($_POST['submit']))
{
	if(!isset($_SESSION['username']))
	{
		$_SESSION['username'] = $_POST['username'];		
	}

	$username = $_POST['username'];
	$password = $_POST['password'];


	$sqlStudent = "SELECT * FROM student WHERE matricNo = '".$username."' AND password = '".$password."'";
	$sqlCoordinator = "SELECT * FROM lecturer WHERE lecturerID = '".$username."' AND password = '".$password."' AND role = 'coordinator'";
	$sqlSupervisor = "SELECT * FROM lecturer WHERE lecturerID = '".$username."' AND password = '".$password."' AND role != 'coordinator'";

	$resultStudent = $conn->query($sqlStudent);
	$resultCoordinator = $conn->query($sqlCoordinator);
	$resultSupervisor = $conn->query($sqlSupervisor);

	if($resultStudent->num_rows == 1){
		?>
		<body onload="student()">
			
		</body>
		<?php
	}else if($resultCoordinator->num_rows == 1){
		?>
		<body onload="coordinator();">
			
		</body>
		<?php
	}else if($resultSupervisor->num_rows == 1){
		?>
		<body onload="supervisor();">
			
		</body>
		<?php
	}else{
		$_SESSION['signInAttempt'] += 1;
		$attempt = 3 - $_SESSION['signInAttempt'];
		if($attempt == 0)
		{
			?>
			<body onload="attemptLimit();">		

			</body>
			<?php
		}
		else
		{
			?>
			<body onload="invalid();">		

			</body>
			<?php
		}
	}




}else{
	?>
		<body onload="invalid()"></body>
		<?php
}
?>

<script type="text/javascript">
	function invalid(){

		$.confirm({
			boxWidth: '27%',
			title: 'Account not found!',
			content: 'Incorrect username or password.<br><?php echo $attempt ?> attempt(s) left.',
			type: 'red',
			typeAnimated: true,
			useBootstrap: false,
			buttons: {
				tryAgain: {
					text: 'Try again',
					btnClass: 'btn-red',
					action: function(){
						window.location.replace('../PD-SaM');
					}
				}
			}
		});		
	}

	function attemptLimit(){
		$.confirm({
			boxWidth: '27%',
			title: 'Exceed attempt limit!',
			content: 'You have exceeded the sign in attempts.<br>Please try again after 10 seconds.',
			type: 'red',
			typeAnimated: true,
			useBootstrap: false,
			buttons: {
				ok: {
					text: 'Okay',
					btnClass: 'btn-red',
					action: function(){
						window.location.replace('../PD-SaM');
					}
				}
			}
		});
	}
</script>