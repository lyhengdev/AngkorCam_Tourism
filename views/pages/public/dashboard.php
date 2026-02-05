<?php
requireLogin();
$pageTitle = 'Dashboard';
$bookingModel = new Booking($db);
$myBookings = $bookingModel->getByUser($_SESSION['user_id']);
$totalBookings = count($myBookings);
ob_start();
?>
<section class="section">
    <div class="container">
        <div class="section-header">
            <p class="eyebrow dark">Dashboard</p>
            <h1>Welcome, <?= e($_SESSION['name']) ?>!</h1>
            <p class="lead">Track your bookings, manage upcoming trips, and discover new routes.</p>
        </div>
        <div class="row g-4">
            <div class="col-md-4">
                <div class="stat-card">
                    <i class="bi bi-calendar-check" style="font-size: 2.6rem; color: var(--clay);"></i>
                    <h3 class="mt-3"><?= $totalBookings ?></h3>
                    <p class="text-muted mb-0">Total Bookings</p>
                </div>
            </div>
            <div class="col-md-4">
                <a href="?page=tours" class="text-decoration-none">
                    <div class="stat-card">
                        <i class="bi bi-map" style="font-size: 2.6rem; color: var(--jade);"></i>
                        <h5 class="mt-3">Browse Tours</h5>
                        <p class="text-muted mb-0">Find new adventures</p>
                    </div>
                </a>
            </div>
            <div class="col-md-4">
                <a href="?page=my-bookings" class="text-decoration-none">
                    <div class="stat-card">
                        <i class="bi bi-list-check" style="font-size: 2.6rem; color: var(--river);"></i>
                        <h5 class="mt-3">My Bookings</h5>
                        <p class="text-muted mb-0">View your history</p>
                    </div>
                </a>
            </div>
        </div>
    </div>
</section>
<?php $content = ob_get_clean(); require __DIR__ . '/../../layouts/main.php'; ?>
