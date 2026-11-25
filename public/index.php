<?php
require __DIR__ . '/../includes/bootstrap.php';
$events = $pdo->query("SELECT e.*, c.name AS category FROM events e LEFT JOIN categories c ON c.id=e.category_id WHERE e.status='published' AND e.event_date >= NOW() ORDER BY e.event_date LIMIT 6")->fetchAll();
$pageTitle = 'Accueil';
require __DIR__ . '/../includes/header.php';
?>
<section class="hero">
  <div class="hero-content">
    <span class="eyebrow">Culture • Sport • Divertissement</span>
    <h1>Vivez vos moments<br><em>au maximum</em></h1>
    <p>Découvrez les meilleurs événements au Sénégal et réservez vos billets en quelques clics.</p>
    <div class="hero-actions">
      <a class="btn" href="/events.php">Voir les événements</a>
      <a class="btn btn-outline" href="#comment">Comment ça marche ?</a>
    </div>
  </div>
  <div class="hero-art" aria-hidden="true">
    <div class="ticket ticket-back"><span>VIP</span></div>
    <div class="ticket ticket-front"><span>SAMA</span><strong>BILLET</strong><small>N° 2026</small></div>
  </div>
</section>

<section class="section">
  <div class="section-heading"><div><span class="eyebrow">À ne pas manquer</span><h2>Événements populaires</h2></div><a href="/events.php">Tout voir →</a></div>
  <div class="event-grid">
  <?php foreach ($events as $event): ?>
    <article class="event-card">
      <a class="event-image" href="/event.php?id=<?= $event['id'] ?>" style="background-image:url('<?= esc($event['image']) ?>')">
        <span class="date-badge"><strong><?= date('d', strtotime($event['event_date'])) ?></strong><?= strtoupper(strftime('%b', strtotime($event['event_date']))) ?></span>
        <span class="category-badge"><?= esc($event['category']) ?></span>
      </a>
      <div class="event-body">
        <h3><a href="/event.php?id=<?= $event['id'] ?>"><?= esc($event['title']) ?></a></h3>
        <p class="location">⌖ <?= esc($event['location']) ?></p>
        <div class="card-bottom"><strong><?= formatMoney((float)$event['price']) ?></strong><a href="/event.php?id=<?= $event['id'] ?>">Réserver →</a></div>
      </div>
    </article>
  <?php endforeach; ?>
  </div>
</section>

<section class="steps" id="comment">
  <span class="eyebrow">Simple et rapide</span><h2>Comment ça marche ?</h2>
  <div class="step-grid">
    <div><b>1</b><h3>Choisissez</h3><p>Trouvez l’événement qui vous fait vibrer.</p></div>
    <div><b>2</b><h3>Réservez</h3><p>Sélectionnez vos places et vos options.</p></div>
    <div><b>3</b><h3>Profitez</h3><p>Payez et recevez vos billets instantanément.</p></div>
  </div>
</section>
<?php require __DIR__ . '/../includes/footer.php'; ?>
