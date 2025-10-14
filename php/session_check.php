<?php
// php/session_check.php
session_start();

if (!isset($_SESSION['user_id'])) {
    header("Location: ../login.php");
    exit();
}
?>
// Compare this snippet from php/db_connect.php: