<?php 
include ('top.php');//including header file
if(!(isset($_GET['courseid'])))//checking if course id has been passed
{
    ?><div class="heading">
        No Slots assigned!!!
    </div><?php
    die();
}
$subslot_rows = array();
$course_id = get_safe_value($con, $_GET['courseid']);
$sql = "SELECT * from `subslots` where `course_id` =".$course_id;//selecting slots which have matching course id(these slots have been assigned to this particular course)
$res = mysqli_query($con,$sql);//results of the query stored in $res array

if (mysqli_num_rows($res) > 0) {
    while($row = mysqli_fetch_assoc($res)) {//fetching each row in the result of the query
                 
        $subslot_rows[] = $row; //pushing individual rows to an array containing a list of all the rows
      
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
if(sizeof($subslot_rows)==null)//no slots found that match given course id
{
    ?><div class="heading">
        No Slots assigned!!!
    </div><?php
}
else 
{   //outputing details of the first slot(details of all slots will be same in case of only one course, hence only outputing first slot)
    
    $slot_rows = array();
    for ($i=0; $i <sizeof($subslot_rows) ; $i++) 
    { 
        $slot_id=$subslot_rows[$i]['slot_id'];
            $sql = "Select * from slots where `id` =".$slot_id;//selecting slots which have matching course id(these slots have been assigned to this particular course)
            $res = mysqli_query($con,$sql);//results of the query stored in $res array
            
            if (mysqli_num_rows($res) > 0) 
            {
                while($row = mysqli_fetch_assoc($res)) 
                {//fetching each row in the result of the query
                    
                    array_push($slot_rows,$row); //pushing individual rows to an array containing a list of all the rows
        
                }
            }
    }
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
                Course Name: <?php echo $course_rows[0]['name']; ?>
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
    $hall_rows = array();
    for($i=0; $i <sizeof($slot_rows) ; $i++)
    {
        $sql = "Select * from halls where `id` =".$slot_rows[$i]['hall_id'];
        $res = mysqli_query($con,$sql);
        
        if (mysqli_num_rows($res) > 0) 
        {
            while($row = mysqli_fetch_assoc($res)) 
            {
                                        
                array_push($hall_rows,$row);
                            
            }
        }
    }
    
    
      for($j=0;$j<sizeof($slot_rows);$j++)//loop to print all the slots(halls)
        {//querying seating data from seats table, refer earlier queries.
            
            
            $sql = "Select * from halls where `id` =".$slot_rows[$j]['hall_id'];
            $res = mysqli_query($con,$sql);
            if (mysqli_num_rows($res) == 1) {
                $hall_row = mysqli_fetch_assoc($res);
                $hall_no = $hall_row['room_no'];
                $no_benches = $hall_row['no_benches'];
                $bench_seats = $hall_row['bench_seats'];
            }
            else
            {
                continue;
            }  
                $subslot_rows=array();
            
                $slot_id=$slot_rows[$j]['id'];
                
                $sql = "Select * from subslots where `slot_id` =".$slot_id;//selecting slots which have matching course id(these slots have been assigned to this particular course)
                $res = mysqli_query($con,$sql);//results of the query stored in $res array
                if (mysqli_num_rows($res) > 0) {
                    while($row = mysqli_fetch_assoc($res)) {//fetching each row in the result of the query
                        
                        array_push($subslot_rows,$row); //pushing individual rows to an array containing a list of all the rows
            
                    }
                }
                $seat_rows = array();
                for($p=0;$p<sizeof($subslot_rows);$p++)
                {
                    $sql = "Select * from seats where `subslot_id` =".$subslot_rows[$p]['id']." order by `seat_no` asc";
                    $res = mysqli_query($con,$sql);
                    $seat_rows[$p] = array();
                    if (mysqli_num_rows($res) > 0) {
                        while($row = mysqli_fetch_assoc($res)) {
                                    
                            array_push($seat_rows[$p],$row);
                        
                    }
                    }
                    else
                    {
                    echo "error";
                    }
                }
                if(sizeof($seat_rows)>$bench_seats)   
                {
                    continue;
                }
                if(sizeof($seat_rows)==1)   
                {
                    $size_factor = 12;
                }
                elseif(sizeof($seat_rows)==2)
                {
                    $size_factor = 6;
                }
                elseif(sizeof($seat_rows)==3)
                {
                    $size_factor = 4;
                }
        ?>
        <div class="container cont">
        <div class="row ">
                <div class="col-xs-12 heading positive">
                    Please Note: Only seats coloured in green belong to selected course!!
                </div>
            </div>
        </div>

        <div class="container cont">
            <div class="row ">
                <div class="col-xs-12 heading">
                    Exam Hall No. <?php 
                   echo $hall_no ;
                     ?>
                </div>
            </div>
            
            <div class="row maintop">
                <?php 
                for($q=0;$q<$no_benches;$q++)
                {
                ?>
                <div class="col-md-6 bench">
                    <div class="row">
                        <?php
                        
                        for($p=0;$p<sizeof($subslot_rows);$p++)
                        {
                            if(isset($seat_rows[$p][$q]['seat_no']))
                            {
                            ?>
                            <div class="col-md-<?php echo $size_factor; ?>">
                                    <div class="row ">
                                        <div class="col-xs-12 ">
                                            <?php
                                            $color_picker = "";
                                            if($subslot_rows[$p]['course_id']==$course_id)
                                            {
                                                $color_picker = 'positive';
                                            ?>
                                                <img src="img/seatnewer.png "  class="seatimg">
                                            <?php
                                            }
                                            else
                                            {
                                                ?><img src="img\seatneweryellow.png "  class="seatimg"><?php
                                            }
                                            ?>
                                        </div>
                                    </div>
                                    <div class="row seatinfo <?php echo $color_picker;?>">
                                        <div class="col-xs-12">
                                            <div class="row">
                                                <div class="col-xs-6">
                                                    Seat No:
                                                </div>
                                                <div class="col-xs-6">
                                                    <?php echo $seat_rows[$p][$q]['seat_no']; ?>
                                                </div>
                                            </div>
                                            <div class="row">
                                                <div class="col-xs-12">
                                                    Enroll No:
                                                </div>
                                            </div>
                                            <div class="row">
                                                <div class="col-xs-12">
                                                <?php //querying student details belonging to this particular seat in the particular hall(slot)
                                                $sql = "Select * from student_list where `id` =".$seat_rows[$p][$q]['student_id'];
                                                $res = mysqli_query($con,$sql);
                                                $student = mysqli_fetch_assoc($res);
                                                echo $student['enrollment_no'];
                                                ?>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            <?php
                            }
                            else
                            {
                                ?>
                                <div class="col-md-<?php echo $size_factor; ?>">
                                        <div class="row ">
                                            <div class="col-xs-12 ">
                                                <?php
                                                $color_picker = "";
                                                if($subslot_rows[$p]['course_id']==$course_id)
                                                {
                                                    $color_picker = 'positive';
                                                ?>
                                                    <img src="img/seatnewer.png "  class="seatimg">
                                                <?php
                                                }
                                                else
                                                {
                                                    ?><img src="img\seatneweryellow.png "  class="seatimg"><?php
                                                }
                                                ?>
                                            </div>
                                        </div>
                                        <div class="row seatinfo <?php echo $color_picker;?>">
                                            <div class="col-xs-12">
                                                <div class="row">
                                                    <div class="col-xs-6">
                                                        Seat No:
                                                    </div>
                                                    <div class="col-xs-6">
                                                        --
                                                    </div>
                                                </div>
                                                <div class="row">
                                                    <div class="col-xs-12">
                                                        Enroll No:
                                                    </div>
                                                </div>
                                                <div class="row">
                                                    <div class="col-xs-12">
                                                    --
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                <?php 
                            }
                        }
                        ?>
                    </div>
                    <?php
                /* for($i = 0; $i<sizeof($seat_rows);$i++) {//loop to print all the seating details in this particular slot
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
                <?php }*/?>
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