<?php
require ("connection.php");
if(isset($_SESSION['ADMIN_LOGIN']) && $_SESSION['ADMIN_LOGIN'] != '')
      {   
      
         header('location:index.php');
         die();
      }
      $msg='';

if(isset($_POST['submit']))
{
   $username=get_safe_value($con,$_POST['username']);
   $password=get_safe_value($con,$_POST['password']);

   $sql="select * from admin_users where username='$username' and password ='$password'";
   $res=mysqli_query($con,$sql);
   $count=mysqli_num_rows($res);
   if($count>0) 
   {  
      $_SESSION['ADMIN_LOGIN']='yes';
      $_SESSION['ADMIN_USERNAME']=$username;
      header('location: index.php');
   }
   else{
      $msg = "Please Enter correct login details";
   }
}
?>
<!DOCTYPE html>
<html>
    <head>
        <title>Exam Scheduler</title>
        <link rel="icon" href="img/logo.png" type="image/icon type">
        <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/font-awesome/4.7.0/css/font-awesome.min.css">
        <link rel="stylesheet" href="login.css">
       
     

    </head>

    
<div id="header">

    <a href="index.php"><img src="img/full_logo.png" class="center imgmain"  ></a>
  
</div>

    <body class="col-xs-12">


<div id="login_container">
        <form  method="POST" >
            <h1 id="login_h1"  >Admin Login</h1>

             <div class="label_div">
            <label for="name">Admin Name:</label>
            <br/>
            <input type="text" name="username" id="name" placeholder="Enter your name" required>       
            <br/>

            <label for="pass">Password:</label>
            <br/>
            <input type="password" name="password" id="pass" placeholder="Enter your password" required>       
            <br/>

            <input type="submit" name="submit" id="submit" value="Submit">
            <div class="field_error">
                        <?php echo $msg ?>
                     </div>
             </div>
            
        </form>
    </div>
    <?php

?>
    </body>
</html>