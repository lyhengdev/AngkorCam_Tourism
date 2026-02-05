<?php
requireAdmin();
$tour_id = $_GET['id'] ?? 0;
$tourModel = new Tour($db);
$tour = $tourModel->getById($tour_id);

if (!$tour) {
    setFlash('error', 'Tour not found');
    redirect('?page=admin-tours');
}

$pageTitle = 'Edit Tour - ' . e($tour['title']);

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $data = [
        'title' => sanitize($_POST['title'] ?? ''),
        'location' => sanitize($_POST['location'] ?? ''),
        'category' => sanitize($_POST['category'] ?? 'Cultural'),
        'price' => (float)($_POST['price'] ?? 0),
        'duration' => (int)($_POST['duration'] ?? 1),
        'description' => sanitize($_POST['description'] ?? ''),
        'highlights' => sanitize($_POST['highlights'] ?? ''),
        'included' => sanitize($_POST['included'] ?? ''),
        'excluded' => sanitize($_POST['excluded'] ?? ''),
        'image' => sanitize($_POST['image'] ?? ''),
        'available_seats' => (int)($_POST['available_seats'] ?? 15)
    ];

    if ($tourModel->update($tour_id, $data)) {
        setFlash('success', 'Tour updated successfully');
        redirect('?page=admin-tours');
    } else {
        setFlash('error', 'Failed to update tour');
    }
}

ob_start();
?>
<section class="section">
    <div class="container">
        <div class="d-flex flex-wrap justify-content-between align-items-center mb-4">
            <div>
                <p class="eyebrow dark">Admin</p>
                <h1 class="mb-1">Edit Tour</h1>
            </div>
            <a href="?page=admin-tours" class="btn btn-outline-dark">
                <i class="bi bi-arrow-left me-2"></i>Back to Tours
            </a>
        </div>

        <div class="row g-4">
            <div class="col-lg-8">
                <div class="glass-card">
                    <form method="POST">
                        <div class="mb-3">
                            <label class="form-label">Tour Title</label>
                            <input type="text" name="title" class="form-control" value="<?= e($tour['title']) ?>" required>
                        </div>

                        <div class="row">
                            <div class="col-md-6 mb-3">
                                <label class="form-label">Location</label>
                                <input type="text" name="location" class="form-control" value="<?= e($tour['location']) ?>" required>
                            </div>
                            <div class="col-md-6 mb-3">
                                <label class="form-label">Category</label>
                                <select name="category" class="form-select">
                                    <option value="Cultural" <?= $tour['category'] === 'Cultural' ? 'selected' : '' ?>>Cultural</option>
                                    <option value="Adventure" <?= $tour['category'] === 'Adventure' ? 'selected' : '' ?>>Adventure</option>
                                    <option value="Nature" <?= $tour['category'] === 'Nature' ? 'selected' : '' ?>>Nature</option>
                                    <option value="Beach" <?= $tour['category'] === 'Beach' ? 'selected' : '' ?>>Beach</option>
                                </select>
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-md-4 mb-3">
                                <label class="form-label">Price ($)</label>
                                <input type="number" name="price" class="form-control" value="<?= $tour['price'] ?>" step="0.01" required>
                            </div>
                            <div class="col-md-4 mb-3">
                                <label class="form-label">Duration (Days)</label>
                                <input type="number" name="duration" class="form-control" value="<?= $tour['duration'] ?>" min="1" required>
                            </div>
                            <div class="col-md-4 mb-3">
                                <label class="form-label">Available Seats</label>
                                <input type="number" name="available_seats" class="form-control" value="<?= $tour['available_seats'] ?>" min="1" required>
                            </div>
                        </div>

                    <div class="mb-3">
                        <label class="form-label">Description</label>
                        <textarea name="description" class="form-control" rows="4" required><?= e($tour['description']) ?></textarea>
                    </div>

                    <div class="mb-3">
                        <label class="form-label">Image URL</label>
                        <input type="url" name="image" class="form-control" value="<?= e($tour['image'] ?? '') ?>" placeholder="https://...">
                        <small class="text-muted">Paste a full image URL (Wikimedia or your own hosted image).</small>
                    </div>

                        <div class="mb-3">
                            <label class="form-label">Highlights (separate with |)</label>
                            <textarea name="highlights" class="form-control" rows="3"><?= e($tour['highlights']) ?></textarea>
                        </div>

                        <div class="mb-3">
                            <label class="form-label">What's Included (separate with |)</label>
                            <textarea name="included" class="form-control" rows="2"><?= e($tour['included']) ?></textarea>
                        </div>

                        <div class="mb-3">
                            <label class="form-label">What's Not Included (separate with |)</label>
                            <textarea name="excluded" class="form-control" rows="2"><?= e($tour['excluded']) ?></textarea>
                        </div>

                        <div class="d-flex gap-2">
                            <button type="submit" class="btn btn-gradient px-4">
                                <i class="bi bi-save me-2"></i>Update Tour
                            </button>
                            <a href="?page=admin-tours" class="btn btn-outline-dark">Cancel</a>
                        </div>
                    </form>
                </div>
            </div>
            <div class="col-lg-4">
                <div class="booking-card">
                    <h5 class="mb-3">Checklist</h5>
                    <ul class="list-check">
                        <li>Confirm pricing and availability</li>
                        <li>Update highlights for new stops</li>
                        <li>Review included/excluded details</li>
                    </ul>
                </div>
            </div>
        </div>
    </div>
</section>
<?php $content = ob_get_clean(); require __DIR__ . '/../../layouts/main.php'; ?>
