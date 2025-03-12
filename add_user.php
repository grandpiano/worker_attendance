<?php 
session_start();

if (!isset($_SESSION['isLoginIn'])) {
    header("Location: login.php");
    exit(); // Ensure script stops execution after redirection
}


include("header.php");
include("navbar.php");
?>



<?php
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $user_name = $_POST['user_name'];
    $password = password_hash($_POST['password'], PASSWORD_BCRYPT);

    // Database connection
    $conn = new mysqli('localhost', 'root', '', 'worker_attendant');

    if ($conn->connect_error) {
        die("Connection failed: " . $conn->connect_error);
    }

    $sql = "INSERT INTO user_table (user_name, password) VALUES (?, ?)";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("ss", $user_name, $password);

    if ($stmt->execute()) {
        echo "New user added successfully";
    } else {
        echo "Error: " . $stmt->error;
    }

    $stmt->close();
    $conn->close();
}
?>

<div class="container">
    <div class="row mt-5">
        <div class="col-md-6 offset-md-3">
            <div class="card">
                <div class="card-header bg-primary text-white">
                    <h4>Add New Login User</h4>
                </div>
                <div class="card-body">
    <form method="post" action="">
        <div class="form-group">
            <label for="user_name">User Name:</label>
            <input type="text" class="form-control" id="user_name" name="user_name" required>
        </div>
        <div class="form-group">
            <label for="password">Password:</label>
            <input type="password" class="form-control" id="password" name="password" required>
        </div>
        <button type="submit" class="btn btn-primary">Submit</button>
    </form>
                </div>
            </div>
        </div>
    </div>
</div>
<?php 
include("footer.php");
?>