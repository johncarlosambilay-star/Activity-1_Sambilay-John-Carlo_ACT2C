<?php
require_once __DIR__.'/includes/auth.php';
if (current_user()) redirect('dashboard.php');
$error='';
if ($_SERVER['REQUEST_METHOD']==='POST') {
    $email=strtolower(trim($_POST['email'] ?? '')); $password=$_POST['password'] ?? '';
    foreach (read_data(USERS_FILE) as $user) {
        if (strtolower($user['email'])===$email && password_verify($password,$user['password'])) {
            session_regenerate_id(true); $_SESSION['user']=$user; redirect('dashboard.php');
        }
    }
    $error='Invalid email or password.';
}
layout_start('Login');
if ($error) echo '<div class="alert error">'.e($error).'</div>';
?>
<img src="assets/bpc-logo.png" alt="BPC Logo" class="logo">
<form method="post" class="panel narrow">
<input type="hidden" name="csrf" value="<?=e(csrf_token())?>">
<label>Email address</label><input type="email" name="email" required>
<label>Password</label><input type="password" name="password" required>
<button class="btn">Login</button>
<p>New student? <a href="register.php">Create an account</a></p>
<p class="hint">Default admin: admin@school.edu / Admin123!</p>
</form>
<?php layout_end(); ?>
