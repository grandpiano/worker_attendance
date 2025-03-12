<?php
session_start();

if (!isset($_SESSION['isLoginIn'])) {
    header("Location: login.php");
    exit(); // Ensure script stops execution after redirection
}

include 'db.php';
if (isset($_GET['id'])) {
    $id = $_GET['id'];
    $query = "SELECT * FROM workers WHERE id = ?";
    $stmt = $conn->prepare($query);
    $stmt->bind_param("i", $id);
    $stmt->execute();
    $result = $stmt->get_result();
    $worker = $result->fetch_assoc();
   
}

if (isset($_POST['submit'])) {


    // Retrieve POST data
    $name = $_POST['name'];
    $contact = $_POST['contact'];
    $id = $_POST['id'];
    
    // Prepare the SQL query
    $query = "UPDATE workers SET name = ?, contact = ? WHERE id = ?";
    $stmt = $conn->prepare($query);
    
    // Bind parameters to the query
    $stmt->bind_param("ssi", $name, $contact, $id);
    
    // Execute the query and check if it was successful
    if ($stmt->execute()) {
        // If the query is successful, redirect with a success message
        header("Location: index.php?message=You successfully edited a user profile");
        exit();
    } else {
        // If the query fails, show an error message
        echo "<p class='alert alert-danger'>Error updating worker: " . $stmt->error . "</p>";
    }
    
    
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <title>Edit Worker</title>
    <link href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css" rel="stylesheet">
</head>
<body>
    <?php include("navbar.php"); ?>

    <div class="container mt-5">
        <div class="row">
            <div class="col-md-6 offset-md-3">
                <div class="card">
                    <div class="card-header">
                        <h3 class="mb-4">Edit Worker</h3>
                    </div>
                    <div class="card-body">
                    <form action="edit_worker.php" method="POST">
                        <input type="hidden" name="id" value="<?php echo $worker['id']; ?>">
                        <div class="form-group">
                            <label for="name">Name</label>
                            <div class="input-group">
                                <div class="input-group-prepend">
                                    <span class="input-group-text"><i class="fas fa-user"></i></span>
                                </div>
                                <input type="text" class="form-control"id="name" name="name" required placeholder="Enter worker's name" value="<?php echo $worker['name'] ; ?>">
                            </div>
                        </div>
                        <div class="form-group">
                            <label for="contact">Contact</label>
                            <div class="input-group">
                                <div class="input-group-prepend">
                                    <span class="input-group-text"><i class="fas fa-phone"></i></span>
                                </div>
                                <input type="text" class="form-control" id="contact" name="contact" required placeholder="Enter worker's contact" value="<?php echo $worker['contact']; ?>">
                            </div>
                        </div>
                        <button type="submit" name="submit" class="btn btn-primary">Update Worker</button>
                        <a class="btn btn-secondary " href="index.php">Back to Worker Listings</a>
                    </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <script src="https://code.jquery.com/jquery-3.5.1.slim.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.5.4/dist/umd/popper.min.js"></script>
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>
    <script src="https://kit.fontawesome.com/a076d05399.js"></script>
</body>
</html>
?>
