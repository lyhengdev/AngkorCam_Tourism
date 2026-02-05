<?php
$pageTitle = 'Browse Tours';
$tourModel = new Tour($db);

$filters = [
    'q' => trim($_GET['q'] ?? ''),
    'category' => $_GET['category'] ?? '',
    'location' => $_GET['location'] ?? '',
    'duration' => $_GET['duration'] ?? '',
    'min_price' => $_GET['min_price'] ?? '',
    'max_price' => $_GET['max_price'] ?? '',
    'sort' => $_GET['sort'] ?? 'newest'
];

$currentPage = max(1, (int)($_GET['p'] ?? 1));
$perPage = 12;

$tours = $tourModel->getFiltered($filters, $currentPage, $perPage);
$total = $tourModel->countFiltered($filters);
$locations = $tourModel->getLocations();
$categories = $tourModel->getCategories();
$totalPages = max(1, (int)ceil($total / $perPage));

$buildQuery = function ($params) {
    $filtered = array_filter($params, function ($value) {
        return $value !== '' && $value !== null;
    });
    return http_build_query($filtered);
};
ob_start();
?>
<section class="page-header">
    <div class="container">
        <p class="eyebrow dark">All Cambodia tours</p>
        <h1>Discover Cambodia, from Angkor to Koh Rong</h1>
        <p class="lead">Temple sunrises, floating villages, riverside capitals, and island escapes - all in one place.</p>
    </div>
</section>

<section class="section pt-0">
    <div class="container">
        <div class="filter-bar mb-4">
            <form class="row g-3 align-items-end" method="GET">
                <input type="hidden" name="page" value="tours">
                <div class="col-lg-4">
                    <label class="form-label">Search</label>
                    <input type="text" name="q" class="form-control" placeholder="Temple, island, waterfall..." value="<?= e($filters['q']) ?>">
                </div>
                <div class="col-sm-6 col-lg-2">
                    <label class="form-label">Category</label>
                    <select name="category" class="form-select">
                        <option value="">All</option>
                        <?php foreach ($categories as $row): ?>
                            <?php $cat = $row['category']; ?>
                            <option value="<?= e($cat) ?>" <?= $filters['category'] === $cat ? 'selected' : '' ?>><?= e($cat) ?></option>
                        <?php endforeach; ?>
                    </select>
                </div>
                <div class="col-sm-6 col-lg-2">
                    <label class="form-label">Location</label>
                    <select name="location" class="form-select">
                        <option value="">All</option>
                        <?php foreach ($locations as $row): ?>
                            <?php $loc = $row['location']; ?>
                            <option value="<?= e($loc) ?>" <?= $filters['location'] === $loc ? 'selected' : '' ?>><?= e($loc) ?></option>
                        <?php endforeach; ?>
                    </select>
                </div>
                <div class="col-sm-6 col-lg-2">
                    <label class="form-label">Duration</label>
                    <select name="duration" class="form-select">
                        <option value="">Any</option>
                        <?php foreach ([1, 2, 3] as $d): ?>
                            <option value="<?= $d ?>" <?= $filters['duration'] == $d ? 'selected' : '' ?>><?= $d ?> day<?= $d > 1 ? 's' : '' ?></option>
                        <?php endforeach; ?>
                    </select>
                </div>
                <div class="col-sm-6 col-lg-2">
                    <label class="form-label">Sort by</label>
                    <select name="sort" class="form-select">
                        <option value="newest" <?= $filters['sort'] === 'newest' ? 'selected' : '' ?>>Newest</option>
                        <option value="price_asc" <?= $filters['sort'] === 'price_asc' ? 'selected' : '' ?>>Price: Low to High</option>
                        <option value="price_desc" <?= $filters['sort'] === 'price_desc' ? 'selected' : '' ?>>Price: High to Low</option>
                        <option value="duration_asc" <?= $filters['sort'] === 'duration_asc' ? 'selected' : '' ?>>Duration: Shortest</option>
                        <option value="duration_desc" <?= $filters['sort'] === 'duration_desc' ? 'selected' : '' ?>>Duration: Longest</option>
                    </select>
                </div>
                <div class="col-sm-6 col-lg-2">
                    <label class="form-label">Price range</label>
                    <div class="d-flex gap-2">
                        <input type="number" name="min_price" class="form-control" placeholder="Min" min="0" value="<?= e($filters['min_price']) ?>">
                        <input type="number" name="max_price" class="form-control" placeholder="Max" min="0" value="<?= e($filters['max_price']) ?>">
                    </div>
                </div>
                <div class="col-12 d-flex gap-2">
                    <button class="btn btn-gradient" type="submit"><i class="bi bi-funnel"></i> Apply Filters</button>
                    <a class="btn btn-soft" href="?page=tours">Clear</a>
                </div>
            </form>
        </div>

        <div class="results-meta">
            <?php if ($total > 0): ?>
                <?php $start = (($currentPage - 1) * $perPage) + 1; ?>
                <?php $end = min($currentPage * $perPage, $total); ?>
                <span>Showing <?= $start ?>-<?= $end ?> of <?= $total ?> tours</span>
            <?php else: ?>
                <span>No tours found. Try different filters.</span>
            <?php endif; ?>
        </div>

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

        <?php if ($totalPages > 1): ?>
            <nav class="pagination-modern">
                <?php
                $baseParams = array_merge($filters, ['page' => 'tours']);
                $prevPage = max(1, $currentPage - 1);
                $nextPage = min($totalPages, $currentPage + 1);
                ?>
                <a class="page-link <?= $currentPage === 1 ? 'disabled' : '' ?>" href="?<?= $buildQuery(array_merge($baseParams, ['p' => $prevPage])) ?>">Prev</a>
                <span class="page-status">Page <?= $currentPage ?> of <?= $totalPages ?></span>
                <a class="page-link <?= $currentPage === $totalPages ? 'disabled' : '' ?>" href="?<?= $buildQuery(array_merge($baseParams, ['p' => $nextPage])) ?>">Next</a>
            </nav>
        <?php endif; ?>
    </div>
</section>
<?php $content = ob_get_clean(); require __DIR__ . '/../../layouts/main.php'; ?>
