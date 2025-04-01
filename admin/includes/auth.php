<?php

require_once '../includes/config.php';

// Simple authentication check - replace with proper authentication
if (!isset($_SESSION['admin_logged_in']) || $_SESSION['admin_logged_in'] !== true) {
    header("Location: login.php");
    exit();
}

// You would typically have more robust authentication here
// For example, checking user roles, permissions, etc.