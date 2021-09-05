<?php 
include ('top.php');//including header file
if(!(isset($_GET['courseid'])))//checking if course id has been passed
{
    ?><div class="heading">
        No Slots assigned!!!
    </div><?php
    die();
}
$course_id = get_safe_value($con, $_GET['courseid']);
$sql = "Select * from slots where `course_id` =".$course_id;//selecting slots which have matching course id(these slots have been assigned to this particular course)
$res = mysqli_query($con,$sql);//results of the query stored in $res array
$slot_rows = array();
if (mysqli_num_rows($res) > 0) {
    while($row = mysqli_fetch_assoc($res)) {//fetching each row in the result of the query
                 
        array_push($slot_rows,$row); //pushing individual rows to an array containing a list of all the rows
      
  }
}
$sql = "Select * from course_list where `id` =".$course_id;//querying course list, refer above process of querying slots
$res = mysqli_query($con,$sql);
$course_rows = array();
if (mysqli_num_rows($res) > 0) {
    while($row = mysqli_fetch_assoc($res)) {
                 
        array_push($course_rows,$row);
      
  }
}
if(sizeof($slot_rows)==null)//no slots found that match given course id
{
    ?><div class="heading">
        No Slots assigned!!!
    </div><?php
}
else 
{   //outputing details of the first slot(details of all slots will be same in case of only one course, hence only outputing first slot)
    ?>
    <div class="container cont seating_top">
        <div class="row seating_top_row1">
            <div class="col-md-4">
                Date: <?php echo $slot_rows[0]['slot_date']; ?>
            </div>
            <div class="col-md-4">
                Exam Start Time: <?php echo $slot_rows[0]['start_time']; ?>
            </div>
            <div class="col-md-4">
                Exam End Time: <?php echo $slot_rows[0]['end_time']; ?>
            </div>
            
        </div>
        <div class="row seating_top_row2">
            <div class="col-md-3">
                Course Name: <?php echo $slot_rows[0]['Course_name']; ?>
            </div>
            <div class="col-md-3">
                Semester: <?php echo $course_rows[0]['Semester']; ?>
            </div>
            <div class="col-md-3">
                Duration: <?php echo $course_rows[0]['Duration']; ?>
            </div>
            <div class="col-md-3">
                Marks: <?php echo $course_rows[0]['Marks']; ?>
            </div>
        </div>
        
    </div>  
    <?php
    
      for($j=0;$j<sizeof($slot_rows);$j++)//loop to print all the slots(halls)
        {//querying seating data from seats table, refer earlier queries.
            $sql = "Select * from seats where `slot_id` =".$slot_rows[$j]['id']." order by `seat_no` asc";
            $res = mysqli_query($con,$sql);
            $seat_rows = array();
            if (mysqli_num_rows($res) > 0) {
                while($row = mysqli_fetch_assoc($res)) {
                            
                    array_push($seat_rows,$row);
                
            }
            }
            else
            {
            echo "error";
            }
        ?>
        <div class="container cont">
            <div class="row ">
                <div class="col-xs-12 heading">
                    Exam Hall No. <?php echo $slot_rows[$j]['hall_no']; ?>
                </div>
            </div>
            <div class="row maintop">
                <?php for($i = 0; $i<sizeof($seat_rows);$i++) {//loop to print all the seating details in this particular slot
                    if($i!=0 && $i/3 == 0)
                    {
                        ?></div> <div class="row"> <?php
                    }
                    ?>
                <div class="col-md-4">
                    <div class="row ">
                        <div class="col-xs-12 ">
                        <img src="img/seatnewer.png "  class="seatimg">
                        </div>
                    </div>
                    <div class="row seatinfo">
                        <div class="col-xs-12">
                            <div class="row">
                                <div class="col-xs-6">
                                    Seat No:
                                </div>
                                <div class="col-xs-6">
                                    <?php echo $seat_rows[$i]['seat_no']; ?>
                                </div>
                            </div>
                            <div class="row">
                            <div class="col-xs-4 col-md-6">
                                    Enroll No:
                                </div>
                                <div class="col-xs-8 col-md-6">
                                <?php //querying student details belonging to this particular seat in the particular hall(slot)
                                $sql = "Select * from student_list where `id` =".$seat_rows[$i]['student_id'];
                                $res = mysqli_query($con,$sql);
                                $student = mysqli_fetch_assoc($res);
                                echo $student['enrollment_no'];
                                ?>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <?php }?>
            </div>
        </div>    
        
    <?php
        }
    
}
?>

<?php 
include ('bottom.php');//including footer file
?>