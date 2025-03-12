<?php
session_start();

if (!isset($_SESSION['isLoginIn'])) {
    header("Location: login.php");
    exit(); // Ensure script stops execution after redirection
}

include 'db.php'; // Include database connection

// Get current month and year
$month = date('m');
$year = date('Y');

// Fetch worker attendance summary
$sql = "SELECT workers.id, workers.name, workers.contact,
        SUM(CASE WHEN attendance.status = 'present' THEN 1 ELSE 0 END) AS present_days,
        SUM(CASE WHEN attendance.status = 'absent' THEN 1 ELSE 0 END) AS absent_days
        FROM workers
        LEFT JOIN attendance ON workers.id = attendance.worker_id
        AND MONTH(attendance.date) = $month AND YEAR(attendance.date) = $year
        GROUP BY workers.id";
$result = mysqli_query($conn, $sql);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Attendance Report</title>
    <link rel="stylesheet" href="style.css">
</head>
<body>
    <div class="container">
        <h1>Attendance Report - <?php echo date('F Y'); ?></h1>
        <table>
            <tr>
                <th>Name</th>
                <th>Contact</th>
                <th>Present Days</th>
                <th>Absent Days</th>
            </tr>
            <?php while ($row = mysqli_fetch_assoc($result)) { ?>
                <tr>
                    <td><?php echo $row['name']; ?></td>
                    <td><?php echo $row['contact']; ?></td>
                    <td><?php echo $row['present_days']; ?></td>
                    <td><?php echo $row['absent_days']; ?></td>
                </tr>
            <?php } ?>
        </table>
        <a href="generate_pdf.php" class="download-report">Download as PDF</a>
    </div>
</body>
</html>
