<?php session_start();

$con=mysqli_connect("localhost","root","","exam_scheduler2.0");


?>
<?php

function pr($arr){
    echo '<pre>';
    print_r($arr);
    echo '</pr>' ;
}

function prx($arr){
    echo '<pre>';
    print_r($arr);
    die();

}

function get_safe_value($con, $str)
{   

    if($str!=''){
        $str=trim($str);
    return mysqli_real_escape_string($con, $str);
    }
}
?>