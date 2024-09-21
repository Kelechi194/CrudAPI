<?php
//  $host = 'lcalhost';
//  $username = 'root';
//  $password = '';
//  $dbname    = 'crud_api';

$con = mysqli_connect('localhost', 'root', '', 'crud_api');
if(!$con){
    die("Connection Failed:" . mysqli_connect_error());
}
