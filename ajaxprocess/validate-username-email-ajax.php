<?php
include('../inc/dbconnect.php');

if(isset($_POST['lecturerID_check'])){
	$lecturerID = $_POST['lecturerID'];
	$sqlLec = "SELECT lecturerID from lecturer WHERE lecturerID = '".$lecturerID."'";
	$resultLec = $conn->query($sqlLec);
	$sqlStu = "SELECT matricNo from student WHERE matricNo = '".$lecturerID."'";
	$resultStu = $conn->query($sqlStu);

	if (mysqli_num_rows($resultLec) > 0 || mysqli_num_rows($resultStu) > 0) {
  	  echo "taken";	
  	}else{

  	  echo 'not_taken';
  	}
  	exit();
}

?>