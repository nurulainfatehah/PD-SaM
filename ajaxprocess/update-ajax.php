<?php
if(isset($_POST['updatecoordinator_check'])){
	$lecturerID = $_POST['lecturerID'];
	$email = $_POST['email'];
	$officePhone = $_POST['officePhone'];
	$password = $_POST['password'];

	$sql = "UPDATE lecturer SET email = '".$email."', officePhone = '".$officePhone."', password = '".$password."' WHERE lecturerID = '".$lecturerID."'";

	if($conn->query($sql) === TRUE){
		echo "updated";	

	}else{
		echo "notupdated";	
	}

	exit();

}


?>