<?php
session_start();
include('../inc/dbconnect.php');

$lecturerID = $_SESSION['username'];
$_SESSION['openDiv'] = "guideline"; 
$uploadDateTime = date('Y-m-d H:i:s', time()+28800);


if(isset($_POST['deleteguideline_check'])){
	$guidelineID = $_POST['guidelineID'];
	$sql = "DELETE FROM guideline WHERE guidelineID = '".$guidelineID."'";

	if($conn->query($sql)){
		echo "deleted";
	}else{
		echo "notdeleted";
	}
}
?>