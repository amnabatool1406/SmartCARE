<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

function checkAccess($required_role) {
    if (!isset($_SESSION['user_id']) || $_SESSION['role_id'] != $required_role) {
        header("Location: login.php?error=unauthorized");
        exit();
    }
}
?>