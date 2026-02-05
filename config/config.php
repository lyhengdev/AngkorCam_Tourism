<?php
/**
 * =====================================================
 * AngkorCam Tourism - Configuration & Helpers
 * Professional PHP Application
 * =====================================================
 */

// Error reporting (set to 0 in production)
error_reporting(E_ALL);
ini_set('display_errors', 1);

// =====================================================
// Database Configuration
// =====================================================
define('DB_HOST', 'localhost');
define('DB_USER', 'root');
define('DB_PASS', 'Lyheng!@09092004');
define('DB_NAME', 'angkorcam_pro');

// =====================================================
// Application Configuration
// =====================================================
define('APP_NAME', 'AngkorCam Tourism');
define('APP_URL', 'http://localhost/angkorcam-pro');
define('BASE_PATH', dirname(__DIR__));

// =====================================================
// Database Connection
// =====================================================
try {
    $db = new PDO(
        "mysql:host=" . DB_HOST . ";dbname=" . DB_NAME . ";charset=utf8mb4",
        DB_USER,
        DB_PASS,
        [
            PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
            PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
            PDO::ATTR_EMULATE_PREPARES => false,
            PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
            PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
            PDO::ATTR_EMULATE_PREPARES => false
        ]
    );
} catch (PDOException $e) {
    die("Database Connection Failed: " . $e->getMessage());
}

// =====================================================
// Session Management
// =====================================================
if (session_status() === PHP_SESSION_NONE) {
    ini_set('session.cookie_httponly', 1);
    ini_set('session.use_only_cookies', 1);
    session_start();
}

// =====================================================
// Core Helper Functions
// =====================================================

/**
 * Escape HTML output
 */
function e($value) {
    return htmlspecialchars($value ?? '', ENT_QUOTES, 'UTF-8');
}

/**
 * Redirect to URL
 */
function redirect($url) {
    if (!headers_sent()) {
        header("Location: $url");
        exit;
    }
    echo "<script>window.location.href='$url';</script>";
    exit;
}

/**
 * Check if user is logged in
 */
function isLoggedIn() {
    return isset($_SESSION['user_id']);
}

/**
 * Check if user is admin
 */
function isAdmin() {
    return isset($_SESSION['role']) && $_SESSION['role'] === 'admin';
}

/**
 * Get current user
 */
function getUser() {
    if (!isLoggedIn()) return null;
    
    return [
        'id' => $_SESSION['user_id'] ?? null,
        'name' => $_SESSION['name'] ?? null,
        'email' => $_SESSION['email'] ?? null,
        'role' => $_SESSION['role'] ?? null
    ];
}

/**
 * Set flash message
 */
function setFlash($type, $message) {
    $_SESSION['flash_' . $type] = $message;
}

/**
 * Get and clear flash message
 */
function getFlash($type) {
    if (isset($_SESSION['flash_' . $type])) {
        $message = $_SESSION['flash_' . $type];
        unset($_SESSION['flash_' . $type]);
        return $message;
    }
    return null;
}

/**
 * Generate slug from string
 */
function slugify($text) {
    $text = strtolower(trim($text));
    $text = preg_replace('/[^a-z0-9-]/', '-', $text);
    $text = preg_replace('/-+/', '-', $text);
    return trim($text, '-');
}

/**
 * Format currency
 */
function formatPrice($amount) {
    return '$' . number_format((float)$amount, 2);
}

/**
 * Format date
 */
function formatDate($date, $format = 'M d, Y') {
    if (empty($date)) return '';
    return date($format, strtotime($date));
}

/**
 * Generate unique booking code
 */
function generateBookingCode() {
    return 'AC-' . date('Y') . '-' . strtoupper(substr(uniqid(), -6));
}

/**
 * Sanitize input
 */
function sanitize($input) {
    return htmlspecialchars(strip_tags(trim($input)), ENT_QUOTES, 'UTF-8');
}

/**
 * Validate email
 */
function isValidEmail($email) {
    return filter_var($email, FILTER_VALIDATE_EMAIL) !== false;
}

/**
 * Get asset URL
 */
function asset($path) {
    $path = ltrim($path, '/');

    if (!empty($_SERVER['SCRIPT_NAME'])) {
        $base = str_replace('\\', '/', dirname($_SERVER['SCRIPT_NAME']));
        $base = rtrim($base, '/');
        if ($base === '.') {
            $base = '';
        }
        return ($base === '' ? '' : $base) . '/' . $path;
    }

    return APP_URL . '/public/' . $path;
}

/**
 * Truncate text
 */
function truncate($text, $length = 100) {
    if (strlen($text) <= $length) return $text;
    return substr($text, 0, $length) . '...';
}

/**
 * Get a tour image URL based on slug/title/category
 */
function tourImage($tour) {
    if (!empty($tour['image'])) {
        return $tour['image'];
    }

    $slug = $tour['slug'] ?? slugify($tour['title'] ?? '');
    $category = $tour['category'] ?? '';

    $images = [
        'angkor-wat-sunrise' => 'https://images.unsplash.com/photo-1762270753509-73b6c9d44ecb?auto=format&fit=crop&w=1600&q=80',
        'phnom-penh-city' => 'https://images.unsplash.com/photo-1760341913257-5533ccb9d6de?auto=format&fit=crop&w=1600&q=80',
        'tonle-sap-floating' => 'https://images.pexels.com/photos/32978002/pexels-photo-32978002.jpeg?auto=compress&cs=tinysrgb&w=1600',
        'kampot-kep-escape' => 'https://images.unsplash.com/photo-1634621271670-b6e356ab885e?auto=format&fit=crop&w=1600&q=80',
        'angkor-grand-circuit' => 'https://images.unsplash.com/photo-1759629274346-5e5f036254ef?auto=format&fit=crop&w=1600&q=80',
        'silk-island-bike' => 'https://images.pexels.com/photos/34809450/pexels-photo-34809450.jpeg?auto=compress&cs=tinysrgb&w=1600'
    ];

    $categoryImages = [
        'Cultural' => $images['angkor-wat-sunrise'],
        'Adventure' => $images['angkor-grand-circuit'],
        'Nature' => $images['tonle-sap-floating'],
        'Beach' => $images['kampot-kep-escape']
    ];

    return $images[$slug]
        ?? $categoryImages[$category]
        ?? $images['angkor-wat-sunrise'];
}

/**
 * Log error to file
 */
function logError($message) {
    $logFile = BASE_PATH . '/storage/logs/error.log';
    $timestamp = date('Y-m-d H:i:s');
    $logMessage = "[$timestamp] $message\n";
    error_log($logMessage, 3, $logFile);
}

/**
 * Require login
 */
function requireLogin() {
    if (!isLoggedIn()) {
        setFlash('error', 'Please login to continue');
        redirect('?page=login');
    }
}

/**
 * Require admin
 */
function requireAdmin() {
    if (!isAdmin()) {
        setFlash('error', 'Access denied');
        redirect('?page=home');
    }
}

// =====================================================
// Load Models
// =====================================================
require_once BASE_PATH . '/app/Models/User.php';
require_once BASE_PATH . '/app/Models/Tour.php';
require_once BASE_PATH . '/app/Models/Booking.php';
