<?php
requireLogin();
$tour_id = $_GET['tour_id'] ?? 0;
$tourModel = new Tour($db);
$tour = $tourModel->getById($tour_id);
if (!$tour) redirect('?page=tours');
$pageTitle = 'Book ' . e($tour['title']);
ob_start();
?>
<section class="section">
    <div class="container">
        <div class="row g-5">
            <div class="col-lg-8">
                <div class="stepper">
                    <span class="step">1. Choose tour</span>
                    <span class="step active">2. Traveler details</span>
                    <span class="step">3. Confirmation</span>
                </div>
                <div class="glass-card">
                    <h2 class="mb-4">Complete Your Booking</h2>
                    <form method="POST" action="?page=process-booking">
                        <input type="hidden" name="tour_id" value="<?= $tour['id'] ?>">
                        <div class="row">
                            <div class="col-md-6 mb-3">
                                <label class="form-label">Full Name</label>
                                <input type="text" name="customer_name" class="form-control" value="<?= e($_SESSION['name']) ?>" required>
                            </div>
                            <div class="col-md-6 mb-3">
                                <label class="form-label">Email</label>
                                <input type="email" name="customer_email" class="form-control" value="<?= e($_SESSION['email']) ?>" required>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-md-6 mb-3">
                                <label class="form-label">Phone Number</label>
                                <input type="tel" name="customer_phone" class="form-control" required>
                            </div>
                            <div class="col-md-6 mb-3">
                                <label class="form-label">Booking Date</label>
                                <input type="date" name="booking_date" class="form-control" min="<?= date('Y-m-d') ?>" required>
                            </div>
                        </div>
                        <div class="mb-3">
                            <label class="form-label">Number of Travelers</label>
                            <input type="number" name="travelers" class="form-control" value="1" min="1" max="<?= $tour['available_seats'] ?>" required>
                        </div>
                        <div class="mb-4">
                            <label class="form-label">Special Requests (Optional)</label>
                            <textarea name="notes" class="form-control" rows="3"></textarea>
                        </div>
                        <button type="submit" class="btn btn-gradient btn-lg w-100">
                            <i class="bi bi-check-circle"></i> Confirm Booking
                        </button>
                    </form>
                </div>
            </div>
            <div class="col-lg-4">
                <div class="booking-card sticky-top" style="top: 110px;">
                    <h4>Booking Summary</h4>
                    <hr>
                    <h5><?= e($tour['title']) ?></h5>
                    <p class="text-muted"><i class="bi bi-geo-alt"></i> <?= e($tour['location']) ?></p>
                    <div class="mt-4">
                        <div class="d-flex justify-content-between mb-2">
                            <span>Price:</span>
                            <strong><?= formatPrice($tour['price']) ?></strong>
                        </div>
                        <div class="d-flex justify-content-between mb-2">
                            <span>Duration:</span>
                            <strong><?= $tour['duration'] ?> Day(s)</strong>
                        </div>
                        <div class="d-flex justify-content-between">
                            <span>Seats:</span>
                            <strong><?= $tour['available_seats'] ?></strong>
                        </div>
                    </div>
                    <div class="mt-4 small text-muted">
                        <i class="bi bi-shield-check"></i> Free cancellation within 24 hours
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>
<?php $content = ob_get_clean(); require __DIR__ . '/../../layouts/main.php'; ?>
