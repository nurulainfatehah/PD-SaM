<?php
include('../inc/dbconnect.php');
session_start();
$lecturerID = $_SESSION['username'];

$sqlGuideline = "SELECT * FROM guideline";
$resultGuideline = $conn->query($sqlGuideline);

$sqlLecturerRecord = "SELECT * FROM lecturer WHERE lecturerID = '".$lecturerID."'";
$resultLecturerRecord = $conn->query($sqlLecturerRecord);
$rowInfo = $resultLecturerRecord->fetch_assoc();

$sqlTotalSupervision = "SELECT * FROM student WHERE supervisorID = '".$lecturerID."'";
$resultTotalSupervision = $conn->query($sqlTotalSupervision);

$sqlTotalEvaluator = "SELECT * FROM student WHERE evaluatorID = '".$lecturerID."'";
$resultEvaluator = $conn->query($sqlTotalEvaluator);


$sqlToEvaluateSV = "SELECT * FROM student WHERE supervisorID = '".$lecturerID."'";
$resultToEvaluateSV = $conn->query($sqlToEvaluateSV);

$sqlToEvaluateEV = "SELECT * FROM student WHERE evaluatorID = '".$lecturerID."'";
$resultToEvaluateEV = $conn->query($sqlToEvaluateEV);

$totalToEvaluate = $resultToEvaluateSV->num_rows + $resultToEvaluateEV->num_rows;
?>


<!DOCTYPE html>
<html>
<head>
	<link rel="icon" type="image/png" href="../attachment/bg/logo.png"/>
	<title>PD-SaM - <?php echo ucfirst($_SESSION['username']) ?></title>
	<link rel="stylesheet" type="text/css" href="../css/header.css">
	<link rel="stylesheet" type="text/css" href="../css/dashboard.css">
	<link rel="stylesheet" type="text/css" href="../css/view-profile-popup.css">
	<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css">
	<script type="text/javascript" src="//code.jquery.com/jquery-1.9.1.js"></script>
	<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/jquery-confirm/3.3.2/jquery-confirm.min.css">
	<script src="https://cdnjs.cloudflare.com/ajax/libs/jquery-confirm/3.3.2/jquery-confirm.min.js"></script>
	<script type="text/javascript">

		function approveProposal()
		{
			var keputusan = confirm("Confirm to approve this proposal? This cannot be undone.");

			if(keputusan == true)
			{
				return true;
			}
			else
			{
				return false;
			}
		}

		function rejectProposal()
		{
			var keputusan = confirm("Confirm to reject this proposal? This cannot be undone.");

			if(keputusan == true)
			{
				return true;
			}
			else
			{
				return false;
			}
		}

		function confirmGuideline()
		{
			var title = formGuideline.title.value;

			if(title.length < 5 || title.length > 35)
			{
				$.confirm({
					boxWidth: '27%',
					title: 'Invalid length of title',
					content: 'Title shall be between 5 to 35 characters only. Please try again.',
					type: 'red',
					typeAnimated: true,
					useBootstrap: false,
					buttons: {
						tryAgain: {
							text: 'Try again',
							btnClass: 'btn-red',
							action: function(){
								$('#nama_panduan').focus();
							}
						}
					}
				});
				return false;
			}else if(document.getElementById("attachment").files.length == 0 ){
				$.confirm({
					boxWidth: '27%',
					title: 'Missing attachment',
					content: 'Please upload the attachment to continue.',
					type: 'red',
					typeAnimated: true,
					useBootstrap: false,
					buttons: {
						tryAgain: {
							text: 'Try again',
							btnClass: 'btn-red',
							action: function(){
								$('#attachment').focus();
							}
						}
					}
				});
				return false;
			}else
			{
				return true;
			}
		}


		$( window ).scroll(function() {

			$(".stickyholder").css("visibility", "visible");

		});


		$(document).ready(function() {
			$('#tekanRujukan').click(function(){
				if($('#rujukan').is(":visible")){
					$('#rujukan').hide();
				}else
					$('#rujukan').show();
			});



			$('#hiderujukan').click(function(){
				$('#rujukan').hide();
				$('#tambahpanduanbaru').hide();

				
			});

			$('#tekanSeliaan').click(function(){
				if($('#seliaan').is(":visible")){
					$('#seliaan').hide();
				}else
					$('#seliaan').show();
			});

			$('#hideseliaan').click(function(){
				$('#seliaan').hide();
			});


			$('#tekanPenilaian').click(function(){
				if($('#penilaian').is(":visible")){
					$('#penilaian').hide();
				}else
					$('#penilaian').show();
			});

			$('#hidePenilaian').click(function(){
				$('#penilaian').hide();
			});


			$('#tekantambahpanduan').click(function(){
				if($('#tambahpanduanbaru').css('display') == 'none')
				{
					$('#tambahpanduanbaru').show();
					$( '#nama_panduan').focus();
					$('#canceltambahpanduan').show();
					$('#tambahpanduan').hide();
				}
				else
				{
					$('#tambahpanduanbaru').hide();
					$('#canceltambahpanduan').hide();
					$('#tambahpanduan').show();

				}
				
			});

			$('#click-evaluation').click(function(){
				if($('#evaluation').is(":visible")){
					$('#evaluation').hide();
				}else
					$('#evaluation').show();
			});

			$('#hideevaluation').click(function(){
				$('#evaluation').hide();
			});
			


			$('#tekanBorang').click(function(){
				if($('#borangs').is(":visible")){
					$('#borangs').hide();
				}else
					$('#borangs').show();
			});


			$('#hideBorang').click(function(){
				$('#borangs').hide();
			});


		});
			
	</script>
	<style type="text/css">
		.profile-pic 
		{
			border-radius: 50%;
			height: 140px;
			width: 140px;
			background-size: cover;
			background-position: center;
			background-blend-mode: multiply;
			color: transparent;
			transition: all .3s ease;

		}
		.uploadrule
		{
			font-size: 9px;
			color: red;
			display: inline-block;
		}

		.info-supervision-list
		{
			display: inline-flex;
		    width: 88%;
		    margin-left: 5%;
		    margin-right: auto;
		    height: auto;
		    margin-top: 0;
		    padding: 5px 10px 5px 10px;
		    flex-wrap: wrap;
		    align-content: center;
		    justify-content: center;
		}

		.list-box{
			
		    width: 35%;
		    height: 90px;
		    border: 1px solid grey;
		    border-radius: 25px;
		    height: auto;
		    margin-right: 30px;
		    margin-top: 20px;
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
	<script type="text/javascript">

		function deleteGuideline(obj, title){
			var id = obj.id;
			var title = title;
			$.confirm({
				boxWidth: '27%',
				title: 'Delete ' + title + '?',
				content: 'Are you sure to delete '+ title +'? This cannot be undone.',
				type: 'red',
				typeAnimated: true,
				useBootstrap: false,
				autoClose: 'cancel|10000',
				buttons: {
					confirm: {
						text: 'Delete',
						btnClass: 'btn-red',
						action: function(){
							$.ajax({

								url: "upload-delete-guideline.php",
								type: "POST",
								cache: false,
								data:{
									deleteguideline_check : 1,
									guidelineID: id
								},
								success: function(response){
									if (response == 'deleted' ) {
										$.confirm({
											boxWidth: '27%',
											title: 'Successfully deleted',
											content: '',
											type: 'green',
											typeAnimated: true,
											useBootstrap: false,
											buttons: {
												tryAgain: {
													text: 'Okay',
													btnClass: 'btn-green',
													action: function(){
														window.location.replace('?reload=guideline');
													}
												}
											}
										});
										
									}else if (response == 'notdeleted') {
										$.confirm({
											boxWidth: '27%',
											title: 'Failed to delete guideline',
											content: '',
											type: 'red',
											typeAnimated: true,
											useBootstrap: false,
											buttons: {
												tryAgain: {
													text: 'Okay',
													btnClass: 'btn-red',
													action: function(){
														window.location.replace('?reload=guideline');
													}
												}
											}
										});
										
									}
								}
							});

						}			        	
					},
					cancel: {
						text: 'Cancel',
						btnClass: 'btn-default',
						action: function(){
						}
					}
				}
			});
		}
	</script>
</head>
<script type="text/javascript">

	function invalid(){

		$.confirm({
			boxWidth: '27%',
			title: 'Failed to upload guideline',
			content: 'Sorry, there was a problem to upload guideline. Please try again',
			type: 'red',
			typeAnimated: true,
			useBootstrap: false,
			buttons: {
				tryAgain: {
					text: 'Try again',
					btnClass: 'btn-red',
					action: function(){
						window.location.replace('../Coordinator?reload=guideline');
					}
				}
			}
		});		
	}
	function tooBig(){
		$.confirm({
			boxWidth: '27%',
			title: 'Attachment is too big',
			content: 'Please compress the file. Maximum size is 10MB',
			type: 'red',
			typeAnimated: true,
			useBootstrap: false,
			buttons: {
				tryAgain: {
					text: 'Try again',
					btnClass: 'btn-red',
					action: function(){
						window.location.replace('../Coordinator?reload=guideline');
					}
				}
			}
		});
	}
	function notFound(){
		$.confirm({
			boxWidth: '27%',
			title: 'Attachment cannot be found',
			content: 'Selected attachment does not exist. Please try again',
			type: 'red',
			typeAnimated: true,
			useBootstrap: false,
			buttons: {
				tryAgain: {
					text: 'Try again',
					btnClass: 'btn-red',
					action: function(){
						window.location.replace('../Coordinator?reload=guideline');
					}
				}
			}
		});
	}

	function succeed(){
		$.confirm({
			boxWidth: '27%',
  			title: 'Guideline was successfully uploaded',
		    content: '',
		    type: 'green',
		    typeAnimated: true,
		    useBootstrap: false,
		    buttons: {
		        ok: {
		        	boxWidth: '27%',
		            text: 'Okay',
		            btnClass: 'btn-green',		            
		            action: function(){
		            	window.location.replace('../Coordinator?reload=guideline');
		            }
		        }
		    }
		});
	}
</script>
<?php
if(isset($_POST['submitGuideline'])){

	$uploadDateTime = date('Y-m-d H:i:s', time()+28800);
	$title = $_POST['title'];


	$file = $_FILES["attachment"];
	$fileName = $file['name'];
	$fileTmpName = $file['tmp_name'];
	$fileSize = $file['name'];
	$fileError = $file['error'];
	$fileExt = explode('.', $fileName);
	$fileActualExt = strtolower(end($fileExt));

	if($fileError == 0) // HAS NO PROBLEM 
	{
		if($fileSize < 10000) // DOES NOT EXCEED 10MB
		{
			$fileNameNew = uniqid('', true) . "_" . $fileName;
			$fileDestination = '../attachment/guideline/'.$fileNameNew;
					move_uploaded_file($fileTmpName, $fileDestination);

			
			$sqlUploadGuideline = "INSERT INTO guideline (title, attachment, uploadDateTime, uploadedBy) VALUES ('".$title."', '".$fileNameNew."', '".$uploadDateTime."', '".$lecturerID."')";

			if($conn->query($sqlUploadGuideline) === TRUE)
			{
				?>
				<body onload="return succeed();"></body>
				<?php 
			}
			else
			{
				?>
				<body onload="invalid();"></body>
				<?php
			}
		}
		else
		{
			?>
			<body onload="tooBig();"></body>
			<?php 
		}
	}
	else
	{
		?>
		<body onload="notFound();"></body>
		<?php
	}
}
?>
<body>
	<div class="outerbox" id="top">
		<div class="innerbox">
			<header >
				<a href="../Coordinator"  class="logo">Projek Diploma Supervision and Management System</a>
				<nav>
					<ul>
						<li>
							<a title="home" href="../Coordinator" class="activebar"><i style="font-size: 16px;" class="fa fa-home" aria-hidden="true"></i></a>
						</li>
						<li>
							<a href="documentation.php" title="documentation" >
								<i style="font-size: 16px" class="fa fa-folder-open" aria-hidden="true"></i>
							</a>
						</li>
						<li>
							<a href="milestones.php" title="milestones" >
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
							if(empty($rowInfo['picture']))
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
									<div class="profile-picnohover" style="background-image: url('../attachment/profile/<?php echo $rowInfo['picture']?>');" >
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

			<div class="dashboard" align="center">
				<a id="tekanRujukan" href="#rujukan" style="text-decoration: none;text-decoration-style: none; text-decoration-color: none; color: inherit;">
					<div class="kotakKategori">
						<div class="gambarholder">
							<img id="gambarKategori" src="../attachment/imgsource/book.png" width="70px" height="70px">
						</div>
						<div class="ayatkategori">
							<p class="titleKategori">
								Guideline
							</p>
							
								<?php 
								if($resultGuideline->num_rows == 0 )
								{
									echo "<p class='detailKategori' style='color:gray;'>0</i>";
								}
								else{

									echo "<p class='detailKategori'>" .$resultGuideline->num_rows;
								}
								 ?>
							</p>
						</div>
					</div>
				</a>
				

				<a target="_blank" href="chat-room.php" style="text-decoration: none;text-decoration-style: none; text-decoration-color: none; color: inherit;">
					<div class="kotakKategori">
						<div class="gambarholder">
							<img id="gambarKategori" src="../attachment/imgsource/chat.png" width="70px" height="70px">	
						</div>
						<div class="ayatkategori">
							<p class="titleKategori">
								Chatroom
							</p>
							<?php

							?>
							<p class="detailKategori" style="">
								<?php $totalChatroom = $resultTotalSupervision->num_rows + 2; echo $totalChatroom ?>
							</p>
						</div>
					</div>
				</a>

				<a id="tekanSeliaan" href="#seliaan" style="text-decoration: none;text-decoration-style: none; text-decoration-color: none; color: inherit;">
					<div class="kotakKategori">
						<div class="gambarholder" >
							<img id="gambarKategori" src="../attachment/imgsource/student.png" width="70px" height="70px">
						</div>
						<div class="ayatkategori">
							<p class="titleKategori">
								Supervised Student
							</p>
							
								<?php
								if($resultTotalSupervision->num_rows == 0)
								{
									?>
									<p class="detailKategori" style=" color: gray; margin-top: 0">0
									<?php
								}
								else
								{
									?>
									<p class="detailKategori" style="margin-top: 0">
									<?php echo $resultTotalSupervision->num_rows;
								}

								?>
							</p>
						</div>
					</div>
				</a>
				<br>

				<a id="click-evaluation" href="#evaluation" style="text-decoration: none;text-decoration-style: none; text-decoration-color: none; color: inherit;">
					<div class="kotakKategori" >
						<div class="gambarholder" >
							<img id="gambarKategori" src="../attachment/imgsource/reference.png" width="70px" height="70px">
						</div>
						<div class="ayatkategori">
							<p class="titleKategori">
								PD Assessment
							</p>
							
								<?php
								if($totalToEvaluate== 0)
								{
									?>
									<p class="detailKategori" style=" color: gray; margin-top: 0">0
									<?php
								}
								else
								{
									?>
									<p class="detailKategori" >
									<?php echo $totalToEvaluate;
								}

								?>
							</p>
						</div>
					</div>

				</a>

				<a id="tekanPenilaian" href="#penilaian" style="text-decoration: none;text-decoration-style: none; text-decoration-color: none; color: inherit;">
					<div class="kotakKategori" >
						<div class="gambarholder" >
							<img id="gambarKategori" src="../attachment/imgsource/query.png" width="70px" height="70px">
						</div>
						<div class="ayatkategori">
							<p class="titleKategori">
								Queries & Forum
							</p>
							
								<?php
								if($resultTotalSupervision->num_rows == 0 && $resultEvaluator->num_rows == 0)
								{
									?>
									<p class="detailKategori" style=" color: gray">0
									<?php
								}
								else
								{
									?>
									<p class="detailKategori">
									<?php echo $resultTotalSupervision->num_rows + $resultEvaluator->num_rows;
								}

								?>
							</p>
						</div>
					</div>

				</a>
				<a id="tekanBorang" href="#borangs" style="text-decoration: none;text-decoration-style: none; text-decoration-color: none; color: inherit;">
					<div class="kotakKategori" >
						<div class="gambarholder" >
							<img id="gambarKategori" src="../attachment/imgsource/file-list.png" width="70px" height="70px">
						</div>
						<div class="ayatkategori">
							<p class="titleKategori">
								Borang Penilaian
							</p>
								
								<?php
								$sqlSubmitted = "SELECT COUNT(*) as totalSubmitted FROM student WHERE finalGrade != ''";
								$resultSubmitted = $conn->query($sqlSubmitted);
								$rowSubmitted = $resultSubmitted->fetch_assoc();

								if($rowSubmitted['totalSubmitted'] == '0')
								{
									?>
									<p class="detailKategori" style="color: gray">0
									<?php
								}
								else
								{
									?>
									<p class="detailKategori">
									<?php echo $rowSubmitted['totalSubmitted'];
								}

								?>
							</p>
						</div>
					</div>
				</a>
			</div>

			<?php if(isset($_SESSION['openDiv']) && $_SESSION['openDiv'] == "guideline" ){

				?> <div id="rujukan" class="sectionkategori" > <?php
				unset($_SESSION['openDiv']);
				}else{
					?>
					<div id="rujukan" class="sectionkategori" style="display:none; ">
					<?php
				} ?>
			
				<div class="titleTextHolder">
					GUIDELINE
				</div>
				<hr>
				<p id="hiderujukan" class="hide">hide</p>
				<div class="kontenkategori" >

					<a id="tekantambahpanduan" href="#tambahpanduanbaru">
						<i id="tambahpanduan" title="upload new" class="fa fa-plus-circle" aria-hidden="true"></i>
						<i id="canceltambahpanduan" title="cancel" class="fa fa-ban" aria-hidden="true" style="display: none;"></i>
					</a><br>
					
					

					<div id="tambahpanduanbaru" style="display: none;" align="center">
						<div class="informationHolder" align="center" style="width: 70%">
							UPLOAD GUIDELINE
						</div>
						<form name="formGuideline" onsubmit="return confirmGuideline()" enctype="multipart/form-data" method="post">
							<table align="center" style="width:70%; border-spacing: 5px;">
								<tr>
									<td style="width: 30%;">
										TITLE
									</td>
									<td>
										<input id="nama_panduan" type="text" max="35"  name="title"><span class="asterisk_input"></span>
									</td>
								</tr>
								<tr>
									<td>
										ATTACHMENT
									</td>
									<td style="padding-top: 5px; padding-bottom: 5px;">
										<input type="file" id="attachment" name="attachment" accept="image/jpeg, image/png, image/jpg, application/pdf" ><span class="asterisk_input"></span>
									</td>
								</tr>

							</table>
							<p class="uploadrule" align="center" style="display: contents;" align="center"><strong>Note:</strong> Only format of .pdf, .png, .jpeg, .jpg allowed, maximum size is 10MB.</p>
							<input style="" type="submit" name="submitGuideline" class="submit" value="Upload">
						</form>		
						<hr>
					</div>

					<div style="display: inline; width: 100%; padding-top: 20px;" >
						<?php
						if($resultGuideline->num_rows == 0){
							?>
							<center><i class="remarksnote">0 record of guideline was found</i></center>
							<?php
						}else{
							?>
							<div style="width:50%; margin-left:auto; margin-right: auto; margin-bottom: 10px;">
								<select style="width: 40%;" id="selectOptionGuideline">
									<option value="titleAZ">Title A-Z</option>
									<option selected value="titleZA">Title Z-A</option>
								</select>
								<input style="width: 40%;" placeholder="Search" type="text" name="searchGuideline" id="searchGuideline">
							</div>
							
							<div id="table_guideline">
								
							</div>
							<?php
						}
						?>
					</div>


				</div>
			</div>

			<div id="seliaan" class="sectionkategori" style="display: none">
				<div class="titleTextHolder">
					SUPERVISED STUDENT
				</div>
				<hr>
				<p id="hideseliaan" class="hide">hide</p>
				<div class="kontenkategori" style="padding-top: 15px;">
					<?php
					if($resultTotalSupervision->num_rows == 0)
					{
						?>
						<div align="center"><i class="remarksnote">No record of supervised student found</i></div>
						
						<?php
					}
					else{
						?>
						<a target="_blank" href="printPelajarSeliaan.php?lecturerID=<?php echo $lecturerID ?>"style="text-decoration-style: none; color: inherit; text-decoration: none;">
							<button>Print</button>
						</a>
						<div class="info-supervision-list">
							<?php
							while($rowAnakSeliaan = $resultTotalSupervision->fetch_assoc()){
								
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
								<table >
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
			if(isset($_SESSION['openDiv']) && $_SESSION['openDiv'] == "evaluation")
			{
				?>
				<div id="evaluation" class="sectionkategori" style="">
				<?php unset($_SESSION['openDiv']);
			}
			else
			{
				?>
				<div id="evaluation" class="sectionkategori" style="display: none">
				<?php
			}
			?>
				<div class="titleTextHolder">
					PD ASSESSMENT
				</div>
				<hr>
				<?php
				?>		
				<p id="hide-evaluation" class="hide">hide</p>
				<div class="kontenkategori" align="center" style="padding-left: 0"><br><br>
					<?php
					if($totalToEvaluate == 0)
					{
						?>
						<center><i class="remarksnote">0 record of student that needs to be evaluated</i></center>
						<?php
					}
					else
					{
						if($resultToEvaluateSV->num_rows > 0){ ?>
							<b><p align="center">SUPERVISED STUDENT</p></b> <?php
							while($rowToEvaluateSV = $resultToEvaluateSV->fetch_assoc())
							{							
								?>	
								<a style="text-decoration: none; color: black" target="_blank" href="assessment.php?no_matrik=<?php echo $rowToEvaluateSV['no_matrik']?>">
									<div class="kotakKategori" style="align-items: center; width: 25%; margin-bottom: 30px; margin-top: 15px">
										<div class="gambarholder" style="margin-top: 0px; height: 80px; width:80px">
											<?php
											if(empty($rowToEvaluateSV['picture']))
											{
												?>
												<div class="profile-picnohover" style=" margin-left: 0%;height: 80px; width: 80px; background-image: url('../attachment/imgsource/studenticon.png');" >
												</div>
												<?php
											}
											else
											{
												?>
												<div class="profile-picnohover" style="margin-left: 0%;height: 80px; width: 80px;background-image: url('../attachment/profile/<?php echo $rowToEvaluateSV['picture']?>');" >
												</div>
												
												<?php
											}
											?>
											
										</div>
										<div class="ayatkategori" style="width: 70%;">
											<p class="titleKategori" style="width:100%">
												<b>Evaluation</b><br><br>
												<?php echo $rowToEvaluateSV['matricNo'];
												if(empty($rowToEvaluateSV['finalGrade']))
												{
													echo '<br><i style="color: grey">incomplete</i>';
												}else{
													echo '<br><i style="color: green">evaluated</i>';
												}
												?>
											</p>										
										</div>
									</div>
								</a>
								

								<?php
							}
						}

						if($resultToEvaluateEV->num_rows > 0){ ?>
							<b><p align="center">EVALUATED STUDENT</p></b> <?php
							while($rowToEvaluateEV = $resultToEvaluateEV->fetch_assoc())
							{							
								?>	
								<a style="text-decoration: none; color: black" target="_blank" href="assessment.php?matricNo=<?php echo $rowToEvaluateEV['matricNo']?>">
									<div class="kotakKategori" style="align-items: center; width: 25%; margin-bottom: 30px; margin-top: 15px">
										<div class="gambarholder" style="margin-top: 0px; height: 80px; width:80px">
											<?php
											if(empty($rowToEvaluateEV['picture']))
											{
												?>
												<div class="profile-picnohover" style=" margin-left: 0%;height: 80px; width: 80px; background-image: url('../attachment/imgsource/studenticon.png');" >
												</div>
												<?php
											}
											else
											{
												?>
												<div class="profile-picnohover" style="margin-left: 0%;height: 80px; width: 80px;background-image: url('../attachment/profile/<?php echo $rowToEvaluateEV['picture']?>');" >
												</div>
												
												<?php
											}
											?>
											
										</div>
										<div class="ayatkategori" style="width: 70%;">
											<p class="titleKategori" style="width:100%">
												<b>Evaluation</b><br><br>
												<?php echo $rowToEvaluateEV['matricNo'];
												if(empty($rowToEvaluateEV['finalGrade']))
												{
													echo '<br><i style="color: grey">incomplete</i>';
												}else{
													echo '<br><i style="color: green">evaluated</i>';
												}
												?>
											</p>										
										</div>
									</div>
								</a>
								

								<?php
							}
						}
												
					}
					?>
				</div>
			</div>

		<?php

		if(isset($_SESSION['openDiv']) &&  $_SESSION['openDiv'] == "Penilaian")
		{
			?>
			<div id="penilaian" class="sectionkategori" style="">
			<?php unset($_SESSION['openDiv']);
		}
		else
		{
			?>
			<div id="penilaian" class="sectionkategori" style="display: none">
			<?php
		}
		?>
				<div class="titleTextHolder">
					Queries and Forum
				</div>
				<hr>		
				<p id="hidePenilaian" class="hide">hide</p>
				<div class="kontenkategori" style="padding-left: 0" align="center">
					
				
					<!-- SECTION SV REQUEST/ QUERIES AND FORUM  -->
				</div>
			</div>

			<div id="borangs" class="sectionkategori" style="display: none; ">
				<div class="titleTextHolder">
					Borang Penilaian [Submitted by Supervisor]
				</div>
				<hr>		
				<p id="hideBorang" class="hide">hide</p>
				<a target="_blank" href="printKeputusan.php"style="text-decoration-style: none; color: inherit; text-decoration: none;"><button> Print</button></a>
				<div class="kontenkategori" style="padding-left: 0">
					<?php
					$sqlBorangs = "SELECT matricNo, name, fullMark, finalGrade, sectionGroup,  supervisorID, evaluatorID FROM student WHERE finalGrade != '' ORDER BY matricNo ASC";
					$resultBorangs = $conn->query($sqlBorangs);

					if($resultBorangs->num_rows == 0)
					{
						echo "<center><i class='remarksnote'>0 record of Borang Penilaian PD was submitted by supervisor" . "</i></center>";
					}
					else
					{
						?>
						<table  cellpadding="4" align="center" width="100%" style="margin-top: 20px">
							<tr>
								<th align="center" width="16%">
									Matric No
								</th>
								<th align="center">
									Student Name
								</th>
								<th align="center" width="10%">
									S_/G_
								</th>
								<th align="center" width="10%">
									Mark
								</th>
								<th align="center" width="10%">
									Grade
								</th>
								<th align="center" width="12%">
									Borang Penilaian
								</th>
							</tr>
							<?php
							while($rowBorangs = $resultBorangs->fetch_assoc())
							{
								?>
								<tr>
									<td align="center">
										<?php echo $rowBorangs['matricNo'] ?>
									</td>
									<td>
										<?php echo $rowBorangs['name'] ?>
									</td>
									<td align="center">
										<?php echo $rowBorangs['sectionGroup'] ?>
									</td>
									<td align="center">
										<?php echo $rowBorangs['fullMark'] ?>
									</td>
									<td align="center">
										<?php echo $rowBorangs['finalGrade'] ?>
									</td>
									<td align="center">
										<a target="_blank" style="text-decoration: none;" href="borangPenilaian.php?no_matrik=<?php echo $rowBorangs['no_matrik'] ?>&id_penyelia=<?php echo $rowBorangs['id_penyelia']?>&id_penilai=<?php echo $rowBorangs['id_penilai'] ?>"><i id="sah" class="fa fa-search" aria-hidden="true"></i></a>

										
										<a href="printPenilaian.php?no_matrik=<?php echo $rowBorangs['no_matrik'] ?>&id_penyelia=<?php echo $rowBorangs['id_penyelia']?>&id_penilai=<?php echo $rowBorangs['id_penilai']?>" target="_blank"><i id="sah" class="fa fa-download" aria-hidden="true"></i></a>
									</td>
								</tr>
								<?php
							}

							?>
						</table>
						<?php
					}
					?>
					

				</div>
			</div>

		</div>
	</div>

</div>
	<script type="text/javascript">
		$(document).ready(function(){

			load_guideline();


			//=================================================
			function load_guideline(querySearchGuideline, opt)
			{
				$.ajax({
					url:"search-guideline.php",
					method:"POST",
					data:{querySearchGuideline:querySearchGuideline,
						opt: opt
					},
					success:function(data)
					{
						$('#table_guideline').html(data);
					}
				});
			}
			$('#searchGuideline').keyup(function(){
				var search = $(this).val();
				var opt = $('#selectOptionGuideline :selected').val();
				if(search != '')
				{
					load_guideline(search, opt);
				}
				else
				{
					load_guideline();
				}
			});

			$('#selectOptionGuideline').change(function (){
				var search = $('#searchGuideline').val();
				var opt = $('#selectOptionGuideline :selected').val();
				if(opt != '')
				{
					load_guideline(search, opt);
				}
				else
				{
					load_guideline();
				}

			});
			//=================================================
		});
	</script>
	<?php

	if(isset($_GET['reload'])){
		if($_GET['reload'] == "guideline"){
			?>
			<script type="text/javascript">
				$('#rujukan').show();
				$("html, body").animate({
                   scrollTop: $('html, body').get(0).scrollHeight}, 0);
			</script>
			<?php
			unset($_GET['reload']);
		}
	}
	
	include('../footer.php');
	?>

	<div class="stickyholder" id="stickyholder" style="visibility: hidden;">
		<a class="sticky" href="#top">
			<i class="fa fa-arrow-circle-o-up" aria-hidden="true"></i>
		</a>
	</div>
</body>
</html>