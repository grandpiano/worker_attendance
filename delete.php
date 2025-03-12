<?php
session_start();

if (!isset($_SESSION['isLoginIn'])) {
    header("Location: login.php");
    exit(); // Ensure script stops execution after redirection
}

// Include database connection file
include_once 'db.php';

// Get the user ID from the request
$user_id = $_GET['id'];

// Check if the user ID is valid
if (isset($user_id) && is_numeric($user_id)) {
    // Prepare the SQL delete statement
    $sql = "DELETE FROM workers WHERE id = ?";
    
    // Initialize the statement
    if ($stmt = $conn->prepare($sql)) {
        // Bind the user ID to the statement
        $stmt->bind_param("i", $user_id);
        
        // Execute the statement
        if ($stmt->execute()) {
            // Redirect to the index page
            header("Location: index.php");
            exit();
        } else {
            echo "Error: Could not execute the delete statement.";
        }
        
        // Close the statement
        $stmt->close();
    } else {
        echo "Error: Could not prepare the delete statement.";
    }
} else {
    echo "Error: Invalid user ID.";
}

// Close the database connection
$conn->close();
?>