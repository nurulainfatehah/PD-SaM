<?php
include('../inc/dbconnect.php');
session_start();

if(isset($_POST['registerlecturer_check'])){
	$lecturerID = strtoupper($_POST['lecturerID']);
	$name = strtoupper($_POST['name']);
	$email = $_POST['email'];
	$officePhone = $_POST['officePhone'];

	$sql = "INSERT INTO lecturer (lecturerID, name, email, officePhone) VALUES ('".$lecturerID."', '".$name."', '".$email."', '".$officePhone."')" ;

	if($conn->query($sql) === TRUE){
		$sql2 = "INSERT INTO chatroom_list (participantID, chatroomID) VALUES ('".$lecturerID."', 2)";
			if($conn->query($sql2) === TRUE){
				echo "registered";
			}
	}else
		echo "notregistered";
}else if(isset($_POST['deletelecturer_check'])){
	$lecturerID = $_POST['lecturerID'];
	$sql2 = "DELETE FROM chatroom_list WHERE participantID = '".$lecturerID."'";
	if($conn->query($sql2) === TRUE){
		$sql = "DELETE FROM lecturer WHERE lecturerID = '".$lecturerID."'" ;
		if($conn->query($sql) === TRUE){
			echo "deleted";
		}else
			echo "notdeleted";
	}
}else if(isset($_POST['registerstudent_check'])){
	$matricNo = strtoupper($_POST['matricNo']);
	$name = strtoupper($_POST['name']);
	$email = $_POST['email'];
	$sectionGroup = $_POST['sectionGroup'];
	$phone = $_POST['phone'];

	$sql = "INSERT INTO student (matricNo, name, email, sectionGroup, phone) VALUES ('".$matricNo."', '".$name."', '".$email."', '".$sectionGroup."', '".$phone."')";
	if($conn->query($sql) === TRUE){
		$sql2 = "INSERT INTO chatroom_list (participantID, chatroomID) VALUES ('".$matricNo."', 1)";
		if($conn->query($sql2) === TRUE){
			echo "registered";
		}else
			echo "notregistered";
	}
}else if(isset($_POST['deletestudent_check'])){
	$matricNo = $_POST['matricNo'];
	$sql2 = "DELETE FROM chatroom_list WHERE participantID = '".$matricNo."'";
	if($conn->query($sql2) === TRUE){
		$sql = "DELETE FROM student WHERE matricNo = '".$matricNo."'";
		if($conn->query($sql) === TRUE){
			echo "deleted";
		}else
			echo "notdeleted";
	}
}

?>