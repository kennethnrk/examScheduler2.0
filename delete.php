<?php
require ('connection.php');

$sql = "TRUNCATE TABLE `student_list`";
mysqli_query($con, $sql);
$sql = "TRUNCATE TABLE `course_list`";
mysqli_query($con, $sql);
$sql = "TRUNCATE TABLE `exams`";
mysqli_query($con, $sql);
$sql = "TRUNCATE TABLE `slots`";
mysqli_query($con, $sql);
$sql = "TRUNCATE TABLE `seats`";
mysqli_query($con, $sql);

$files = glob('files/*'); // get all file names
foreach($files as $file){ // iterate files
  if(is_file($file)) {
    unlink($file); // delete file
  }
}

header("location: inputnumbers.php");
?>