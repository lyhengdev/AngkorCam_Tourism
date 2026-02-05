<?php
requireAdmin();
$pageTitle = 'Manage Bookings';
$bookingModel = new Booking($db);
$bookings = $bookingModel->getAll();

// Handle status update
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if (isset($_POST['delete_booking'])) {
        $booking_id = (int)$_POST['booking_id'];
        if ($booking_id && $bookingModel->delete($booking_id)) {
            setFlash('success', 'Booking deleted');
            redirect('?page=admin-bookings');
        }
    } elseif (isset($_POST['update_status'])) {
        $booking_id = (int)$_POST['booking_id'];
        $status = $_POST['status'] ?? '';
        if ($status && $bookingModel->updateStatus($booking_id, $status)) {
            setFlash('success', 'Booking status updated');
            redirect('?page=admin-bookings');
        }
    }
}

ob_start();
?>
<section class="section">
    <div class="container">
        <div class="section-header">
            <p class="eyebrow dark">Admin</p>
            <h1>Manage Bookings</h1>
            <p class="lead">Review bookings and update statuses in real time.</p>
        </div>

        <div class="glass-card">
            <div class="table-responsive">
                <table class="table">
                    <thead>
                        <tr>
                            <th>Booking Code</th>
                            <th>Customer</th>
                            <th>Tour</th>
                            <th>Date</th>
                            <th>Travelers</th>
                            <th>Amount</th>
                            <th>Payment</th>
                            <th>Status</th>
                            <th>Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach ($bookings as $booking): ?>
                            <tr>
                                <td><strong><?= e($booking['booking_code']) ?></strong></td>
                                <td>
                                    <?= e($booking['customer_name']) ?><br>
                                    <small class="text-muted"><?= e($booking['customer_email']) ?></small>
                                </td>
                                <td><?= e($booking['tour_title']) ?></td>
                                <td><?= formatDate($booking['booking_date']) ?></td>
                                <td><?= $booking['travelers'] ?></td>
                                <td><?= formatPrice($booking['total_price']) ?></td>
                                <td><?= ucfirst(e($booking['payment_method'] ?? 'cash')) ?></td>
                                <td>
                                    <span class="badge-status badge-<?= $booking['status'] ?>">
                                        <?= ucfirst($booking['status']) ?>
                                    </span>
                                </td>
                                <td>
                                    <div class="dropdown">
                                        <button class="btn btn-sm btn-gradient dropdown-toggle" type="button" data-bs-toggle="dropdown">
                                            Update
                                        </button>
                                        <ul class="dropdown-menu">
                                            <li>
                                                <form method="POST" class="px-3 py-2">
                                                    <input type="hidden" name="booking_id" value="<?= $booking['id'] ?>">
                                                    <input type="hidden" name="update_status" value="1">
                                                    <button type="submit" name="status" value="confirmed" class="btn btn-sm btn-success w-100 mb-1">
                                                        Confirm
                                                    </button>
                                                    <button type="submit" name="status" value="completed" class="btn btn-sm btn-primary w-100 mb-1">
                                                        Complete
                                                    </button>
                                                    <button type="submit" name="status" value="cancelled" class="btn btn-sm btn-danger w-100">
                                                        Cancel
                                                    </button>
                                                </form>
                                            </li>
                                            <li><hr class="dropdown-divider"></li>
                                            <li>
                                                <form method="POST" class="px-3 pb-2" onsubmit="return confirm('Delete this booking? This cannot be undone.');">
                                                    <input type="hidden" name="booking_id" value="<?= $booking['id'] ?>">
                                                    <button type="submit" name="delete_booking" value="1" class="btn btn-sm btn-outline-danger w-100">
                                                        Delete
                                                    </button>
                                                </form>
                                            </li>
                                        </ul>
                                    </div>
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
