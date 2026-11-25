<?php
require __DIR__.'/../includes/bootstrap.php'; requireLogin();
$stmt=$pdo->prepare("SELECT o.*,e.title,e.location,e.event_date,e.image,t.code,t.is_used FROM orders o JOIN events e ON e.id=o.event_id JOIN tickets t ON t.order_id=o.id WHERE o.user_id=? ORDER BY o.created_at DESC"); $stmt->execute([$_SESSION['user']['id']]); $tickets=$stmt->fetchAll();
$pageTitle='Mes billets'; require __DIR__.'/../includes/header.php';
?>
<section class="dashboard"><div class="section-heading"><div><span class="eyebrow">Bonjour <?= esc($_SESSION['user']['name']) ?></span><h1>Mes billets</h1></div></div><?php if(!$tickets): ?><div class="empty-state"><h2>Aucun billet pour le moment</h2><a class="btn" href="/events.php">Découvrir les événements</a></div><?php endif; ?><div class="ticket-list">
<?php foreach($tickets as $ticket): ?><article class="user-ticket"><div class="ticket-main"><img src="<?= esc($ticket['image']) ?>" alt=""><div><span><?= date('d/m/Y',strtotime($ticket['event_date'])) ?></span><h2><?= esc($ticket['title']) ?></h2><p><?= esc($ticket['location']) ?></p></div></div><div class="ticket-code"><strong><?= esc($ticket['code']) ?></strong><span><?= $ticket['is_used']?'Utilisé':'Valide' ?></span></div></article><?php endforeach; ?></div></section>
<?php require __DIR__.'/../includes/footer.php'; ?>
