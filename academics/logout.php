<?php
include 'db.php';

// ---------- LOGOUT ----------
if (isset($_POST['logout'])) {
    // If using sessions later: session_destroy();
    header("Location: ../index.php"); // Redirect to home page
    exit();
}

// ---------- FACULTY DELETE ----------
if (isset($_POST['delete_id'])) {
    // ... your existing delete code
}
