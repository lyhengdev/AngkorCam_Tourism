<?php
requireLogin();
if ($_SERVER['REQUEST_METHOD'] !== 'POST') redirect('?page=home');

$tourModel = new Tour($db);
$bookingModel = new Booking($db);
$currentUser = getUser();

$tour_id = (int)($_POST['tour_id'] ?? 0);
$tour = $tourModel->getById($tour_id);

if (!$tour) {
    setFlash('error', 'Tour not found');
    redirect('?page=tours');
}

$travelers = (int)($_POST['travelers'] ?? 1);
$total_price = $tour['price'] * $travelers;
$paymentMethod = sanitize($_POST['payment_method'] ?? 'cash');
$allowedPaymentMethods = ['cash'];
if (!in_array($paymentMethod, $allowedPaymentMethods, true)) {
    $paymentMethod = 'cash';
}

$bookingData = [
    'booking_code' => generateBookingCode(),
    'tour_id' => $tour_id,
    'user_id' => $currentUser['id'] ?? null,
    'customer_name' => sanitize($_POST['customer_name'] ?? ''),
    'customer_email' => sanitize($_POST['customer_email'] ?? ''),
    'customer_phone' => sanitize($_POST['customer_phone'] ?? ''),
    'travelers' => $travelers,
    'booking_date' => $_POST['booking_date'] ?? '',
    'total_price' => $total_price,
    'payment_method' => $paymentMethod,
    'notes' => sanitize($_POST['notes'] ?? '')
];

if ($bookingModel->create($bookingData)) {
    $emailSent = sendBookingReceipt($bookingData, $tour);
    $message = 'Booking successful! Code: ' . $bookingData['booking_code'];
    if ($emailSent) {
        $message .= ' (Receipt sent to email)';
    }
    setFlash('success', $message);
    redirect('?page=my-bookings');
} else {
    setFlash('error', 'Booking failed. Please try again.');
    redirect('?page=booking&tour_id=' . $tour_id);
}
?>
