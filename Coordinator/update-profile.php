<?php
include('../inc/dbconnect.php');
session_start();
$lecturerID = $_SESSION['username'];

$email = $_POST['email'];
$officePhone = $_POST['officePhone'];
$password = $_POST['password1'];

$sqlKemaskiniprofile = "UPDATE lecturer SET email = '".$email."', officePhone = '".$officePhone."', password = '".$password."' WHERE lecturerID = '".$lecturerID."'";

?>
<title>Updating profile...</title>
<script type="text/javascript">
	function berjayaKemaskini()
	{
		$.confirm({
			boxWidth: '27%',
  			title: 'Successful profile update',
		    type: 'green',
		    typeAnimated: true,
		    useBootstrap: false,
		    buttons: {
		        ok: {
		        	boxWidth: '27%',
		            text: 'Okay',
		            btnClass: 'btn-green',		            
		            action: function(){
		            	window.location.replace('profile.php');
		            }
		        }
		    }
		});
	}

	function gagalKemaskini()
	{
		$.confirm({
			boxWidth: '27%',
			title: 'Failed to update',
			content: 'A problem occurs when updating. Please try again.',
			type: 'red',
			typeAnimated: true,
			useBootstrap: false,
			buttons: {
				tryAgain: {
					text: 'Try again',
					btnClass: 'btn-red',
					action: function(){
						window.location.replace('profile.php');
					}
				}
			}
		});
	}
</script>
<?php 

if($conn->query($sqlKemaskiniprofile) === TRUE)
{

	?>
	<body onload="return berjayaKemaskini();"></body>
	<?php
}
else
{
	?>
	<body onload="return gagalKemaskini();"></body>
	<?php
}

?>