<?php
requireAdmin();
$pageTitle = 'Admin Dashboard';

// Get statistics
$tourModel = new Tour($db);
$bookingModel = new Booking($db);
$stats = $bookingModel->getStats();

ob_start();
?>
<section class="section">
    <div class="container">
        <div class="section-header">
            <p class="eyebrow dark">Admin</p>
            <h1>Operations Dashboard</h1>
            <p class="lead">Monitor tours, bookings, and revenue at a glance.</p>
        </div>

        <div class="row g-4 mb-5">
            <div class="col-md-3">
                <div class="stat-card">
                    <i class="bi bi-map" style="font-size: 2.4rem; color: var(--forest);"></i>
                    <h3 class="mt-3"><?= $db->query("SELECT COUNT(*) FROM tours")->fetchColumn() ?></h3>
                    <p class="text-muted mb-0">Total Tours</p>
                </div>
            </div>
            <div class="col-md-3">
                <div class="stat-card">
                    <i class="bi bi-calendar-check" style="font-size: 2.4rem; color: var(--jade);"></i>
                    <h3 class="mt-3"><?= $stats['total_bookings'] ?></h3>
                    <p class="text-muted mb-0">Total Bookings</p>
                </div>
            </div>
            <div class="col-md-3">
                <div class="stat-card">
                    <i class="bi bi-people" style="font-size: 2.4rem; color: var(--river);"></i>
                    <h3 class="mt-3"><?= $db->query("SELECT COUNT(*) FROM users WHERE role='customer'")->fetchColumn() ?></h3>
                    <p class="text-muted mb-0">Customers</p>
                </div>
            </div>
            <div class="col-md-3">
                <div class="stat-card">
                    <i class="bi bi-currency-dollar" style="font-size: 2.4rem; color: var(--clay);"></i>
                    <h3 class="mt-3"><?= formatPrice($stats['total_revenue'] ?? 0) ?></h3>
                    <p class="text-muted mb-0">Revenue</p>
                </div>
            </div>
        </div>

        <div class="row g-4">
            <div class="col-md-6">
                <a href="?page=admin-tours" class="text-decoration-none">
                    <div class="feature-card text-center py-5">
                        <i class="bi bi-map" style="font-size: 3rem; color: var(--forest);"></i>
                        <h4 class="mt-3 mb-2">Manage Tours</h4>
                        <p class="text-muted mb-0">Add, edit, or retire listings</p>
                    </div>
                </a>
            </div>
            <div class="col-md-6">
                <a href="?page=admin-bookings" class="text-decoration-none">
                    <div class="feature-card text-center py-5">
                        <i class="bi bi-calendar-check" style="font-size: 3rem; color: var(--jade);"></i>
                        <h4 class="mt-3 mb-2">Manage Bookings</h4>
                        <p class="text-muted mb-0">Review and update statuses</p>
                    </div>
                </a>
            </div>
        </div>

        <div class="glass-card mt-5">
            <h4 class="mb-4">Recent Bookings</h4>
            <div class="table-responsive">
                <table class="table">
                    <thead>
                        <tr>
                            <th>Code</th>
                            <th>Customer</th>
                            <th>Tour</th>
                            <th>Date</th>
                            <th>Amount</th>
                            <th>Status</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach ($bookingModel->getRecent(5) as $booking): ?>
                            <tr>
                                <td><strong><?= e($booking['booking_code']) ?></strong></td>
                                <td><?= e($booking['customer_name']) ?></td>
                                <td><?= e($booking['tour_title']) ?></td>
                                <td><?= formatDate($booking['booking_date']) ?></td>
                                <td><?= formatPrice($booking['total_price']) ?></td>
                                <td><span class="badge-status badge-<?= $booking['status'] ?>"><?= ucfirst($booking['status']) ?></span></td>
                            </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</section>
<?php $content = ob_get_clean(); require __DIR__ . '/../../layouts/main.php'; ?>
