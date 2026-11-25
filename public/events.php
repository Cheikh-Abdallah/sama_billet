<?php
require __DIR__ . '/../includes/bootstrap.php';
$q = trim($_GET['q'] ?? '');
$category = $_GET['category'] ?? '';
$sql = "SELECT e.*, c.name AS category FROM events e LEFT JOIN categories c ON c.id=e.category_id WHERE e.status='published' AND e.event_date >= NOW()";
$params = [];
if ($q !== '') { $sql .= " AND (e.title LIKE ? OR e.location LIKE ?)"; $params[] = "%$q%"; $params[] = "%$q%"; }
if ($category !== '') { $sql .= " AND c.id = ?"; $params[] = $category; }
$sql .= " ORDER BY e.event_date";
$stmt = $pdo->prepare($sql); $stmt->execute($params); $events = $stmt->fetchAll();
$categories = $pdo->query("SELECT * FROM categories ORDER BY name")->fetchAll();
$pageTitle = 'Tous les événements'; require __DIR__ . '/../includes/header.php';
?>
<section class="page-header"><span class="eyebrow">Découvrez votre prochaine sortie</span><h1>Tous les événements</h1></section>
<section class="search-bar">
<form method="get">
<input name="q" value="<?= esc($q) ?>" placeholder="Rechercher un événement ou une ville…">
<select name="category"><option value="">Toutes les catégories</option><?php foreach($categories as $c): ?><option value="<?= $c['id'] ?>" <?= $category==$c['id']?'selected':'' ?>><?= esc($c['name']) ?></option><?php endforeach; ?></select>
<button class="btn">Rechercher</button>
</form>
</section>
<section class="section">
<div class="results-info"><strong><?= count($events) ?></strong> événement(s) trouvé(s)</div>
<div class="event-grid">
<?php foreach($events as $event): ?>
<article class="event-card"><a class="event-image" href="/event.php?id=<?= $event['id'] ?>" style="background-image:url('<?= esc($event['image']) ?>')"><span class="date-badge"><strong><?= date('d',strtotime($event['event_date'])) ?></strong><?= strtoupper(date('M',strtotime($event['event_date']))) ?></span></a><div class="event-body"><h3><a href="/event.php?id=<?= $event['id'] ?>"><?= esc($event['title']) ?></a></h3><p class="location">⌖ <?= esc($event['location']) ?></p><div class="card-bottom"><strong><?= formatMoney((float)$event['price']) ?></strong><a href="/event.php?id=<?= $event['id'] ?>">Réserver →</a></div></div></article>
<?php endforeach; ?>
<?php if(!$events): ?><div class="empty-state"><h2>Aucun événement trouvé</h2><p>Essayez une autre recherche.</p></div><?php endif; ?>
</div></section>
<?php require __DIR__ . '/../includes/footer.php'; ?>
