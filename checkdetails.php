<?php 
require('connection.php');

if(!(isset($_POST['submit'])))
{
    header('location: inputnumbers.php');//checking of data has been submitted
}

//forwarding required post values using session variable
$_SESSION['POST']=array();
$_SESSION['POST']['no_depts'] = $_POST['no_depts'];
$_SESSION['POST']['term'] = $_POST['term'];
$_SESSION['POST']['startdate'] = $_POST['startdate'];
$_SESSION['POST']['enddate'] = $_POST['enddate'];
$csv_mimetypes = array(//list of csv file types
    'text/csv',
    
    'application/csv',
    'text/comma-separated-values',
    'application/excel',
    'application/vnd.ms-excel',
    'application/vnd.msexcel',
    
    'application/octet-stream',
    
);
$term_identifier = $_SESSION['POST']['term']=='EVEN'?0:1;//checking term

//start of parsing of halls file
$array_halls = array();
$_SESSION['POST']['hall'] = array();

if(in_array($_FILES['halls']['type'], $csv_mimetypes))//checking file type
{
  $halls = rand(111111111,999999999).'_'.$_FILES['halls']['name'];
  move_uploaded_file($_FILES['halls']['tmp_name'], 'files/'.$halls);
  
  if(($open_halls = fopen("files/".$halls, "r")) !== FALSE)//opening file for reading
  {
    while (($data_halls = fgetcsv($open_halls, 1000, ",")) !== FALSE) // parsing csv data into array
    {        
      $array_halls[] = $data_halls; 
    }
    fclose($open_halls);
  }
  else//if file cannot be opened
  {
    ?>
    <script>
      window.location.replace("delete.php?errmsg=Unable to open Halls file ");
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
      die();
  }
  for($i=1, $q=0;$i<(sizeof($array_halls));$i++)//for each line of data in csv file
  {//checking of number of rows match, checking for null values
      if((sizeof($array_halls[$i])==4) && (intval($array_halls[$i][3]) < 4 && intval($array_halls[$i][3]) >0)&&($array_halls[$i][1] !== null && $array_halls[$i][2] !== null))
      {
          $_SESSION['POST']['hall'][$q]['room_no'] = intval($array_halls[$i][1]);
          $_SESSION['POST']['hall'][$q]['no_benches'] = intval($array_halls[$i][2]);
          $_SESSION['POST']['hall'][$q]['bench_seats'] = intval($array_halls[$i][3]);
          $q++;
      }

  }
  $_SESSION['POST']['no_examhalls'] = sizeof($_SESSION['POST']['hall']);
  if($_SESSION['POST']['no_examhalls'] == 0 || $_SESSION['POST']['no_examhalls'] == null)//checking if relevant data hasn't been parsed
  {
    ?>
      <script>
        window.location.replace("delete.php?errmsg=Halls file empty/wrong format");
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
      die();
  }
}
else//file type dosent match csv mimetypes
{
    header('location: delete.php?errmsg='.$_FILES['halls']['name'].' wrong file format');
}
//end of parsing of halls file


$_SESSION['POST']['dept'] = $_POST['dept'];

//parsing courses and students
$array_courses = array();
$_SESSION['POST']['course'] = array();
$_SESSION['POST']['no_courses'] = 0;
for($b=0; $b<$_POST['no_depts']; $b++)
{
  //start of parsing courses
if(in_array($_FILES['courses'.$b]['type'], $csv_mimetypes))//checking courses file type
{
  $courses = rand(111111111,999999999).'_'.$_FILES['courses'.$b]['name'];
  move_uploaded_file($_FILES['courses'.$b]['tmp_name'], 'files/'.$courses);
  
  if(($open_courses = fopen("files/".$courses, "r")) !== FALSE)//opening file
  {
    while (($data_courses = fgetcsv($open_courses, 1000, ",")) !== FALSE) //reading/parsing csv data
    {        
      $array_courses[] = $data_courses; 
    }
    fclose($open_courses);
  }
  else
  {
    ?>
    <script>
      window.location.replace("delete.php?errmsg=Unable to open Courses file ");
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
      die();
  }
  for($i=1 ;$i<(sizeof($array_courses));$i++)
  {
      if((sizeof($array_courses[$i])==6) && ($array_courses[$i][1]!==null && $array_courses[$i][2]!== null) && (intval($array_courses[$i][3])%2==$term_identifier) && (intval($array_courses[$i][4])==1) && (intval($array_courses[$i][5]) <=100 && intval($array_courses[$i][5]) >0) )
      {
          $_SESSION['POST']['course'][]['name'] = $array_courses[$i][1];
          $q = sizeof($_SESSION['POST']['course'])-1;
          $_SESSION['POST']['course'][$q]['code'] = $array_courses[$i][2];
          $_SESSION['POST']['course'][$q]['sem'] = intval($array_courses[$i][3]);
          $_SESSION['POST']['course'][$q]['dur'] = intval($array_courses[$i][4]);
          $_SESSION['POST']['course'][$q]['mrks'] = intval($array_courses[$i][5]);

          
          $_SESSION['POST']['course'][$q]['dept'] = $_POST['dept'][$b];

      }
      
  }
  $_SESSION['POST']['no_courses'] += sizeof($_SESSION['POST']['course']);
  if($_SESSION['POST']['no_courses'] == 0 || $_SESSION['POST']['no_courses'] == null)//checking if relevant data has been parsed
  {
    
    ?>
    
      <script>
        window.location.replace("delete.php?errmsg=Courses file empty/wrong format");
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
      pr($_SESSION['POST']);
      die();
  }
}
else
{
    header('location: delete.php?errmsg='.$_FILES['courses'.$b]['name'].' wrong file format');
}
//finished parsing courses

$array_students = array();
$_SESSION['POST']['student'] = array();
$_SESSION['POST']['no_students'] = 0;

//parsing students

if(in_array($_FILES['students'.$b]['type'], $csv_mimetypes))
{
  $students = rand(111111111,999999999).'_'.$_FILES['students'.$b]['name'];
  move_uploaded_file($_FILES['students'.$b]['tmp_name'], 'files/'.$students);
  
  if(($open_students = fopen("files/".$students, "r")) !== FALSE)//opening file
  {
    while (($data_students = fgetcsv($open_students, 1000, ",")) !== FALSE) //reading/parsing csv data
    {        
      $array_students[] = $data_students; 
    }
    fclose($open_students);
  }
  else
  {
    ?>
    <script>
      window.location.replace("delete.php?errmsg=Unable to open students file ");
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
      die();
  }
  for($i=1 ;$i<(sizeof($array_students));$i++)
  {//checking for null values and correct number of columns
      if((sizeof($array_students[$i])==4) && ($array_students[$i][2] !== null && $array_students[$i][1] !== null &&$array_students[$i][3] !== null))
      {
        
        for($j=0; $j<$_SESSION['POST']['no_courses']; $j++)
        { //checking to see if parsed student's course sode matches any existing course
          if($array_students[$i][3] == $_SESSION['POST']['course'][$j]['code'])
          {
            
            $student_allot=false;
            
            for($k=0; $k<$_SESSION['POST']['no_students']; $k++)
            {//checking to see if parsed student's enrollment number matches existing student
              if($_SESSION['POST']['student'][$k]['enroll'] == $array_students[$i][1])
              {
                $course_alot = false;
                for($l=0; $l<sizeof($_SESSION['POST']['student'][$k]['courses']);$l++)
                { //checking to see if existing student's course code matches partsed course code
                  if($_SESSION['POST']['student'][$k]['courses'][$l]==$array_students[$i][3])
                  {
                      $course_alot = true;
                      break 3;
                  }
                }
                if($course_alot == false)
                {//appending new course to existing student's course array
                  $_SESSION['POST']['student'][$k]['courses'][] = $array_students[$i][3];
                  $student_allot= true;
                  break 2;
                }
              }
            }
            //if student dosen't exist create new studen with the relevant data
            if($student_allot==false)
            {
              $_SESSION['POST']['student'][]['name'] = $array_students[$i][2];
              $q = sizeof($_SESSION['POST']['student'])-1;
              $_SESSION['POST']['student'][$q]['enroll'] = $array_students[$i][1];
              $_SESSION['POST']['student'][$q]['courses'][] = $array_students[$i][3];
                        
              $_SESSION['POST']['student'][$q]['dept'] = $_POST['dept'][$b];

              
              $_SESSION['POST']['no_students'] ++;
              
            }
            break ;
            
          }
          
        }
          
      }
      
  }
  //checking if any students fit the parsing criteria
  if($_SESSION['POST']['no_students'] == 0 || $_SESSION['POST']['no_students'] == null)
  {
    
    ?>
    
      <script>
        window.location.replace("delete.php?errmsg=Students file empty/wrong format");
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
      die();
  }
  
}
else//file dosen't match csv mime types
{
    header('location: delete.php?errmsg='.$_FILES['students'.$b]['name'].' wrong file format');
}

}
//redirecting to the main.php page for scheduling
?>
<script>
  window.location.replace("main.php");
</script>
<?php

?>