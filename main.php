<?php
require("top.php");

//chceking if details have been passed using the session variable
if(!(isset($_SESSION['POST'])))
{
    header('location:inputnumbers.php');
}
else
{//assigning values to POST array
    $_POST = $_SESSION['POST'];
    unset($_SESSION['POST']);
    
}

//inserting exam details into the database
$sql = "INSERT INTO `exams` (`id`, `no_courses`, `no_students`, `no_examhalls`, `startdate`, `enddate`, `term`) VALUES (NULL, '".$_POST['no_courses']."', '".$_POST['no_students']."', '".$_POST['no_examhalls']."', '".$_POST['startdate']."', '".$_POST['enddate']."', '".$_POST['term']."');";
mysqli_query($con, $sql);
$exam_id = mysqli_insert_id($con);

//start of class declaration section
class Semester
{
    public $Courses =  array();
    public $no;

    function __construct($no)
    {
        $this->no = $no;
    }
}

class Department
{
    public $id;
    public $name;
    public $semesters =  array();
    public $Courses =  array();
    public $students = array();

    function __construct($name, $term)
    {
        $this->name = $name;
        if($term=='odd')
        {
            $this->semesters[] =  new Semester('1');
            $this->semesters[] =  new Semester('3');
            $this->semesters[] =  new Semester('5');
        }
        elseif($term=='even')
        {
            $this->semesters[] =  new Semester('2');
            $this->semesters[] =  new Semester('4');
            $this->semesters[] =  new Semester('6');
        }
    }
}
class SubSlot
{
    public $subSlotNo;
    public $slotNo; 
    public $slotHall;
    public $slotDay;
    public $subSlotCourse;
    public $subSlotCourseNo;
    public $subSlotCourseId;
    public $subSlotSem;
    public $subSlotStudentList = array();

    function __construct($no, $hall, $day, $slot_no)
    {
        $this->slotHall = $hall;
        $this->slotDay = $day;
        $this->subSlotNo =  $no;
        $this->slotNo = $slot_no;
    }
}
class TimeSlot
{
    public $id;
    public $slotNo;
    public $slotHall;
    public $slotDay;
    public $slotTime;
    public $subSlots = array();
    public $subSlotCount;
        
    function __construct($no, $hallno, $day, $time, $seats_per_bench)
    {
        $this->slotNo = $no;
        $this->slotHall = $hallno;
        $this->slotDay = $day;
        $this->slotTime = $time;
        $this->subSlotCount = $seats_per_bench;

        for($i=0; $i<$seats_per_bench; $i++)
        {
            $this->subSlots[$i]= new SubSlot($i, $hallno, $day, $no);
        }
        
    }
    
}
class Student
{
    public $id;
    public $name;
    public $enroll;
    public $course_list = array(); 
    public $dept;   

    function __construct($id, $name, $enroll, $dept)
    {   
        $this->id = $id;
        $this->name = $name;
        $this->enroll = $enroll;
        $this->dept = $dept;
    
    }
    function outputdata()
    {
        echo $this->id;
        echo $this->name;
        
        echo $this->enroll;
    
    }
    
}

class Course
{
    public $id;
    public $name;
    public $code;
    public $sem;
    public $duration;
    public $marks;
    public $listOfStudents = array();
    public $scheduledDate;
    public $scheduledTimeSlots = array();
    public $dept;    
    
    public $studentIndex;

    function __construct($id, $name, $sem, $dur, $mrks, $code, $dept)
    {   
        $this->id = $id;
        $this->name = $name;
        $this->sem =$sem;
        $this->duration = $dur;
        $this->marks = $mrks;
        $this->code = $code;
        $this->dept =  $dept;
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
    public $id;
    public $room_no;
    public $no_benches;
    public $seats_per_bench;
    
    public $slot = array();

    

    function __construct($hall_no, $day, $room_no, $no_benches, $seats_per_bench)
    {   
        $this->hall_no = $hall_no;
        $this->slot[0] = new TimeSlot(0, $hall_no, $day, "11:00:00", $seats_per_bench);
        $this->slot[1] = new TimeSlot(1, $hall_no, $day, "12:00:00", $seats_per_bench);
        $this->slot[2] = new TimeSlot(2, $hall_no, $day, "1:00:00", $seats_per_bench);
        $this->slot[3] = new TimeSlot(3, $hall_no, $day, "2:00:00", $seats_per_bench);
        $this->room_no = $room_no;
        $this->no_benches = $no_benches;
        $this->seats_per_bench = $seats_per_bench;
    }
    
}
//end of class declaration section


$startdate = new DateTime($_POST['startdate']);//first date of exam
$enddate = new DateTime($_POST['enddate']);//last date of exam
$durationOfExaminations = $enddate->diff($startdate)->format("%a");
$durationOfExaminations ++;
$strstdate = $startdate->format('Y-m-d');
$streddate = $enddate->format('Y-m-d');
//echo $durationOfExaminations;

$Courses =array();
$students = array();
$days = array();
$halls = array();
$departments = array();

//iniatializing array of Course objects
for($i=0; $i<$_POST['no_courses']; $i++)
{   
    $course_name = get_safe_value($con, $_POST['course'][$i]['name']);
    $course_sem = get_safe_value($con, $_POST['course'][$i]['sem']);
    $course_dur = get_safe_value($con, $_POST['course'][$i]['dur']);
    $course_mrks = get_safe_value($con, $_POST['course'][$i]['mrks']);
    $course_code = get_safe_value($con, $_POST['course'][$i]['code']);
    $course_dept = get_safe_value($con, $_POST['course'][$i]['dept']);
    $sql="INSERT INTO `course_list` (`id`, `name`, `Semester`, `Duration`, `Marks`, `Code`, `Dept`) VALUES (NULL,'$course_name','$course_sem','$course_dur','$course_mrks','$course_code','$course_dept');";
    if (!(mysqli_query($con, $sql))) {
        
      
        echo "Error: " . $sql . "<br>" . mysqli_error($con);
      }
    $last_id = mysqli_insert_id($con);
    $Courses[$i] = new Course($last_id,$course_name,$course_sem,$course_dur,$course_mrks, $course_code, $course_dept);
    
}

//initializing array of student objects

for($i=0; $i<$_POST['no_students']; $i++)
{   
    $student_name = get_safe_value($con, $_POST['student'][$i]['name']);
    $student_enroll = get_safe_value($con, $_POST['student'][$i]['enroll']);
    $student_dept = get_safe_value($con, $_POST['student'][$i]['dept']);
    
    $sql="INSERT INTO `student_list` (`id`, `name`, `dept`, `enrollment_no`) VALUES (NULL,'$student_name','$student_dept','$student_enroll')";
    if (mysqli_query($con, $sql)) {
       
      } else {
        echo "Error: " . $sql . "<br>" . mysqli_error($con);
      }
    $last_id = mysqli_insert_id($con);
    $students[$i] = new student($last_id,$student_name,$student_enroll,$student_dept);

    $students[$i]->course_list = $_POST['student'][$i]['courses'];
    
}

//iniatializing array of department objects

for($i=0; $i<$_POST['no_depts']; $i++)
{
    $dept_name = get_safe_value($con, $_POST['dept'][$i]);
    $departments[$i] = new Department($dept_name, $_POST['term']);

    //adding courses to their relevant semester objects of department objects

    for($j=0; $j<$_POST['no_courses']; $j++)
    {
        if($Courses[$j]->dept == $departments[$i]->name)
        {
            for($k=0;$k<sizeof($departments[$i]->semesters);$k++)
            {
                if($departments[$i]->semesters[$k]->no == $Courses[$j]->sem)
                {
                    array_push($departments[$i]->semesters[$k]->Courses, $Courses[$j]);
                }
            }
            
        }
    }

    //adding students to the student lists of their relevant department objects

    for($j=0; $j<$_POST['no_students']; $j++)
    {
        if($students[$j]->dept == $departments[$i]->name)
        {
            array_push($departments[$i]->students, $students[$j]);
        }
    }

    $dept_no_Courses = sizeof($departments[$i]->Courses);
    $dept_no_students = sizeof($departments[$i]->students);

    //inserting department details into the database

    $sql="INSERT INTO `departments` (`id`, `name`, `no_courses`, `no_students`) VALUES (NULL, '$dept_name', '$dept_no_Courses', '$dept_no_students')";
    if (!(mysqli_query($con, $sql))) {
        
      
        echo "Error: " . $sql . "<br>" . mysqli_error($con);
      }
    $dept_id = mysqli_insert_id($con);
    $departments[$i]->id = $dept_id;

}

//iniatializing the relevant day array of DateTime objects

$idate = new DateTime($startdate->format('Y-m-d'));

for($i=0 ; $i<$durationOfExaminations;$idate->modify('+1 day'), $i++)
{
    $days[$i] = new DateTime($idate->format('Y-m-d'));

    //iniatializing the relevant exam hall objects

    for($j=0; $j<$_POST['no_examhalls'];$j++)
{
    $halls[$i][$j] = new Hall($j, $i, $_POST['hall'][$j]['room_no'],$_POST['hall'][$j]['no_benches'],$_POST['hall'][$j]['bench_seats']);
    //inserting hall data in database if first iteration of loop
    if($i == 0)
    {
        $sql="INSERT INTO `halls` (`id`, `room_no`, `no_benches`, `bench_seats`) VALUES (NULL, '".$_POST['hall'][$j]['room_no']."', '".$_POST['hall'][$j]['no_benches']."', '".$_POST['hall'][$j]['bench_seats']."')";
        if (!(mysqli_query($con, $sql))) 
        {
            echo "Error: " . $sql . "<br>" . mysqli_error($con);
        }
        $hall_id = mysqli_insert_id($con);
        $halls[$i][$j]->id = $hall_id;
    }
    //assigning id to halls for following iterations of the loop
    else
    {
        $halls[$i][$j]->id = $halls[0][$j]->id;
    }
}
}

//Adding students to course list of relevant course

for($i=0; $i<$_POST['no_courses']; $i++){
    for($j=0;$j<$_POST['no_students'] ; $j++)
    {
        for($k = 0; $k<sizeof($students[$j]->course_list); $k++)
        {
            if($Courses[$i]->code == $students[$j]->course_list[$k])
            {
                $Courses[$i]->addStudent($students[$j]);
            }
        }
    }
}

$errors = array();// array for storing error messages

$completedCourses = array();//array for storing Courses that have finished scheduling
//start of scheduling algorithm
for($a=0; $a<sizeof($departments); $a++)//looping through department objects
{
    for($b=0; $b<sizeof($departments[$a]->semesters); $b++)//looping through semester objects of departments
    {
        for($i=0; $i<sizeof($departments[$a]->semesters[$b]->Courses); $i++)//looping through courses
        {  
            
            $slotschosen = array();//array for scheduled slots
            $requiredseats = sizeof($departments[$a]->semesters[$b]->Courses[$i]->listOfStudents);

            $total_no_benches = 0;//total benches in all halls
            for($j=0; $j<$_POST['no_examhalls']; $j++)//calculating total benches
            {
                $total_no_benches += $halls[0][$j]->no_benches;
            }
            if($requiredseats < 1)//checking if course has students
            {
                $errors[] = "Course ". $departments[$a]->semesters[$b]->Courses[$i]->name." has no students";
                continue;
            }
            else if($total_no_benches < $requiredseats)//cheching if the number of students can be accomodated in the given halls
            {
                $errors[]= "Course ". $departments[$a]->semesters[$b]->Courses[$i]->name." exceeds seat limit!!";
                continue;
            }
            else
            {   //looping through days in the exam schedule
                for($j=0; $j<$durationOfExaminations; $j++)
                {
                    for($l=0; $l<$_POST['no_courses']; $l++)//checking if course of the same department ansd semester has been scheduled on the same day
                    {
                        if(($departments[$a]->semesters[$b]->Courses[$i]->sem == $Courses[$l]->sem) && ($days[$j]->format('Y-m-d') == $Courses[$l]->scheduledDate) && ($departments[$a]->semesters[$b]->Courses[$i]->dept == $Courses[$l]->dept))    
                        {
                            continue 2;
                        }       
                    }//checking if course duration is 1 hr
                    if($departments[$a]->semesters[$b]->Courses[$i]->duration == 1)
                        {
                            for($l=0; $l<4; $l++)//looping through the slots
                            {
                                //checking if required slots satisfy the course's seat needs
                                if(sizeof($slotschosen)>0)
                                {
                                    $seatsWithinChosenSlots = 0;
                                    for($m=0; $m<sizeof($slotschosen); $m++)
                                    {
                                        $hallIndex = $slotschosen[$m]->slotHall;
                                        $seatsWithinChosenSlots += $halls[$j][$hallIndex]->no_benches;
                                    }
                                    if($seatsWithinChosenSlots>=$requiredseats)//seat needs satisfied
                                    {
                                        break;
                                    }
                                    else//seat needs not satisfied
                                    {//reset slotschosen array
                                        unset($slotschosen);
                                        $slotschosen = array();
                                    }
                                }
                                else
                                {//reset slotschosen array
                                    unset($slotschosen);
                                    $slotschosen = array();
                                }
                                
                                for($k=0; $k<$_POST['no_examhalls'];$k++)//looping through the exam halls
                                {
                                    //checking if required slots satisfy the course's seat needs
                                    if(sizeof($slotschosen)>0)
                                    {
                                        $seatsWithinChosenSlots = 0;
                                        for($m=0; $m<sizeof($slotschosen); $m++)
                                        {
                                            $hallIndex = $slotschosen[$m]->slotHall;
                                            $seatsWithinChosenSlots += $halls[$j][$hallIndex]->no_benches;
                                        }
                                        if($seatsWithinChosenSlots>=$requiredseats)//seat needs satisfied
                                        {
                                            break;
                                        }
                                    }
                                    for($m=0; $m<$halls[$j][$k]->slot[$l]->subSlotCount; $m++)//looping through the  sub slots
                                    {   //checking if a particular subslot is occupied otr available
                                        if($halls[$j][$k]->slot[$l]->subSlots[$m]->subSlotCourse==NULL)
                                        {
                                            array_push($slotschosen, $halls[$j][$k]->slot[$l]->subSlots[$m]);//selecting available slots
                                            break;//exit sub slot loop
                                        }
                                    }
                                }
                                
                            }
                        }
                    
                                    
                }

            }   
            if(sizeof($slotschosen)<= 0)// checking if slot assigned to particular course
            {
                $errors[]= "Course ". $departments[$a]->semesters[$b]->Courses[$i]->name." No slots assigned!!";
            }
                        
            if(sizeof($slotschosen)>0)// checking if slot assigned to particular course
            {
                //assigning schedule details to respective courses
                if($departments[$a]->semesters[$b]->Courses[$i]->duration == 1)
                {   
                    
                    
                     
                    $csi = 0;
                    $skip;
                    unset($skip);
                    $skip =false;
                    for($index=0;$index<sizeof($slotschosen);$index++)
                    {
                        $slotno_temp = $slotschosen[$index]->slotNo;
                        $subSlotno_temp = $slotschosen[$index]->subSlotNo;
                        $slothall_temp = $slotschosen[$index]->slotHall;
                        $slotday_temp = $slotschosen[$index]->slotDay;
                        
                        $halls[$slotday_temp][$slothall_temp]->slot[$slotno_temp]->subSlots[$subSlotno_temp]->subSlotCourse = $departments[$a]->semesters[$b]->Courses[$i]->name;
                        $halls[$slotday_temp][$slothall_temp]->slot[$slotno_temp]->subSlots[$subSlotno_temp]->subSlotSem = $departments[$a]->semesters[$b]->Courses[$i]->sem;
                        $halls[$slotday_temp][$slothall_temp]->slot[$slotno_temp]->subSlots[$subSlotno_temp]->subSlotCourseNo = $i;
                        $halls[$slotday_temp][$slothall_temp]->slot[$slotno_temp]->subSlots[$subSlotno_temp]->subSlotCourseId = $departments[$a]->semesters[$b]->Courses[$i]->id;

                        //assigning seats to students on the basis of the scheduled slots
                        for($c=0;$csi<sizeof($departments[$a]->semesters[$b]->Courses[$i]->listOfStudents) && $c<$halls[$slotday_temp][$slothall_temp]->no_benches ;$c++, $csi++)
                        {   

                            $halls[$slotday_temp][$slothall_temp]->slot[$slotno_temp]->subSlots[$subSlotno_temp]->subSlotStudentList[$c] = $departments[$a]->semesters[$b]->Courses[$i]->listOfStudents[$csi];
                            
                        }
                        
                        array_push($departments[$a]->semesters[$b]->Courses[$i]->scheduledTimeSlots,$halls[$slotday_temp][$slothall_temp]->slot[$slotno_temp]->subSlots[$subSlotno_temp]);
                    }
                    $departments[$a]->semesters[$b]->Courses[$i]->scheduledDate = $days[$slotday_temp]->format('Y-m-d');
                    array_push($completedCourses, $departments[$a]->semesters[$b]->Courses[$i]);
                    
                    //updating scheduled date in courses table in the database
                    $sql = "update course_list set Scheduled_date ='".$departments[$a]->semesters[$b]->Courses[$i]->scheduledDate."' where id =". $departments[$a]->semesters[$b]->Courses[$i]->id.";" ;       
                    if (mysqli_query($con, $sql)) {
            
                    } else {
                        $errors[]= "Error: " . $sql . "<br>" . mysqli_error($con);
                    }
                }
            
                else
                {
                    $errors[]= "Course ". $departments[$a]->semesters[$b]->Courses[$i]->name." : This version only supports 1 hour long exams!!";
                }
            }
            else
            {
                $errors[]= "Course ". $departments[$a]->semesters[$b]->Courses[$i]->name." No slots assigned!!";
            }
        }
    }
}

//storing schedule details into database
for($i=0;$i<sizeof($completedCourses);$i++)
{
    if($completedCourses[$i]->duration == 1)
    {
        for($j=0;$j<sizeof($completedCourses[$i]->scheduledTimeSlots);$j++)
        {
            $temp_hallid = $halls[$completedCourses[$i]->scheduledTimeSlots[$j]->slotDay][$completedCourses[$i]->scheduledTimeSlots[$j]->slotHall]->id;
            $temp_slotDate = $days[$completedCourses[$i]->scheduledTimeSlots[$j]->slotDay]->format('Y-m-d');
            $temp_courseId = $completedCourses[$i]->id;
            $temp_courseName = $completedCourses[$i]->name;
            $temp_no_students = sizeof($completedCourses[$i]->scheduledTimeSlots[$j]->subSlotStudentList);
            $temp_no_benches = $halls[$completedCourses[$i]->scheduledTimeSlots[$j]->slotDay][$completedCourses[$i]->scheduledTimeSlots[$j]->slotHall]->no_benches;
            
            //checking if particular slot already exists in the database
            if (!isset($halls[$completedCourses[$i]->scheduledTimeSlots[$j]->slotDay][$completedCourses[$i]->scheduledTimeSlots[$j]->slotHall]->slot[$completedCourses[$i]->scheduledTimeSlots[$j]->slotNo]->id))
            {
            
                $temp_slotTime = $halls[$completedCourses[$i]->scheduledTimeSlots[$j]->slotDay][$completedCourses[$i]->scheduledTimeSlots[$j]->slotHall]->slot[$completedCourses[$i]->scheduledTimeSlots[$j]->slotNo]->slotTime;
                $temp_startTime = $temp_slotTime;
                $temp_timestamp = strtotime($temp_slotTime) + 60*60;
                $temp_endTime = date('H:i', $temp_timestamp);
                $temp_no_subslots = $halls[$completedCourses[$i]->scheduledTimeSlots[$j]->slotDay][$completedCourses[$i]->scheduledTimeSlots[$j]->slotHall]->slot[$completedCourses[$i]->scheduledTimeSlots[$j]->slotNo]->subSlotCount;
                
                //inserting slot details into the database
                $sql = "INSERT INTO `slots` (`id`, `start_time`, `end_time`, `no_subslots`, `hall_id`, `slot_date`, `exam_id`) VALUES (NULL, '".$temp_startTime."', '".$temp_endTime."', '".$temp_no_subslots."', '".$temp_hallid."', '".$temp_slotDate."', '".$exam_id."');";
                mysqli_query($con, $sql);
                $slot_id = mysqli_insert_id($con);
                $halls[$completedCourses[$i]->scheduledTimeSlots[$j]->slotDay][$completedCourses[$i]->scheduledTimeSlots[$j]->slotHall]->slot[$completedCourses[$i]->scheduledTimeSlots[$j]->slotNo]->id = $slot_id;
            }
            else
            {
                $slot_id = $halls[$completedCourses[$i]->scheduledTimeSlots[$j]->slotDay][$completedCourses[$i]->scheduledTimeSlots[$j]->slotHall]->slot[$completedCourses[$i]->scheduledTimeSlots[$j]->slotNo]->id;
            }

            //inserting subslot details into the database
            $sql = "INSERT INTO `subslots` (`id`, `slot_id`, `hall_id`, `no_students`, `course_name`, `course_id`) VALUES (NULL, '".$slot_id."', '".$temp_hallid."', '".$temp_no_students."', '".$temp_courseName."', '".$temp_courseId."');";
            mysqli_query($con, $sql);
            $subSlot_id = mysqli_insert_id($con);
            
            //assigning seats to students on the basis of the scheduled slots
            for($k=0;$k<sizeof($completedCourses[$i]->scheduledTimeSlots[$j]->subSlotStudentList);$k++)
            {
                $seat_no = $k + 1 + (($completedCourses[$i]->scheduledTimeSlots[$j]->subSlotNo)*$temp_no_benches);
                $student_id = $completedCourses[$i]->scheduledTimeSlots[$j]->subSlotStudentList[$k];
                //inserting seating details into the database
                $sql = "INSERT INTO `seats` (`id`, `slot_id`, `subslot_id`, `seat_no`, `student_id`) VALUES (NULL, '$slot_id', '$subSlot_id', '$seat_no', '$student_id');";
                mysqli_query($con, $sql);
            }
        }
    }
  else
  {
      $errors[] = 'database error';
  }
}

//storing the errors array as a session variable for further use
$_SESSION['main_errors'] = $errors;
?>

<script>
    //redirecting to the viewschedule.php page in order to display the schedule
    window.location.replace("viewschedule.php");
</script>
<?php
require("bottom.php");
?>