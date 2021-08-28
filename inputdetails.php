<?php
require ("top.php");//including header file
if(isset($_POST['submit']))//checking of the required data has been posted from inputnumbers.php
{
    $no_depts = $_POST['no_depts'];
}
else
{
    header('location: index.php');
}
/* Start of the form */
?>
<form action="checkdetails.php" method="POST" enctype="multipart/form-data">

<div class="container cont">
    <div class="row heading ">
       Input Hall Details
    </div>
    <div class="row toprow">
        <div class="col-md-6 label">
            List of Halls:
        </div>
        <div class="col-md-6">
            <input type="file"  name="halls" id="halls" required>
        </div>
    </div>
<?php
for($i=0; $i<$no_depts; $i++)
{
?>

    <div class="row heading toprow">
        Department <?php echo $i+1; ?>
    </div>
    <div class="row toprow">
        <div class="col-md-6 label">
            Select Department
        </div>
        <div class="col-md-6">
            <select name="dept[<?php echo $i; ?>]" id="dept<?php echo $i; ?>" required>
                <option value="Mechanical Engineering">Mechanical Engineering</option>
                <option value="Civil Engineering">Civil Engineering</option>
                <option value="Electrical Engineering">Electrical Engineering </option>
                <option value="Industrial Electronics">Industrial Electronics</option>
                <option value="Computer Engineering">Computer Engineering</option>
            </select>
        </div>
    </div>
    <div class="row">
        <div class="col-md-6 label">
            List of Courses:
        </div>
        <div class="col-md-6">
            <input type="file"  name="courses<?php echo $i; ?>" id="courses<?php echo $i; ?>" required>
        </div>
    </div>
    <div class="row">
        <div class="col-md-6 label">
            List of Students:
        </div>
        <div class="col-md-6">
            <input type="file"  name="students<?php echo $i; ?>" id="students<?php echo $i; ?>" required>
        </div>
    </div>


<?php
}
?>    
    <div class="row">
        
        <div class="col-md-12 submitbutton">
            <input type="submit" value="submit" name="submit">
        </div>

    </div>
</div>
<input type="hidden" name="no_depts" id="no_depts" value="<?php echo $no_depts ?>">
<input type="hidden"  id="startdate" name="startdate" value="<?php echo $_POST['startdate'] ?>">
<input type="hidden"  id="enddate" name="enddate" value="<?php echo $_POST['enddate']  ?>">
<input type="hidden" id="term" name="term" value="<?php echo $_POST['term']  ?>">

</form>
<?php
require ("bottom.php");
?>