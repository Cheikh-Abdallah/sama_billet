<?php
require __DIR__.'/../includes/bootstrap.php';
requireLogin();

$id = (int)($_GET['id'] ?? 0);
$requestedQuantity = max(1, min(10, (int)($_GET['quantity'] ?? 1)));
$stmt = $pdo->prepare("SELECT * FROM events WHERE id=? AND status='published'");
$stmt->execute([$id]);
$event = $stmt->fetch();
if (!$event) {
    http_response_code(404);
    exit('Événement introuvable.');
}

$remaining = max(0, (int)$event['capacity'] - (int)$event['sold']);
$quantity = min($requestedQuantity, $remaining);
$total = (float)$event['price'] * $quantity;
$error = '';
$paymentMethods = [
    'wave' => 'Wave',
    'orange' => 'Orange Money',
    'free_money' => 'Free Money',
    'card' => 'Carte bancaire (Visa / Mastercard)',
];
$selectedMethod = 'wave';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    verifyCsrf();
    $selectedMethod = (string)($_POST['payment_method'] ?? '');
    if (!array_key_exists($selectedMethod, $paymentMethods)) {
        $error = 'Veuillez choisir un moyen de paiement valide.';
    } elseif ($quantity < 1 || $quantity > $remaining) {
        $error = 'Le nombre de places demandé n’est plus disponible.';
    } else {
        try {
            $pdo->beginTransaction();
            // Recheck stock inside the transaction so concurrent reservations cannot oversell.
            $lock = $pdo->prepare("SELECT capacity, sold FROM events WHERE id=? AND status='published' FOR UPDATE");
            $lock->execute([$id]);
            $stock = $lock->fetch();
            $available = $stock ? max(0, (int)$stock['capacity'] - (int)$stock['sold']) : 0;
            if (!$stock || $quantity > $available) {
                throw new RuntimeException('stock');
            }

            // Démo : ce flux ne débite pas réellement le client. Brancher un prestataire et
            // confirmer son webhook avant de passer la commande à « paid » en production.
            $reference = 'SB' . date('YmdHis') . random_int(100, 999);
            $order = $pdo->prepare('INSERT INTO orders(user_id,event_id,quantity,total,payment_method,payment_status,reference) VALUES(?,?,?,?,?,"paid",?)');
            $order->execute([$_SESSION['user']['id'], $id, $quantity, $total, $selectedMethod, $reference]);
            $orderId = (int)$pdo->lastInsertId();
            $ticket = $pdo->prepare('INSERT INTO tickets(order_id,code) VALUES(?,?)');
            for ($i = 1; $i <= $quantity; $i++) {
                $ticket->execute([$orderId, 'TKT-' . $reference . '-' . str_pad((string)$i, 3, '0', STR_PAD_LEFT)]);
            }
            $update = $pdo->prepare('UPDATE events SET sold=sold+? WHERE id=?');
            $update->execute([$quantity, $id]);
            $pdo->commit();
            flash('success', 'Réservation de démonstration confirmée. Référence : ' . $reference);
            redirect('/dashboard.php');
        } catch (RuntimeException $exception) {
            if ($pdo->inTransaction()) $pdo->rollBack();
            $error = 'Les places demandées ne sont plus disponibles. Veuillez actualiser la page.';
        } catch (Throwable $exception) {
            if ($pdo->inTransaction()) $pdo->rollBack();
            $error = 'La réservation n’a pas pu être enregistrée. Veuillez réessayer.';
        }
    }
}

$pageTitle = 'Paiement';
require __DIR__.'/../includes/header.php';
?>
<section class="checkout">
  <div class="checkout-form auth-card">
    <span class="eyebrow">Dernière étape</span>
    <h1>Finaliser la réservation</h1>
    <?php if ($error): ?><div class="alert error"><?= esc($error) ?></div><?php endif; ?>
    <?php if ($remaining < 1): ?><div class="alert error">Cet événement est complet.</div><?php else: ?>
    <form method="post">
      <input type="hidden" name="csrf" value="<?= csrfToken() ?>">
      <h3>Moyen de paiement</h3>
      <label class="payment-option"><input type="radio" name="payment_method" value="wave" <?= $selectedMethod === 'wave' ? 'checked' : '' ?>><b>Wave</b><span>Mobile Money · Sénégal</span></label>
      <label class="payment-option"><input type="radio" name="payment_method" value="orange" <?= $selectedMethod === 'orange' ? 'checked' : '' ?>><b>Orange Money</b><span>Mobile Money · Sénégal</span></label>
      <label class="payment-option"><input type="radio" name="payment_method" value="free_money" <?= $selectedMethod === 'free_money' ? 'checked' : '' ?>><b>Free Money</b><span>Mobile Money · Sénégal</span></label>
      <label class="payment-option"><input type="radio" name="payment_method" value="card" <?= $selectedMethod === 'card' ? 'checked' : '' ?>><b>Carte bancaire</b><span>Visa / Mastercard</span></label>
      <p class="payment-demo-note">Mode démonstration : aucun paiement réel n’est débité. L’activation des paiements nécessite les identifiants d’un prestataire et la configuration de ses notifications de confirmation.</p>
      <button class="btn btn-full">Continuer — <?= formatMoney($total) ?></button>
    </form>
    <?php endif; ?>
  </div>
  <aside class="order-summary"><h2>Votre réservation</h2><h3><?= esc($event['title']) ?></h3><p><?= esc($event['location']) ?></p><div class="price-row"><span><?= $quantity ?> billet<?= $quantity > 1 ? 's' : '' ?></span><strong><?= formatMoney($total) ?></strong></div></aside>
</section>
<?php require __DIR__.'/../includes/footer.php'; ?>
