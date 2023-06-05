<?php
session_start();
include('../inc/dbconnect.php');

if(isset($_POST['querySearchGuideline'])){

	$search = mysqli_real_escape_string($conn, $_POST["querySearchGuideline"]);
	$querySearchGuideline = "";

	if($_POST['opt'] == "titleAZ"){
		$querySearch = "SELECT * FROM guideline INNER JOIN lecturer ON guideline.uploadedBy = lecturer.lecturerID AND (lecturer.name LIKE '%".$search."%' OR title LIKE '%".$search."%' OR attachment LIKE '%".$search."%' or lecturerID LIKE '%".$search."%' OR uploadDateTime LIKE '%".$search."%') ORDER BY title";

	}else if($_POST['opt'] == "titleZA"){
		$querySearch = "SELECT * FROM guideline INNER JOIN lecturer ON guideline.uploadedBy = lecturer.lecturerID AND (lecturer.name LIKE '%".$search."%' OR title LIKE '%".$search."%' OR attachment LIKE '%".$search."%' or lecturerID LIKE '%".$search."%' OR uploadDateTime LIKE '%".$search."%') ORDER BY title DESC";
	}else{
		$querySearch = "SELECT * FROM guideline INNER JOIN lecturer ON guideline.uploadedBy = lecturer.lecturerID AND (lecturer.name LIKE '%".$search."%' OR title LIKE '%".$search."%' OR attachment LIKE '%".$search."%' or lecturerID LIKE '%".$search."%' OR uploadDateTime LIKE '%".$search."%') ORDER BY title DESC";
	}
}else{
	$querySearch = "SELECT * FROM guideline INNER JOIN lecturer ON guideline.uploadedBy = lecturer.lecturerID ORDER BY title DESC";
}

$result = $conn->query($querySearch);
if($result->num_rows > 0)
{
	while($rowGuideline = $result->fetch_assoc())
	{
		?>
		<div style="display: inline-block; width: 130px; height: 160px;  align-items: center; vertical-align: middle;" align="center" >
			<a style="float: right; margin-right: 19px;" title="delete" onclick="deleteGuideline(this, '<?php echo $rowGuideline["title"] ?>')" id="<?php echo $rowGuideline['guidelineID'] ?>"><i id="minustrash" class="fa fa-minus-circle" aria-hidden="true"></i></a>
			<br>
			<a title="<?php echo $rowGuideline['title'] ?>" download="<?php echo $rowGuideline['attachment'] ?>" href="../attachment/guideline/<?php echo $rowGuideline['attachment'] ?>">
				<?php
				if (preg_match('/\bpdf\b/', $rowGuideline['attachment'])) {
					?>

					<img src="../attachment/imgsource/pdf.png" width="40px" height="50px">

					<?php
				}
				else if(preg_match('/\bpng\b/', $rowGuideline['attachment']))
				{
					?>

					<img src="../attachment/imgsource/png.png" width="40px" height="50px">

					<?php 
				}
				else if(preg_match('/\bjpg\b/', $rowGuideline['attachment']) || preg_match('/\bjpeg\b/', $rowGuideline['attachment']))
				{
					?>

					<img src="../attachment/imgsource/jpg.png" width="50px" height="50px">

					<?php
				}
				?> 
				<?php
				$sqlPensyarahPanduan = "SELECT name FROM lecturer WHERE lecturerID = '".$rowGuideline['lecturerID']."'";

				$newHour = date("h:i a", strtotime($rowGuideline['uploadDateTime']));
				$newDate = date("d-m-Y", strtotime($rowGuideline['uploadDateTime'])); 
				$resultPensyarahPanduan = $conn->query($sqlPensyarahPanduan);
				$rowPensyarahPanduan = $resultPensyarahPanduan->fetch_assoc();
				echo "<br>" . $rowGuideline['title'] . "</a><br>";
				echo $newDate . "<br> ";
				echo $newHour . "<br> ";
				echo "<i style='color:gray'>" . $rowPensyarahPanduan['name'] . "</i>" ;	

				?>
			</div>
			<?php	
		}
}else
{
	$output ='<center><i class="remarksnote">0 record of guideline was found</i></center>';
	echo $output;
}

?>