<?php

session_start();

if (!isset($_SESSION['isLoginIn'])) {
    header("Location: login.php");
    exit(); // Ensure script stops execution after redirection
}

// Include database connection
include 'db.php';

// Get the date from POST request
$attendance_date = $_POST['attendance_date'];

// Fetch attendance log
$query = "
    SELECT workers.name, attendance_logs.attendance_date, attendance_logs.attendance_status 
    FROM attendance_logs 
    JOIN workers ON workers.id = attendance_logs.worker_id 
    WHERE attendance_logs.attendance_date = ?
";

$stmt = $conn->prepare($query);
$stmt->bind_param("s", $attendance_date);
$stmt->execute();
$result = $stmt->get_result();

include("header.php");
include("navbar.php");
?>


    <div class="container-fluid mt-5">
        <div class="card">
            <div class="card-header">
                <h2 class="">Attendance Log for <?php echo htmlspecialchars($attendance_date); ?></h2>
            </div>
            <div class="card-body">
        <table class="table table-bordered table-sm table-striped" id="myTable">
            <thead class="thead-dark">
                <tr>
                    <th>Worker Name</th>
                    <th>Attendance Date</th>
                    <th>Status</th>
                </tr>
            </thead>
            <tbody>
                <?php while ($row = $result->fetch_assoc()): ?>
                    <tr>
                        <td><?php echo htmlspecialchars($row['name']); ?></td>
                        <td><?php echo htmlspecialchars($row['attendance_date']); ?></td>
                        <td><?php echo htmlspecialchars($row['attendance_status']); ?></td>
                    </tr>
                <?php endwhile; ?>
            </tbody>
        </table>
            </div>
        </div>
    </div>


<?php include("footer.php");

$stmt->close();
$conn->close();
?>