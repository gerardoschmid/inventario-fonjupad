<?php 

session_start();

require_once __DIR__ . '/../config/database.php';
require_once __DIR__ . '/../config/helpers.php';

// Auth guard: Skip check if we are on the index.php (login page)
$current_page = basename($_SERVER['PHP_SELF']);
if(!isset($_SESSION['userId']) && $current_page !== 'index.php') {
	header('location:'.$store_url);	
    exit();
} 



?>