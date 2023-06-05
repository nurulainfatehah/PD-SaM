<?php
include('../inc/dbconnect.php');

?>
<?php
if(isset($_POST['assign_sv'])){

	$matricNo = $_GET['matricNo'];
	$svID = $_POST['assign_sv'];

	$supervisorID = substr($svID, 0, 10);
	$supervisorName = substr($svID, 10);

	$sqlAssign = "UPDATE student SET supervisorID = '".$supervisorID."' WHERE matricNo = '".$matricNo."'";
	if($conn->query($sqlAssign) === TRUE){
		?>
		<script type="text/javascript">
			window.location.replace('user-management.php?reload=successAssignSV');
		</script>
		<?php
	}else{
		?>
		<script type="text/javascript">
			window.location.replace('user-management.php?reload=successAssignSV');
		</script>
		<?php
	}

}else if(isset($_POST['assign_evaluator'])){

	$matricNo = $_GET['matricNo'];
	$evaluatorID = $_POST['assign_evaluator'];

	$evaluatorID = substr($svID, 0, 10);
	$evaluatorName = substr($svID, 10);

	$sqlAssign = "UPDATE student SET evaluatorID = '".$evaluatorID."' WHERE matricNo = '".$matricNo."'";
	if($conn->query($sqlAssign) === TRUE){
		?>
		<script type="text/javascript">
			window.location.replace('user-management.php?reload=successAssignEV');
		</script>
		<?php
	}else{
		?>
		<script type="text/javascript">
			window.location.replace('user-management.php?reload=successEV');
		</script>
		<?php
	}
	
}
?>

