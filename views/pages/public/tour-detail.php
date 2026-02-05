<?php
$tour_id = $_GET['id'] ?? 0;
$tourModel = new Tour($db);
$tour = $tourModel->getById($tour_id);
if (!$tour) redirect('?page=tours');
$pageTitle = e($tour['title']);
ob_start();
?>
<section class="section">
    <div class="container">
        <div class="row g-5">
            <div class="col-lg-8">
                <div class="glass-card p-0 overflow-hidden mb-4">
                    <img src="<?= e(tourImage($tour)) ?>" class="img-fluid" alt="<?= e($tour['title']) ?>" decoding="async">
                    <div class="p-4">
                        <span class="badge-gradient"><i class="bi bi-geo-alt"></i> <?= e($tour['location']) ?></span>
                        <h1 class="mt-3"><?= e($tour['title']) ?></h1>
                        <p class="lead"><?= e($tour['description']) ?></p>
                        <div class="tour-facts">
                            <span><i class="bi bi-calendar3"></i> <?= $tour['duration'] ?> Day(s)</span>
                            <span><i class="bi bi-people"></i> <?= $tour['available_seats'] ?> Seats</span>
                            <span><i class="bi bi-tags"></i> <?= e($tour['category']) ?></span>
                        </div>
                    </div>
                </div>

                <?php if ($tour['highlights']): ?>
                    <div class="glass-card mb-4">
                        <h4 class="mb-3">Tour Highlights</h4>
                        <ul class="list-check">
                            <?php foreach (explode('|', $tour['highlights']) as $highlight): ?>
                                <li><?= e($highlight) ?></li>
                            <?php endforeach; ?>
                        </ul>
                    </div>
                <?php endif; ?>

                <div class="row g-4">
                    <div class="col-md-6">
                        <div class="booking-card h-100">
                            <h5 class="mb-3"><i class="bi bi-check-circle"></i> Included</h5>
                            <ul class="list-check">
                                <?php foreach (explode('|', $tour['included']) as $item): ?>
                                    <li><?= e($item) ?></li>
                                <?php endforeach; ?>
                            </ul>
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="booking-card h-100">
                            <h5 class="mb-3"><i class="bi bi-x-circle"></i> Not Included</h5>
                            <ul class="list-cross">
                                <?php foreach (explode('|', $tour['excluded']) as $item): ?>
                                    <li><?= e($item) ?></li>
                                <?php endforeach; ?>
                            </ul>
                        </div>
                    </div>
                </div>
            </div>

            <div class="col-lg-4">
                <div class="booking-card sticky-top" style="top: 110px;">
                    <h3 class="tour-price mb-2"><?= formatPrice($tour['price']) ?></h3>
                    <p class="text-muted mb-4">per person</p>
                    <ul class="list-unstyled mb-4">
                        <li class="mb-2"><i class="bi bi-calendar3 text-muted"></i> <?= $tour['duration'] ?> Day(s)</li>
                        <li class="mb-2"><i class="bi bi-people text-muted"></i> <?= $tour['available_seats'] ?> Seats</li>
                        <li class="mb-2"><i class="bi bi-shield-check text-muted"></i> Free cancellation</li>
                    </ul>
                    <a href="?page=booking&tour_id=<?= $tour['id'] ?>" class="btn btn-gradient w-100 btn-lg mb-3">
                        <i class="bi bi-calendar-check"></i> Book Now
                    </a>
                    <div class="small text-muted">
                        <i class="bi bi-credit-card"></i> Secure payment<br>
                        <i class="bi bi-headset"></i> 24/7 support
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>
<?php $content = ob_get_clean(); require __DIR__ . '/../../layouts/main.php'; ?>
