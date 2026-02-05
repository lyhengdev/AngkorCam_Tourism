<?php
requireAdmin();
$pageTitle = 'Add New Tour';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $tourModel = new Tour($db);
    $upload = saveUploadedImage('image_file');
    if ($upload['error']) {
        setFlash('error', $upload['error']);
    } else {
        $imageUrl = sanitize($_POST['image'] ?? '');
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
            'image' => $upload['path'] ?: $imageUrl,
            'available_seats' => (int)($_POST['available_seats'] ?? 15)
        ];

        if ($tourModel->create($data)) {
            setFlash('success', 'Tour added successfully');
            redirect('?page=admin-tours');
        } else {
            setFlash('error', 'Failed to add tour');
        }
    }
}

ob_start();
?>
<section class="section">
    <div class="container">
        <div class="d-flex flex-wrap justify-content-between align-items-center mb-4">
            <div>
                <p class="eyebrow dark">Admin</p>
                <h1 class="mb-1">Add New Tour</h1>
            </div>
            <a href="?page=admin-tours" class="btn btn-outline-dark">
                <i class="bi bi-arrow-left me-2"></i>Back to Tours
            </a>
        </div>

        <div class="row g-4">
            <div class="col-lg-8">
                <div class="glass-card">
                    <form method="POST" enctype="multipart/form-data">
                        <div class="mb-3">
                            <label class="form-label">Tour Title</label>
                            <input type="text" name="title" class="form-control" required>
                        </div>

                        <div class="row">
                            <div class="col-md-6 mb-3">
                                <label class="form-label">Location</label>
                                <input type="text" name="location" class="form-control" required>
                            </div>
                            <div class="col-md-6 mb-3">
                                <label class="form-label">Category</label>
                                <select name="category" class="form-select">
                                    <option value="Cultural">Cultural</option>
                                    <option value="Adventure">Adventure</option>
                                    <option value="Nature">Nature</option>
                                    <option value="Beach">Beach</option>
                                </select>
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-md-4 mb-3">
                                <label class="form-label">Price ($)</label>
                                <input type="number" name="price" class="form-control" step="0.01" required>
                            </div>
                            <div class="col-md-4 mb-3">
                                <label class="form-label">Duration (Days)</label>
                                <input type="number" name="duration" class="form-control" value="1" min="1" required>
                            </div>
                            <div class="col-md-4 mb-3">
                                <label class="form-label">Available Seats</label>
                                <input type="number" name="available_seats" class="form-control" value="15" min="1" required>
                            </div>
                        </div>

                    <div class="mb-3">
                        <label class="form-label">Description</label>
                        <textarea name="description" class="form-control" rows="4" required></textarea>
                    </div>

                    <div class="mb-3">
                        <label class="form-label">Image URL</label>
                        <input type="url" name="image" class="form-control" placeholder="https://...">
                        <small class="text-muted">Paste a full image URL (Wikimedia or your own hosted image).</small>
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Or Upload Image</label>
                        <input type="file" name="image_file" class="form-control" accept="image/*">
                        <small class="text-muted">JPG, PNG, WEBP, or GIF. Max 5MB.</small>
                    </div>

                        <div class="mb-3">
                            <label class="form-label">Highlights (separate with |)</label>
                            <textarea name="highlights" class="form-control" rows="3" placeholder="Example: Temple visit|Expert guide|Sunset viewing"></textarea>
                            <small class="text-muted">Separate each highlight with | symbol</small>
                        </div>

                        <div class="mb-3">
                            <label class="form-label">What's Included (separate with |)</label>
                            <textarea name="included" class="form-control" rows="2" placeholder="Transport|Guide|Entrance fees"></textarea>
                        </div>

                        <div class="mb-3">
                            <label class="form-label">What's Not Included (separate with |)</label>
                            <textarea name="excluded" class="form-control" rows="2" placeholder="Meals|Tips|Personal expenses"></textarea>
                        </div>

                        <div class="d-flex gap-2">
                            <button type="submit" class="btn btn-gradient px-4">
                                <i class="bi bi-save me-2"></i>Add Tour
                            </button>
                            <a href="?page=admin-tours" class="btn btn-outline-dark">Cancel</a>
                        </div>
                    </form>
                </div>
            </div>

            <div class="col-lg-4">
                <div class="booking-card">
                    <h5 class="mb-3">Tips</h5>
                    <ul class="list-check">
                        <li>Write clear, engaging descriptions</li>
                        <li>Use | to separate multiple items</li>
                        <li>Be specific about what's included</li>
                        <li>Set realistic available seats</li>
                    </ul>
                </div>
            </div>
        </div>
    </div>
</section>
<?php $content = ob_get_clean(); require __DIR__ . '/../../layouts/main.php'; ?>
