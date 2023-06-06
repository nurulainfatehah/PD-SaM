<?php
include('../inc/dbconnect.php');
session_start();
$lecturerID = $_SESSION['username'];

if(isset($_GET['matricNo'])){

	$matricNo = $_GET['matricNo'];
	$sqlStudent = "SELECT * FROM student WHERE matricNo = '".$matricNo."'";
	$resultStudent = $conn->query($sqlStudent);
	$row = $resultStudent->fetch_assoc();

	$sqlSupervisor = "SELECT * FROM lecturer WHERE lecturerID = '".$row['supervisorID']."'";
	$resultSupervisor = $conn->query($sqlSupervisor);
	$rowSupervisor = $resultSupervisor->fetch_assoc();

	$sqlEvaluator = "SELECT * FROM lecturer WHERE lecturerID = '".$row['evaluatorID']."'";
	$resultEvaluator = $conn->query($sqlEvaluator);
	$rowEvaluator = $resultEvaluator->fetch_assoc();

	$type = "";
	if($lecturerID == $row['supervisorID'])
	{
		$type = "supervisor";
	}
	else if($lecturerID == $row['evaluatorID'])
	{
		$type = "evaluator";
	}

	$sqlFormList = "SELECT * FROM progress_form";
	$resultFormList = $conn->query($sqlFormList);

	?>

	<!DOCTYPE html>
	<html>
	<head>
		<title>Assessment - <?php echo $row['matricNo']?></title>
		
		<link rel="stylesheet" type="text/css" href="../css/assessment.css">
		<script type="text/javascript" src="//code.jquery.com/jquery-1.9.1.js"></script>
		<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/jquery-confirm/3.3.2/jquery-confirm.min.css">
		<script src="https://cdnjs.cloudflare.com/ajax/libs/jquery-confirm/3.3.2/jquery-confirm.min.js"></script>
		<script type="text/javascript">
			function noProjectTitle(){
				$.confirm({
					boxWidth: '27%',
					title: 'Missing Project Title',
					content: 'Please remind the student to specify their Projek Diploma\'s title.',
					type: 'red',
					typeAnimated: true,
					useBootstrap: false,
					buttons: {
						tryAgain: {
							text: 'Try again',
							btnClass: 'btn-red',
							action: function(){
									window.close();
							}
						}
					}
				});
			}
		</script>
	</head>
<?php
	if(empty($row['projectTitle']))
	{
		$_SESSION['BukaDiv'] = "Penilaian";
		echo "<body onload='return noProjectTitle();'>";
	}
	else
	{
		echo "<body>";

	}
?>
	<div class="holder">
		Borang Penilaian <br>Projek Diploma
	</div>
	<div style=" " class="penilaianbody">
		<table cellpadding="3" width="50%" style=" margin-top: 10px">
			<tr>
				<td class="tdtitle">
					SUPERVISOR
				</td>
				<td class="inputtr" colspan="3">
					<?php
					if($resultSupervisor->num_rows == 0)
					{
						echo "<i style='color:gray'>not assigned yet</i>";
					}
					else
					{
						echo $rowSupervisor['name'];
					}
					
					?>
				</td>
			</tr>
			<tr>
				<td class="tdtitle">
					EVALUATOR
				</td>
				<td class="inputtr" colspan="3">
					<?php
					if(empty($row['evaluatorID']))
					{
						echo "<i style='color:gray'>not assigned yet</i>";
					}
					else
					{
						echo $rowEvaluator['name'];
					}
					
					?>
				</td>
			</tr>
			<tr>
				<td class="tdtitle">
					MATRIC NO.
				</td>
				<td class="inputtr" colspan="3">
					<?php echo $row['matricNo']?>
				</td>
			</tr>
			<tr>
				<td class="tdtitle">
					STUDENT
				</td>
				<td class="inputtr" colspan="3">
					<?php echo $row['name']?>
				</td>
			</tr>
			<tr>
				<td class="tdtitle">
					PHONE
				</td>
					 
				<td class="inputtr"  colspan="3">
					<?php echo $row['phone']?>
				</td>
			</tr>
		</table>

		<select id="select-form" style="width">
			<option title="-- Choose Assessment Form --" selected value="">-- Choose Assessment Form --</option>
			<option title="OBE Summary" value="obeSummary">OBE Summary</option>
			<?php
			$nForm = 0;
			while($rowForm = $resultFormList->fetch_assoc()){
				$nForm++;
				if($nForm == 4){
					?>
					<option title="Status (week 10)" value="status">Status (week 10)</option>
					<?php
				}
				if($rowForm['formRole'] == strtoupper($type)){
					?>
					<option title="<?php echo ucwords(strtolower($rowForm['formName'])); ?>" value="<?php $rowForm['progressFormID'] ?>"><?php echo ucwords(strtolower($rowForm['formName'])); ?></option>
					<?php
				}else{
					?>
					<option disabled title="<?php echo ucwords(strtolower($rowForm['formName'])); ?>"><?php echo ucwords(strtolower($rowForm['formName'])); ?></option>
					<?php
				}
			}
			?>
		</select>

		<div id="show_form">
		</div>
	</div>
	<script type="text/javascript">
		$(document).ready(function(){
			load_form();

			/*js load selected form */
			//=================================================================
			function load_form(opt){
				$.ajax({
					url:"progress-form.php",
					method:"POST",
					data:{
						opt: opt
					},
					success:function(data)
					{
						$('#show_form').html(data);
					}
				});
			}

			$('#select-form').change(function (){
				var opt = $('#select-form').val();
				if(opt != '')
				{
					load_student(opt);
				}
				else
				{
					load_student();
				}

			});
		});
	</script>
	<?php
	include('../footer.php');
	?>
	</body>
	</html>
	<?php
}

?>