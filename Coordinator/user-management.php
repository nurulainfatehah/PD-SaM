<?php
include('../inc/dbconnect.php');
session_start();
$lecturerID = $_SESSION['username'];
include('../ajaxprocess/validate-username-email-ajax.php');

$sqlLecturerRecord = "SELECT * FROM lecturer ORDER BY FIELD(role,'coordinator') DESC";
$resultLecturerRecord = $conn->query($sqlLecturerRecord);

$sqlStudent = "SELECT * FROM student ORDER BY matricNo";
$resultStudent = $conn->query($sqlStudent);

$sqlPassword = "SELECT password FROM lecturer WHERE lecturerID = '".$lecturerID."'";
$resultPassword = $conn->query($sqlPassword);
$rowInfopassword = $resultPassword->fetch_assoc();

$sqlInfo = "SELECT * FROM lecturer WHERE lecturerID = '".$lecturerID."'";
$resultInfo = $conn->query($sqlInfo);
$rowInfo = $resultInfo->fetch_assoc();

$sqlSupervisorList = "SELECT lecturer.name AS lecturer, lecturer.lecturerID AS lecturerID FROM student INNER JOIN lecturer ON student.supervisorID = lecturer.lecturerID GROUP BY lecturerID ORDER BY lecturer ASC";
$resultSupervisorList = $conn->query($sqlSupervisorList);

?>

<!DOCTYPE html>
<html>
<head>
	<link rel="icon" type="image/png" href="../attachment/bg/logo.png"/>
	<title>PD-SaM - <?php echo ucfirst($_SESSION['username']) ?></title>
	<link rel="stylesheet" type="text/css" href="../css/header.css">
	<link rel="stylesheet" type="text/css" href="../css/dashboard.css">
	<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css">
	<script type="text/javascript" src="//code.jquery.com/jquery-1.9.1.js"></script>
	<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/jquery-confirm/3.3.2/jquery-confirm.min.css">
	<script src="https://cdnjs.cloudflare.com/ajax/libs/jquery-confirm/3.3.2/jquery-confirm.min.js"></script>
	<script type="text/javascript">
		$( window ).scroll(function() {

			$("#stickyholder").css("visibility", "visible");

		});

		$(document).ready(function() {

			$('#click-lecturer').click(function(){
				if($('#section-lecturer').is(":visible")){
					$('#section-lecturer').hide();
				}else
					$('#section-lecturer').show();
				
			});

			$('#hide-section-lecturer').click(function(){
				$('#section-lecturer').hide();
			});

			$('#btn-add-lecturer').click(function(){
				$('#btn-cancel-lecturer').show();
				$('#btn-add-lecturer').hide();
				$('#add-lecturer').show();				
			});

			$('#btn-cancel-lecturer').click(function(){
				$('#btn-add-lecturer').show();
				$('#btn-cancel-lecturer').hide();
				$('#add-lecturer').hide();				
			});

			$('#lecturerID-lecturer').on('blur', function(){
				var lecturerID = $('#lecturerID-lecturer').val();
				if (lecturerID == '') {
				    lecturerID_state = false;
				    return;
				}
				$.ajax({
				    url: '../ajaxprocess/validate-username-email-ajax.php',
				    type: 'post',
				    data: {
				      'lecturerID_check' : 1,
				      'lecturerID' : lecturerID,
				   },
					success: function(response){
				    if (response == 'taken' ) {
				    	lecturerID_state = false;
				        $.confirm({
				          boxWidth: '27%',
				            title: 'ID already exist!',
				            content: 'Sorry, the ID entered is taken. Please confirm again.',
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
				        $('#submit-lecturer').prop('disabled', true);
				        $('#submit-lecturer').css({ 'color': 'white', 'background-color': 'gray' });
				      }else if (response == 'not_taken') {
				        username_state = true;
				        $('#submit-lecturer').prop('disabled', false);
				        $('#submit-lecturer').css({ 'color': '#fff', 'background-color': 'rgba(117, 20, 117, 0.6)' });
				        $("#submit-lecturer").mouseenter(function() {
				            $(this).css("background-color", "rgba(117, 20, 117, 0.3)").css("color", "#1A5573");
				        }).mouseleave(function() {
				             $(this).css("background-color", "rgba(117, 20, 117, 0.6)").css("color", "#fff");
				        });
				      }
				    }
				});
			});	

			$('#click-student').click(function(){
				if($('#section-student').is(":visible")){
					$('#section-student').hide();
				}else
					$('#section-student').show();
			});

			$('#hide-section-student').click(function(){
				$('#section-student').hide();
			});

			$('#btn-add-student').click(function(){
				$('#btn-cancel-student').show();
				$('#btn-add-student').hide();
				$('#add-student').show();				
			});

			$('#btn-cancel-student').click(function(){
				$('#btn-add-student').show();
				$('#btn-cancel-student').hide();
				$('#add-student').hide();				
			});

			$('#click-supervision').click(function(){
				if($('#section-supervision').is(":visible")){
					$('#section-supervision').hide();
				}else
					$('#section-supervision').show();
			});

			$('#hide-section-supervision').click(function(){
				$('#section-supervision').hide();
			});

			$('#add-supervision-assignment').click(function(){
				$('#supervision-assignment').show();
				$('#add-supervision-assignment').hide();
				$('#cancel-supervision-assignment').show();
			});

			$('#cancel-supervision-assignment').click(function(){
				$('#supervision-assignment').hide();
				$('#add-supervision-assignment').show();
				$('#cancel-supervision-assignment').hide();
			});
		});
	</script>
	<style type="text/css">
		select#assign-sv, select#assign-ev{
			width: 100%;
		}
	</style>
</head>
<body>
	<div class="outerbox" id="top">
		<div class="innerbox">
			<header>
				<a href="../Coordinator"  class="logo">Projek Diploma Supervision and Management System</a>
				<nav>
					<ul>
						<li>
							<a title="home" href="../Coordinator" ><i style="font-size: 16px;" class="fa fa-home" aria-hidden="true"></i></a>
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
							<a title="user management" href="user-management.php" class="activebar"><i style="font-size: 16px;" class="fa fa-users" aria-hidden="true"></i></a>
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
				<a id="click-lecturer" href="#section-lecturer" style="text-decoration: none;text-decoration-style: none; text-decoration-color: none; color: inherit;">
					<div class="kotakKategori">
						<div class="gambarholder">
							<img id="gambarKategori" src="../attachment/imgsource/lecturer.png" width="70px" height="70px">
						</div>
						<div class="ayatkategori">
							<p class="titleKategori">
								Staff Management
							</p>
							
								<?php 
								if($resultLecturerRecord->num_rows == 0 )
								{
									echo "<p class='detailKategori' style='color:gray;'>0</i>";
								}
								else{

									echo "<p class='detailKategori'>" .$resultLecturerRecord->num_rows;
								}
								 ?>
							</p>
						</div>
					</div>
				</a>

				<a id="click-student" href="#section-student" style="text-decoration: none;text-decoration-style: none; text-decoration-color: none; color: inherit;">
					<div class="kotakKategori">
						<div class="gambarholder">
							<img id="gambarKategori" src="../attachment/imgsource/student-manage.png" width="70px" height="70px">
						</div>
						<div class="ayatkategori">
							<p class="titleKategori">
								Student Management
							</p>
							
								<?php 
								if($resultStudent->num_rows == 0 )
								{
									echo "<p class='detailKategori' style='color:gray; margin-top: 0'>0</i>";
								}
								else{

									echo "<p class='detailKategori' style='margin-top: 0'>" .$resultStudent->num_rows;
								}
								 ?>
							</p>
						</div>
					</div>
				</a>	

				<a id="click-supervision" href="#section-supervision" style="text-decoration: none;text-decoration-style: none; text-decoration-color: none; color: inherit;">
					<div class="kotakKategori">
						<div class="gambarholder">
							<img id="gambarKategori" src="../attachment/imgsource/supervisor.png" width="70px" height="70px">
						</div>
						<div class="ayatkategori">
							<p class="titleKategori">
								Supervision Management
							</p>
							
							<p style="color: gray; margin-top: 0; font-size: 11px" class="detailKategori">
								further<br>information..
							</p>
						</div>
					</div>
				</a>		
			</div>



			<!-- section lecturer  -->
			<?php if(isset($_SESSION['openDiv']) && $_SESSION['openDiv'] == "student-management" ){

				?> <div id="section-lecturer" class="sectionkategori" > <?php
				unset($_SESSION['openDiv']);
				}else{
					?>
					<div id="section-lecturer" class="sectionkategori" style="display:none; ">
					<?php
				} ?>
				
				<div class="titleTextHolder">
					Staff Management
				</div>
				<hr>
				<div class="kontenkategori" >
					<div class="btn-click-print">
						
				
						<a id="click-add-lecturer" href="#add-lecturer">
							<i id="btn-add-lecturer" title="add new" class="fa fa-plus-circle" aria-hidden="true"></i>
							<i id="btn-cancel-lecturer" title="cancel" class="fa fa-ban" aria-hidden="true" style="display: none;"></i>
						</a>
						<p id="hide-section-lecturer" class="hide" style="margin-left: auto;">hide</p>
					</div>
					
					<br>

					<div id="add-lecturer" style="display: none;" align="center">
						<div class="informationHolder" align="center" style="width: 70%">
							REGISTER STAFF
						</div>
						<table align="center" style="width:70%; border-spacing: 5px;">
							<tr>
								<td style="width: 30%;">
									STAFF ID
								</td>
								<td>
									<input id="lecturerID-lecturer" type="text" name="lecturerID" placeholder="STAFF ID" style="text-transform: uppercase;"><span class="asterisk_input" ></span>
								</td>
							</tr>
							<tr>
								<td>
									PASSWORD
								</td>
								<td>
									<input type="text" max="30" readonly="" value="pass1234" placeholder="PASSWORD">
								</td>
							</tr>
							<tr>
								<td>
									FULL NAME
								</td>
								<td>
									<input id="name-lecturer" type="text" name="name" placeholder="FULL NAME" style="text-transform: uppercase;"><span class="asterisk_input"></span>
								</td>
							</tr>
							<tr>
								<td>
									E-MAIL
								</td>
								<td>
									<input id="email-lecturer" type="email" name="email" placeholder="E-MAIL"><span class="asterisk_input"></span>
								</td>
							</tr>
							<tr>
								<td>
									OFFICE NO.
								</td>
								<td>
									<input id="officePhone" type="text" name="officePhone" placeholder="OFFICE NO."><span class="asterisk_input"></span>
								</td>
							</tr>
						</table>	
						<center>
							<input type="submit" name="submit-lecturer" id="submit-lecturer" class="submit" value="Register">
						</center>						
						<hr>
					</div>
					<div style="display: inline; width: 100%; padding-top: 20px;" >
						<?php
						if($resultLecturerRecord->num_rows == 0){
							?>
							<center><i class="remarksnote">0 record of lecturer was found</i></center>
							<?php
						}else{
							?>
							<div style="width:50%; margin-left:auto; margin-right: auto; margin-bottom: 10px;">
								<select style="width: 40%;" id="selectOptionLecturer">
									<option selected value="idAZ">ID A-Z</option>
									<option value="idZA">ID Z-A</option>
									<option value="nameAZ">Full Name A-Z</option>
									<option value="nameZA">Full Name Z-A</option>
									<option value="roleCoordinator">Coordinator</option>
									<option value="roleSupervisor">Supervisor</option>
								</select>
								<input style="width: 40%;" placeholder="Search" type="text" name="searchLecturer" id="searchLecturer">
							</div>							
							<div id="table_lecturer">
								
							</div>
							<?php
						}
						?>
					</div>
				</div>
			</div>

			<!-- section student -->
			<?php if(isset($_SESSION['openDiv']) && $_SESSION['openDiv'] == "student-management" ){

				?> <div id="section-student" class="sectionkategori" > <?php
				unset($_SESSION['openDiv']);
				}else{
					?>
					<div id="section-student" class="sectionkategori" style="display:none; ">
					<?php
				} ?>
				
				<div class="titleTextHolder">
					Student Management
				</div>
				<hr>
				<div class="kontenkategori" >
					<div class="btn-click-print">
						
				
						<a id="click-add-student" href="#add-student">
							<i id="btn-add-student" title="add new" class="fa fa-plus-circle" aria-hidden="true"></i>
							<i id="btn-cancel-student" title="cancel" class="fa fa-ban" aria-hidden="true" style="display: none;"></i>
						</a>
						<p id="hide-section-student" class="hide" style="margin-left: auto;">hide</p>
					</div>
					
					<br>

					<div id="add-student" style="display: none;" align="center">
						<div class="informationHolder" align="center" style="width: 70%">
							REGISTER STUDENT
						</div>
						<table align="center" style="width:70%; border-spacing: 5px;">
							<tr>
								<td style="width: 30%;">
									MATRIC NO.
								</td>
								<td>
									<input id="matricNo-student" type="text" name="matricNo" placeholder="MATRIC NO" style="text-transform: uppercase;"><span class="asterisk_input" ></span>
								</td>
							</tr>
							<tr>
								<td>
									PASSWORD
								</td>
								<td>
									<input type="text" max="30" readonly="" value="pass1234" placeholder="PASSWORD">
								</td>
							</tr>
							<tr>
								<td>
									FULL NAME
								</td>
								<td>
									<input id="name-student" type="text" name="name" placeholder="FULL NAME" style="text-transform: uppercase;"><span class="asterisk_input"></span>
								</td>
							</tr>
							<tr>
								<td>
									E-MAIL
								</td>
								<td>
									<input id="email-student" type="email" name="email" placeholder="E-MAIL"><span class="asterisk_input"></span>
								</td>
							</tr>
							<tr>
								<td>
									SECTION/GROUP
								</td>
								<td>
									<select id="sectionGroup-student" name="sectionGroup">
										<option value="1/1">S1/G1</option>
										<option value="1/2">S1/G2</option>
										<option value="1/3">S1/G3</option>
										<option value="2/1">S2/G1</option>
										<option value="2/2">S2/G2</option>
										<option value="2/3">S2/G3</option>
										<option value="3/1">S3/G1</option>
										<option value="3/2">S3/G2</option>
										<option value="3/3">S3/G3</option>
									</select>
								</td>
							</tr>
							<tr>
								<td>
									PHONE
								</td>
								<td>
									<input id="phone-student" type="text" name="phone" placeholder="PHONE"><span class="asterisk_input"></span>
								</td>
							</tr>
						</table>	
						<center>
							<input type="submit" name="submit-student" id="submit-student" class="submit" value="Register">
						</center>						
						<hr>
					</div>
					<div style="display: inline; width: 100%; padding-top: 20px;" >
						<?php
						if($resultStudent->num_rows == 0){
							?>
							<center><i class="remarksnote">0 record of student was found</i></center>
							<?php
						}else{
							?>
							<div style="width:50%; margin-left:auto; margin-right: auto; margin-bottom: 10px;">
								<select style="width: 40%;" id="selectOptionStudent">
									
									<option selected value="matricAZ">Matric No. A-Z</option>
									<option value="matricZA">Matric No. Z-A</option>
									<option value="nameAZ">Full Name A-Z</option>
									<option value="nameZA">Full Name Z-A</option>
								</select>
								<input style="width: 40%;" placeholder="Search" type="text" name="searchStudent" id="searchStudent">
							</div>							
							<div id="table_student">
		
							</div>
							<?php
						}
						?>
					</div>
				</div>
			</div>

			<!-- section supervision -->
			<?php if(isset($_SESSION['openDiv']) && $_SESSION['openDiv'] == "supervision-management" ){

				?> <div id="section-supervision" class="sectionkategori" > <?php
				unset($_SESSION['openDiv']);
				}else{
					?>
					<div id="section-supervision" class="sectionkategori" style="display:none; ">
					<?php
				} ?>
				
				<div class="titleTextHolder">
					Supervision Management
				</div>
				<hr>
				<div class="kontenkategori" >
					<div class="btn-click-print">
						<p id="hide-section-supervision" class="hide" style="margin-left: auto;">hide</p>
					</div>
					
					<br>
					<div style="display: inline; width: 100%; padding-top: 20px;" >
						<?php
						if($resultSupervisorList->num_rows == 0){
							?>
							<center><i class="remarksnote">0 record of supervision assignment was found</i></center>
							<?php
						}else{
							?>
							<div style="width:50%; margin-left:auto; margin-right: auto; margin-bottom: 10px;">
								<select style="width: 40%;" id="selectOptionSupervision">
									<option selected value="nameAZ">Supervisor Name A-Z</option>
									<option value="nameZA">Supervisor Name Z-A</option>
								</select>
								<input style="width: 40%;" placeholder="Search" type="text" name="searchSupervision" id="searchSupervision">
							</div>							
							<div id="table_supervision">
		
							</div>
							<?php
						}
						?>
						<br>
						<a href="#supervision-assignment" id="click-supervision-assignment" style="padding-left:10px">
							<i id="add-supervision-assignment" class="fa fa-ellipsis-h" aria-hidden="true" title="assign supervision" style="font-size: 19px;"></i>
							<i id="cancel-supervision-assignment" class="fa fa-ban" aria-hidden="true" style="font-size: 19px; display: none;" title="cancel"></i>
						</a>
					</div>
				</div>
				<div id="supervision-assignment" style="display: none">
					<p style="font-size: 14px; font-weight: bold;  padding-left: 10px">Supervision and Evaluation Panel Assignment<i style="font-size: 9px; margin-left: 10px">*Maximum of 5 supervised and evaluated student for each lecturer.</i></p>
					<hr>
					<div id="table_assignment">
						
					</div>
				</div>
			</div>
		</div>		
	</div>
	<script type="text/javascript">
		$(document).ready(function(){

			load_lecturer();
			load_student();
			load_supervision();
			load_assignment();


			/* js load table lecturer */
			//==================================================
			function load_lecturer(querySearchLecturer, opt){
				$.ajax({
					url:"search-lecturer.php",
					method:"POST",
					data:{querySearchLecturer:querySearchLecturer,
						opt: opt
					},
					success:function(data)
					{
						$('#table_lecturer').html(data);
					}
				});
			}
			$('#searchLecturer').keyup(function(){
				var search = $(this).val();
				var opt = $('#selectOptionLecturer :selected').val();
				if(search != '')
				{
					load_lecturer(search, opt);
				}
				else
				{
					load_lecturer();
				}
			});

			$('#selectOptionLecturer').change(function (){
				var search = $('#searchLecturer').val();
				var opt = $('#selectOptionLecturer :selected').val();
				if(opt != '')
				{
					load_lecturer(search, opt);
				}
				else
				{
					load_lecturer();
				}

			});

			/* js load table student */
			//=================================================
			function load_student(querySearchStudent, opt){
				$.ajax({
					url:"search-student.php",
					method:"POST",
					data:{querySearchStudent:querySearchStudent,
						opt: opt
					},
					success:function(data)
					{
						$('#table_student').html(data);
					}
				});
			}
			$('#searchStudent').keyup(function(){
				var search = $(this).val();
				var opt = $('#selectOptionStudent :selected').val();
				if(search != '')
				{
					load_student(search, opt);
				}
				else
				{
					load_student();
				}
			});

			$('#selectOptionStudent').change(function (){
				var search = $('#searchStudent').val();
				var opt = $('#selectOptionStudent :selected').val();
				if(opt != '')
				{
					load_student(search, opt);
				}
				else
				{
					load_student();
				}

			});

			/* js load table supervision */
			//=================================================
			function load_supervision(querySearchSupervision, opt){
				$.ajax({
					url:"search-supervision.php",
					method:"POST",
					data:{querySearchSupervision:querySearchSupervision,
						opt: opt
					},
					success:function(data)
					{
						$('#table_supervision').html(data);
					}
				});
			}
			$('#searchSupervision').keyup(function(){
				var search = $(this).val();
				var opt = $('#selectOptionSupervision :selected').val();
				if(search != '')
				{
					load_supervision(search, opt);
				}
				else
				{
					load_supervision();
				}
			});

			$('#selectOptionSupervision').change(function (){
				var search = $('#searchSupervision').val();
				var opt = $('#selectOptionSupervision :selected').val();
				if(opt != '')
				{
					load_supervision(search, opt);
				}
				else
				{
					load_supervision();
				}

			});

			/* js load assignment */
			//=================================================
			function load_assignment(){
				$.ajax({
					url:"search-assignment.php",
					method:"POST",
					data:{
						assignment_check: 1
					},
					success:function(data)
					{
						$('#table_assignment').html(data);
					}
				});
			}
			
		});
		function load_add_lecturer(lecturerID, name, email, officePhone){
			$.ajax({
				url: "register-delete-user.php",
				type: "POST",
				data: {
					registerlecturer_check: 1,
					lecturerID: lecturerID,
					name: name,
					email: email,
					officePhone: officePhone				
				},
				cache: false,
				success: function(dataResult){
					if(dataResult == "registered"){
						$.confirm({
							boxWidth: '27%',
							title: 'Successfully registered',
							content: '',
							type: 'green',
							typeAnimated: true,
							useBootstrap: false,
							buttons: {
								tryAgain: {
									text: 'Okay',
									btnClass: 'btn-green',
									action: function(){
										window.location.replace('?reload=lecturer');
									}
								}
							}
						});
					}
					else if(dataResult == "notregistered"){
						$.confirm({
							boxWidth: '27%',
							title: 'Failed to register',
							content: '',
							type: 'red',
							typeAnimated: true,
							useBootstrap: false,
							buttons: {
								tryAgain: {
									text: 'Okay',
									btnClass: 'btn-red',
									action: function(){
										window.location.replace('?reload=lecturer');
									}
								}
							}
						});
					}

				}
			});
		}
		function deleteLecturer(obj, name){
			var lecturerID = obj.id;
			var name = name;
			$.confirm({
				boxWidth: '27%',
				title: 'Remove ' + name + '?',
				content: 'Are you sure to remove '+ name +' account? This cannot be undone.',
				type: 'red',
				typeAnimated: true,
				useBootstrap: false,
				autoClose: 'cancel|5000',
				buttons: {
					confirm: {
						text: 'Remove',
						btnClass: 'btn-red',
						action: function(){
							$.ajax({
								url: "register-delete-user.php",
								type: "POST",
								data: {
									deletelecturer_check: 1,
									lecturerID: lecturerID			
								},
								cache: false,
								success: function(dataResult){
									if(dataResult == "deleted"){
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
														window.location.replace('?reload=lecturer');
													}
												}
											}
										});
									}
									else if(dataResult == "notdeleted"){
										$.confirm({
											boxWidth: '27%',
											title: 'Failed to delete',
											content: '',
											type: 'red',
											typeAnimated: true,
											useBootstrap: false,
											buttons: {
												tryAgain: {
													text: 'Okay',
													btnClass: 'btn-red',
													action: function(){
														window.location.replace('?reload=lecturer');
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

		function load_add_student(matricNo, name, email, sectionGroup, phone){
			$.ajax({
				url: "register-delete-user.php",
				type: "POST",
				data: {
					registerstudent_check: 1,
					matricNo: matricNo,
					name: name,
					email: email,
					sectionGroup: sectionGroup,
					phone: phone				
				},
				cache: false,
				success: function(dataResult){
					if(dataResult == "registered"){
						$.confirm({
							boxWidth: '27%',
							title: 'Successfully registered',
							content: '',
							type: 'green',
							typeAnimated: true,
							useBootstrap: false,
							buttons: {
								tryAgain: {
									text: 'Okay',
									btnClass: 'btn-green',
									action: function(){
										window.location.replace('?reload=student');
									}
								}
							}
						});
					}
					else if(dataResult == "notregistered"){
						$.confirm({
							boxWidth: '27%',
							title: 'Failed to register',
							content: '',
							type: 'red',
							typeAnimated: true,
							useBootstrap: false,
							buttons: {
								tryAgain: {
									text: 'Okay',
									btnClass: 'btn-red',
									action: function(){
										window.location.replace('?reload=student');
									}
								}
							}
						});
					}

				}
			});
		}
		function deleteStudent(obj, name){
			var matricNo = obj.id;
			var name = name;
			$.confirm({
				boxWidth: '27%',
				title: 'Remove ' + name + '?',
				content: 'Are you sure to remove '+ name +' account? This cannot be undone.',
				type: 'red',
				typeAnimated: true,
				useBootstrap: false,
				autoClose: 'cancel|5000',
				buttons: {
					confirm: {
						text: 'Remove',
						btnClass: 'btn-red',
						action: function(){
							$.ajax({
								url: "register-delete-user.php",
								type: "POST",
								data: {
									deletestudent_check: 1,
									matricNo: matricNo			
								},
								cache: false,
								success: function(dataResult){
									if(dataResult == "deleted"){
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
														window.location.replace('?reload=student');
													}
												}
											}
										});
									}
									else if(dataResult == "notdeleted"){
										$.confirm({
											boxWidth: '27%',
											title: 'Failed to delete',
											content: '',
											type: 'red',
											typeAnimated: true,
											useBootstrap: false,
											buttons: {
												tryAgain: {
													text: 'Okay',
													btnClass: 'btn-red',
													action: function(){
														window.location.replace('?reload=student');
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

	</script>
	<?php

	if(isset($_GET['reload'])){
		if($_GET['reload'] == "lecturer"){
			?>
			<script type="text/javascript">
				$('#section-lecturer').show();
				$("html, body").animate({
                   scrollTop: $('html, body').get(0).scrollHeight}, 0);
			</script>
			<?php
			unset($_GET['reload']);
		}
		else if($_GET['reload'] == "student"){
			?>
			<script type="text/javascript">
				$('#section-student').show();
				$("html, body").animate({
                   scrollTop: $('html, body').get(0).scrollHeight}, 0);
			</script>
			<?php
			unset($_GET['reload']);
		}else if($_GET['reload'] == "supervision"){
			?>
			<script type="text/javascript">
				$('#section-supervision').show();
				$("html, body").animate({
                   scrollTop: $('html, body').get(0).scrollHeight}, 0);
			</script>
			<?php
			unset($_GET['reload']);
		}else if($_GET['reload'] == "successAssignSV"){
			?>
			<script type="text/javascript">
				$('#section-supervision').show();
				$('#supervision-assignment').show();
				$('#add-supervision-assignment').hide();
				$('#cancel-supervision-assignment').show();
				$('html, body').animate({
				   scrollTop: $('#section-supervision').offset().top
				   //scrollTop: $('#your-id').offset().top
				   //scrollTop: $('.your-class').offset().top
				}, 'smooth');
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