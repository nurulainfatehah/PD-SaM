<?php 
include('../inc/dbconnect.php');
session_start();
$id_pensyarah = $_SESSION['username'];

$sqlRekodPensyarah = "SELECT * FROM lecturer WHERE lecturerID = '".$id_pensyarah."'";
$resultRekodPensyarah = $conn->query($sqlRekodPensyarah);
$rowRekodPensyarah = $resultRekodPensyarah->fetch_assoc();



$sqlAnakSeliaan = "SELECT matricNo, name, picture, sectionGroup, phone, projectTitle FROM student WHERE supervisorID = '".$id_pensyarah."'";
$resultAnakSeliaan = $conn->query($sqlAnakSeliaan);


$sqlPassword = "SELECT password FROM lecturer WHERE lecturerID = '".$id_pensyarah."'";
$resultPassword = $conn->query($sqlPassword);
$rowPassword = $resultPassword->fetch_assoc();
$pw = $rowPassword['password'];

include('../ajaxprocess/update-ajax.php');

if(isset($_POST['submitpicture'])){
		
		$file = $_FILES["file_picture"];
		$fileName = $file['name'];
		$fileTmpName = $file['tmp_name'];
		$fileSize = $file['name'];
		$fileError = $file['error'];

		$fileExt = explode('.', $fileName);
		$fileActualExt = strtolower(end($fileExt));

		if($fileError == 0) //PICTURE HAS NO PROBLEM 
		{
			if($fileSize < 10000) //PICTURE DOES NOT EXCEED 10MB
			{
				$fileNameNew = $id_pensyarah . "_profile." . $fileActualExt ;
				$fileDestination = '../attachment/profile/'.$fileNameNew;
				move_uploaded_file($fileTmpName, $fileDestination);

						
				$sqlUpdatePicture = "UPDATE lecturer SET picture = '".$fileNameNew."' WHERE lecturerID = '".$id_pensyarah."'";

				if($conn->query($sqlUpdatePicture) === TRUE)
				{
					unset($_POST['submitpicture']);
					echo("<script>location.href = 'profile.php?changed';</script>");
				}else{
					?>
					<script>
						invalid();
					</script>
					<?php 
				}

			}else{
				?>
				<script>
					pictTooBig();
				</script>
				<?php 
			}
		}else{
			?>
			<script>
				pictNotFound();
			</script>
			<?php 
		}

	}

?>

<!DOCTYPE html>
<html>
<head>
	<link rel="icon" type="image/png" href="../attachment/bg/logo.png"/>
	<title>My Profile - <?php echo ucfirst($id_pensyarah);?></title>
	<link rel="stylesheet" type="text/css" href="../css/header.css">
	<link rel="stylesheet" type="text/css" href="../css/profile.css">
	<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css">
	<script type="text/javascript" src="//code.jquery.com/jquery-1.9.1.js"></script>
	<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/jquery-confirm/3.3.2/jquery-confirm.min.css">
	<style type="text/css">
		.maklumat
		{
			display: inline-flex;
			width: 88%;
			margin-left: 5%;
			margin-right: auto;		
			height: auto;
			margin-top: 2px;
			padding: 5px 10px 5px 10px;
			flex-wrap: wrap;
		    align-content: center;
		    justify-content: center;
		}

		.list-box{
			width:35%;
			height: 90px;
			border: 1px solid grey;
			border-radius: 25px;
			height: auto;
			margin-right: 10px;
			margin-top: 10px;
			padding: 7px 7px 10px 7px;
		}

		.list-profile-pic{
			border-radius: 50%;
			height: 60px;
			width: 60px;
			background-size: cover;
			background-position: center;
			color: transparent;
			margin-left: auto;
			margin-right: auto;
		}
	</style>
	<script src="https://cdnjs.cloudflare.com/ajax/libs/jquery-confirm/3.3.2/jquery-confirm.min.js"></script>
	<script type="text/javascript">
		function viewStudent(obj, name){
			var matricNo = obj.id;
			var name = name;
			$.confirm({
				boxWidth: '60%',
				useBootstrap: false,			
				title: name,
				cancelButton: false,
				content: 'url:../ajaxprocess/viewStudent.php?matricNo=' + matricNo,
				buttons: {
					ok:{        
						isHidden: true, // hide the button       
					},
					 close: function () {
        			}
				}
				// onContentReady: function () {
				// 	/*var self = this;
				// 	this.setContentPrepend('<div>Prepended text</div>');
				// 	setTimeout(function () {
				// 		self.setContentAppend('<div>Appended text after 2 seconds</div>');
				// 	}, 2000);*/
				// },

			});
		}

		$( window ).scroll(function() {

			$(".stickyholder").css("visibility", "visible");

		});

		$(document).ready(function (){

			$('#email').css('border','1px solid transparent');
			$('#officePhone').css('border','1px solid transparent');
			$('#password1').css('border','1px solid transparent');
			$('#password2').css('border','1px solid transparent');
			$('#email').prop('readonly',true);
			$('#officePhone').prop('readonly',true);
			$('#password1').prop('readonly',true);
			$('#password2').prop('readonly',true);

			$('#changepic').click(function(){
				$('#file_picture').toggle();
				$('#submitpicture').toggle();
			});

			$('#hideUpload').click(function(){
				$('#file_picture').val('');
				$('#file_picture').toggle();
				$('#submitpicture').toggle();
			 
			});

			$('#edit').click(function(){
				$('#email').css('border','1px solid #ccc');
				$('#officePhone').css('border','1px solid #ccc');
				$('#password1').css('border','1px solid #ccc');
				$('#password2').css('border','1px solid #ccc');
				$('#email').prop('readonly',false);
				$('#email').css('background-color','white');
				$('#officePhone').prop('readonly',false);
				$('#officePhone').css('background-color','white');
				$('#password1').prop('readonly', false);
				$('#password1').css('background-color','white');
				$('#password2').prop('readonly', false);
				$('#password2').css('background-color','white');
				$('#cancelEdit').show();
				$('#edit').hide();
				$('#update').css('visibility', 'visible');
			});

			$('#cancelEdit').click(function(){
				$('#cancelEdit').hide();
				$('#edit').show();
				$('#update').css('visibility', 'hidden');
				$('#email').val("<?php echo $rowRekodPensyarah['email']?>");
				$('#officePhone').val("<?php echo $rowRekodPensyarah['officePhone']?>");
				$('#password1').val("<?php echo $rowRekodPensyarah['password']?>");
				$('#password2').val("<?php echo $rowRekodPensyarah['password']?>");
				$('#email').css('border','1px solid transparent');
				$('#email').css('background-color','transparent');
				$('#officePhone').css('border','1px solid transparent');
				$('#officePhone').css('background-color','transparent');
				$('#password1').css('border','1px solid transparent');
				$('#password1').css('background-color','transparent');
				$('#password2').css('border','1px solid transparent');
				$('#password2').css('background-color','transparent');
				$('#email').prop('readonly',true);
				$('#officePhone').prop('readonly',true);
				$('#password1').prop('readonly',true);
				$('#pas').css('background-color','transparent');
				$('#password2').prop('readonly',true);
			});

			$('#password1').on('blur', function(){
				var password1 = $('#password1').val();
				if(password1.length < 8 || password1.length > 100){
					$.confirm({
						boxWidth: '27%',
					    title: '',
					    content: 'Invalid length of password! Password must be between 8 to 100 characters only.',
					    type: 'red',
					    typeAnimated: true,
					    useBootstrap: false,
					    buttons: {
					        tryAgain: {
					            text: 'Try again',
					            btnClass: 'btn-red',
					            action: function(){
					            	$('#password1').val("");
					            	$('#password1').focus();									
					            }
					        }
					    }
					});
					$('#update').prop('disabled', true);					
					$('#update').css({ 'color': 'white', 'background-color': 'gray' });
				}else{
					$('#update').prop('disabled', false);
			        $('#update').css({ 'color': '#fff', 'background-color': 'rgba(117, 20, 117, 0.6)' });
				}
			});


			$('#password2').on('blur', function(){
				var password1 = $('#password1').val();
				var pass2 = $('#password2').val();

				
				if(pass2!=password1){
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
									$('#password1').focus();
								}
							}
						}
					});
					$('#update').prop('disabled', true);					
					$('#update').css({ 'color': 'white', 'background-color': 'gray' });
				}else{
					$('#update').prop('disabled', false);
			        $('#update').css({ 'color': '#fff', 'background-color': 'rgba(117, 20, 117, 0.6)' });
				}

			});

			$('#email').on('blur', function(){
				var email = $('#email').val();
				if (email == '') {
					$.confirm({
						boxWidth: '27%',
						title: 'Email is empty',
						content: 'Please fill in all fields',
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
					$('#update').prop('disabled', true);					
					$('#update').css({ 'color': 'white', 'background-color': 'gray' });
				}else if (/\s/.test(email)) {
					
					$.confirm({
						boxWidth: '27%',
						title: 'Email contains whitespaces',
						content: 'Email shall not have any whitespaces. Please try again',
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
					$('#update').prop('disabled', true);					
					$('#update').css({ 'color': 'white', 'background-color': 'gray' });
				}else{
					$('#update').prop('disabled', false);
			        $('#update').css({ 'color': '#fff', 'background-color': 'rgba(117, 20, 117, 0.6)' });
				}
			});

			$('#officePhone').on('blur', function(){
				var officePhone = $('#officePhone').val();
				if (officePhone == '') {
					$.confirm({
						boxWidth: '27%',
						title: 'Office Phone is empty',
						content: 'Please fill in all fields',
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
					$('#update').prop('disabled', true);					
					$('#update').css({ 'color': 'white', 'background-color': 'gray' });
				}else if (officePhone.length < 10 || officePhone.length > 10) {
					
					$.confirm({
						boxWidth: '27%',
						title: 'Invalid office number',
						content: 'Office number must be of 10 digits',
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
					$('#update').prop('disabled', true);					
					$('#update').css({ 'color': 'white', 'background-color': 'gray' });
				}else{
					$('#update').prop('disabled', false);
			        $('#update').css({ 'color': '#fff', 'background-color': 'rgba(117, 20, 117, 0.6)' });
				}
			});

			function succeed(){
				window.location.replace("profile.php");	
			}

		});

		/*function tukarpass() //alert default password
		{
			$.confirm({
				
				boxWidth: '27%',
	  			title: 'Warning',
	  			icon: 'fa fa-warning',
			    content: 'Default password has not been updated yet. Please update your password immediately.',
			    type: 'orange',
			    typeAnimated: true,
			    useBootstrap: false,
			    buttons: {
			       ok: {
		        	boxWidth: '27%',
		            text: 'Okay',
		            btnClass: 'btn-orange',		            
		            action: function(){
		            	
		            }
		        }
			    }
			});
			return false;
		}*/

		function update(){
			var username = $('#hiddenusername').val();
			var email = $('#email').val(); 
			var phone = $('#officePhone').val();
			var password = $('#password1').val();
			$.ajax({

				url: "profile.php",
				type: "POST",
				cache: false,
				data:{
					updatecoordinator_check : 1,
					lecturerID: username,
					email: email,
					officePhone: phone,
					password: password,
				},
				success: function(response){
					if (response == 'updated' ) {
						$.confirm({
							boxWidth: '27%',
							title: 'Successfully updated',
							content: '',
							type: 'green',
							typeAnimated: true,
							useBootstrap: false,
							buttons: {
								tryAgain: {
									text: 'Okay',
									btnClass: 'btn-green',
									action: function(){
										location.reload();
									}
								}
							}
						});
						
					}else if (response == 'notupdated') {
						$.confirm({
							boxWidth: '27%',
							title: 'Failed to update',
							content: '',
							type: 'red',
							typeAnimated: true,
							useBootstrap: false,
							buttons: {
								tryAgain: {
									text: 'Okay',
									btnClass: 'btn-red',
									action: function(){
										location.reload();
									}
								}
							}
						});
						
					}
				}
			});
		}

		function validatePic(){

			if(document.getElementById("file_picture").files.length == 0 ){
			    $.confirm({
					boxWidth: '27%',
				    title: "",
				    content: 'Please upload your picture to continue.',
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

		function showpass(){
			var showpass1 = document.getElementById('password1');
			var showpass2 = document.getElementById('password2');

			if(showpass1.type == "password")
			{
				showpass1.type = "text";
				showpass2.type = "text";
			}
			else
			{
				showpass1.type = "password";
				showpass2.type = "password";
			}
		}

		function validateUpdate(){
			var email = $('#email').val();
			var phone = $('#officePhone').val();
			var password = $('#password1').val();


			if(email.length < 3 || email.length > 200){

				$.confirm({
					boxWidth: '27%',
				    title: 'Invalid length of email!',
				    content: 'Email must be between 3 to 200 characters only.',
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
			}else if(phone.length < 10 || phone.length > 11){
				$.confirm({
					boxWidth: '27%',
				    title: 'Invalid office phone!',
				    content: 'Phone must be between 10 to 11 digits only.',
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
				$.confirm({
					boxWidth: '27%',
					typeAnimated: true,
				    useBootstrap: false,
				    autoClose: 'cancel|15000',
				    title: 'Are you sure to update?',
				    content: '' +
				    '<form action="" class="formName" id="formdialog">' +
				    '<div class="form-group">' +
				    '<label>Enter your current password to continue</label>' +
				    '<input style="padding-top: 10px; padding-bottom: 10px" type="password" placeholder="current password" class="name form-control" required />' +
				    '</div>' +
				    '</form>',
				    buttons: {
				        formSubmit: {
				            text: 'Submit',
				            btnClass: 'btn-blue',
				            action: function () {
				                var currentpass = this.$content.find('.name').val();
				                var passform = $('#hiddenpw').val();
				                if(currentpass != passform){
				                    $.confirm({
										boxWidth: '27%',
									    title: 'Invalid password!',
									    content: 'Password entered does not match with your current password.',
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
				                }else{
				                	update();
				                }
				                
				            }
				        },
				        cancel: function () {
				        },
				    },
				    onContentReady: function () {
				        // bind to events
				        var jc = this;
				        this.$content.find('#formdialog').on('submit', function (e) {
				            // if the user submits the form by pressing enter in the field.
				            e.preventDefault();
				            jc.$$formSubmit.trigger('click'); // reference the button and click it
				        });
				    }
				});
			}
		}

	</script>
	<script type="text/javascript">
		function invalid(){

			$.confirm({
				boxWidth: '27%',
				title: '',
				content: 'Sorry, there was a problem to upload your picture. Please try again',
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
		}

		function pictTooBig(){
			$.confirm({
				boxWidth: '27%',
				title: '',
				content: 'Picture is too big. Please use another picture. Maximum size is 10MB',
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
		}

		function pictNotFound(){
			$.confirm({
				boxWidth: '27%',
				title: '',
				content: 'Picture is not found. Please try again.',
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
		}
	</script>
</head>
<?php
if($rowRekodPensyarah['password'] == "pass1234")
{
	?>
	<!-- <body onload="return tukarpass();"> -->
	<?php
}
else
{
	?>
	<body>
	<?php
}
?>
	<div class="outerbox" id="top">
		<div class="innerbox">
			<header >
				<a href="index.php"  class="logo">Projek Diploma Supervision and Management System</a>
				<nav>
					<ul>
						<li>
							<a title="home" href="../Coordinator"><i style="font-size: 16px;" class="fa fa-home" aria-hidden="true"></i></a>
						</li>
						<li>
							<a href="supervision.php" title="supervision" title="">
								<i style="font-size: 16px" class="fa fa-folder-open" aria-hidden="true"></i>
							</a>
						</li>
						<li>
							<a href="forum-milestones.php" title="forum and milestones" title="">
								<i style="font-size: 16px" class="fa fa-calendar-check-o" aria-hidden="true"></i>
							</a>
						</li>
						<li>
							<a title="user management" href="user-management.php"><i style="font-size: 16px;" class="fa fa-users" aria-hidden="true"></i></a>
						</li>
						<li>
							<a href="report.php" target="_blank" title="report">
								<i style="font-size: 16px;" class="fa fa-pie-chart" aria-hidden="true"></i>
							</a>
						</li>
						<li>
							<?php 
							if(empty($rowRekodPensyarah['picture']))
							{
								?>
								<a title="profile" href="profile.php" style="text-decoration-style: none; text-decoration: none; padding-right: 20px ; padding-bottom: 0; padding-top: 0;  padding-left: 0px">
									<div class="profile-picnohover" style="background-image: url('../attachment/imgsource/lecturer1.png');" >
									</div>
								</a>
								<?php
							}
							else
							{
								?>
								<a title="profile" href="profile.php" style="text-decoration-style: none; text-decoration: none; padding-right: 20px ; padding-bottom: 0; padding-top: 0;  padding-left: 0px">
									<div class="profile-picnohover" style="background-image: url('../attachment/profile/<?php echo $rowRekodPensyarah['picture']?>');" >
									</div>
								</a>
								<?php
							}
							?>
						</li>
						<li>
							<a title="sign out" href="../sign-out.php"><i style="font-size: 16px;" class="fa fa-sign-out" aria-hidden="true"></i></a>
						</li>
					</ul>
				</nav>
				<div class="clearfix"></div>
			</header>

			<div class="profailholder">
				<a class="changePicture" id="changepic" aria-label="Change Profile Picture">
				  	<?php
				  	if(empty($rowRekodPensyarah['picture']))
				  	{
				  		?>
				  		<div class="profile-pic" style="background-image: url('../attachment/imgsource/lecturer1.png');" >
				  		<?php 
				  	}
				  	else
				  	{
				  		?>
				  		<div class="profile-pic" style="background-image: url('../attachment/profile/<?php echo $rowRekodPensyarah['picture']; ?>');" >
				  		<?php 
				  	}
				  	?>  	
				  		<span id="changespan">Change</span>
				    </div>

				</a>
				<form name="formpicture" onsubmit="return validatePic()" action="" id="formpicture" method="POST" enctype="multipart/form-data">
					<div style="display: inline-flex; margin-left: 15%">
						<input accept="image/jpeg, image/png, image/jpg" type="file" id="file_picture" name="file_picture" style="margin-left: 15%; margin-top: 8px; display: none; ">
						<input type="submit" id="submitpicture" name="submitpicture" class="submit" value="Save" style="cursor: pointer; display: none; width: 18%; " >
					</div>
					
				</form>	
			</div><p id="edit" class="hide" style="width: 19%;">edit</p><p id="cancelEdit" class="hide" style="display: none; width: 19%;">cancel</p>

			
			<div class="informationHolder" align="center">
				INFORMATION
			</div>
			<div class="information">
				<table align="center" width="90%">
					<tr>
						<td style="width: 37%">
							STAFF ID
						</td>
						<td>
							<?php echo $id_pensyarah; ?>
						</td>
					</tr>
					<tr>
						<td style="width: 37%">
							NAME
						</td>
						<td>
							<?php echo $rowRekodPensyarah['name']; ?>
						</td>
					</tr>
					<tr>
						<td style="width: 37%">
							E-MAIL
						</td>
						<td>
							<input type="email" id="email" style="background-color: transparent;" name="email" value="<?php echo $rowRekodPensyarah['email']?>">
						</td>
					</tr>
					<tr>
						<td style="width: 37%">
							OFFICE PHONE
						</td>
						<td>
							<input type="number" id="officePhone" style="background-color: transparent;" name="officePhone" value="<?php echo $rowRekodPensyarah['officePhone']; ?>">
						</td>
					</tr>

				</table>
			</div>

			<div class="informationHolder" align="center" style="margin-top: 20px">
				CHANGE PASSWORD
			</div>
			<div class="information">
				<table align="center" width="90%">
					<tr>
						<td style="width: 37%">
							PASSWORD
						</td>
						<td>
							<input id="password1" style="background-color: transparent;" type="password" name="password1" value="<?php echo $rowRekodPensyarah['password']; ?>" >
							<span><i onclick="showpass()" style="margin-left: 4px; cursor: pointer;" class="fa fa-eye" aria-hidden="true"></i></span>
						</td>
					</tr>
					<tr>
						<td style="width: 36%">
							CONFIRM PASSWORD
						</td>
						<td>
							<input id="password2" style="background-color: transparent;" type="password" name="password2" value="<?php echo $rowRekodPensyarah['password']; ?>">
						</td>
					</tr>
				</table>
			</div>
			<center>
				<input onclick="return validateUpdate()" type="submit" name="update" id="update" value="Save" class="submit" style="visibility: hidden;">
				<input type="hidden" name="hiddenusername" id="hiddenusername" value="<?php echo $id_pensyarah ?>"><input type="hidden" name="hiddenpw" id="hiddenpw" value="<?php echo $rowRekodPensyarah['password'] ?>">
				</center>

			<div class="informationHolder" style="margin-top: 15px;">
				SUPERVISED STUDENT (<?php echo $resultAnakSeliaan->num_rows ?>)
			</div>
			<div class="maklumat">
				<?php
				if($resultAnakSeliaan->num_rows == 0)
				{
					?>
					<i style="color: gray; text-align: center;">0 record of supervised student found</i>
					<?php
				}
				else{
					$count = 0;
						while($rowAnakSeliaan = $resultAnakSeliaan->fetch_assoc()){
							$count += 1;
							?>
							<div class="list-box">
								<?php
									
								if(empty($rowAnakSeliaan['picture']))
								{ ?>
									<div align="center" class="list-profile-pic" style="background-image: url('../attachment/imgsource/studenticon.png'); margin-left: 40%" >
									</div>									
									<?php								
								}
								else
								{
									?>
									<div align="center" class="list-profile-pic" style="background-image: url('../attachment/profile/<?php echo $rowAnakSeliaan['picture'] ?>'); margin-left: 40%" >
									</div>									
									<?php
								}
								?>
								<table>
									<tr>
										<td style="text-align: center;" colspan="2">
											<?php

											echo $rowAnakSeliaan['name'];

											?>
										</td>
									</tr>
									<tr>
										<td width="40%">
											MATRIC NO
										</td>
										<td>
											<a title="view <?php echo strtolower($rowAnakSeliaan['name']) ?>" onclick="viewStudent(this,'<?php echo $rowAnakSeliaan["name"] ?>')" id="<?php echo $rowAnakSeliaan['matricNo'] ?>"><?php echo $rowAnakSeliaan['matricNo'] ?></a>
										</td>
									</tr>
									<tr>
										<td>
											PROJECT TITLE
										</td>
										<td>
											<?php
											if(empty($rowAnakSeliaan['projectTitle'])){
												echo '<i>-</i>';
											}else
												echo '<i>' . $rowAnakSeliaan['projectTitle'] . '</i>';

											?>
										</td>
									</tr>
								</table>
							</div>
					
					<br><br>
			<?php } } ?>
			</div>
		</div>
	</div>
	<?php

	include('../footer.php');
	?>

	<div class="stickyholder" style="visibility: hidden;">
		<a class="sticky" href="#top">
			<i class="fa fa-arrow-circle-o-up" aria-hidden="true"></i>
		</a>
	</div>

</body>
</html>