<?php
session_start();

if (!isset($_SESSION['isLoginIn'])) {
    header("Location: login.php");
    exit(); // Ensure script stops execution after redirection
}


include 'db.php';

// Handle Search, Filter, and Attendance Logging
$search = isset($_POST['search']) ? $_POST['search'] : '';
$attendance_filter = isset($_POST['attendance_filter']) ? $_POST['attendance_filter'] : '';
$date = date("Y-m-d");  // Get today's date

// Mark attendance (Present/Absent)
if (isset($_GET['log_attendance'])) {
    $worker_id = $_GET['worker_id'];
    $status = $_GET['attendance_status'];

    // Insert into attendance_logs table
    $query = "INSERT INTO attendance_logs (worker_id, attendance_status, attendance_date) 
              VALUES ($worker_id, '$status', '$date')";
    mysqli_query($conn, $query);
    header("Location: index.php");
    exit();
}

// Build query with search and filter
$query = "SELECT * FROM workers WHERE name LIKE '%$search%'";
if ($attendance_filter != '') {
    $query .= " AND attendance = '$attendance_filter'";
}

$result = mysqli_query($conn, $query);

$total_user = 0;

$user_count_query = "SELECT COUNT(*) as total_users FROM user_table";
$user_count_result = mysqli_query($conn, $user_count_query);
$user_count_row = mysqli_fetch_assoc($user_count_result);
$total_users = $user_count_row['total_users'];


include("header.php");
include("navbar.php");

?>

    <div class="container-fluid mt-5">
        <div class="row">
            <div class="col-md-9">
            <div class="card shadow-sm ">
            <div class="card-header bg-primary text-white d-flex justify-content-between">
                <h4>Workers Log</h4>
                <a href="add_worker.php" class="btn btn-warning">Add Worker</a>
            </div>
            <div class="card-body">
            <table class="table table-bordered table-sm" id="myTable">
            <thead class="">
                <tr>
                    <th>Name</th>
                    <th>Contact</th>
                    <th>WhatsApp</th>
                    <th>Action</th>
                </tr>
            </thead>
            <tbody>
                <?php while ($worker = mysqli_fetch_assoc($result)) { ?>
                    <tr>
                        <td><?php echo $worker['name']; ?></td>
                        <td><?php echo $worker['contact']; ?></td>
                        <td>
                        <a href="https://wa.me/<?=$worker['contact']?>" target="_blank" class="btn btn-success">
                            <i class="fab fa-whatsapp"></i>
                        </a>
                        </td>
                        <td>
                        <a class="btn btn-success btn-sm" href="edit_worker.php?id=<?php echo $worker['id']; ?>">
    <i class="fas fa-edit"></i> Edit
</a>

<a class="btn btn-danger btn-sm" href="delete.php?id=<?php echo $worker['id']; ?>&attendance_status=Absent">
    <i class="fas fa-trash-alt"></i> Delete
</a>

                        </td>
                    </tr>
                    <?php $total_user++; ?>
                <?php } ?>
            </tbody>
        </table>
            </div>
        </div>
            </div>
            <div class="col-md-3">
                <div class="card border border-primary">
                    <div class="card-header bg-primary text-white">
                        <h4>Satistics</h4>
                    </div>
                    <div class="card-body">
                        <div class="row text-center ">
                            <div class="col-6">
                                <div class="card-body bg-sm border">
                                    <i class="fas fa-users text-primary"></i>
                                    <h3><?=$total_user?></h3>
                                    <p>Total Workers</p>
                                </div>
                            </div>
                            <div class="col-6">
                                <div class="card-body rounded bg-sm border">
                                <i class="fas fa-user text-primary"></i>
                                    <h3><?=$total_users?></h3>
                                    <p>Total Total Users</p>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

   <?php 

   include("footer.php");