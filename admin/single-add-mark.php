<!DOCTYPE html>
<html>
	<script src="https://code.jquery.com/jquery-3.1.1.min.js"></script>
	<link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/css/bootstrap.min.css">
	<script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/js/bootstrap.min.js"></script>
	<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-slider/9.7.2/css/bootstrap-slider.min.css">
	<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-select/1.12.1/css/bootstrap-select.css" />
	<script type="text/javascript" src="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-slider/9.7.2/bootstrap-slider.min.js"></script>
	<script src="//cdnjs.cloudflare.com/ajax/libs/bootstrap-select/1.6.3/js/bootstrap-select.min.js"></script>

	<?php 
		include "../includes/admin-navbar.php";
		include "../includes/header.php";
		include "../db_handler.php";
	?>
<head>
	<title></title>
</head>
<body>
	<div id="wrapper">
		<div id="page-wrapper">
			<div class="container-fluid">
				<div class="container">
				    <h1>Add Single Mark</h1>
				  	<hr>
					<div class="row">
				      <div class="col-md-9 personal-info">
				        <h3>Module Mark</h3>
				        <form class="form-horizontal" role="form" method="post">
                      	    <div class="form-group">
	                        	<label for="ModuleDetails" class="col-md-3 control-label">Student ID & Name:</label>
			                        <div class="col-md-8">
										<select class="form-group form-control" data-show-subtext="true" data-live-search="true" id="student" name="student" style="margin-left: -1px;" required>
											<option selected="selected" disabled>-- SELECT STUDENT --</option>
											<?php 
												$assess = mysqli_real_escape_string($conn, $_GET['id']);
												$sql = "SELECT module_code FROM assessment WHERE assessment_code = '$assess'";
												$res = mysqli_query($conn, $sql);

												while($row1 = mysqli_fetch_array($res)) {
													$moduleC = $row1['module_code'];
												}

												$sql1 = "SELECT level FROM module WHERE module_code = '$moduleC'";
												$res1 = mysqli_query($conn, $sql1);

												while($row2 = mysqli_fetch_array($res1)) {
													$level = $row2['level'];
												}

												$query = "SELECT id, name, surname FROM users WHERE level = '$level' AND rank = 'student'";
									            $result = mysqli_query($conn, $query) or die(mysqli_error($conn));
									            while ($row = mysqli_fetch_array($result)) {
									                echo "<option value='$row[0]'>$row[0] - $row[1] $row[2]</option>";
									            }
											?>        
			                            </select>
			                        </div>
	                    	</div>
                  	      <div class="form-group">
	                        <label for="AssessmentChosen" class="col-md-3 control-label">Assessment:</label>
		                        <div class="col-md-8">
		                            <select name="assessment" id="assessment" class="form-control" required>
			                            <option selected="selected" selected disabled>-- SELECT ASSESSMENT -- </option>
			                                <?php 
												$statement = "SELECT name, sub_assessment FROM assessment WHERE assessment_code = '$assess'";

									            $result = mysqli_query($conn, $statement) or die(mysqli_error($conn));
									            while ($row = mysqli_fetch_array($result)) {
									                echo "<option value='$row[0]'>Assessment: $row[0] - Sub Assessment: $row[1]</option>";
									            }
											?>
		                            </select>
	                        	</div>
	                      </div>
  				          <div class="form-group">
				            <label class="col-lg-3 control-label">Mark:</label>
				            <div class="col-lg-8">
				              <input class="form-control" type="number" id="mark" name="mark" min="0" max="100" required>
				            </div>
				          </div>				          
  				          <div class="form-group">
				            <label class="col-lg-3 control-label">Engagement:</label>
				            <div class="col-lg-8">
						        <input id="ex19" name="engagement" type="text"
						              data-provide="slider"
						              data-slider-ticks="[1, 2, 3, 4]"
						              data-slider-ticks-labels='["poor", "ok", "good", "excellent"]'
						              data-slider-min="1"
						              data-slider-max="3"
						              data-slider-step="1"
						              data-slider-value="3"
						              data-slider-tooltip="hide" required />
				            </div>
				          </div>				          
				          <div class="form-group">
				            <label class="col-lg-3 control-label">Feedback:</label>
				            <div class="col-lg-8">
				              <textarea class="form-control" onkeyup="textCounter(this,'counter',250);" type="text" id="comment" name="comment" rows="5" required></textarea>
				              <input disabled maxlength="3" size="3" value="250" id="counter">
					      		<small>Characters remaining.</small>
					      		<script>
									function textCounter(field,field2,maxlimit)
									{
									 	var countfield = document.getElementById(field2);
									 	if ( field.value.length > maxlimit ) {
									  		field.value = field.value.substring( 0, maxlimit );
									  		return false;
									 	} else {
									  		countfield.value = maxlimit - field.value.length;
									 	}
									}
						  		</script>
			            	</div>
				          </div>		          
				          <div class="form-group">
				            <label class="col-md-3 control-label"></label>
				            <div class="col-md-8">
				              <input type="submit" name="submit" class="btn btn-primary" value="Add Mark">
				              <span></span>
				              <input type="reset" class="btn btn-default" value="Cancel" onclick="goBack()">
				            </div>
				          </div>
				        </form>
				      </div>
				  </div>
				</div>
				<hr>
			</div>
		</div>
	</div>
	<script src="../js/jquery.js"></script>
	<script src="../js/bootstrap.min.js"></script>
	<script>
		function goBack() {
			window.location.href = '../home/adminHome.php';
		}
	</script>
</body>
</html>

<style type="text/css">
	#counter {
	    padding: 2px;
	    border: 1px solid #eee;
	    font: 1em 'Trebuchet MS',verdana,sans-serif;
	    color: black;
	    border: none;
	}
	textarea {
    	resize: none;
	}
</style>

<?php 
	if(isset($_POST['submit'])) {
		$id = mysqli_insert_id($conn);
		$student = mysqli_real_escape_string($conn, $_REQUEST['student']);
		$engagement = mysqli_real_escape_string($conn, $_REQUEST['engagement']);
		$comments = mysqli_real_escape_string($conn, $_REQUEST['comment']);
		$markGiven = mysqli_real_escape_string($conn, $_REQUEST['mark']);
		$suba = mysqli_real_escape_string($conn, $_REQUEST['assessment']);

		// assess - Assessment code

		$module = $moduleC;
		
		$get = "SELECT mark1, mark2, mark3, final_mark, sub_assessment, feedback FROM marks WHERE student_id = '$student' AND module_code = '$module'";
		$res = mysqli_query($conn, $get);
		$given = mysqli_fetch_array($res);

		$mark1 = $given['mark1'];
		$mark2 = $given['mark2'];
		$mark3 = $given['mark3'];
		$final = $given['final_mark'];
		$subadb = $given['sub_assessment'];
		$feedback = $given['feedback'];
		$feedbackOverall = $feedback . $comments;

		if ($subadb == $suba) {
			if ($mark1 == 0) {
				if ($engagement == '1') {
				$query = "INSERT INTO marks (mark_id, module_code, assessment_code, sub_assessment, student_id, mark1, mark2, mark3, final_mark, engagement, feedback) VALUES ('" . $id . "', '" . $module . "', '" . $assess . "', '" . $suba . "', '" . $student . "', '" . $markGiven . "', '0', '0', '" . $markGiven . "', 'Poor', '" . $comments . "')";
				}

				if ($engagement == '2') {
					$query = "INSERT INTO marks (mark_id, module_code, assessment_code, sub_assessment, student_id, mark1, mark2, mark3, final_mark, engagement, feedback) VALUES ('" . $id . "', '" . $module . "', '" . $assess . "', '" . $suba . "', '" . $student . "', '" . $markGiven . "', '0', '0', '" . $markGiven . "', 'Ok', '" . $comments . "')";
				}

				if ($engagement == '3') {
					$query = "INSERT INTO marks (mark_id, module_code, assessment_code, sub_assessment, student_id, mark1, mark2, mark3, final_mark, engagement, feedback) VALUES ('" . $id . "', '" . $module . "', '" . $assess . "', '" . $suba . "', '" . $student . "', '" . $markGiven . "', '0', '0', '" . $markGiven . "', 'Good', '" . $comments . "')";
				}

				if ($engagement == '4') {
					$query = "INSERT INTO marks (mark_id, module_code, assessment_code, sub_assessment, student_id, mark1, mark2, mark3, final_mark, engagement, feedback) VALUES ('" . $id . "', '" . $module . "', '" . $assess . "', '" . $suba . "', '" . $student . "', '" . $markGiven . "', '0', '0', '" . $markGiven . "', 'Excellent', '" . $comments . "')";
				}
			}

			else if ($mark1 != 0) {
				if ($mark2 != 0) {
					$markCheck = $mark1 - $mark2;

					if ($markCheck >= 10 || $mark1 < 40 || $mark2 < 40) {
						$query = "UPDATE marks SET mark3 = '$markGiven', final_mark = '$markGiven', feedback = '$feedbackOverall' WHERE student_id = '$student' AND module_code = '$module'";
					}

					else { 
						$emessageS = "Student already has been marked. This mark will therfore not save.";
						echo "<script type='text/javascript'>alert('$emessageS');goBack();</script>";
					}
				}

				else {
					$finalMark = $mark1 + $markGiven;
						$query = "UPDATE marks SET mark2 = '$markGiven', final_mark = '$finalMark', feedback = '$feedbackOverall' WHERE student_id = '$student' AND module_code = '$module'";
				}
			}		
		}	

		else if ($subadb != $suba) {
			if ($engagement == '1') {
				$query = "INSERT INTO marks (mark_id, module_code, assessment_code, sub_assessment, student_id, mark1, mark2, mark3, final_mark, engagement, feedback) VALUES ('" . $id . "', '" . $module . "', '" . $assess . "', '" . $suba . "', '" . $student . "', '" . $markGiven . "', '0', '0', '" . $markGiven . "', 'Poor', '" . $comments . "')";
			}

			if ($engagement == '2') {
				$query = "INSERT INTO marks (mark_id, module_code, assessment_code, sub_assessment, student_id, mark1, mark2, mark3, final_mark, engagement, feedback) VALUES ('" . $id . "', '" . $module . "', '" . $assess . "', '" . $suba . "', '" . $student . "', '" . $markGiven . "', '0', '0', '" . $markGiven . "', 'Ok', '" . $comments . "')";
			}

			if ($engagement == '3') {
				$query = "INSERT INTO marks (mark_id, module_code, assessment_code, sub_assessment, student_id, mark1, mark2, mark3, final_mark, engagement, feedback) VALUES ('" . $id . "', '" . $module . "', '" . $assess . "', '" . $suba . "', '" . $student . "', '" . $markGiven . "', '0', '0', '" . $markGiven . "', 'Good', '" . $comments . "')";
			}

			if ($engagement == '4') {
				$query = "INSERT INTO marks (mark_id, module_code, assessment_code, sub_assessment, student_id, mark1, mark2, mark3, final_mark, engagement, feedback) VALUES ('" . $id . "', '" . $module . "', '" . $assess . "', '" . $suba . "', '" . $student . "', '" . $markGiven . "', '0', '0', '" . $markGiven . "', 'Excellent', '" . $comments . "')";
			}
		}
		
		$result = mysqli_query($conn, $query) or die(mysqli_error($conn));
		mysqli_close($conn);
		echo "<script>goBack();</script>";
	}
?>