<?php

include('../inc/dbconnect.php');
session_start();

// get student with no sv OR no evaluator
$sqlNoSVorEV = "SELECT name, matricNo, supervisorID, evaluatorID FROM student WHERE (supervisorID = '' OR supervisorID IS NULL) OR (evaluatorID = '' OR evaluatorID IS NULL) ORDER BY supervisorID ASC";
$resultNoSVorEV = $conn->query($sqlNoSVorEV);
$svName = "";
$hasSV = "";

if(isset($_POST['assignment_check'])){
	// if everyone already have a sv and evaluator
	if($resultNoSVorEV->num_rows == 0){
		?>
		<br>
		<center><i class="remarksnote">All students already assigned to a supervisor and evaluation panel</i></center>
		<?php

	}else{
		?>
		
		<table>
			<tr>
				<th style="width: 12%">
					MATRIC NO.
				</th>
				<th>
					STUDENT
				</th>
				<th style="width: 25%">
					SUPERVISOR
				</th>
				<th style="width: 25%">
					EVALUATOR
				</th>
			</tr>
			<script>
				function confirm_assign_sv(obj, name){
					var name = name;
					if(obj.value == ""){

					}else{
						idName = obj.value;
						lecturerID = idName.substr(0, 9);
						lecturerName = idName.substr(10);
						$.confirm({
							boxWidth: '27%',
							title: 'Assign Supervisor?',
							content: 'Are you sure to assign ' + lecturerName + ' as supervisor for ' + name + '?',
							type: 'green',
							typeAnimated: true,
							useBootstrap: false,
							autoClose: 'cancel|5000',
							buttons: {
								confirm: {
									text: 'Assign',
									btnClass: 'btn-green',
									action: function(){
										obj.form.submit();
									}			        	
								},
								cancel: {
									text: 'Cancel',
									btnClass: 'btn-default',
									action: function(){
										document.getElementById("assign-sv").selectedIndex=0;
									}
								}
							}
						});	
					}
									
				}
			</script>
			<?php
			// list of student that have no SV or EV
			while($rowNoSVorEV = $resultNoSVorEV->fetch_assoc()){

				?>
				<tr>
					
					<td align="center"><?php echo $rowNoSVorEV['matricNo'] ?></td>
					<td><?php echo $rowNoSVorEV['name'] ?></td>
					<td>
						<?php
						if(empty($rowNoSVorEV['supervisorID'])){
							?>
							<form name="formAssign" id="formSV" method="post" action="assign-supervisor-evaluator.php?matricNo=<?php echo $rowNoSVorEV['matricNo'] ?>">
							<select id="assign-sv" name="assign_sv" title="assign supervisor" onchange="confirm_assign_sv(this, '<?php echo $rowNoSVorEV["name"] ?>');">
								<option value="" selected title="assign supervisor">assign supervisor</option>
								<?php
								// get records of all lecturer
								$sqlLecturer = "SELECT lecturerID, name FROM lecturer";
								$resultLecturer = $conn->query($sqlLecturer);

								while($rowLecturer = $resultLecturer->fetch_assoc())
								{	
									$svName = "";
									$hasSV = "";
									// check total supervised student for each lecturer
									$sqlTotalSupervised = "SELECT COUNT(*) as totalSupervised FROM student WHERE supervisorID = '".$rowLecturer['lecturerID']."'";
									$resultTotalSupervised = $conn->query($sqlTotalSupervised);
									$rowTotalSupervised = $resultTotalSupervised->fetch_assoc();

									if($rowTotalSupervised['totalSupervised'] > 5)
									{
										?>
										<option disabled title="<?php echo $rowLecturer['lecturerID']; ?>"><?php echo $rowLecturer['lecturerID']; ?></option>
										<?php
									}
									else
									{
										?>
										<option value="<?php echo $rowLecturer['lecturerID'] . $rowLecturer['name'] ?>" title="<?php echo $rowLecturer['name']; ?>">
											<?php
											echo $rowLecturer['name'] . " [" . $rowTotalSupervised['totalSupervised'] . "]";
											?>
										</option>
										<?php
									}


								}
							?>
							</select> 
						</form>
						<?php
						}else{
							
							$svName = "";
							$hasSV = "";
							$sqlGetSV = "SELECT supervisorID, lecturer.name AS svName FROM student JOIN lecturer ON student.supervisorID = lecturer.lecturerID WHERE matricNo = '".$rowNoSVorEV['matricNo']."'";
							$resultGetSV = $conn->query($sqlGetSV);
							if($resultGetSV->num_rows > 0){
								$rowSV = $resultGetSV->fetch_assoc();
								$svName = $rowSV['svName'];
								$hasSV = $rowSV['supervisorID'];
							}
							echo $svName;
							
						}
						?>
					</td>
					<td>
						<form name="assign_sv" id="formEV" method="post" action="assign-supervisor-evaluator.php?matricNo=<?php echo $rowNoSVorEV['matricNo'] ?>">
							<select id="assign-evaluator" name="assign_evaluator" title="assign evaluator" onchange="confirm_assign_ev(this, '<?php echo $rowNoSVorEV["name"] ?>');">
								<option value="" title="assign evaluator">assign evaluator</option>

								<?php
								// get records of all lecturer
								$sqlLecturer = "SELECT lecturerID, name FROM lecturer";
								$resultLecturer = $conn->query($sqlLecturer);
								while($rowLecturer = $resultLecturer->fetch_assoc())
								{	// check total supervised student for each lecturer
									$sqlTotalEvaluated = "SELECT COUNT(*) AS total FROM student WHERE evaluatorID = '".$rowLecturer['lecturerID']."'";
									$resultTotalEvaluated = $conn->query($sqlTotalEvaluated);
									$rowTotalEvaluated = $resultTotalEvaluated->fetch_assoc();

									if($rowTotalEvaluated['total'] > 5)
									{
										?>
										<option disabled title="<?php echo $rowLecturer['lecturerID']; ?>"><?php echo $rowLecturer['name']; ?></option>
										<?php
									}else if($rowLecturer['lecturerID'] == $hasSV){
										?>
										<option disabled title="<?php echo $rowLecturer['lecturerID']; ?>"><?php echo $rowLecturer['name'] . ' (SV) '; ?></option>
										<?php
									}
									else
									{
										?>
										<option value="<?php echo $rowLecturer['lecturerID'] . $rowLecturer['name'] ?>" title="<?php echo $rowLecturer['name']; ?>">
											<?php
											echo $rowLecturer['name'] . " [" . $rowTotalEvaluated['total'] . "]";
											?>
										</option>
										<?php
									}


								}
								?>
							</select>
						</form>
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

