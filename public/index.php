<?php
/**
 * =====================================================
 * AngkorCam Tourism - Front Controller
 * Clean & Simple Routing
 * =====================================================
 */

// Load configuration
require_once __DIR__ . '/../config/config.php';

// Get requested page
$page = $_GET['page'] ?? 'home';

// Define routes - Clean and organized
$routes = [
    // Public Pages
    'home' => 'public/home',
    'tours' => 'public/tours',
    'tour' => 'public/tour-detail',
    'booking' => 'public/booking',
    'process-booking' => 'public/process-booking',
    'about' => 'public/about',
    'faq' => 'public/faq',
    'travel-guide' => 'public/travel-guide',
    'terms' => 'public/terms',
    'privacy' => 'public/privacy',
    
    // Auth Pages
    'login' => 'public/login',
    'register' => 'public/register',
    'logout' => 'public/logout',
    
    // User Pages
    'dashboard' => 'public/dashboard',
    'profile' => 'public/profile',
    'my-bookings' => 'public/my-bookings',
    
    // Admin Pages
    'admin' => 'admin/dashboard',
    'admin-tours' => 'admin/tours',
    'admin-add-tour' => 'admin/add-tour',
    'admin-edit-tour' => 'admin/edit-tour',
    'admin-bookings' => 'admin/bookings',
];

// Handle logout
if ($page === 'logout') {
    clearAuthCookie();
    setFlash('success', 'Logged out successfully');
    redirect('?page=home');
}

// Check if route exists
if (!isset($routes[$page])) {
    $page = 'home';
}

// Get view file path
$viewFile = $routes[$page];
$viewPath = BASE_PATH . '/views/pages/' . $viewFile . '.php';

// Load the view
if (file_exists($viewPath)) {
    require_once $viewPath;
} else {
    die("Page not found: $viewFile");
}
