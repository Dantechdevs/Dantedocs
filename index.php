<?php
// index.php

// Define app name
define('APP_NAME', 'DanteDocs');

// Load config and database
require_once __DIR__ . '/php/config.php';
require_once __DIR__ . '/php/db_connect.php';

// Optional: Session start for future login handling
session_start();

// For now, redirect directly to dashboard
header("Location: pages/dashboard.php");
exit;
