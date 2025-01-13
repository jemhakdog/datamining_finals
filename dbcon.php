<?php

$con = mysqli_connect("localhost","root","","students");

if(!$con){
    die('Connection Failed'. mysqli_connect_error());
}

?>