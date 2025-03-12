<?php
include 'db.php';

$name = $_POST['name'];
$contact = $_POST['contact'];
$attendance = $_POST['attendance'];

$query = "INSERT INTO workers (name, contact, attendance) VALUES ('$name', '$contact', '$attendance')";
mysqli_query($conn, $query);

header("Location: index.php");
?>
