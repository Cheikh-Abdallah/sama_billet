<?php
require __DIR__ . '/../includes/bootstrap.php';
$id = (int)($_GET['id'] ?? 0);
$stmt = $pdo->prepare("SELECT e.*, c.name AS category FROM events e LEFT JOIN categories c ON c.id=e.category_id WHERE e.id=?");
$stmt->execute([$id]); $event=$stmt->fetch();
if(!$event) { http_response_code(404); exit('Événement introuvable'); }
$remaining = max(0,(int)$event['capacity']-(int)$event['sold']);
$pageTitle=$event['title']; require __DIR__.'/../includes/header.php';
?>
<section class="event-detail">
<div class="detail-image" style="background-image:url('<?= esc($event['image']) ?>')"><span class="category-badge"><?= esc($event['category']) ?></span></div>
<div class="detail-info"><span class="eyebrow">Événement confirmé</span><h1><?= esc($event['title']) ?></h1><p class="detail-meta">◷ <?= date('d/m/Y à H:i',strtotime($event['event_date'])) ?><br>⌖ <?= esc($event['location']) ?></p><p><?= nl2br(esc($event['description'])) ?></p>
<form class="booking-card" action="/checkout.php" method="get"><input type="hidden" name="id" value="<?= $event['id'] ?>"><label>Nombre de billets</label><div class="quantity"><button type="button" data-minus>−</button><input name="quantity" value="1" min="1" max="<?= min(10,$remaining) ?>" readonly><button type="button" data-plus>+</button></div><div class="price-row"><span>À partir de</span><strong data-price="<?= $event['price'] ?>"><?= formatMoney((float)$event['price']) ?></strong></div><button class="btn btn-full" <?= $remaining<1?'disabled':'' ?>><?= $remaining>0?'Réserver maintenant':'Complet' ?></button><small><?= $remaining ?> places disponibles</small></form></div>
</section>
<?php require __DIR__.'/../includes/footer.php'; ?>
