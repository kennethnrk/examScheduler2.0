<?php 
include ('top.php');
if(!(isset($_GET['courseid'])))
{
    ?><div class="heading">
        No Slots assigned!!!
    </div><?php
    die();
}
$course_id = get_safe_value($con, $_GET['courseid']);
$sql = "Select * from slots where `course_id` =".$course_id;
$res = mysqli_query($con,$sql);
$slot_rows = array();
if (mysqli_num_rows($res) > 0) {
    while($row = mysqli_fetch_assoc($res)) {
                 
        array_push($slot_rows,$row);
      
  }
}
$sql = "Select * from course_list where `id` =".$course_id;
$res = mysqli_query($con,$sql);
$course_rows = array();
if (mysqli_num_rows($res) > 0) {
    while($row = mysqli_fetch_assoc($res)) {
                 
        array_push($course_rows,$row);
      
  }
}
if(sizeof($slot_rows)==null)
{
    ?><div class="heading">
        No Slots assigned!!!
    </div><?php
}
else 
{
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
    
      for($j=0;$j<sizeof($slot_rows);$j++)
        {
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
                <?php for($i = 0; $i<sizeof($seat_rows);$i++) {
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
                                <?php 
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
include ('bottom.php');
?>