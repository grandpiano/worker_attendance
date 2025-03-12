<?php
session_start();

if (!isset($_SESSION['isLoginIn'])) {
    header("Location: login.php");
    exit(); // Ensure script stops execution after redirection
}

$conn = new mysqli("localhost", "root", "", "worker_attendant");

// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

if (isset($_GET['id']) && isset($_GET['status'])) {
    $worker_id = $_GET['id'];
    $status = $_GET['status'];
    $date = date('Y-m-d');

    // Check if the attendance for the worker on the current date already exists
    $check_sql = "SELECT * FROM attendance_logs WHERE worker_id = ? AND attendance_date = ?";
    $stmt = $conn->prepare($check_sql);
    $stmt->bind_param("is", $worker_id, $date);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows > 0) {
    echo "<div class='alert alert-warning alert-dismissible fade show' role='alert'>
        Attendance for this worker has already been recorded today.
        <button type='button' class='close' data-dismiss='alert' aria-label='Close'>
            <span aria-hidden='true'>&times;</span>
        </button>
          </div>";
    } else {
        // Insert a new record
        $insert_sql = "INSERT INTO attendance_logs (worker_id, attendance_date, attendance_status) VALUES (?, ?, ?)";
        $stmt = $conn->prepare($insert_sql);
        $stmt->bind_param("iss", $worker_id, $date, $status);
        if ($stmt->execute()) {
            echo "<div class='alert alert-success alert-dismissible fade show' role='alert'>
                Attendance marked successfully.
                <button type='button' class='close' data-dismiss='alert' aria-label='Close'>
                    <span aria-hidden='true'>&times;</span>
                </button>
            </div>";
        } else {
            echo "<div class='alert alert-danger'>Error marking attendance: " . $stmt->error . "</div>";
        }
    }
    $stmt->close();
}

// Fetch workers
$sql = "SELECT * FROM workers";
$result = $conn->query($sql);
include("header.php");
include("navbar.php");

?>


    <div class="container-fluid mt-5">
        <div class="card">
            <div class="card-header bg-primary text-white d-flex justify-content-between">
                <h2 class="">Worker List</h2>
                <form method="post" action="view_attendance_log.php" class="form-inline">
                    <div class="form-group mb-2">
                        <label for="date" class="sr-only">Date</label>
                        <input type="date" class="form-control" id="date" name="attendance_date" value="<?php echo date('Y-m-d'); ?>">
                    </div>
                    <button type="submit" class="btn btn-warning  ml-2">Get Log</button>
                </form>
            </div>
            <div class="card-body">
            <table class="table table-bordered table-sm" id="myTable">
            <thead>
                <tr>
                    <th>ID</th>
                    <th>Name</th>
                    <th>Action</th>
                </tr>
            </thead>
            <tbody>
            <?php 
if ($result->num_rows > 0) {
    while ($row = $result->fetch_assoc()) {
        echo "<tr>";
        echo "<td>" . $row["id"] . "</td>";
        echo "<td>" . $row["name"] . "</td>";
        echo "<td>
                <a href='record_attendance.php?id=" . $row["id"] . "&status=present' class='btn btn-success btn-sm'>
                    <i class='fas fa-check'></i> Present
                </a>
                <a href='record_attendance.php?id=" . $row["id"] . "&status=absent' class='btn btn-danger btn-sm ml-2'>
                    <i class='fas fa-times'></i> Absent
                </a>
              </td>";
        echo "</tr>";
    }
} else {
    echo "<tr><td colspan='3' class='text-center'>No workers found</td></tr>";
}
?>

            </tbody>
        </table>
            </div>
        </div>
    </div>

    <!-- Attendance Modal -->
    <div class="modal fade" id="attendanceModal" tabindex="-1" role="dialog" aria-labelledby="attendanceModalLabel" aria-hidden="true">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="attendanceModalLabel">Attendance Status</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    Attendance for this worker has already been recorded today.
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                </div>
            </div>
        </div>
    </div>

<?php include("footer.php"); ?>

<?php
$conn->close();
?>
