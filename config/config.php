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
define('DB_HOST', '127.0.0.1'); // use "mysql" if the PHP app runs inside Docker
define('DB_USER', 'root');
define('DB_PASS', '');
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
        'angkor-wat-sunrise-bayon' => 'https://commons.wikimedia.org/wiki/Special:FilePath/Sunrise_at_Angkor_Wat_Cambodia.jpg?width=1600',
        'ta-prohm-jungle-temple' => 'https://commons.wikimedia.org/wiki/Special:FilePath/Ta_Prohm_(I).jpg?width=1600',
        'preah-khan-angkor-thom' => 'https://commons.wikimedia.org/wiki/Special:FilePath/Preah_Khan_temple_at_Angkor,_Cambodia.jpg?width=1600',
        'tonle-sap-floating-village' => 'https://commons.wikimedia.org/wiki/Special:FilePath/Tonl%C3%A9_Sap_Floating_Village_(6202547090).jpg?width=1600',
        'phnom-penh-royal-palace' => 'https://commons.wikimedia.org/wiki/Special:FilePath/Royal_Palace,_Phnom_Penh_Cambodia_1.jpg?width=1600',
        'battambang-bamboo-train' => 'https://commons.wikimedia.org/wiki/Special:FilePath/Bamboo_train_Battambang.jpg?width=1600',
        'kampot-kep-coastal-escape' => 'https://commons.wikimedia.org/wiki/Special:FilePath/Kep_Crab_Market.jpg?width=1600',
        'koh-rong-island-escape' => 'https://commons.wikimedia.org/wiki/Special:FilePath/Koh_Rong_-_Cambodia_(50925116073).jpg?width=1600',
        'kratie-mekong-dolphins' => 'https://commons.wikimedia.org/wiki/Special:FilePath/Irrawaddy_Dolphin.jpg?width=1600'
    ];

    $categoryImages = [
        'Cultural' => $images['angkor-wat-sunrise-bayon'],
        'Adventure' => $images['battambang-bamboo-train'],
        'Nature' => $images['tonle-sap-floating-village'],
        'Beach' => $images['koh-rong-island-escape']
    ];

    return $images[$slug]
        ?? $categoryImages[$category]
        ?? $images['angkor-wat-sunrise-bayon'];
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
