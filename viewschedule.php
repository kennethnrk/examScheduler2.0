<?php
include 'calendar.php';
if(isset($_SESSION['ADMIN_LOGIN']) && $_SESSION['ADMIN_LOGIN'] != '')
{  }


else
{
   header('location:login.php');
   die();
}
?>
<html>
<head> 
<title>Exam Scheduler</title>
<link rel="icon" href="img/logo.png" type="image/icon type">  
<link href="calendar.css" type="text/css" rel="stylesheet" />
<link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/font-awesome/4.7.0/css/font-awesome.min.css">
</head>
<body>
<?php

 
$calendar = new Calendar();
 
echo $calendar->show();
?>
</body>
</html>