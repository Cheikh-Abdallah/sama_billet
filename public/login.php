<?php
require __DIR__.'/../includes/bootstrap.php';
if(isLoggedIn()) redirect('/index.php');
$error='';
if($_SERVER['REQUEST_METHOD']==='POST') { verifyCsrf(); $email=strtolower(trim($_POST['email']??'')); $password=$_POST['password']??''; $stmt=$pdo->prepare('SELECT * FROM users WHERE email=?'); $stmt->execute([$email]); $user=$stmt->fetch(); if($user && password_verify($password,$user['password'])) { $_SESSION['user']=['id'=>$user['id'],'name'=>$user['name'],'email'=>$user['email'],'role'=>$user['role']]; redirect(isAdmin()?'/admin.php':'/dashboard.php'); } $error='E-mail ou mot de passe incorrect.'; }
$pageTitle='Connexion'; require __DIR__.'/../includes/header.php';
?>
<section class="auth-page"><div class="auth-card"><span class="eyebrow">Bon retour</span><h1>Connexion</h1><?php if($error): ?><div class="alert error"><?= esc($error) ?></div><?php endif; ?><form method="post"><input type="hidden" name="csrf" value="<?= csrfToken() ?>"><label>Adresse e-mail<input type="email" name="email" required></label><label>Mot de passe<input type="password" name="password" required></label><button class="btn btn-full">Se connecter</button></form><p class="auth-switch">Nouveau ? <a href="/register.php">Créer un compte</a></p></div></section>
<?php require __DIR__.'/../includes/footer.php'; ?>
