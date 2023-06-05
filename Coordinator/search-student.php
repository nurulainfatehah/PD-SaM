<?php
include('../inc/dbconnect.php');
session_start();
$total = 0;

if(isset($_POST['querySearchStudent'])){
	$search = mysqli_real_escape_string($conn, $_POST["querySearchStudent"]);
	$querySearchStudent = "";
	if($_POST['opt'] == "nameAZ"){
		$querySearch = "SELECT * FROM student WHERE (name LIKE '%".$search."%' OR matricNo LIKE '%".$search."%' OR sectionGroup LIKE '%".$search."%' OR phone LIKE '%".$search."%' or projectTitle LIKE '%".$search."%') ORDER BY name";
	}else if($_POST['opt'] == "nameZA"){
		$querySearch = "SELECT * FROM student WHERE (name LIKE '%".$search."%' OR matricNo LIKE '%".$search."%' OR sectionGroup LIKE '%".$search."%' OR phone LIKE '%".$search."%' or projectTitle LIKE '%".$search."%') ORDER BY name DESC";
	}else if($_POST['opt'] == "matricAZ"){
		$querySearch = "SELECT * FROM student WHERE (name LIKE '%".$search."%' OR matricNo LIKE '%".$search."%' OR sectionGroup LIKE '%".$search."%' OR phone LIKE '%".$search."%' or projectTitle LIKE '%".$search."%') ORDER BY matricNo";

	}else if($_POST['opt'] == "matricZA"){
		$querySearch = "SELECT * FROM student WHERE (name LIKE '%".$search."%' OR matricNo LIKE '%".$search."%' OR sectionGroup LIKE '%".$search."%' OR phone LIKE '%".$search."%' or projectTitle LIKE '%".$search."%') ORDER BY matricNo DESC";
	}else{
		$querySearch = "SELECT * FROM student WHERE (name LIKE '%".$search."%' OR matricNo LIKE '%".$search."%' OR sectionGroup LIKE '%".$search."%' OR phone LIKE '%".$search."%' or projectTitle LIKE '%".$search."%') ORDER BY matricNo";
	}
}else{
	$querySearch = "SELECT * FROM student ORDER BY name";
}

$result = $conn->query($querySearch);
if($result->num_rows > 0){
	?>
	<script type="text/javascript">
		function viewStudent(obj, name){
			var matricNo = obj.id;
			var name = name;
			$.confirm({
				boxWidth: '60%',
				useBootstrap: false,			
				title: name,
				cancelButton: false,
				content: 'url:../ajaxprocess/viewStudent.php?matricNo=' + matricNo,
				buttons: {
					ok:{        
						isHidden: true, // hide the button       
					},
					 close: function () {
        			}
				}
				// onContentReady: function () {
				// 	/*var self = this;
				// 	this.setContentPrepend('<div>Prepended text</div>');
				// 	setTimeout(function () {
				// 		self.setContentAppend('<div>Appended text after 2 seconds</div>');
				// 	}, 2000);*/
				// },

			});
		}

	</script>
	<a id="print-btn" target="_blank" href="print/print-student-list.php"><button>Print</button></a><br><br>
	<table align="center">
		<tr>
			<th width="5%">No.</th>
			<th width="10%">Matric No.</th>
			<th>Full Name</th>
			<th width="7%" >S/G</th>
			<th width="10%">Phone</th>
			<th width="10%">Action</th>
		</tr>
		<?php
		while($row = $result->fetch_assoc()){
			$total++
			?>
			<tr>
				<td align="center"><?php echo $total ?></td>
				<td align="center"><?php echo $row['matricNo'] ?></td>
				<td><?php echo $row['name'] ?></td>
				<td align="center"><?php echo $row['sectionGroup'] ?></td>
				<td align="center"><?php echo $row['phone'] ?></td>
				<td align="center">
				<?php
					if(empty($row['supervisorID'])){
						?>

						<a title="remove <?php echo strtolower($row['name']) ?>" onclick="deleteStudent(this, '<?php echo $row["name"] ?>')" id="<?php echo $row['matricNo'] ?>" class="oppose"><i class="fa fa-trash" aria-hidden="true"></i></a>
						<a href="#table_student" title="view <?php echo strtolower($row['name']) ?>" onclick="viewStudent(this,'<?php echo $row["name"] ?>')" id="<?php echo $row['matricNo'] ?>" ><i class="fa fa-search" aria-hidden="true"></i></a>
						<?php
					}else{
						?>
						<a title="cannot be removed as it associates with other important data" style="cursor: default; color: grey"><i class="fa fa-trash" aria-hidden="true"></i></a>
						<a href="#table_student" title="view <?php echo strtolower($row['name']) ?>" onclick="viewStudent(this,'<?php echo $row["name"] ?>')" id="<?php echo $row['matricNo'] ?>" ><i class="fa fa-search" aria-hidden="true"></i></a>
						<?php
					}
				?>
				</td>
			</tr>
			<?php
		}
		?>
	</table>
	<br><center style="font-style: italic"><?php echo $result->num_rows; ?> record(s) found.</center>
	<?php

}else{
	?>
	<center><i class="remarksnote">0 record of student was found</i></center>
	<?php
}

?>

