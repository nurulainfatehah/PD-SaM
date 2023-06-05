<?php
include('inc/dbconnect.php');
session_start();

if(isset($_SESSION['username']))
{
	unset($_SESSION['username']);
}
if (isset($_SESSION['lockSignIn'])) 
{
	$locktime = time() - $_SESSION['lockSignIn'];
	if($locktime > 10)
	{
		unset($_SESSION['lockSignIn']);
		unset($_SESSION['signInAttempt']);

	}
}

if(!isset($_SESSION['signInAttempt']))
{
	$_SESSION['signInAttempt'] = 0;
}



?>
<!DOCTYPE html>
<html>
<head>
	<meta charset="utf-8">
	<link rel="icon" type="image/png" href="attachment/bg/logo.png"/>
	<link rel="stylesheet" type="text/css" href="css/header.css">
	<link rel="stylesheet" type="text/css" href="css/index.css">
	<title>Projek Diploma Supervision and Management System</title>
	<meta name="viewport" content="width=device-width, initial-scale=1.0">
	<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css">
	<script type="text/javascript" src="//code.jquery.com/jquery-1.9.1.js"></script>
	<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/jquery-confirm/3.3.2/jquery-confirm.min.css">
	<script src="https://cdnjs.cloudflare.com/ajax/libs/jquery-confirm/3.3.2/jquery-confirm.min.js"></script>

	<script type="text/javascript">
		
		/*$(document).ready(function(){


		});

		$(function(){



		});*/

		function validateForm(){
			var username = form.username.value;
			var password = form.password.value;

			if(username.length == 0 && password.length == 0){
				$('#username').focus();
				$.confirm({
					boxWidth: '27%',
				    title: 'Blank field!',
				    content: 'Please fill in username and password.',
				    type: 'red',
				    typeAnimated: true,
				    useBootstrap: false,
				    buttons: {
				        tryAgain: {
				            text: 'Try again',
				            btnClass: 'btn-red',
				            action: function(){

				            }
				        }
				    }
				});

				return false;
			}else if(username.length == 0){
				$('#username').focus();
				$.confirm({
					boxWidth: '27%',
				    title: 'Blank field!',
				    content: 'Please fill in username.',
				    type: 'red',
				    typeAnimated: true,
				    useBootstrap: false,
				    buttons: {
				        tryAgain: {
				            text: 'Try again',
				            btnClass: 'btn-red',
				            action: function(){

				            }
				        }
				    }
				});

				return false;
			}else if(password.length == 0){
				$('#password').focus();
				$.confirm({
					boxWidth: '27%',
				    title: 'Blank field!',
				    content: 'Please fill in password.',
				    type: 'red',
				    typeAnimated: true,
				    useBootstrap: false,
				    buttons: {
				        tryAgain: {
				            text: 'Try again',
				            btnClass: 'btn-red',
				            action: function(){

				            }
				        }
				    }
				});

				return false;
			}else if(username.length != 10){
				$('#username').focus();
				$.confirm({
					boxWidth: '27%',
				    title: 'Invalid username!',
				    content: 'Please try again.',
				    type: 'red',
				    typeAnimated: true,
				    useBootstrap: false,
				    buttons: {
				        tryAgain: {
				            text: 'Try again',
				            btnClass: 'btn-red',
				            action: function(){

				            }
				        }
				    }
				});

				return false;
			}else if(password.length < 7){
				$('#password').focus();
				$.confirm({
					boxWidth: '27%',
				    title: 'Invalid password!',
				    content: 'Please try again.',
				    type: 'red',
				    typeAnimated: true,
				    useBootstrap: false,
				    buttons: {
				        tryAgain: {
				            text: 'Try again',
				            btnClass: 'btn-red',
				            action: function(){

				            }
				        }
				    }
				});

				return false;
			}else if(/\s/.test(username)){
				$.confirm({
					boxWidth: '27%',
				    title: 'Username contains whitespaces.',
				    content: 'Username shall not have any whitespaces. Please try again',
				    type: 'red',
				    typeAnimated: true,
				    useBootstrap: false,
				    buttons: {
				        tryAgain: {
				            text: 'Try again',
				            btnClass: 'btn-red',
				            action: function(){
				            }
				        }
				    }
				});
				return false;
			}else{
				return true;
			}
		}


		function forgotPassword(){

			$.confirm({
				boxWidth: '27%',
				title: 'Reset password?',   
				useBootstrap: false,
				content: '' +
				'<form name="formforget" method="POST" action="reset-password.php" class="formName">' +
				'<div class="form-group" align="center">' +

				'<input type="text" placeholder="USERNAME" class="username form-control" name="forgotusername" required /><br>' +
				'<input  type="email" name="forgotemail" placeholder="E-MAIL" style="margin-top:8px" class="email form-control" required />' +
				'</div>' +
				'</form>',
				buttons: {
					formSubmit: {

						text: 'RESET',
						btnClass: 'btn-blue',
						action: function () {

							var name = this.$content.find('.username').val();
							var mail = this.$content.find('.email').val();
							var mail_format = /^\w+([\.-]?\w+)*@\w+([\.-]?\w+)*(\.\w{2,3})+$/;
							if(!name || !mail){

								forgotPasswordEmpty();
								return false;

							}else if(!mail.match(mail_format)){
								forgotEmailInvalid();
								return false;
							}
							else
								formforget.submit();
						}
					},
					cancel: function () {
			            //close
			        },
			    },
			    onContentReady: function () {
			        // bind to events
			        var jc = this;
			        this.$content.find('form').on('submit', function (e) {
			            // if the user submits the form by pressing enter in the field.
			            e.preventDefault();
			            jc.$$formSubmit.trigger('click'); // reference the button and click it
			        });
			    }
			});
			return false;
		}

		function forgotEmailInvalid(){

			$.confirm({
				boxWidth: '27%',
				title: 'Invalid email',
				content: 'Enter a valid email. Please try again',
				type: 'red',
				typeAnimated: true,
				useBootstrap: false,
				buttons: {
					tryAgain: {
						text: 'Try again',
						btnClass: 'btn-red',
						action: function(){
						}
					}
				}
			});
			return false;
		}

		function forgotPasswordEmpty(){

			$.confirm({
				boxWidth: '27%',
				title: 'Blank field!',
				content: 'Please enter username and email to reset password.',
				type: 'red',
				typeAnimated: true,
				useBootstrap: false,
				buttons: {
					tryAgain: {
						text: 'Try again',
						btnClass: 'btn-red',
						action: function(){
						}
					}
				}
			});
			return false;
		}
	
	</script>
</head>

<body>
	<div class="outerbox" id="top" style="background-color: rgba(255,255,255, 0.9);">

		<div class="innerbox">
			<header>
				<a href="" class="logo">Projek Diploma Supervision and Management System</a>
				
				<div class="clearfix"></div>
			</header>
			<div class="loginbox" align="center">
				<div class="loginImage">
					<img src="attachment/bg/lecturer.png" style="transform: scaleX(-1);">
					<img src="attachment/bg/student.png" style="margin-left: -30px; ">
				</div>
				<form name="form" action="sign-in.php" method="post" onsubmit="return validateForm();">
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
								PASSWORD
							</td>
							<td>
								<input placeholder="PASSWORD" type="password" name="password" id="password">
								<span class=asterisk_input></span>
							</td>
						</tr>
					</table><br>
					<a href="" onclick="return forgotPassword();" class="hide" style="margin-right: 5px;">Forgot password?</a><br>
					<?php
					if($_SESSION['signInAttempt'] > 2)
					{
						$_SESSION['lockSignIn'] = time();
						?>
						<p style="color: red; font-family: sans-serif; font-size: 12px;">You have exceeded the sign in attempts. Please try again after 10 seconds.</p>
						<?php
					}
					else{
						?>
						<input class="submit" type="reset" name="reset" value="Reset">
						<input class="submit" type="submit" name="submit" value="Sign in" >
						<?php
					}


					if($_SESSION['signInAttempt'] == 1)
					{
						?>
						<p style="color: orange; font-family: sans-serif; font-size: 12px;">Two attempts left. Session will freeze for 10 seconds after 3 attempts.</p>
						<?php
					}
					else if($_SESSION['signInAttempt'] == 2)
					{
						?>
						<p style="color: orange; font-family: sans-serif; font-size: 12px;">One attempt left. Session will freeze for 10 seconds after 3 attempts.</p>
						<?php
					}
					?>
					
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


<!--<script src="js/validateUsername.js"></script> --> <!-- AJAX load username/ email without reloading the page -->
