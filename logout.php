<?php
session_start();
session_unset();
session_destroy();

// Redirect using PHP first
header("Location: login.php");
exit();
?>

<script>
    // If PHP header redirection fails, use JavaScript
    window.location.href = "login.php";
</script>
