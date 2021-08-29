<?php
require("top.php");

if(!(isset($_SESSION['POST'])))
{
    header('location:inputnumbers.php');
}
else
{
    $_POST = $_SESSION['POST'];
    unset($_SESSION['POST']);
}


$sql = "INSERT INTO `exams` (`id`, `no_courses`, `no_students`, `no_examhalls`, `no_seats`, `startdate`, `enddate`) VALUES (NULL, '".$_POST['no_courses']."', '".$_POST['no_students']."', '".$_POST['no_examhalls']."', '".$_POST['no_seats']."', '".$_POST['startdate']."', '".$_POST['enddate']."');";
mysqli_query($con, $sql);
$exam_id = mysqli_insert_id($con);

?>
<?php
class Department
{
    public $id;
    public $name;
    public $Courses =  array();
    function __construct($id, $name)
    {
        $this->id = $id;
        $this->mame = $name;
    }
}
class TimeSlot
{
    public $slotNo;
    public $slotHall;
    public $slotDay;
    public $slotCourse;
    public $courseNo;
    public $courseId;
    public $slotSem;
    public $slotTime;
    public $slotStudentList = array();
    
    function __construct($no, $hallno, $day, $time)
    {
        $this->slotNo = $no;
        $this->slotHall = $hallno;
        $this->slotDay = $day;
        $this->slotTime = $time;
        $this->slotCourse = NULL;
    }
    
}
class Student
{
    public $id;
    public $name;
    public $sem;
    public $enroll;
    public $course_list = array();

    function __construct($id, $name, $sem, $enroll)
    {   
        $this->id = $id;
        $this->name = $name;
        $this->sem =$sem;
        $this->enroll = $enroll;
    
    }
    function outputdata()
    {
        echo $this->id;
        echo $this->name;
        echo $this->sem;
        echo $this->enroll;
    
    }
}

class Course
{
    public $id;
    public $name;
    public $sem;
    public $duration;
    public $marks;
    public $listOfStudents = array();
    public $scheduledDate;
    public $scheduledTimeSlots = array();
    
    public $studentIndex;

    function __construct($id, $name, $sem, $dur, $mrks)
    {   
        $this->id = $id;
        $this->name = $name;
        $this->sem =$sem;
        $this->duration = $dur;
        $this->marks = $mrks;
        $this->studentIndex = 0;
    
    }
    function outputdata()
    {
        echo $this->id;
        echo $this->name;
        echo $this->sem;
        echo $this->duration;
        echo $this->marks;
    
    }
    function addStudent($student)
    {
        $this->listOfStudents[$this->studentIndex]=$student->id;
        $this->studentIndex++;
    
    }
}

class Hall
{   
    public $hall_no;
    public static $no_seats;
    
    public $slot = array();

    

    function __construct($hall_no, $day)
    {   
        $this->hall_no = $hall_no;
        $this->slot[0] = new TimeSlot(1, $hall_no, $day, "11:00:00");
        $this->slot[1] = new TimeSlot(2, $hall_no, $day, "12:00:00");
        $this->slot[2] = new TimeSlot(3, $hall_no, $day, "1:00:00");
        $this->slot[3] = new TimeSlot(4, $hall_no, $day, "2:00:00");
   
    }
    
}

$no_seats = $_POST['no_seats'];

$startdate = new DateTime($_POST['startdate']);
$enddate = new DateTime($_POST['enddate']);
$durationOfExaminations = $enddate->diff($startdate)->format("%a");
$durationOfExaminations ++;
$strstdate = $startdate->format('Y-m-d');
$streddate = $enddate->format('Y-m-d');
echo $durationOfExaminations;

$Courses =array();
$students = array();
$days = array();
$halls = array();

for($i=0; $i<$_POST['no_courses']; $i++)
{   
    $course_name = get_safe_value($con, $_POST['course_name'.$i+1]);
    $course_sem = get_safe_value($con, $_POST['course_sem'.$i+1]);
    $course_dur = get_safe_value($con, $_POST['course_dur'.$i+1]);
    $course_mrks = get_safe_value($con, $_POST['course_mrks'.$i+1]);
    $sql="INSERT INTO `course_list` (`id`, `name`, `Semester`, `Duration`, `Marks`) VALUES (NULL,'$course_name','$course_sem','$course_dur','$course_mrks')";
    if (!(mysqli_query($con, $sql))) {
        
      
        echo "Error: " . $sql . "<br>" . mysqli_error($con);
      }
    $last_id = mysqli_insert_id($con);
    $Courses[$i] = new Course($last_id,$course_name,$course_sem,$course_dur,$course_mrks);
    
}
for($i=0; $i<$_POST['no_students']; $i++)
{   
    $student_name = get_safe_value($con, $_POST['student_name'.$i+1]);
    $student_sem = get_safe_value($con, $_POST['student_sem'.$i+1]);
    $student_enroll = get_safe_value($con, $_POST['student_enroll'.$i+1]);
    
    $sql="INSERT INTO `student_list` (`id`, `name`, `Semester`, `enrollment_no`) VALUES (NULL,'$student_name','$student_sem','$student_enroll')";
    if (mysqli_query($con, $sql)) {
       
      } else {
        echo "Error: " . $sql . "<br>" . mysqli_error($con);
      }
    $last_id = mysqli_insert_id($con);
    $students[$i] = new student($last_id,$student_name,$student_sem,$student_enroll);
    
}


$idate = new DateTime($startdate->format('Y-m-d'));

for($i=0 ; $i<$durationOfExaminations;$idate->modify('+1 day'), $i++)
{
    $days[$i] = new DateTime($idate->format('Y-m-d'));
   
    for($j=0; $j<$_POST['no_examhalls'];$j++)
{
    $halls[$i][$j] = new Hall($j, $i);
}
}

for($i=0; $i<$_POST['no_courses']; $i++){
for($j=0;$j<$_POST['no_students'] ; $j++)
{
    if($Courses[$i]->sem == $students[$j]->sem)
    {
        $Courses[$i]->addStudent($students[$j]);
    }
}
}



$completedCourses = array();

for($i=0; $i<$_POST['no_courses']; $i++)
{  
    
    $slotschosen = array();
    $requiredseats = sizeof($Courses[$i]->listOfStudents);
    $requiredhalls;
    if($requiredseats%$no_seats==0) 
    {
        $requiredhalls = (int)$requiredseats/$no_seats ;
    }
    else
    {
        $requiredhalls = (int)$requiredseats/$no_seats + 1;
    }
    if($_POST['no_examhalls']*$no_seats < $requiredseats)
    {
        echo "Course ". $Courses[$i]->name." exceeds seat limit!!";
        continue;
    }
    else
    {
        for($j=0; $j<$durationOfExaminations; $j++)
        {
          for($l=0; $l<$_POST['no_courses']; $l++)
          {
            if(($Courses[$i]->sem == $Courses[$l]->sem) && ($days[$j]->format('Y-m-d') == $Courses[$l]->scheduledDate))    
            {
                continue 2;
            }       
          }
            if($Courses[$i]->duration == 1)
                {
                    for($l=0; $l<4; $l++)
                    {
                        if(sizeof($slotschosen)*$no_seats >= $requiredseats)
                            {
                                break;
                            }
                        else
                        {
                            unset($slotschosen);
                            $slotschosen = array();
                        }
                        for($k=0; $k<$_POST['no_examhalls'];$k++)
                        {
                            if(sizeof($slotschosen)*$no_seats >= $requiredseats)
                            {
                                break;
                            }
                            if($halls[$j][$k]->slot[$l]->slotCourse==NULL)
                            {
                                array_push($slotschosen, $halls[$j][$k]->slot[$l]);
                            }
                            
                        }
                        
                    }
                }
            
                            
        }

    }   
   
    if(sizeof($slotschosen)>0)
    {
        if($Courses[$i]->duration == 1)
        {   
            
            
            $a = sizeof($slotschosen);
            $csi = 0;
            $skip;
            unset($skip);
            $skip =false;
            for($b=0;$b<$a;$b++)
            {
                $slotno_temp = $slotschosen[$b]->slotNo -1;
                $slothall_temp = $slotschosen[$b]->slotHall;
                $slotday_temp = $slotschosen[$b]->slotDay;
                
                $halls[$slotday_temp][$slothall_temp]->slot[$slotno_temp]->slotCourse = $Courses[$i]->name;
                $halls[$slotday_temp][$slothall_temp]->slot[$slotno_temp]->slotSem = $Courses[$i]->sem;
                $halls[$slotday_temp][$slothall_temp]->slot[$slotno_temp]->courseNo = $i;
                $halls[$slotday_temp][$slothall_temp]->slot[$slotno_temp]->courseId = $Courses[$i]->id;

                
                for($c=0;$c<$_POST['no_seats']&&$csi<sizeof($Courses[$i]->listOfStudents);$c++,$csi++)
                {   

                    $halls[$slotday_temp][$slothall_temp]->slot[$slotno_temp]->slotStudentList[$c] = $Courses[$i]->listOfStudents[$csi];
                    
                }
                
                array_push($Courses[$i]->scheduledTimeSlots,$halls[$slotday_temp][$slothall_temp]->slot[$slotno_temp]);
            }
            $Courses[$i]->scheduledDate = $days[$slotday_temp]->format('Y-m-d');
            array_push($completedCourses, $Courses[$i]);
            echo $Courses[$i]->scheduledDate;
            $sql = "update course_list set Scheduled_date ='".$Courses[$i]->scheduledDate."' where id =". $Courses[$i]->id.";" ;       
            if (mysqli_query($con, $sql)) {
       
            } else {
              echo "Error: " . $sql . "<br>" . mysqli_error($con);
            }
        }
       
        else
        echo "error";
    }
    else
    {
        echo "error";
    }
}


pr($completedCourses);

for($i=0;$i<$_POST['no_courses'];$i++)
{
    if($Courses[$i]->duration == 1)
    {
        for($j=0;$j<sizeof($Courses[$i]->scheduledTimeSlots);$j++)
        {
            $temp_slotTime = $Courses[$i]->scheduledTimeSlots[$j]->slotTime;
            $temp_startTime = $temp_slotTime;
            $temp_timestamp = strtotime($temp_slotTime) + 60*60;
            $temp_endTime = date('H:i', $temp_timestamp);
            $temp_courseId = $Courses[$i]->scheduledTimeSlots[$j]->courseId;
            $temp_courseName = $Courses[$i]->scheduledTimeSlots[$j]->slotCourse;
            $temp_hallNo = $Courses[$i]->scheduledTimeSlots[$j]->slotHall +1;
            $temp_slotDate = $days[$Courses[$i]->scheduledTimeSlots[$j]->slotDay]->format('Y-m-d');

            $sql = "INSERT INTO `slots` (`id`, `start_time`, `end_time`, `course_id`, `Course_name`, `hall_no`, `slot_date`, `exam_id`) VALUES (NULL, '".$temp_startTime."', '".$temp_endTime."', '".$temp_courseId."', '".$temp_courseName."', '".$temp_hallNo."', '".$temp_slotDate."', '".$exam_id."');";
            mysqli_query($con, $sql);
            $slot_id = mysqli_insert_id($con);
            for($k=0;$k<sizeof($Courses[$i]->scheduledTimeSlots[$j]->slotStudentList);$k++)
            {
                $seat_no = $k + 1;
                $student_id = $Courses[$i]->scheduledTimeSlots[$j]->slotStudentList[$k];
                $sql = "INSERT INTO `seats` (`id`, `slot_id`, `seat_no`, `student_id`) VALUES (NULL, '$slot_id', '$seat_no', '$student_id');";
                mysqli_query($con, $sql);
            }
        }
    }
  else
  {
      echo 'database error';
  }
}


?>

<script>
window.location.replace("viewschedule.php");
</script>
<?php
require("bottom.php");
?>