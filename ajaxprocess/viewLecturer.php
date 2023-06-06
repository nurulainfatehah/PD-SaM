<?php
include('../inc/dbconnect.php');
session_start();

if(isset($_GET['lecturerID'])){
	$lecturerID = $_GET['lecturerID'];

	$sqlLec = "SELECT * FROM lecturer WHERE lecturerID = '".$lecturerID."'";
	$resultLec = $conn->query($sqlLec);
	$row = $resultLec->fetch_assoc();

	$sqlSVList = "SELECT * FROM student WHERE supervisorID = '".$lecturerID."'";
	$resultSVList = $conn->query($sqlSVList);

	$sqlEVList = "SELECT * FROM student WHERE evaluatorID = '".$lecturerID."'";
	$resultEVList = $conn->query($sqlEVList);

	?>

	<!DOCTYPE html>
	<html>
	<head>
		<meta charset="utf-8">
		<meta name="viewport" content="width=device-width, initial-scale=1">
		<link rel="stylesheet" type="text/css" href="../css/view-profile-popup.css">
		<link rel="stylesheet" type="text/css" href="../css/header.css">
		<link rel="stylesheet" type="text/css" href="../css/dashboard.css">
		<title><?php $row['name'] ?></title>
		<style type="text/css">

		</style>
	</head>
	<body >
		<hr>
		<div align="center" style="width: 90%; margin-left: auto; margin-right: auto;">
			<div class="popup-grid-container">
				<div class="grid-child purple" style="padding-top: 10%; padding-bottom: 20%;">
					<!--Grid Column 1-->

					<?php
					if(empty($row['picture']))
					{
						?>
						<div class="popup-profile-picnohoverbig" style="background-image: url('../attachment/imgsource/lecturer1.png'); margin-top: 10%; margin-bottom: 10%" >
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
								<td style="width: 27%">
									STAFF ID
								</td>
								<td>
									<?php echo $row['lecturerID']; ?>
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
									<?php echo $row['officePhone'] ?>
								</td>
							</tr>
							<tr>
								<td>
									ROLE
								</td>
								<td>
									<?php echo $row['role']; ?>
								</td>
							</tr>
						</table>
					</div>
				</div>					  	
			</div>
			<?php
			if($resultSVList->num_rows > 0){
				?>
				<hr>
				<center style="font-size: 14px; font-weight: bold;">SUPERVISED LIST (<?php echo $resultSVList->num_rows ?>)</center>
				<hr>
				
				<div id="main" align="center">
					<?php
					while ($rowSVList = $resultSVList->fetch_assoc()){
						?>
						<div id="list-box">
							<?php
							if(empty($rowSVList['picture']))
							{
								?>
								<div class="pic-list" style="background-image: url('../attachment/imgsource/studenticon.png');" >
									<?php 
							}
							else
							{
								?>
								<div class="pic-list" style="background-image: url('../attachment/profile/<?php echo $rowSVList['picture']?>');" >
								<?php 
							}
							?>
								</div>
							<p id="list-data">
								<?php
								echo '<b>' . $rowSVList['matricNo']. '</b><br> ' . $rowSVList['name'];
								?>
							</p>
						</div>
						<?php
					}
					?>						
				</div>
				<?php
			}
			?>

			<?php
			if($resultEVList->num_rows > 0){
				?>
				<br><br>
				<hr>
				<center style="font-size: 14px; font-weight: bold;">EVALUATION LIST (<?php echo $resultEVList->num_rows ?>)</center>
				<hr>
				<div id="main" align="center">
					<?php
					while ($rowEVList = $resultEVList->fetch_assoc()){
						?>
						<div id="list-box">
							<?php
							if(empty($rowEVList['picture']))
							{
								?>
								<div class="pic-list" style="background-image: url('../attachment/imgsource/studenticon.png');" >
									<?php 
							}
							else
							{
								?>
								<div class="pic-list" style="background-image: url('../attachment/profile/<?php echo $rowEVList['picture']?>');" >
								<?php 
							}
							?>
								</div>
							<p id="list-data">
								<?php
								echo '<b>' . $rowEVList['matricNo']. '</b><br> ' . $rowEVList['name'];
								?>
							</p>
						</div>
						<?php
					}
					?>						
				</div>
				<?php
			}
			?>
			
		</div>
		</div>
	</body>
	</html> 
	<?php
}else{

}

