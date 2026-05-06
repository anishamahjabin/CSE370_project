<?php
$conn = mysqli_connect("localhost", "root", "", "parking");
if (!$conn) { die("Connection failed"); }
session_start();
?>