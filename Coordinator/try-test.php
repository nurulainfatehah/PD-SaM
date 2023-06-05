<?php
include('../inc/dbconnect.php');
session_start();
$sqlNoSVorEV = "SELECT name, matricNo, supervisorID, evaluatorID FROM student WHERE (supervisorID = '' OR supervisorID IS NULL) OR (evaluatorID = '' OR evaluatorID IS NULL) ORDER BY supervisorID ASC";
$resultNoSVorEV = $conn->query($sqlNoSVorEV);

$sqlLecturer = "SELECT lecturerID, name FROM lecturer";
$resultLecturer = $conn->query($sqlLecturer);


if(isset($_POST['assignment_check'])){
	if($resultNoSVorEV->num_rows == 0){
		?>
		<br>
		<center><i class="remarksnote">All students already assigned to a supervisor and evaluation panel</i></center>
		<?php

	}else{
		?>
		<table align="center">
			<tr>
				<th style="width:15%">
					MATRIC NO.
				</th>
				<th style="width: 25%">
					STUDENT
				</th>
				<th style="width: 25%">
					SUPERVISOR
				</th>
				<th style="width: 25%">
					EVALUATOR
				</th>
				<th>
					<i class="fa fa-pencil-square-o" aria-hidden="true"></i>
				</th>
			</tr>


			<?php
			while($rowNoSVorEV = $resultNoSVorEV->fetch_assoc()){
				$svName = "";
				$sqlGetSV = "SELECT lecturer.name AS svName FROM student JOIN lecturer ON student.supervisorID = lecturer.lecturerID WHERE matricNo = '".$rowNoSVorEV['matricNo']."'";
				$resultGetSV = $conn->query($sqlGetSV);
				if($resultGetSV->num_rows > 0){
					$rowSV = $resultGetSV->fetch_assoc();
					$svName = $rowSV['svName'];
				}
				?>
				<tr>
					<td align="center">
						<?php echo $rowNoSVorEV['matricNo']; ?>
					</td>
					<td align="center">
						<?php echo $rowNoSVorEV['name']; ?>
					</td>
					<td>
						<?php
						if(empty($rowNoSVorEV['supervisorID'])){
							?>
							<select id="assign-supervisor" title="assign supervisor" name="assign_supervisorID" style="width: 80%">
								<option value="" >-- assign supervisor --</option>
								
								<?php
								$rowLecturer = "";
								while($rowLecturer = $resultLecturer->fetch_assoc()){
									$sqlTotalSupervised = "SELECT COUNT(*) as totalSupervised FROM student WHERE supervisorID = '".$rowLecturer['lecturerID']."'";
									$resultTotalSupervised = $conn->query($sqlTotalSupervised);
									$rowSupervised = $resultTotalSupervised->fetch_assoc();
			
									if($rowSupervised['totalSupervised'] > 5){
										?>
										<option title="<?php echo $rowLecturer['name'] ?>" disabled><?php echo $rowLecturer['name'] . ' [' . $rowSupervised['totalSupervised'] . ']' ?></option>
										<?php
									}else{
										?>
										<option title="<?php echo $rowLecturer['name'] ?>" value="<?php echo $rowLecturer['lecturerID'] ?>"><?php echo $rowLecturer['name'] . ' [' . $rowSupervised['totalSupervised'] . ']' ?></option>
										<?php
									}
								}
								?>
							</select>
							<?php
						}
						else{
							echo $svName;
						}


						?>
					</td>
					<td>
						<select id="assign-evaluator" title="assign evaluator" name="assign_evaluatorID" style="width: 80%">
							<option value="" >-- assign evaluator --</option>
							<?php
							if($rowNoSVorEV['lecturerID'] != ""){
								$sqlGetSV = "SELECT lecturer.name AS svName, lecturerID AS svID FROM student JOIN lecturer ON student.supervisorID = lecturer.lecturerID WHERE matricNo = '".$rowNoSVorEV['matricNo']."'";
								$resultGetSV = $conn->query($sqlGetSV);
								if($resultGetSV->num_rows > 0){
									$rowSV = $resultGetSV->fetch_assoc();
									$svName = $rowSV['svID'];
								}
								while($rowLecturer = $resultLecturer->fetch_assoc()){
									$sqlTotalEvaluated = "SELECT COUNT(*) as totalEvaluated FROM student WHERE evaluatorID = '".$rowLecturer['lecturerID']."'";
									$resultTotalEvaluated = $conn->query($sqlTotalEvaluated);
									$rowEvaluated = $resultTotalEvaluated->fetch_assoc();

									if($rowEvaluated['totalEvaluated'] > 5 || $rowLecturer['lecturerID'] == $svName){
										?>
										<option title="<?php echo $rowLecturer['name'] ?>" disabled><?php echo $rowLecturer['name'] . ' [' . $rowEvaluated['totalSupervised'] . ']' ?></option>
										<?php
									}else{
										?>
										<option title="<?php echo $rowLecturer['name'] ?>" value="<?php echo $rowLecturer['lecturerID'] ?>"><?php echo $rowLecturer['name'] . ' [' . $rowEvaluated['totalSupervised'] . ']' ?></option>
										<?php
									}
								}
							}else{

							}

							?>
						</select>
					</td>
					<td>
						lalalala
					</td>
				</tr>

				<?php
			}
			?>

		</table>
		<?php

	}
}



?>