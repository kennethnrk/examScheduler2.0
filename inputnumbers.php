<?php

require ("top.php");//including header file
$msg = '';
if(isset($_GET['errmsg'] )&& $_GET['errmsg'] !== null)
{
    $msg = $_GET['errmsg'];
}

/* Checking to see if existing exmas have been scheduled */
$sql = "Select * from exams";
$res = mysqli_query($con,$sql);
$files = glob('files/*');
if (mysqli_num_rows($res) > 0 || sizeof($files)>0) {
    ?>
    <div class="container cont">
    <div class="row heading">
        <div class="col-xs-12">
     You Have Existing Exams Scheduled !!
        </div>
    </div>
    <div class="row outer_clear">
        <div class="col-xs-12">
            <a href="delete.php" class="clear_data"> Clear Existing Exams</a>
        </div>
    </div>
    </div>
    <?php
}
?>

<!-- Form used to input required details -->
<div class="container cont">
    <div class="row heading">
        <div class="col-xs-12">
     Please Enter The Following Information:
        </div>
    </div>
    
    <form action="inputdetails.php" method="POST" enctype="multipart/form-data">
    
     <div class="row toprow">
        <div class="col-md-6 label">
            Number of Departments:
        </div>
        <div class="col-md-6">
            <input type="number" min="1" max="5"name="no_depts" id="no_depts" required>
        </div>
    </div>
       

    <div class="row">
        <div class="col-md-6 label">
            Exam Start Date:
        </div>
        <div class="col-md-6">
        <input type="date" id="startdate" name="startdate" required>
        </div>
    </div>

    <div class="row">
        <div class="col-md-6 label">
            Exam End Date:
        </div>
        <div class="col-md-6">
        <input type="date" id="enddate" name="enddate" required>
        </div>

    </div>
    
    <div class="row">
        <div class="col-md-6 label">
            Choose Term
        </div>
        <div class="col-md-6">
        <select name="term" id="term" required>
            <option value="odd">ODD</option>
             <option value="even">EVEN</option>
        </select>
        </div>
    </div>
    <div class="row">
        <div class="col-md-12">
            <div class="field_error">
                <?php echo $msg ?>
            </div>
        </div>
    </div>
    <div class="row">
        
        <div class="col-md-12 submitbutton">
            <input type="submit" value="submit" name="submit">
        </div>

    </div>
    
    </form>
</div>
<!-- End of form -->

<?php
require ("bottom.php");//including footer
     
?>
