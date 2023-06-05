<?php
include('../inc/dbconnect.php');
session_start();

if(isset($_GET['matricNo'])){
	$svName = "";
	$svEmail = "";
	$evName = "";
	$evEmail = "";
	$matricNo = $_GET['matricNo'];
	$sqlStudent = "SELECT * FROM student WHERE matricNo = '".$matricNo."'";
	$resultStudent = $conn->query($sqlStudent);
	$row = $resultStudent->fetch_assoc();

	$sqlSV = "SELECT lecturer.name AS svName, lecturer.email AS svEmail FROM student JOIN lecturer ON student.supervisorID = lecturer.lecturerID WHERE matricNo = '".$matricNo."'";
	$resultSV = $conn->query($sqlSV);
	if($resultSV->num_rows > 0){
		$rowSV = $resultSV->fetch_assoc();
		$svName = $rowSV['svName'];
		$svEmail = $rowSV['svEmail'];
	}

	$sqlEV = "SELECT lecturer.name AS evName, lecturer.email AS evEmail FROM student JOIN lecturer ON student.evaluatorID = lecturer.lecturerID WHERE matricNo = '".$matricNo."'";
	$resultEV = $conn->query($sqlEV);
	if($resultEV->num_rows > 0){
		$rowEV = $resultEV->fetch_assoc();
		$evName = $rowEV['evName'];
		$evEmail = $rowEV['evEmail'];
	}

	?>

	<!DOCTYPE html>
	<html>
	<head>
		<meta charset="utf-8">
		<meta name="viewport" content="width=device-width, initial-scale=1">
		<link rel="stylesheet" type="text/css" href="../css/view-profile-popup.css">
		<title><?php $row['name'] ?></title>
	</head>
	<body >
		<hr>
		<div align="center" style="width: 90%; margin-left: auto; margin-right: auto;">

			<div class="popup-grid-container">

				<div class="grid-child purple" style="padding-top: 30%; padding-bottom: 20%;">
					<!--Grid Column 1-->

					<?php
					if(empty($row['picture']))
					{
						?>
						<div class="popup-profile-picnohoverbig" style="background-image: url('../attachment/imgsource/studenticon.png'); margin-top: 10%; margin-bottom: 10%" >
							<?php 
						}
						else
						{
							?>
							<div class="popup-profile-picnohoverbig" style="background-image: url('../attachment/profile/<?php echo $row['picture']?>');  margin-top: 10%; margin-bottom: 10%" >
								<?php 
							}
							?>
						</div>
					</div>
					<div class="grid-child green" style="margin-left: 2%; padding-top: 0%; padding-bottom: 0%;">
						<!--Grid Column 2-->						
						<div class="popup-informationHolder" style="width:95%">
							<?php echo $row['name']; ?>
						</div>
						<div class="popup-information" style="width: 95%">
							<table style="border: 1px solid #ccc;" cellspacing="2">
								<tr>
									<td style="width: 35%">
										MATRIC NO.
									</td>
									<td>
										<?php echo $row['matricNo']; ?>
									</td>
								</tr>
								<tr>
									<td>
										PROJECT TITLE
									</td>
									<td>
										<?php if(empty($row['projectTitle'])){
											echo '-';
										}else
											echo strtoupper($row['projectTitle']); ?>
									</td>
								</tr>
								<tr>
									<td>
										SECTION/ GROUP
									</td>
									<td>
										<?php echo $row['sectionGroup'] ?>
									</td>
								</tr>
								<tr>
									<td>
										E-MAIL
									</td>
									<td>
										<?php echo $row['email'] ?>
									</td>
								</tr>
								<tr>
									<td>
										PHONE
									</td>
									<td>
										<?php echo $row['phone'] ?>
									</td>
								</tr>
								<tr>
									<td>
										SUPERVISOR
									</td>
									<td>
										<?php 
										if($svName != ""){
											echo $svName . ' <i style="color: grey; font-size:12px">('. $svEmail .')</i>'; 
										}else{
											echo '<i style="color: grey">not assigned yet</i>';
										}
										?>
									</td>
								</tr>
								<tr>
									<td>
										EVALUATOR
									</td>
									<td>
										<?php 
										if($evName != ""){
											echo $evName . ' <i style="color: grey;font-size:12px">('. $evEmail .')</i>';
										}else{
											echo '<i style="color: grey">not assigned yet</i>';
										}
										?>
									</td>
								</tr>
							</table>
						</div>
					</div>					  	
				</div>


			</div>	
		</body>
		</html> 
	<?php
}else{

}

