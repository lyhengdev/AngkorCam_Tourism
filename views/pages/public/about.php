<?php
$pageTitle = 'About - AngkorCam Tourism';
$tourModel = new Tour($db);
$featured = $tourModel->getFeatured(4);
ob_start();
?>
<section class="section section-alt">
    <div class="container position-relative">
        <div class="row align-items-center g-5">
            <div class="col-lg-6">
                <p class="eyebrow dark">About AngkorCam</p>
                <h1>Crafting journeys with heart, history, and local care</h1>
                <p class="lead">We are a Cambodian-led travel studio curating small-group experiences that respect heritage and celebrate modern culture.</p>
                <div class="d-flex flex-wrap gap-3 mt-4">
                    <span class="info-pill"><i class="bi bi-globe2"></i> Locally owned</span>
                    <span class="info-pill"><i class="bi bi-people"></i> Small groups</span>
                    <span class="info-pill"><i class="bi bi-shield-check"></i> Verified partners</span>
                </div>
                <div class="mt-4">
                    <a href="?page=tours" class="btn btn-gradient">Explore tours</a>
                </div>
            </div>
            <div class="col-lg-6">
                <div class="story-media">
                    <?php foreach ($featured as $tour): ?>
                        <div class="image-tile">
                            <img src="<?= e(tourImage($tour)) ?>" alt="<?= e($tour['title']) ?>" loading="lazy" decoding="async">
                        </div>
                    <?php endforeach; ?>
                </div>
            </div>
        </div>
    </div>
</section>

<section class="section">
    <div class="container">
        <div class="story-grid">
            <div class="story-copy">
                <p class="eyebrow dark">Our mission</p>
                <h2>Elevate every day of travel with stories you can feel</h2>
                <p class="lead">We design routes around real people: temple caretakers, chefs, artists, and river communities who open their world to you.</p>
                <p class="text-muted">Every itinerary is paced with breathing room, cultural context, and local flavors so you return with more than photos.</p>
            </div>
            <div class="story-media">
                <div class="highlight-card">
                    <h4>Heritage-first planning</h4>
                    <p class="text-muted">We align with conservation guidelines and promote respectful temple visits.</p>
                </div>
                <div class="highlight-card">
                    <h4>Community-led hosts</h4>
                    <p class="text-muted">Our partners are local storytellers who live the culture daily.</p>
                </div>
                <div class="highlight-card">
                    <h4>Comfort without crowds</h4>
                    <p class="text-muted">Small groups, flexible timing, and quiet viewpoints.</p>
                </div>
                <div class="highlight-card">
                    <h4>Transparent pricing</h4>
                    <p class="text-muted">Know what is included before you book, always.</p>
                </div>
            </div>
        </div>
    </div>
</section>

<section class="section section-alt">
    <div class="container position-relative">
        <div class="section-header">
            <p class="eyebrow dark">Our values</p>
            <h2>What we stand for</h2>
            <p class="lead">Our team is built on hospitality, integrity, and the belief that travel should empower local communities.</p>
        </div>
        <div class="row g-4">
            <div class="col-md-4">
                <div class="feature-card h-100 text-center">
                    <i class="bi bi-compass" style="font-size: 2.6rem; color: var(--clay);"></i>
                    <h5 class="mt-3">Curated routes</h5>
                    <p class="text-muted">Every day balances iconic sites with hidden local moments.</p>
                </div>
            </div>
            <div class="col-md-4">
                <div class="feature-card h-100 text-center">
                    <i class="bi bi-heart" style="font-size: 2.6rem; color: var(--jade);"></i>
                    <h5 class="mt-3">Human-centered travel</h5>
                    <p class="text-muted">We prioritize dignity, fair pay, and long-term partnerships.</p>
                </div>
            </div>
            <div class="col-md-4">
                <div class="feature-card h-100 text-center">
                    <i class="bi bi-stars" style="font-size: 2.6rem; color: var(--river);"></i>
                    <h5 class="mt-3">Memorable details</h5>
                    <p class="text-muted">From sunrise timing to local snacks, the little things matter.</p>
                </div>
            </div>
        </div>
    </div>
</section>

<section class="section">
    <div class="container">
        <div class="section-header">
            <p class="eyebrow dark">How we plan</p>
            <h2>Thoughtful planning, simple booking</h2>
        </div>
        <div class="row g-4">
            <div class="col-md-4">
                <div class="step-card">
                    <div class="step-number">01</div>
                    <h5>Listen first</h5>
                    <p class="text-muted">Tell us your pace, interests, and travel style.</p>
                </div>
            </div>
            <div class="col-md-4">
                <div class="step-card">
                    <div class="step-number">02</div>
                    <h5>Design the route</h5>
                    <p class="text-muted">We craft a plan that balances highlights and hidden gems.</p>
                </div>
            </div>
            <div class="col-md-4">
                <div class="step-card">
                    <div class="step-number">03</div>
                    <h5>Travel with ease</h5>
                    <p class="text-muted">Clear confirmations, expert guides, and support throughout.</p>
                </div>
            </div>
        </div>
    </div>
</section>

<section class="section section-alt">
    <div class="container position-relative">
        <div class="row g-4">
            <div class="col-md-4">
                <div class="stat-card">
                    <i class="bi bi-geo-alt" style="font-size: 2.6rem; color: var(--clay);"></i>
                    <h3 class="mt-3">50+</h3>
                    <p class="text-muted mb-0">Curated routes</p>
                </div>
            </div>
            <div class="col-md-4">
                <div class="stat-card">
                    <i class="bi bi-people" style="font-size: 2.6rem; color: var(--jade);"></i>
                    <h3 class="mt-3">120+</h3>
                    <p class="text-muted mb-0">Local hosts</p>
                </div>
            </div>
            <div class="col-md-4">
                <div class="stat-card">
                    <i class="bi bi-star-fill" style="font-size: 2.6rem; color: var(--river);"></i>
                    <h3 class="mt-3">4.9</h3>
                    <p class="text-muted mb-0">Average rating</p>
                </div>
            </div>
        </div>
    </div>
</section>
<?php $content = ob_get_clean(); require __DIR__ . '/../../layouts/main.php'; ?>
