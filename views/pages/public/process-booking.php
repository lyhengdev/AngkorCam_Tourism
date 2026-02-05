<?php
requireLogin();
if ($_SERVER['REQUEST_METHOD'] !== 'POST') redirect('?page=home');

$tourModel = new Tour($db);
$bookingModel = new Booking($db);

$tour_id = (int)($_POST['tour_id'] ?? 0);
$tour = $tourModel->getById($tour_id);

if (!$tour) {
    setFlash('error', 'Tour not found');
    redirect('?page=tours');
}

$travelers = (int)($_POST['travelers'] ?? 1);
$total_price = $tour['price'] * $travelers;

$bookingData = [
    'booking_code' => generateBookingCode(),
    'tour_id' => $tour_id,
    'user_id' => $_SESSION['user_id'],
    'customer_name' => sanitize($_POST['customer_name'] ?? ''),
    'customer_email' => sanitize($_POST['customer_email'] ?? ''),
    'customer_phone' => sanitize($_POST['customer_phone'] ?? ''),
    'travelers' => $travelers,
    'booking_date' => $_POST['booking_date'] ?? '',
    'total_price' => $total_price,
    'notes' => sanitize($_POST['notes'] ?? '')
];

if ($bookingModel->create($bookingData)) {
    setFlash('success', 'Booking successful! Code: ' . $bookingData['booking_code']);
    redirect('?page=my-bookings');
} else {
    setFlash('error', 'Booking failed. Please try again.');
    redirect('?page=booking&tour_id=' . $tour_id);
}
?>
