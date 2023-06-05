<?php
include('../inc/dbconnect.php');
session_start();

if(isset($_POST['querySearchSupervision'])){
	$search = mysqli_real_escape_string($conn, $_POST["querySearchSupervision"]);
	$querySearchStudent = "";

	if($_POST['opt'] == "nameAZ"){
		$querySearch = "SELECT lecturer.name AS lecturer, lecturer.lecturerID AS lecturerID FROM student INNER JOIN lecturer ON student.supervisorID = lecturer.lecturerID WHERE (lecturer.name LIKE '%".$search."%' OR student.name LIKE '%".$search."%' OR lecturer.lecturerID LIKE '%".$search."%') GROUP BY lecturerID ORDER BY lecturer ASC";

	}else if($_POST['opt'] == "nameZA"){
		$querySearch = "SELECT lecturer.name AS lecturer, lecturer.lecturerID AS lecturerID FROM student INNER JOIN lecturer ON student.supervisorID = lecturer.lecturerID WHERE (lecturer.name LIKE '%".$search."%' OR student.name LIKE '%".$search."%' OR lecturer.lecturerID LIKE '%".$search."%') GROUP BY lecturerID ORDER BY lecturer DESC";
	}else{
		$querySearch = "SELECT lecturer.name AS lecturer, lecturer.lecturerID AS lecturerID FROM student INNER JOIN lecturer ON student.supervisorID = lecturer.lecturerID WHERE (lecturer.name LIKE '%".$search."%' OR student.name LIKE '%".$search."%' OR lecturer.lecturerID LIKE '%".$search."%') GROUP BY lecturerID ORDER BY lecturer ASC";
	}

}else{
	$querySearch = "SELECT lecturer.name AS lecturer, lecturer.lecturerID AS lecturerID FROM student INNER JOIN lecturer ON student.supervisorID = lecturer.lecturerID GROUP BY lecturerID ORDER BY lecturer ASC";
}

$result =$conn->query($querySearch);

if($result->num_rows > 0){
	?>
	<a id="print-btn" target="_blank" href="print/print-supervision-list.php"><button>Print</button></a><br><br>
	<table align="center">
		<tr>
			<th colspan="5">SUPERVISION LIST</th>
		</tr>		
		<tr>
			<th>NO.</th>
			<th>#</th>
			<th width="50%">NAME</th>
			<th width="40%">SUPERVISOR</th>
			
		</tr>
		<?php
		$totalGroup = 0;
		while($rowSupervisor = $result->fetch_assoc()){
			$totalSVS = 0;
			$totalGroup++;
			$sqlSVStudent = "SELECT student.name AS student FROM student, lecturer WHERE lecturerID = '".$rowSupervisor['lecturerID']."' AND student.supervisorID = '".$rowSupervisor['lecturerID']."' ";

			$resultSVStudent = $conn->query($sqlSVStudent);
			$totalSVS = $resultSVStudent->num_rows;
			$totalStudentList = 0;
			
			for($i = 0; $i < $totalSVS; $i++){}
			while($rowSVS = $resultSVStudent->fetch_assoc()){
				if($totalStudentList <= $totalSVS)
					$totalStudentList++;
				?>
				<tr>
					<?php 
					if($totalStudentList == 1){
						?>
						<td align="center" rowspan="<?php echo $totalSVS ?>" colspan="1"><?php echo $totalGroup ?></td>
						<?php
					}
					?>	
					<td align="center"><?php echo $totalStudentList ?></td>
					<td><?php echo $rowSVS['student'] ?></td>
					<?php 
					if($totalStudentList == 1){
						?>
						<td rowspan="<?php echo $totalSVS ?>" colspan="1"><?php echo $rowSupervisor['lecturer'] ?></td>
						<?php
					}
					?>					
				</tr>

				<?php
			}
			
		}

		?>
	</table>
	<?php
}else{
	?>
	<center><i class="remarksnote">0 record of supervision assignment was found</i></center>
	<?php
}
?>