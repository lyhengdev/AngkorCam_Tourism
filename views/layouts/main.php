<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="description" content="Discover Cambodia with AngkorCam Tourism - handcrafted tours and cultural experiences">
    <title><?= $pageTitle ?? 'AngkorCam Tourism - Discover Cambodia' ?></title>

    <!-- Bootstrap 5.3 -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">

    <!-- Bootstrap Icons -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.0/font/bootstrap-icons.css">

    <!-- Custom Styles -->
    <link href="<?= asset('css/style.css') ?>" rel="stylesheet">
</head>
<body>

    <!-- Navigation -->
    <nav class="navbar navbar-expand-lg">
        <div class="container">
            <a class="navbar-brand" href="?page=home">
                <span class="brand-mark">
                    <i class="bi bi-compass"></i>
                    AngkorCam
                </span>
                <span class="brand-tagline">Journeys in Cambodia</span>
            </a>

            <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav">
                <span class="navbar-toggler-icon"></span>
            </button>

            <div class="collapse navbar-collapse" id="navbarNav">
                <ul class="navbar-nav ms-auto align-items-lg-center">
                    <li class="nav-item">
                        <a class="nav-link" href="?page=home">Home</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="?page=tours">Tours</a>
                    </li>

                    <?php if (isLoggedIn()): ?>
                        <li class="nav-item">
                            <a class="nav-link" href="?page=my-bookings">
                                <i class="bi bi-calendar-check"></i> My Bookings
                            </a>
                        </li>

                        <?php if (isAdmin()): ?>
                            <li class="nav-item">
                                <a class="nav-link" href="?page=admin">
                                    <i class="bi bi-speedometer2"></i> Admin
                                </a>
                            </li>
                        <?php endif; ?>

                        <li class="nav-item dropdown">
                            <a class="nav-link dropdown-toggle" href="#" role="button" data-bs-toggle="dropdown">
                                <i class="bi bi-person-circle"></i> <?= e($_SESSION['name']) ?>
                            </a>
                            <ul class="dropdown-menu dropdown-menu-end">
                                <li><a class="dropdown-item" href="?page=dashboard">
                                    <i class="bi bi-grid"></i> Dashboard
                                </a></li>
                                <li><hr class="dropdown-divider"></li>
                                <li><a class="dropdown-item" href="?page=logout">
                                    <i class="bi bi-box-arrow-right"></i> Logout
                                </a></li>
                            </ul>
                        </li>
                        <li class="nav-item ms-lg-2">
                            <a class="btn btn-gradient btn-sm" href="?page=tours">Book a Tour</a>
                        </li>
                    <?php else: ?>
                        <li class="nav-item">
                            <a class="btn btn-soft" href="?page=login">Login</a>
                        </li>
                        <li class="nav-item ms-lg-2">
                            <a class="btn btn-gradient btn-sm" href="?page=register">Create Account</a>
                        </li>
                    <?php endif; ?>
                </ul>
            </div>
        </div>
    </nav>

    <!-- Flash Messages -->
    <?php if ($success = getFlash('success')): ?>
        <div class="container mt-3">
            <div class="alert alert-success alert-dismissible fade show" role="alert">
                <i class="bi bi-check-circle me-2"></i><?= e($success) ?>
                <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
            </div>
        </div>
    <?php endif; ?>

    <?php if ($error = getFlash('error')): ?>
        <div class="container mt-3">
            <div class="alert alert-danger alert-dismissible fade show" role="alert">
                <i class="bi bi-exclamation-triangle me-2"></i><?= e($error) ?>
                <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
            </div>
        </div>
    <?php endif; ?>

    <!-- Main Content -->
    <main>
        <?= $content ?>
    </main>

    <!-- Footer -->
    <footer class="footer">
        <div class="container">
            <div class="row">
                <div class="col-lg-4 mb-4 mb-lg-0">
                    <h5 class="mb-3">
                        <i class="bi bi-compass"></i> AngkorCam Tourism
                    </h5>
                    <p class="opacity-75">
                        Handcrafted journeys across Cambodia, led by local experts. Explore temples,
                        villages, and hidden landscapes with care and comfort.
                    </p>
                    <div class="mt-3">
                        <a href="#" class="me-3 fs-5"><i class="bi bi-facebook"></i></a>
                        <a href="#" class="me-3 fs-5"><i class="bi bi-instagram"></i></a>
                        <a href="#" class="me-3 fs-5"><i class="bi bi-twitter-x"></i></a>
                        <a href="#" class="fs-5"><i class="bi bi-youtube"></i></a>
                    </div>
                </div>

                <div class="col-lg-2 col-md-4 mb-4 mb-lg-0">
                    <h6 class="mb-3">Explore</h6>
                    <ul class="list-unstyled">
                        <li class="mb-2"><a href="?page=tours">Browse Tours</a></li>
                        <li class="mb-2"><a href="?page=home">Stories</a></li>
                        <li class="mb-2"><a href="?page=home">Travel Guide</a></li>
                        <li class="mb-2"><a href="?page=home">FAQ</a></li>
                    </ul>
                </div>

                <div class="col-lg-3 col-md-4 mb-4 mb-lg-0">
                    <h6 class="mb-3">Signature Routes</h6>
                    <ul class="list-unstyled">
                        <li class="mb-2"><a href="?page=tours">Angkor Sunrise Trail</a></li>
                        <li class="mb-2"><a href="?page=tours">Mekong River Life</a></li>
                        <li class="mb-2"><a href="?page=tours">Phnom Penh Heritage</a></li>
                        <li class="mb-2"><a href="?page=tours">Coastal Retreats</a></li>
                    </ul>
                </div>

                <div class="col-lg-3 col-md-4">
                    <h6 class="mb-3">Contact</h6>
                    <ul class="list-unstyled">
                        <li class="mb-2">
                            <i class="bi bi-envelope me-2"></i>
                            hello@angkorcam.com
                        </li>
                        <li class="mb-2">
                            <i class="bi bi-telephone me-2"></i>
                            +855 12 345 678
                        </li>
                        <li class="mb-2">
                            <i class="bi bi-geo-alt me-2"></i>
                            Siem Reap, Cambodia
                        </li>
                    </ul>
                </div>
            </div>

            <hr class="my-4 opacity-25">

            <div class="row align-items-center">
                <div class="col-md-6 text-center text-md-start mb-3 mb-md-0">
                    <p class="mb-0 opacity-75">&copy; <?= date('Y') ?> AngkorCam Tourism. All rights reserved.</p>
                </div>
                <div class="col-md-6 text-center text-md-end">
                    <a href="#" class="me-3 opacity-75">Privacy Policy</a>
                    <a href="#" class="opacity-75">Terms of Service</a>
                </div>
            </div>
        </div>
    </footer>

    <!-- Bootstrap JS Bundle -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>

    <!-- Auto-hide alerts -->
    <script>
        setTimeout(() => {
            const alerts = document.querySelectorAll('.alert');
            alerts.forEach(alert => {
                const bsAlert = new bootstrap.Alert(alert);
                bsAlert.close();
            });
        }, 5000);

        document.querySelectorAll('a[href^="#"]').forEach(anchor => {
            anchor.addEventListener('click', function (e) {
                e.preventDefault();
                const target = document.querySelector(this.getAttribute('href'));
                if (target) {
                    target.scrollIntoView({ behavior: 'smooth' });
                }
            });
        });
    </script>
</body>
</html>
