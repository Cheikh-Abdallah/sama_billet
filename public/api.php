<?php
require __DIR__.'/../includes/bootstrap.php';
header('Content-Type: application/json; charset=utf-8');
$action=$_GET['action']??'';
if($action==='events'){ $rows=$pdo->query("SELECT e.id,e.title,e.location,e.event_date,e.price,e.image,c.name AS category FROM events e LEFT JOIN categories c ON c.id=e.category_id WHERE e.status='published' ORDER BY e.event_date")->fetchAll(); echo json_encode(['success'=>true,'data'=>$rows]); exit; }
if($action==='event'){ $id=(int)($_GET['id']??0); $stmt=$pdo->prepare('SELECT * FROM events WHERE id=?'); $stmt->execute([$id]); echo json_encode(['success'=>true,'data'=>$stmt->fetch()]); exit; }
echo json_encode(['success'=>false,'error'=>'Action inconnue']);
