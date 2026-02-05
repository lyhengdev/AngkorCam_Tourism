<?php
$pageTitle = 'Terms of Service - AngkorCam Tourism';
ob_start();
?>
<section class="section">
    <div class="container">
        <div class="section-header">
            <p class="eyebrow dark">Legal</p>
            <h1>Terms of Service</h1>
            <p class="lead">These terms outline the rules and responsibilities for using AngkorCam services.</p>
        </div>
        <div class="policy-card">
            <h4>1. Bookings</h4>
            <p class="text-muted">Bookings are confirmed upon receipt of a booking code. Availability may change until confirmation is issued.</p>

            <h4 class="mt-4">2. Payments</h4>
            <p class="text-muted">Payment methods may vary by tour. Unless otherwise stated, payment is collected on arrival.</p>

            <h4 class="mt-4">3. Cancellations</h4>
            <p class="text-muted">Full refunds are available within 24 hours of booking. After that, rescheduling options may be offered.</p>

            <h4 class="mt-4">4. Responsibility</h4>
            <p class="text-muted">Travelers are responsible for following local laws, safety guidance, and cultural etiquette.</p>

            <h4 class="mt-4">5. Changes</h4>
            <p class="text-muted">We may update these terms. Continued use indicates acceptance of the latest version.</p>
        </div>
    </div>
</section>
<?php $content = ob_get_clean(); require __DIR__ . '/../../layouts/main.php'; ?>
