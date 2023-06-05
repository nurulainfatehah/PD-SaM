<?php
include('inc/dbconnect.php');
$userID = $_GET['userID'];



if(isset($_POST['change'])){

	$newpw = $_POST['password1'];
	$sql = "SELECT * FROM lecturer WHERE lecturerID = '".$userID."'";
	$resultLec = $conn->query($sqlLec);

	if(mysqli_num_rows($resultLec) > 0){
		$sqlUpdate = "UPDATE password SET password = '".$newpw."' WHERE lecturerID = '".$userID."'";
		if($conn->query($sqlUpdate) === TRUE){
			$sqlRemoveTemp = "UPDATE lecturer SET tempPassword = NULL WHERE lecturerID = '".$userID."'";
			
		}

	}else{
		$sqlUpdate = "UPDATE password SET password = '".$newpw."' WHERE matricNo = '".$userID."'";
		if($conn->query($sqlUpdate) === TRUE){
			$sqlRemoveTemp = "UPDATE student SET tempPassword = NULL WHERE matricNo = '".$userID."'";
		}
		
	}


}

?>
<!DOCTYPE html>
<html>
<head>
	<meta charset="utf-8">
	<link rel="stylesheet" type="text/css" href="css/header.css">
	<link rel="stylesheet" type="text/css" href="css/index.css">
	<title>Reset Password - Enter Temporary Key</title>
	<meta name="viewport" content="width=device-width, initial-scale=1.0">
	<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css">
	<script type="text/javascript" src="//code.jquery.com/jquery-1.9.1.js"></script>
	<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/jquery-confirm/3.3.2/jquery-confirm.min.css">
	<script src="https://cdnjs.cloudflare.com/ajax/libs/jquery-confirm/3.3.2/jquery-confirm.min.js"></script>
	<script type="text/javascript">
		function invalid(){

			$.confirm({
				boxWidth: '27%',
				title: 'Invalid username and temporary key.',
				content: 'The username and temporary key entered does not match. Please try again.',
				type: 'red',
				typeAnimated: true,
				useBootstrap: false,
				buttons: {
					tryAgain: {
						text: 'Try again',
						btnClass: 'btn-red',
						action: function(){
							$('#username').val('');
							$('#inputkey').val('');
						}
					}
				}
			});		
		}

		function notExist(){

			$.confirm({
				boxWidth: '27%',
				title: 'Fail to reset password.',
				content: 'Username and key entered are not a match and cannot be found.',
				type: 'red',
				typeAnimated: true,
				useBootstrap: false,
				buttons: {
					tryAgain: {
						text: 'Try again',
						btnClass: 'btn-red',
						action: function(){
							window.location.replace('../PD-SaM/confirm-reset-password.php?userID=<?php echo $userID ?>');
						}
					}
				}
			});		
		}

		function succeed(){
			$.confirm({
				boxWidth: '27%',
	  			title: 'Password successfully changed.',
			    content: 'Password has been successfully changed. Press OK to continue to the login form.',
			    type: 'green',
			    typeAnimated: true,
			    useBootstrap: false,
			    buttons: {
			        ok: {
			        	boxWidth: '27%',
			            text: 'Okay',
			            btnClass: 'btn-green',		            
			            action: function(){
			            	window.location.replace('../PD-SaM/');
			            }
			        }
			    }
			});
		}

		

		function validateForm(){
			var username = '<?php echo $userID; ?>';
			var pw1 = formchange.password1.value;
			var pw2 = formchange.password2.value;

			if(pw1 == "" || pw2 == ""){

			}else if(pw2 != pw1){
				$.confirm({
						boxWidth: '27%',
						title: '',
						content: 'Password and confirm password is mismatch. Please try again.',
						type: 'red',
						typeAnimated: true,
						useBootstrap: false,
						buttons: {
							tryAgain: {
								text: 'Try again',
								btnClass: 'btn-red',
								action: function(){
									$('#password1').val("");
									$('#password2').val("");
									return false;
								}
							}
						}
					});
			}else if(pw1.length < 7 || pw1.length > 60){
				$.confirm({
						boxWidth: '27%',
					    title: '',
					    content: 'Invalid length of password! Password must be between 7 to 60 characters only.',
					    type: 'red',
					    typeAnimated: true,
					    useBootstrap: false,
					    buttons: {
					        tryAgain: {
					            text: 'Try again',
					            btnClass: 'btn-red',
					            action: function(){
					            	$('#pw1').val("");
					            	$('#pw1').focus();
					            	return false;								
					            }
					        }
					    }
				});
			}else{
				return true;
			}

		}

		$(document).ready(function() {

			$('#nextreset').on('click', function(){
				var username = $('#username').val();
				var inputkey = $('#inputkey').val();
				var userID = '<?php echo $userID; ?>';
				if (username == '' || inputkey == '') {
				    username_state = false;
				    invalid();
				    return;
				}else if(username != userID){
					invalid();
					return;
				}
				$.ajax({
				    url: 'ajaxprocess/reset-next.php',
				    type: 'post',
				    data: {
				      'username_check' : 1,
				      'username' : username,
				      'inputkey' : inputkey
				   },
					success: function(response){
				    if (response == 'exist' ) {
				    	username_state = true;
				        $('#nextreset').prop('disabled', false);
				        $('#nextreset').css({ 'color': '#fff', 'background-color': 'rgba(117, 20, 117, 0.6)' });
				        $("#nextreset").mouseenter(function() {
				            $(this).css("background-color", "rgba(117, 20, 117, 0.3)").css("color", "#1A5573");
				        }).mouseleave(function() {
				             $(this).css("background-color", "rgba(117, 20, 117, 0.6)").css("color", "#fff");
				        });
				        $('#inputkeybox').hide();
				        $('#newpasswordbox').show();
				    }else if (response == 'notexist') {
				        
				        username_state = false;
				        $.confirm({
				          boxWidth: '27%',
				            title: 'Invalid username and temporary key',
				            content: 'The username and temporary key entered does not match. Please try again.',
				            type: 'red',
				            typeAnimated: true,
				            useBootstrap: false,
				            buttons: {
				                ok: {
				                    text: 'Okay',
				                    btnClass: 'btn-red',
				                    action: function(){
				                    }
				                }
				            }
				        });
				        
				        $('#nextreset').prop('disabled', true);
				        $('#nextreset').css({ 'color': 'white', 'background-color': 'gray' });
				        $('#newpasswordbox').hide();
				        $('#inputkeybox').show();
				      }
				    }
				});
			});	
		});
	</script>
</head>
<body>
	<div id="top" class="outerbox">
		<div class="innerbox">
			<header>
				<a href="../PD-SaM" class="logo">Projek Diploma Supervision System</a>
				
				<div class="clearfix"></div>
			</header>
			<div class="loginbox" align="center" id="inputkeybox">
				<h1>Reset Password - Enter Temporary Key</h1>
				
					<table cellpadding="5">
						<tr>
							<td style="text-align: center;">
								USERNAME
							</td>
							<td>
								<input placeholder="USERNAME" type="text" name="username" id="username" autofocus>
								<span class="asterisk_input"></span>
							</td>
						</tr>
						<tr>
							<td style="text-align: center;">
								TEMPORARY KEY
							</td>
							<td>
								<input placeholder="KEY" type="text" name="inputkey" id="inputkey">
								<span class=asterisk_input></span>
							</td>
						</tr>
						<tr>
							<td colspan="2" style="text-align: right;">
								<button class="submit" id="nextreset">Next</button>
							</td>
						</tr>
					</table><br>
					
			</div>
			<div class="loginbox" align="center" style="display: none;" id="newpasswordbox">
				<form name="formchange" action="" method="post" onsubmit="return validateForm();">
					<h1>Enter New Password</h1>
					<table cellpadding="5">
						<tr>
							<td style="text-align: center;">
								ENTER NEW PASSWORD
							</td>
							<td>
								<input placeholder="PASSWORD" type="password" name="password1" id="password1" autofocus>
								<span class="asterisk_input"></span>
							</td>
						</tr>
						<tr>
							<td style="text-align: center;">
								RE-ENTER NEW PASSWORD
							</td>
							<td>
								<input placeholder="RE-ENTER PASSWORD" type="password" name="password2" id="password2">
								<span class=asterisk_input></span>
							</td>
						</tr>
						<tr>
							<td colspan="2" style="text-align: right;">
								<input type="submit" name="change" class="submit" id="reset" value="RESET">
							</td>
						</tr>
					</table><br>					
				</form>
			</div>
		</div>
	</div>
	<?php
		include('footer.php');
	?>
	<div id="stickyholder" style="visibility: hidden;">
		<a class="sticky" href="#top">
			<i class="fa fa-arrow-circle-o-up" aria-hidden="true"></i>
		</a>
	</div>
</body>
</html>