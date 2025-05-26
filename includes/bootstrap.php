<?php
// Include configuration files
require_once __DIR__ . '/../config/config.php';
require_once __DIR__ . '/../config/database.php';

// Include required utility classes
require_once __DIR__ . '/../utils/Helpers.php';
require_once __DIR__ . '/../utils/Auth.php';

// Debug session status
error_log("Session status before Auth initialization: " . session_status());

// Initialize authentication
$auth = Auth::getInstance();

// Debug session status after Auth initialization
error_log("Session status after Auth initialization: " . session_status()); 