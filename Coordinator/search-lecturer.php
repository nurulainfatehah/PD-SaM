<?php
include('../inc/dbconnect.php');
session_start();
$usernameInSession = $_SESSION['username'];

$total = 0;

if(isset($_POST['querySearchLecturer'])){
	$search = mysqli_real_escape_string($conn, $_POST["querySearchLecturer"]);
	$querySearchLecturer = "";

	if($_POST['opt'] == "idAZ"){
		$querySearch = "SELECT * FROM lecturer WHERE (name LIKE '%".$search."%' OR lecturerID LIKE '%".$search."%' OR status LIKE '%".$search."%' or email LIKE '%".$search."%' OR officePhone LIKE '%".$search."%') ORDER BY lecturerID";
	}else if($_POST['opt'] == "idZA"){
		$querySearch = "SELECT * FROM lecturer WHERE (name LIKE '%".$search."%' OR lecturerID LIKE '%".$search."%' OR status LIKE '%".$search."%' or email LIKE '%".$search."%' OR officePhone LIKE '%".$search."%') ORDER BY lecturerID DESC";
	}else if($_POST['opt'] == "nameAZ"){
		$querySearch = "SELECT * FROM lecturer WHERE (name LIKE '%".$search."%' OR lecturerID LIKE '%".$search."%' OR status LIKE '%".$search."%' or email LIKE '%".$search."%' OR officePhone LIKE '%".$search."%') ORDER BY name";
	}else if($_POST['opt'] == "nameZA"){
		$querySearch = "SELECT * FROM lecturer WHERE (name LIKE '%".$search."%' OR lecturerID LIKE '%".$search."%' OR status LIKE '%".$search."%' or email LIKE '%".$search."%' OR officePhone LIKE '%".$search."%') ORDER BY name DESC";
	}else if($_POST['opt'] == "roleCoordinator"){
		$querySearch = "SELECT * FROM lecturer WHERE role = 'COORDINATOR' AND (name LIKE '%".$search."%' OR lecturerID LIKE '%".$search."%' OR status LIKE '%".$search."%' or email LIKE '%".$search."%' OR officePhone LIKE '%".$search."%') ORDER BY lecturerID DESC";
	}else if($_POST['opt'] == "roleCoordinator"){
		$querySearch = "SELECT * FROM lecturer WHERE role = 'COORDINATOR' AND (name LIKE '%".$search."%' OR lecturerID LIKE '%".$search."%' OR status LIKE '%".$search."%' or email LIKE '%".$search."%' OR officePhone LIKE '%".$search."%') ORDER BY lecturerID DESC";
	}else if($_POST['opt'] == "roleSupervisor"){
		$querySearch = "SELECT * FROM lecturer WHERE role = 'SUPERVISOR' AND (name LIKE '%".$search."%' OR lecturerID LIKE '%".$search."%' OR status LIKE '%".$search."%' or email LIKE '%".$search."%' OR officePhone LIKE '%".$search."%') ORDER BY lecturerID DESC";
	}else{
		$querySearch = "SELECT * FROM lecturer WHERE (name LIKE '%".$search."%' OR lecturerID LIKE '%".$search."%' OR status LIKE '%".$search."%' or email LIKE '%".$search."%' OR officePhone LIKE '%".$search."%') ORDER BY lecturerID";
	}
}else{
		$querySearch = "SELECT * FROM lecturer ORDER BY lecturerID";
}


$result = $conn->query($querySearch);
if($result->num_rows > 0)
{
	?>
	<a id="print-btn" target="_blank" href="print/print-lecturer-list.php"><button>Print</button></a><br><br>
	<table align="center">
		<tr>
			<th width="5%">No.</th>
			<th width="10%">Staff ID</th>
			<th>Full Name</th>
			<th width="13%" >Office No.</th>
			<th width="10%">Status</th>
			<th width="15%">Role</th>
			<th width="10%">Action</th>
		</tr>
	<?php
	while($row = $result->fetch_assoc()){
		$sqlFKassociated = "SELECT * FROM lecturer WHERE lecturerID='".$row['lecturerID']."' AND lecturer.lecturerID IN (SELECT supervision_request.lecturerID FROM supervision_request)" ;
		$resultFK = $conn->query($sqlFKassociated);
		$total++;
		if($row['lecturerID'] == $usernameInSession){
			?>
			<tr>
				<td align="center"><?php echo $total ?></td>
				<td align="center"><?php echo $row['lecturerID'] ?></td>
				<td><?php echo $row['name'] ?></td>
				<td align="center"><?php echo $row['officePhone'] ?></td>
				<td align="center"><?php echo $row['status'] ?></td>
				<td align="center"><?php echo $row['role'] ?></td>
				<td align="center">
					<a title="view <?php echo $row['name'] ?>" onclick="viewLecturer(this)" id="<?php echo $row["lecturerID"] ?>"><i class="fa fa-search" aria-hidden="true"></i></a>
				</td>
			</tr>
			<?php
		}else if($resultFK->num_rows > 0){
			?>
			<tr>
				<td align="center"><?php echo $total ?></td>
				<td align="center"><?php echo $row['lecturerID'] ?></td>
				<td><?php echo $row['name'] ?></td>
				<td align="center"><?php echo $row['officePhone'] ?></td>
				<td align="center"><?php echo $row['status'] ?></td>
				<td align="center"><?php echo $row['role'] ?></td>
				<td align="center">
					<a  style="cursor: default; color: gray" ><i title="Lecturer cannot be removed as it associates with important record" class="fa fa-trash" aria-hidden="true"></i></a>
					<a href="#table_lecturer" title="view <?php echo strtolower($row['name']) ?>" onclick="viewLecturer(this)" id="<?php echo $row["lecturerID"] ?>"><i class="fa fa-search" aria-hidden="true"></i></a>
				</td>
			</tr>
			<?php
		}else{
		?>
			<tr>
				<td align="center"><?php echo $total ?></td>
				<td align="center"><?php echo $row['lecturerID'] ?></td>
				<td><?php echo $row['name'] ?></td>
				<td align="center"><?php echo $row['officePhone'] ?></td>
				<td align="center"><?php echo $row['status'] ?></td>
				<td align="center"><?php echo $row['role'] ?></td>
				<td align="center">
					<a title="remove <?php echo strtolower($row['name']) ?>" onclick="deleteLecturer(this, '<?php echo $row["name"] ?>')" id="<?php echo $row['lecturerID'] ?>" class="oppose"><i class="fa fa-trash" aria-hidden="true"></i></a>
					<a href="#table_lecturer" title="view <?php echo strtolower($row['name']) ?>" onclick="viewLecturer(this)" id="<?php echo $row['lecturerID'] ?>" ><i class="fa fa-search" aria-hidden="true"></i></a>
				</td>
			</tr>
		<?php
		}
	}

	?>
	</table>
	<br><center style="font-style: italic"><?php echo $result->num_rows; ?> record(s) found.</center>
	<?php
}else{
	?>
	<center><i class="remarksnote">0 record of lecturer was found</i></center>
	<?php
}
?>