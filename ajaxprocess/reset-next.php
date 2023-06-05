<?php 
  include('../inc/dbconnect.php');

  if (isset($_POST['username_check'])) {
  	$username = $_POST['username'];
    $inputkey = $_POST['inputkey'];

  	$sqlLec = "SELECT lecturerID FROM lecturer WHERE lecturerID='$username' AND tempPassword = '$inputkey'";
  	$resultLec = $conn->query($sqlLec);

    $sqlStu = "SELECT matricNo FROM student WHERE matricNo='$username' AND tempPassword = '$inputkey'";
    $resultStu = $conn->query($sqlStu);

  	if (mysqli_num_rows($resultLec) > 0 || mysqli_num_rows($resultStu) > 0) {
  	  echo "exist";	
  	}else{

  	  echo 'notexist';
  	}
  	exit();

  }

 ?>