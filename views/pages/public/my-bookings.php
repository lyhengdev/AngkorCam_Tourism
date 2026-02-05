<?php
requireLogin();
$currentUser = getUser();
$pageTitle = 'My Bookings';
$bookingModel = new Booking($db);
$userId = $currentUser['id'] ?? 0;
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['cancel_booking'])) {
    $bookingId = (int)($_POST['booking_id'] ?? 0);
    if ($bookingId && $bookingModel->cancelByUser($bookingId, $userId)) {
        setFlash('success', 'Booking cancelled');
        redirect('?page=my-bookings');
    } else {
        setFlash('error', 'Unable to cancel booking');
        redirect('?page=my-bookings');
    }
}
$bookings = $bookingModel->getByUser($userId);
ob_start();
?>
<section class="section">
    <div class="container">
        <div class="section-header">
            <p class="eyebrow dark">Your trips</p>
            <h1>My Bookings</h1>
            <p class="lead">Keep track of your upcoming experiences and past journeys.</p>
        </div>
        <?php if (empty($bookings)): ?>
            <div class="glass-card text-center py-5">
                <i class="bi bi-calendar-x" style="font-size: 4rem; opacity: 0.3;"></i>
                <h4 class="mt-3">No bookings yet</h4>
                <p class="text-muted">Start exploring our amazing tours!</p>
                <a href="?page=tours" class="btn btn-gradient mt-3">Browse Tours</a>
            </div>
        <?php else: ?>
            <?php foreach ($bookings as $booking): ?>
                <div class="booking-card mb-3">
                    <div class="row align-items-center">
                        <div class="col-md-8">
                            <h5><?= e($booking['tour_title']) ?></h5>
                            <p class="text-muted mb-2">
                                <i class="bi bi-geo-alt"></i> <?= e($booking['location']) ?> |
                                <i class="bi bi-calendar"></i> <?= formatDate($booking['booking_date']) ?>
                            </p>
                            <p class="mb-0">
                                <span class="badge-status badge-<?= $booking['status'] ?>"><?= ucfirst($booking['status']) ?></span>
                                <strong class="ms-3">Code:</strong> <?= e($booking['booking_code']) ?>
                                <strong class="ms-3">Payment:</strong> <?= ucfirst(e($booking['payment_method'] ?? 'cash')) ?>
                            </p>
                        </div>
                        <div class="col-md-4 text-md-end mt-3 mt-md-0">
                            <h4 class="tour-price mb-1"><?= formatPrice($booking['total_price']) ?></h4>
                            <small class="text-muted"><?= $booking['travelers'] ?> traveler(s)</small>
                            <?php if (in_array($booking['status'], ['pending', 'confirmed'], true)): ?>
                                <form method="POST" class="mt-3" onsubmit="return confirm('Cancel this booking?');">
                                    <input type="hidden" name="booking_id" value="<?= $booking['id'] ?>">
                                    <button type="submit" name="cancel_booking" value="1" class="btn btn-outline-danger btn-sm">
                                        Cancel Booking
                                    </button>
                                </form>
                            <?php endif; ?>
                        </div>
                    </div>
                </div>
            <?php endforeach; ?>
        <?php endif; ?>
    </div>
</section>
<?php $content = ob_get_clean(); require __DIR__ . '/../../layouts/main.php'; ?>
