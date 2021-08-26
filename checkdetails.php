<?php 
require('connection.php');

if(!(isset($_POST['submit'])))
{
    header('location: inputnumbers.php');
}
$_SESSION['POST']=array();
$_SESSION['POST']['no_examhalls'] = $_POST['no_examhalls'];
$_SESSION['POST']['no_seats'] = $_POST['no_seats'];
$_SESSION['POST']['startdate'] = $_POST['startdate'];
$_SESSION['POST']['enddate'] = $_POST['enddate'];
$csv_mimetypes = array(
    'text/csv',
    
    'application/csv',
    'text/comma-separated-values',
    'application/excel',
    'application/vnd.ms-excel',
    'application/vnd.msexcel',
    
    'application/octet-stream',
    
);

if (in_array($_FILES['courses']['type'], $csv_mimetypes) && in_array($_FILES['students']['type'], $csv_mimetypes)) {
    
    $courses=rand(111111111,999999999).'_'.$_FILES['courses']['name'];
    move_uploaded_file($_FILES['courses']['tmp_name'], 'files/'.$courses);
    $students=rand(111111111,999999999).'_'.$_FILES['students']['name'];
    move_uploaded_file($_FILES['students']['tmp_name'], 'files/'.$students);

   
    
    
  if (($open_courses = fopen("files/".$courses, "r")) !== FALSE) 
  {
  
    while (($data_courses = fgetcsv($open_courses, 1000, ",")) !== FALSE) 
    {        
      $array_courses[] = $data_courses; 
    }
  
    fclose($open_courses);
  }
  
 
 
  for($i =1, $q=1;$i<sizeof($array_courses);$i++)
  {
      if(sizeof($array_courses[$i])==5 && ((intval($array_courses[$i][2]) <7 && intval($array_courses[$i][2]) >0)&&(intval($array_courses[$i][3]) <3 && intval($array_courses[$i][2]) >0)) && intval($array_courses[$i][4]) !== null && intval($array_courses[$i][1]) !== null)
      {
          $_SESSION['POST']['course_name'.$q] = $array_courses[$i][1];
          $_SESSION['POST']['course_sem'.$q] = intval($array_courses[$i][2]);
          $_SESSION['POST']['course_dur'.$q] = intval($array_courses[$i][3]);
          $_SESSION['POST']['course_mrks'.$q] = intval($array_courses[$i][4]);
          $q++;
      }

  }
  $_SESSION['POST']['no_courses']= $q-1;
  if (($open_students = fopen("files/".$students, "r")) !== FALSE) 
  {
  
    while (($data_students = fgetcsv($open_students, 1000, ",")) !== FALSE) 
    {        
      $array_students[] = $data_students; 
    }
  
    fclose($open_students);
  }
  
  
 
  for($i =1, $q=1;$i<sizeof($array_students);$i++)
  {
      if(sizeof($array_students[$i])==4 && (intval($array_students[$i][2])>0 && intval($array_students[$i][2])<7) && $array_students[$i][1] != null && $array_students[$i][3] != null)
      {
          $_SESSION['POST']['student_name'.$q] = $array_students[$i][1];
          $_SESSION['POST']['student_sem'.$q] = intval($array_students[$i][2]);
          $_SESSION['POST']['student_enroll'.$q] = $array_students[$i][3];
          $q++;         
      }

  }
  $_SESSION['POST']['no_students']= $q-1;
  
  if($_SESSION['POST']['no_courses'] <= 0)
  {
    ?>
    <script>
      window.location.replace("inputnumbers.php?err=1");
      </script>
      <?php
      unset($_SESSION['POST']);
      $files = glob('files/*'); // get all file names
      foreach($files as $file)
      { // iterate files
        if(is_file($file)) 
        {
          unlink($file); // delete file
        }
      }
  }
  if($_SESSION['POST']['no_students'] <= 0)
  {
    ?>
    <script>
      window.location.replace("inputnumbers.php?err=2");
      </script>
      <?php
      unset($_SESSION['POST']);
      $files = glob('files/*'); // get all file names
      foreach($files as $file)
      { // iterate files
        if(is_file($file)) 
        {
          unlink($file); // delete file
        }
      }
  }
  ?>
<script>
  window.location.replace("main.php");
  </script>
  <?php
  
}
else
{
    header('location: inputnumbers.php?err=0');
}



?>