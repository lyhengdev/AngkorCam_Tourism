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
define('DB_HOST', getenv('DB_HOST') ?: '127.0.0.1'); // use "mysql" if the PHP app runs inside Docker
define('DB_PORT', getenv('DB_PORT') ?: '3306');
define('DB_USER', getenv('DB_USER') ?: 'root');
define('DB_PASS', getenv('DB_PASS') ?: '');
define('DB_NAME', getenv('DB_NAME') ?: 'angkorcam_pro');
define('DB_SSL_CA', getenv('DB_SSL_CA') ?: ''); // optional CA path for TLS (e.g., TiDB Cloud)

// =====================================================
// Application Configuration
// =====================================================
define('APP_NAME', 'AngkorCam Tourism');
define('APP_URL', 'http://localhost/angkorcam-pro');
define('BASE_PATH', dirname(__DIR__));
define('JWT_SECRET', getenv('JWT_SECRET') ?: 'angkorcam_secret');
define('JWT_TTL', (int)(getenv('JWT_TTL') ?: 604800)); // 7 days

// =====================================================
// Database Connection
// =====================================================
try {
    $dsn = "mysql:host=" . DB_HOST . ";port=" . DB_PORT . ";dbname=" . DB_NAME . ";charset=utf8mb4";
    $options = [
        PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
        PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
        PDO::ATTR_EMULATE_PREPARES => false
    ];
    if (!empty(DB_SSL_CA)) {
        $sslCa = DB_SSL_CA;
        if ($sslCa[0] !== '/' && !preg_match('~^[A-Za-z]:\\\\~', $sslCa)) {
            $sslCa = BASE_PATH . '/' . ltrim($sslCa, '/');
        }
        $options[PDO::MYSQL_ATTR_SSL_CA] = $sslCa;
        $options[PDO::MYSQL_ATTR_SSL_VERIFY_SERVER_CERT] = true;
    }
    $db = new PDO($dsn, DB_USER, DB_PASS, $options);
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
// JWT Helpers
// =====================================================
function base64UrlEncode($data) {
    return rtrim(strtr(base64_encode($data), '+/', '-_'), '=');
}

function base64UrlDecode($data) {
    $remainder = strlen($data) % 4;
    if ($remainder) {
        $data .= str_repeat('=', 4 - $remainder);
    }
    return base64_decode(strtr($data, '-_', '+/'));
}

function jwtEncode($payload, $secret) {
    $header = ['typ' => 'JWT', 'alg' => 'HS256'];
    $segments = [
        base64UrlEncode(json_encode($header)),
        base64UrlEncode(json_encode($payload))
    ];
    $signingInput = implode('.', $segments);
    $signature = hash_hmac('sha256', $signingInput, $secret, true);
    $segments[] = base64UrlEncode($signature);
    return implode('.', $segments);
}

function jwtDecode($token, $secret) {
    if (empty($token)) return null;
    $parts = explode('.', $token);
    if (count($parts) !== 3) return null;

    [$headerB64, $payloadB64, $signatureB64] = $parts;
    $header = json_decode(base64UrlDecode($headerB64), true);
    $payload = json_decode(base64UrlDecode($payloadB64), true);
    if (!is_array($header) || !is_array($payload)) return null;
    if (($header['alg'] ?? '') !== 'HS256') return null;

    $signature = base64UrlDecode($signatureB64);
    $expected = hash_hmac('sha256', $headerB64 . '.' . $payloadB64, $secret, true);
    if (!hash_equals($expected, $signature)) return null;

    $now = time();
    if (isset($payload['exp']) && $now >= (int)$payload['exp']) return null;
    if (isset($payload['nbf']) && $now < (int)$payload['nbf']) return null;

    return $payload;
}

function isHttpsRequest() {
    if (!empty($_SERVER['HTTPS']) && $_SERVER['HTTPS'] !== 'off') return true;
    return isset($_SERVER['SERVER_PORT']) && (int)$_SERVER['SERVER_PORT'] === 443;
}

function issueAuthToken($user) {
    $now = time();
    $payload = [
        'sub' => (int)($user['id'] ?? 0),
        'name' => $user['name'] ?? '',
        'email' => $user['email'] ?? '',
        'role' => $user['role'] ?? 'customer',
        'iat' => $now,
        'exp' => $now + JWT_TTL
    ];
    return jwtEncode($payload, JWT_SECRET);
}

function setAuthCookie($token) {
    setcookie('auth_token', $token, [
        'expires' => time() + JWT_TTL,
        'path' => '/',
        'secure' => isHttpsRequest(),
        'httponly' => true,
        'samesite' => 'Lax'
    ]);
}

function clearAuthCookie() {
    setcookie('auth_token', '', [
        'expires' => time() - 3600,
        'path' => '/',
        'secure' => isHttpsRequest(),
        'httponly' => true,
        'samesite' => 'Lax'
    ]);
}

function getAuthPayload() {
    $token = $_COOKIE['auth_token'] ?? null;
    return jwtDecode($token, JWT_SECRET);
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
    return getAuthPayload() !== null;
}

/**
 * Check if user is admin
 */
function isAdmin() {
    $payload = getAuthPayload();
    return $payload && ($payload['role'] ?? '') === 'admin';
}

/**
 * Get current user
 */
function getUser() {
    $payload = getAuthPayload();
    if (!$payload) return null;
    return [
        'id' => $payload['sub'] ?? null,
        'name' => $payload['name'] ?? null,
        'email' => $payload['email'] ?? null,
        'role' => $payload['role'] ?? null
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
        $image = $tour['image'];
        if (preg_match('~^https?://~i', $image)) {
            return $image;
        }
        return asset($image);
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
 * Save uploaded image and return ['path' => string|null, 'error' => string|null]
 */
function saveUploadedImage($fieldName, $subDir = 'uploads/tours', $maxSizeMb = 5) {
    if (empty($_FILES[$fieldName]) || $_FILES[$fieldName]['error'] === UPLOAD_ERR_NO_FILE) {
        return ['path' => null, 'error' => null];
    }

    $file = $_FILES[$fieldName];
    if ($file['error'] !== UPLOAD_ERR_OK) {
        return ['path' => null, 'error' => 'Image upload failed. Please try again.'];
    }

    $maxBytes = (int)$maxSizeMb * 1024 * 1024;
    if ($file['size'] > $maxBytes) {
        return ['path' => null, 'error' => 'Image is too large. Max size is ' . $maxSizeMb . 'MB.'];
    }

    $finfo = new finfo(FILEINFO_MIME_TYPE);
    $mime = $finfo->file($file['tmp_name']);
    $allowed = [
        'image/jpeg' => 'jpg',
        'image/png' => 'png',
        'image/webp' => 'webp',
        'image/gif' => 'gif'
    ];
    if (!isset($allowed[$mime])) {
        return ['path' => null, 'error' => 'Invalid image type. Use JPG, PNG, WEBP, or GIF.'];
    }

    if (!empty(getenv('CLOUDINARY_CLOUD_NAME'))) {
        return uploadToCloudinary($file['tmp_name'], $file['name'], $mime);
    }

    $relativeDir = trim($subDir, '/');
    $targetDir = BASE_PATH . '/public/' . $relativeDir;
    if (!is_dir($targetDir) && !mkdir($targetDir, 0755, true)) {
        return ['path' => null, 'error' => 'Upload directory is not writable.'];
    }

    $fileName = 'tour_' . date('Ymd_His') . '_' . bin2hex(random_bytes(4)) . '.' . $allowed[$mime];
    $targetPath = $targetDir . '/' . $fileName;
    if (!move_uploaded_file($file['tmp_name'], $targetPath)) {
        return ['path' => null, 'error' => 'Failed to save the uploaded image.'];
    }

    return ['path' => $relativeDir . '/' . $fileName, 'error' => null];
}

/**
 * Upload image to Cloudinary and return ['path' => url|null, 'error' => string|null]
 */
function uploadToCloudinary($tmpPath, $originalName, $mimeType) {
    $cloudName = getenv('CLOUDINARY_CLOUD_NAME') ?: '';
    $apiKey = getenv('CLOUDINARY_API_KEY') ?: '';
    $apiSecret = getenv('CLOUDINARY_API_SECRET') ?: '';
    $folder = getenv('CLOUDINARY_FOLDER') ?: '';

    if ($cloudName === '' || $apiKey === '' || $apiSecret === '') {
        return ['path' => null, 'error' => 'Cloudinary is not configured.'];
    }
    if (!function_exists('curl_init')) {
        return ['path' => null, 'error' => 'PHP cURL extension is required for Cloudinary uploads.'];
    }

    $timestamp = time();
    $params = ['timestamp' => $timestamp];
    if ($folder !== '') {
        $params['folder'] = $folder;
    }
    $signature = cloudinarySignature($params, $apiSecret);

    $endpoint = 'https://api.cloudinary.com/v1_1/' . $cloudName . '/image/upload';
    $postFields = $params;
    $postFields['api_key'] = $apiKey;
    $postFields['signature'] = $signature;
    $postFields['file'] = new CURLFile($tmpPath, $mimeType, $originalName);

    $ch = curl_init($endpoint);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
    curl_setopt($ch, CURLOPT_POST, true);
    curl_setopt($ch, CURLOPT_POSTFIELDS, $postFields);
    curl_setopt($ch, CURLOPT_TIMEOUT, 30);

    $response = curl_exec($ch);
    if ($response === false) {
        $error = curl_error($ch);
        curl_close($ch);
        return ['path' => null, 'error' => 'Cloudinary upload failed: ' . $error];
    }
    $status = curl_getinfo($ch, CURLINFO_HTTP_CODE);
    curl_close($ch);

    $data = json_decode($response, true);
    if ($status >= 300 || empty($data['secure_url'])) {
        $message = $data['error']['message'] ?? 'Cloudinary upload failed.';
        return ['path' => null, 'error' => $message];
    }

    return ['path' => $data['secure_url'], 'error' => null];
}

function cloudinarySignature($params, $apiSecret) {
    ksort($params);
    $pairs = [];
    foreach ($params as $key => $value) {
        $pairs[] = $key . '=' . $value;
    }
    return sha1(implode('&', $pairs) . $apiSecret);
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
 * Send HTML email (best-effort)
 */
function sendEmail($to, $subject, $html, $fromName = 'AngkorCam Tourism', $fromEmail = 'no-reply@angkorcam.com') {
    if (empty($to) || empty($subject) || empty($html)) {
        return false;
    }
    if (!function_exists('mail')) {
        logError("Mail function not available. Email to $to not sent.");
        return false;
    }

    $headers = [];
    $headers[] = 'MIME-Version: 1.0';
    $headers[] = 'Content-type: text/html; charset=UTF-8';
    $headers[] = 'From: ' . $fromName . ' <' . $fromEmail . '>';
    $headers[] = 'Reply-To: ' . $fromEmail;

    $result = @mail($to, $subject, $html, implode("\r\n", $headers));
    if (!$result) {
        logError("Failed to send email to $to with subject $subject.");
    }
    return $result;
}

/**
 * Booking receipt email
 */
function sendBookingReceipt($booking, $tour) {
    $customerName = e($booking['customer_name'] ?? '');
    $bookingCode = e($booking['booking_code'] ?? '');
    $bookingDate = e(formatDate($booking['booking_date'] ?? ''));
    $travelers = e($booking['travelers'] ?? '');
    $total = e(formatPrice($booking['total_price'] ?? 0));
    $payment = e(ucfirst($booking['payment_method'] ?? 'cash'));
    $tourTitle = e($tour['title'] ?? '');
    $tourLocation = e($tour['location'] ?? '');

    $html = '
        <div style="font-family: Arial, sans-serif; color: #1f241f; line-height: 1.6;">
            <h2 style="margin-bottom: 0;">AngkorCam Booking Confirmation</h2>
            <p style="margin-top: 4px;">Hi ' . $customerName . ',</p>
            <p>Thanks for booking with AngkorCam. Here are your details:</p>
            <table style="width: 100%; border-collapse: collapse; margin: 16px 0;">
                <tr><td style="padding: 8px 0;"><strong>Booking Code</strong></td><td style="padding: 8px 0;">' . $bookingCode . '</td></tr>
                <tr><td style="padding: 8px 0;"><strong>Tour</strong></td><td style="padding: 8px 0;">' . $tourTitle . '</td></tr>
                <tr><td style="padding: 8px 0;"><strong>Location</strong></td><td style="padding: 8px 0;">' . $tourLocation . '</td></tr>
                <tr><td style="padding: 8px 0;"><strong>Date</strong></td><td style="padding: 8px 0;">' . $bookingDate . '</td></tr>
                <tr><td style="padding: 8px 0;"><strong>Travelers</strong></td><td style="padding: 8px 0;">' . $travelers . '</td></tr>
                <tr><td style="padding: 8px 0;"><strong>Total</strong></td><td style="padding: 8px 0;">' . $total . '</td></tr>
                <tr><td style="padding: 8px 0;"><strong>Payment</strong></td><td style="padding: 8px 0;">' . $payment . '</td></tr>
            </table>
            <p>If you need to change or cancel your booking, visit your dashboard.</p>
            <p style="margin-top: 24px;">Safe travels,<br>AngkorCam Team</p>
        </div>
    ';

    $subject = 'Your AngkorCam booking ' . $bookingCode;
    return sendEmail($booking['customer_email'] ?? '', $subject, $html);
}

/**
 * Require login
 */
function requireLogin() {
    if (!isLoggedIn()) {
        setFlash('error', 'Please login to continue');
        redirect('?page=login');
    }
    $payload = getAuthPayload();
    $userId = (int)($payload['sub'] ?? 0);
    if ($userId <= 0) {
        clearAuthCookie();
        setFlash('error', 'Session expired. Please login again.');
        redirect('?page=login');
    }

    global $db;
    $userModel = new User($db);
    $user = $userModel->getById($userId);
    if (!$user || ($user['status'] ?? '') !== 'active') {
        clearAuthCookie();
        setFlash('error', 'Session expired. Please login again.');
        redirect('?page=login');
    }

    if (($payload['role'] ?? '') !== ($user['role'] ?? '')) {
        $token = issueAuthToken($user);
        setAuthCookie($token);
    }
}

/**
 * Require admin
 */
function requireAdmin() {
    requireLogin();
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
