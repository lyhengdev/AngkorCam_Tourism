<?php
$pageTitle = 'FAQ - AngkorCam Tourism';
$faqs = [
    [
        'q' => 'Can I customize a tour?',
        'a' => 'Yes. Share your pace, interests, and travel dates. We will tailor the timing, stops, and experiences to match.'
    ],
    [
        'q' => 'What is your cancellation policy?',
        'a' => 'Cancel within 24 hours of booking for a full refund. After that, we will do our best to reschedule.'
    ],
    [
        'q' => 'Do tours include entrance fees?',
        'a' => 'Each tour lists what is included. Many temple tickets are excluded so you can choose a pass length.'
    ],
    [
        'q' => 'How big are the groups?',
        'a' => 'Most tours run in small groups of 6-10 travelers to keep the experience personal.'
    ],
    [
        'q' => 'Are your guides licensed?',
        'a' => 'Yes. We work with licensed local guides who are trained in heritage and safety standards.'
    ],
    [
        'q' => 'What should I wear to temples?',
        'a' => 'Shoulders and knees should be covered. Lightweight long sleeves are ideal.'
    ],
    [
        'q' => 'Can I book for a family?',
        'a' => 'Absolutely. We can adapt the pace, add breaks, and suggest child-friendly activities.'
    ],
    [
        'q' => 'What payment methods are accepted?',
        'a' => 'Currently we accept cash (pay on arrival). Additional options can be arranged on request.'
    ]
];
ob_start();
?>
<section class="section">
    <div class="container">
        <div class="section-header">
            <p class="eyebrow dark">FAQ</p>
            <h1>Frequently asked questions</h1>
            <p class="lead">Everything you need to know before planning your Cambodia journey.</p>
        </div>
        <div class="faq-shell">
            <div class="accordion" id="faqAccordion">
                <?php foreach ($faqs as $index => $faq): ?>
                    <?php $collapseId = 'faqItem' . $index; ?>
                    <div class="accordion-item">
                        <h2 class="accordion-header">
                            <button class="accordion-button <?= $index === 0 ? '' : 'collapsed' ?>" type="button" data-bs-toggle="collapse" data-bs-target="#<?= $collapseId ?>">
                                <?= e($faq['q']) ?>
                            </button>
                        </h2>
                        <div id="<?= $collapseId ?>" class="accordion-collapse collapse <?= $index === 0 ? 'show' : '' ?>" data-bs-parent="#faqAccordion">
                            <div class="accordion-body">
                                <?= e($faq['a']) ?>
                            </div>
                        </div>
                    </div>
                <?php endforeach; ?>
            </div>
        </div>
    </div>
</section>
<?php $content = ob_get_clean(); require __DIR__ . '/../../layouts/main.php'; ?>
