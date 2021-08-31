<?php 
require ('top.php');
if(!(isset($_GET['date'])))
{?><div class="container cont">
    <div class="heading">
            No exams Today
        </div>
   </div>
        <?php
     die();   
}

$todaysDate =new DateTime($_GET['date']) ;
$course_list = array();
$sql = "Select * from course_list  ";
$res = mysqli_query($con,$sql);
$rows = array();
if (mysqli_num_rows($res) > 0) {
    while($row = mysqli_fetch_assoc($res)) {
                 
        array_push($rows,$row);
      
  }
  for($i=0;$i<sizeof($rows);$i++)
  {
    if($rows[$i]['Scheduled_date']==$todaysDate->format('Y-m-d'))
    {
        array_push($course_list, $rows[$i]);
    }
  }
}

?>
<div class="container cont">
    <div class="row">
        <div class="col-xs-12 heading">
            <?php echo $todaysDate->format('d')." ".$todaysDate->format('F'). " ".$todaysDate->format('Y'); ?>
        </div>
    </div>
    <table>
        <tr class="table_head">
            <th>No.</th>
            <th>Name</th>
            <th>Department</th>
            <th>Semester</th>
            <th>Duration</th>
            <th>Marks</th>
        </tr>
        
        <?php
        
        if(sizeof($course_list)!=null)
        {
            for($i=0;$i<sizeof($course_list);$i++)
            {
            ?>
            
            <tr >
                <td class="viewfullcourse"><a class="viewfullcourse" href="viewseating.php?courseid=<?php echo $course_list[$i]['id']; ?>"><?php echo $i+1; ?></a></td>
                <td class="viewfullcourse"><a class="viewfullcourse" href="viewseating.php?courseid=<?php echo $course_list[$i]['id']; ?>"><?php echo $course_list[$i]['name']; ?></a></td>
                <td class="viewfullcourse"><a class="viewfullcourse" href="viewseating.php?courseid=<?php echo $course_list[$i]['id']; ?>"><?php echo explode(' ',trim($course_list[$i]['Dept']))[0]; ?></a></td>
                <td class="viewfullcourse"><a class="viewfullcourse" href="viewseating.php?courseid=<?php echo $course_list[$i]['id']; ?>"><?php echo $course_list[$i]['Semester']; ?></a></td>
                <td class="viewfullcourse"><a class="viewfullcourse" href="viewseating.php?courseid=<?php echo $course_list[$i]['id']; ?>"><?php echo $course_list[$i]['Duration']; ?>Hr</a></td>
                <td class="viewfullcourse"><a class="viewfullcourse" href="viewseating.php?courseid=<?php echo $course_list[$i]['id']; ?>"><?php echo $course_list[$i]['Marks']; ?></a></td>
            </tr>
         
        <?php 
            }
        }
         ?>
    </table>
    <?php if(sizeof($course_list)==null) { ?>
        <div class="heading">
            No exams Today
        </div>
    <?php } ?>
    
</div>
<?php 
    
require ('bottom.php');

?>