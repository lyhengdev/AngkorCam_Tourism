<?php
$pageTitle = 'Home - AngkorCam Tourism';
$tourModel = new Tour($db);
$tours = $tourModel->getFeatured(6);
ob_start();
?>
<section class="hero">
    <div class="container position-relative">
        <div class="row align-items-center gy-5">
            <div class="col-lg-6">
                <p class="eyebrow">AngkorCam Journeys</p>
                <h1>Angkor at Dawn, Islands by Dusk</h1>
                <p class="lead">Explore Cambodia's temples, rivers, and islands with small-group tours led by local guides.</p>
                <div class="hero-actions">
                    <a href="?page=tours" class="btn btn-gradient btn-lg">
                        <i class="bi bi-map"></i> Explore Tours
                    </a>
                    <?php if (isLoggedIn()): ?>
                        <a href="?page=my-bookings" class="btn btn-outline-gradient btn-lg">My Bookings</a>
                    <?php else: ?>
                        <a href="?page=register" class="btn btn-outline-gradient btn-lg">Create Account</a>
                    <?php endif; ?>
                </div>
                <div class="hero-meta">
                    <div class="meta-pill"><i class="bi bi-star-fill me-2"></i>4.9 guest rating</div>
                    <div class="meta-pill"><i class="bi bi-geo-alt me-2"></i>50+ places to visit</div>
                    <div class="meta-pill"><i class="bi bi-shield-check me-2"></i>Secure booking</div>
                </div>
            </div>
            <div class="col-lg-6">
                <div class="hero-card">
                    <span class="badge-soft">Trip of the week</span>
                    <h3>Angkor Wat Sunrise & Bayon Faces</h3>
                    <p class="mb-3">Start before dawn, catch the sunrise at Angkor Wat, and finish at Bayon's iconic stone faces.</p>
                    <div class="hero-card-meta">
                        <span><i class="bi bi-calendar3"></i> 1 day</span>
                        <span><i class="bi bi-geo-alt"></i> Siem Reap</span>
                        <span><i class="bi bi-currency-dollar"></i> From $48</span>
                    </div>
                    <a href="?page=tours" class="btn btn-gradient btn-sm mt-4">View itinerary</a>
                </div>
            </div>
        </div>
    </div>
</section>

<section class="section">
    <div class="container">
        <div class="section-header">
            <p class="eyebrow dark">Cambodia highlights</p>
            <h2>Curated routes across temples, rivers, and coast</h2>
            <p class="lead">Choose sunrise temples, floating villages, or island escapes tailored to your pace.</p>
        </div>
        <div class="row g-4">
            <?php foreach ($tours as $tour): ?>
                <div class="col-md-6 col-lg-4">
                    <div class="tour-card fade-up">
                        <div style="overflow: hidden;">
                            <img src="<?= e(tourImage($tour)) ?>" class="tour-card-img" alt="<?= e($tour['title']) ?>" loading="lazy">
                        </div>
                        <div class="tour-card-body">
                            <span class="badge-gradient mb-3"><i class="bi bi-geo-alt"></i> <?= e($tour['location']) ?></span>
                            <h4 class="mb-3"><?= e($tour['title']) ?></h4>
                            <p class="text-muted mb-4"><?= truncate($tour['description'], 120) ?></p>
                            <div class="d-flex justify-content-between align-items-center">
                                <div>
                                    <div class="tour-price"><?= formatPrice($tour['price']) ?></div>
                                    <small class="text-muted"><i class="bi bi-calendar3"></i> <?= $tour['duration'] ?> Day(s)</small>
                                </div>
                                <a href="?page=tour&id=<?= $tour['id'] ?>" class="btn btn-gradient">View Details</a>
                            </div>
                        </div>
                    </div>
                </div>
            <?php endforeach; ?>
        </div>
        <div class="text-center mt-5">
            <a href="?page=tours" class="btn btn-soft">View All Tours</a>
        </div>
    </div>
</section>

<section class="section" style="background: var(--gradient-soft);">
    <div class="container">
        <div class="section-header">
            <p class="eyebrow dark">How it works</p>
            <h2>Plan a trip in minutes</h2>
            <p class="lead">From choosing a route to meeting your guide, every step is clear and supported.</p>
        </div>
        <div class="row g-4">
            <div class="col-md-4">
                <div class="step-card">
                    <div class="step-number">01</div>
                    <h5>Pick your route</h5>
                    <p class="text-muted">Browse curated tours with transparent pricing, duration, and highlights.</p>
                </div>
            </div>
            <div class="col-md-4">
                <div class="step-card">
                    <div class="step-number">02</div>
                    <h5>Reserve instantly</h5>
                    <p class="text-muted">Lock in your dates, add traveler details, and receive a confirmed booking code.</p>
                </div>
            </div>
            <div class="col-md-4">
                <div class="step-card">
                    <div class="step-number">03</div>
                    <h5>Meet your guide</h5>
                    <p class="text-muted">We handle logistics so you can focus on the journey and local stories.</p>
                </div>
            </div>
        </div>
    </div>
</section>

<section class="section">
    <div class="container">
        <div class="section-header">
            <p class="eyebrow dark">Why AngkorCam</p>
            <h2>Local expertise, global standards</h2>
            <p class="lead">Trusted guides, curated partners, and seamless support for every traveler.</p>
        </div>
        <div class="row g-4">
            <div class="col-md-6 col-lg-3">
                <div class="feature-card text-center">
                    <i class="bi bi-award" style="font-size: 2.8rem; color: var(--clay);"></i>
                    <h5 class="mt-3">Expert Guides</h5>
                    <p class="text-muted">Local storytellers who know each temple and village.</p>
                </div>
            </div>
            <div class="col-md-6 col-lg-3">
                <div class="feature-card text-center">
                    <i class="bi bi-shield-check" style="font-size: 2.8rem; color: var(--jade);"></i>
                    <h5 class="mt-3">Safe & Secure</h5>
                    <p class="text-muted">Verified partners and transparent booking support.</p>
                </div>
            </div>
            <div class="col-md-6 col-lg-3">
                <div class="feature-card text-center">
                    <i class="bi bi-wallet2" style="font-size: 2.8rem; color: var(--river);"></i>
                    <h5 class="mt-3">Fair Pricing</h5>
                    <p class="text-muted">No hidden fees. Every tour lists what is included.</p>
                </div>
            </div>
            <div class="col-md-6 col-lg-3">
                <div class="feature-card text-center">
                    <i class="bi bi-headset" style="font-size: 2.8rem; color: var(--sun);"></i>
                    <h5 class="mt-3">24/7 Support</h5>
                    <p class="text-muted">Travel with confidence knowing help is one message away.</p>
                </div>
            </div>
        </div>
    </div>
</section>
<?php $content = ob_get_clean(); require __DIR__ . '/../../layouts/main.php'; ?>
