<?php
$pageTitle = 'Browse Tours';
$tourModel = new Tour($db);
$tours = $tourModel->getAll();
ob_start();
?>
<section class="page-header">
    <div class="container">
        <p class="eyebrow dark">All tours</p>
        <h1>Find your next story</h1>
        <p class="lead">From iconic temples to coastal retreats, every route is crafted for comfort and discovery.</p>
    </div>
</section>

<section class="section pt-0">
    <div class="container">
        <div class="row g-4">
            <?php foreach ($tours as $tour): ?>
                <div class="col-md-6 col-lg-4">
                    <div class="tour-card">
                        <div style="overflow: hidden;">
                            <img src="<?= e(tourImage($tour)) ?>" class="tour-card-img" alt="<?= e($tour['title']) ?>" loading="lazy">
                        </div>
                        <div class="tour-card-body">
                            <span class="badge-gradient mb-3"><i class="bi bi-geo-alt"></i> <?= e($tour['location']) ?></span>
                            <h4><?= e($tour['title']) ?></h4>
                            <p class="text-muted mb-4"><?= truncate($tour['description'], 120) ?></p>
                            <div class="d-flex justify-content-between align-items-center">
                                <div>
                                    <div class="tour-price"><?= formatPrice($tour['price']) ?></div>
                                    <small class="text-muted"><i class="bi bi-calendar3"></i> <?= $tour['duration'] ?> Day(s)</small>
                                </div>
                                <a href="?page=tour&id=<?= $tour['id'] ?>" class="btn btn-gradient">Book Now</a>
                            </div>
                        </div>
                    </div>
                </div>
            <?php endforeach; ?>
        </div>
    </div>
</section>
<?php $content = ob_get_clean(); require __DIR__ . '/../../layouts/main.php'; ?>
