<?php
require __DIR__.'/../includes/bootstrap.php';
if(isLoggedIn()) redirect('/index.php');
$error='';
if($_SERVER['REQUEST_METHOD']==='POST') {
 verifyCsrf(); $name=trim($_POST['name']??''); $email=strtolower(trim($_POST['email']??'')); $phone=trim($_POST['phone']??''); $password=$_POST['password']??'';
 if(!filter_var($email,FILTER_VALIDATE_EMAIL)) $error='Adresse e-mail invalide.';
 elseif(strlen($password)<8) $error='Le mot de passe doit contenir au moins 8 caractères.';
 else { $check=$pdo->prepare('SELECT id FROM users WHERE email=?'); $check->execute([$email]); if($check->fetch()) $error='Cette adresse existe déjà.'; else { $stmt=$pdo->prepare('INSERT INTO users(name,email,phone,password) VALUES(?,?,?,?)'); $stmt->execute([$name,$email,$phone,password_hash($password,PASSWORD_DEFAULT)]); flash('success','Compte créé. Vous pouvez maintenant vous connecter.'); redirect('/login.php'); }}
}
$pageTitle='Créer un compte'; require __DIR__.'/../includes/header.php';
?>
<section class="auth-page"><div class="auth-card"><span class="eyebrow">Rejoignez-nous</span><h1>Créer un compte</h1><?php if($error): ?><div class="alert error"><?= esc($error) ?></div><?php endif; ?><form method="post"><input type="hidden" name="csrf" value="<?= csrfToken() ?>"><label>Nom complet<input name="name" required value="<?= esc($_POST['name']??'') ?>"></label><label>Adresse e-mail<input type="email" name="email" required value="<?= esc($_POST['email']??'') ?>"></label><label>Téléphone<input name="phone" value="<?= esc($_POST['phone']??'') ?>"></label><label>Mot de passe<input type="password" name="password" minlength="8" required></label><button class="btn btn-full">Créer mon compte</button></form><p class="auth-switch">Déjà inscrit ? <a href="/login.php">Se connecter</a></p></div></section>
<?php require __DIR__.'/../includes/footer.php'; ?>
