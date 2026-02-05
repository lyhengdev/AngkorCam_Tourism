<?php
$pageTitle = 'Register';
if (isLoggedIn()) redirect('?page=dashboard');
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $userModel = new User($db);
    $name = sanitize($_POST['name'] ?? '');
    $email = sanitize($_POST['email'] ?? '');
    $password = $_POST['password'] ?? '';

    if ($userModel->emailExists($email)) {
        setFlash('error', 'Email already registered');
    } elseif ($userModel->register($name, $email, $password)) {
        setFlash('success', 'Registration successful! Please login.');
        redirect('?page=login');
    } else {
        setFlash('error', 'Registration failed');
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
                                <p class="eyebrow">Join AngkorCam</p>
                                <h3>Create your travel profile</h3>
                                <p class="opacity-75">Save your favorite tours and manage bookings in one place.</p>
                            </div>
                            <div class="mt-4">
                                <small class="opacity-75">We respect your privacy. No spam, ever.</small>
                            </div>
                        </div>
                        <div class="col-lg-7 auth-card">
                            <h2 class="mb-4">Create account</h2>
                            <form method="POST">
                                <div class="mb-3">
                                    <label class="form-label">Full Name</label>
                                    <input type="text" name="name" class="form-control" required>
                                </div>
                                <div class="mb-3">
                                    <label class="form-label">Email Address</label>
                                    <input type="email" name="email" class="form-control" required>
                                </div>
                                <div class="mb-3">
                                    <label class="form-label">Password</label>
                                    <input type="password" name="password" class="form-control" required minlength="6">
                                    <small class="text-muted">Minimum 6 characters</small>
                                </div>
                                <button type="submit" class="btn btn-gradient w-100 mb-3">
                                    <i class="bi bi-person-plus"></i> Register
                                </button>
                            </form>
                            <p class="text-muted mb-0">
                                Have an account? <a href="?page=login" class="fw-bold">Login</a>
                            </p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>
<?php $content = ob_get_clean(); require __DIR__ . '/../../layouts/main.php'; ?>
