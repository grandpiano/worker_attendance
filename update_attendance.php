<?php
session_start();

if (!isset($_SESSION['isLoginIn'])) {
    header("Location: login.php");
    exit(); // Ensure script stops execution after redirection
}

include 'db.php';

$id = $_GET['id'];
$status = $_GET['status'];

$query = "UPDATE workers SET attendance='$status' WHERE id=$id";
mysqli_query($conn, $query);

header("Location: index.php");
?>
