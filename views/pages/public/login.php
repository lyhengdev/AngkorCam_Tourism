<?php
$pageTitle = 'Login';
if (isLoggedIn()) redirect('?page=dashboard');
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $userModel = new User($db);
    if ($userModel->login($_POST['email'] ?? '', $_POST['password'] ?? '')) {
        setFlash('success', 'Welcome back!');
        redirect('?page=dashboard');
    } else {
        setFlash('error', 'Invalid email or password');
    }
}
ob_start();
?>
<section class="section">
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-lg-8">
                <div class="auth-shell">
                    <div class="row g-0">
                        <div class="col-lg-5 auth-aside d-flex flex-column justify-content-between">
                            <div>
                                <p class="eyebrow">Welcome back</p>
                                <h3>Continue your Cambodia story</h3>
                                <p class="opacity-75">Access your bookings, saved tours, and travel notes.</p>
                            </div>
                            <div class="mt-4">
                                <small class="opacity-75">Need help? Contact our 24/7 support team.</small>
                            </div>
                        </div>
                        <div class="col-lg-7 auth-card">
                            <h2 class="mb-4">Sign in</h2>
                            <form method="POST">
                                <div class="mb-3">
                                    <label class="form-label">Email Address</label>
                                    <input type="email" name="email" class="form-control" required>
                                </div>
                                <div class="mb-3">
                                    <label class="form-label">Password</label>
                                    <input type="password" name="password" class="form-control" required>
                                </div>
                                <button type="submit" class="btn btn-gradient w-100 mb-3">
                                    <i class="bi bi-box-arrow-in-right"></i> Login
                                </button>
                            </form>
                            <p class="text-muted mb-0">
                                Don't have an account? <a href="?page=register" class="fw-bold">Create one</a>
                            </p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>
<?php $content = ob_get_clean(); require __DIR__ . '/../../layouts/main.php'; ?>
