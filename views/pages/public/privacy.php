<?php
$pageTitle = 'Privacy Policy - AngkorCam Tourism';
ob_start();
?>
<section class="section">
    <div class="container">
        <div class="section-header">
            <p class="eyebrow dark">Legal</p>
            <h1>Privacy Policy</h1>
            <p class="lead">We respect your privacy and handle your information with care.</p>
        </div>
        <div class="policy-card">
            <h4>Information we collect</h4>
            <p class="text-muted">We collect contact details, booking preferences, and communication history to deliver your trip.</p>

            <h4 class="mt-4">How we use data</h4>
            <p class="text-muted">Your data is used to confirm bookings, provide support, and improve our services.</p>

            <h4 class="mt-4">Sharing</h4>
            <p class="text-muted">We only share necessary details with trusted partners to complete your booking.</p>

            <h4 class="mt-4">Security</h4>
            <p class="text-muted">We apply industry-standard safeguards and limit internal access to customer data.</p>

            <h4 class="mt-4">Contact</h4>
            <p class="text-muted">For questions, email hello@angkorcam.com.</p>
        </div>
    </div>
</section>
<?php $content = ob_get_clean(); require __DIR__ . '/../../layouts/main.php'; ?>
