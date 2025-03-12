<?php
session_start();

if (!isset($_SESSION['isLoginIn'])) {
    header("Location: login.php");
    exit(); // Ensure script stops execution after redirection
}

include 'db.php';

// Handle form submission
if (isset($_POST['submit'])) {
    $name = $_POST['name'];
    $contact = $_POST['contact'];
    $attendance = 'Absent';  // Default attendance status

    // Insert new worker into the database
    $query = "INSERT INTO workers (name, contact, attendance) VALUES ('$name', '$contact', '$attendance')";
    mysqli_query($conn, $query);
    header("Location: index.php");
    exit();
}
include("header.php");
include("navbar.php");
?>


    <div class="container mt-5">
        <div class="row">
            <div class="col-md-6 offset-md-3">
                <div class="card shadow-lg">
                    <div class="card-header bg-primary text-white">
                        <h3 class="">Add Worker</h3>
                    </div>
                    <div class="card-body">
                    <form method="POST">
            <div class="form-group">
                <label for="name">Name</label>
                <div class="input-group">
                    <div class="input-group-prepend">
                        <span class="input-group-text"><i class="fas fa-user"></i></span>
                    </div>
                    <input type="text" class="form-control" id="name" name="name" required placeholder="Enter worker's name">
                </div>
            </div>
            <div class="form-group">
                <label for="contact">Contact</label>
                <div class="input-group">
                    <div class="input-group-prepend">
                        <span class="input-group-text"><i class="fas fa-phone"></i></span>
                    </div>
                    <input type="text" class="form-control" id="contact" name="contact" required placeholder="Enter worker's contact">
                </div>
            </div>
            <button type="submit" name="submit" class="btn btn-primary">Add Worker</button>
            <a class="btn btn-secondary " href="index.php">Back to Worker Listings</a>
        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
   <?php 
   include("footer.php");