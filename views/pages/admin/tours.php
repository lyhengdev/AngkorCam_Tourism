<?php
requireAdmin();
$pageTitle = 'Manage Tours';
$tourModel = new Tour($db);
$tours = $db->query("SELECT * FROM tours ORDER BY created_at DESC")->fetchAll();
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['delete_tour'])) {
    $tour_id = (int)$_POST['tour_id'];
    if ($tour_id && $tourModel->delete($tour_id)) {
        setFlash('success', 'Tour deleted');
        redirect('?page=admin-tours');
    }
}
ob_start();
?>
<section class="section">
    <div class="container">
        <div class="d-flex flex-wrap justify-content-between align-items-center mb-4">
            <div>
                <p class="eyebrow dark">Admin</p>
                <h1 class="mb-1">Manage Tours</h1>
            </div>
            <a href="?page=admin-add-tour" class="btn btn-gradient">
                <i class="bi bi-plus-circle me-2"></i>Add New Tour
            </a>
        </div>

        <div class="glass-card">
            <div class="table-responsive">
                <table class="table">
                    <thead>
                        <tr>
                            <th>Tour</th>
                            <th>Location</th>
                            <th>Price</th>
                            <th>Duration</th>
                            <th>Seats</th>
                            <th>Status</th>
                            <th>Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach ($tours as $tour): ?>
                            <tr>
                                <td>
                                    <strong><?= e($tour['title']) ?></strong><br>
                                    <small class="text-muted"><?= e($tour['category']) ?></small>
                                </td>
                                <td><?= e($tour['location']) ?></td>
                                <td><?= formatPrice($tour['price']) ?></td>
                                <td><?= $tour['duration'] ?> Day(s)</td>
                                <td><?= $tour['available_seats'] ?></td>
                                <td>
                                    <span class="badge-status <?= $tour['status'] === 'active' ? 'badge-confirmed' : 'badge-pending' ?>">
                                        <?= ucfirst($tour['status']) ?>
                                    </span>
                                </td>
                                <td>
                                    <a href="?page=admin-edit-tour&id=<?= $tour['id'] ?>" class="btn btn-sm btn-gradient">
                                        <i class="bi bi-pencil"></i> Edit
                                    </a>
                                    <form method="POST" class="d-inline" onsubmit="return confirm('Delete this tour? It will be hidden from customers.');">
                                        <input type="hidden" name="tour_id" value="<?= $tour['id'] ?>">
                                        <button type="submit" name="delete_tour" value="1" class="btn btn-sm btn-outline-danger">
                                            <i class="bi bi-trash"></i> Delete
                                        </button>
                                    </form>
                                </td>
                            </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</section>
<?php $content = ob_get_clean(); require __DIR__ . '/../../layouts/main.php'; ?>
