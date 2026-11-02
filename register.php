<?php
require_once __DIR__.'/includes/auth.php';
$error='';
if ($_SERVER['REQUEST_METHOD']==='POST') {
    $users=read_data(USERS_FILE);
    $name=trim($_POST['name']??''); $email=strtolower(trim($_POST['email']??''));
    $sid=trim($_POST['student_id']??''); $course=trim($_POST['course']??'');
    $year=trim($_POST['year_level']??''); $password=$_POST['password']??'';
    foreach($users as $u) if(strtolower($u['email'])===$email || ($sid!=='' && ($u['student_id']??'')===$sid)) $error='Email or student ID is already registered.';
    if(!$error && (strlen($name)<2 || !filter_var($email,FILTER_VALIDATE_EMAIL) || strlen($password)<8)) $error='Enter valid details. Password must contain at least 8 characters.';
    if(!$error) {
        $users[]=['id'=>next_id($users),'name'=>$name,'email'=>$email,'password'=>password_hash($password,PASSWORD_DEFAULT),'role'=>'student','student_id'=>$sid,'course'=>$course,'year_level'=>$year,'created_at'=>date('c')];
        write_data(USERS_FILE,$users); set_flash('Registration successful. You can now log in.'); redirect('login.php');
    }
}
layout_start('Student Registration');
if($error) echo '<div class="alert error">'.e($error).'</div>';
?>
<form method="post" class="panel">
<input type="hidden" name="csrf" value="<?=e(csrf_token())?>">
<div class="form-grid">
<div><label>Full name</label><input name="name" required></div>
<div><label>School email</label><input type="email" name="email" required></div>
<div><label>Student ID</label><input name="student_id" required></div>
<div><label>Course / Program</label><input name="course" required></div>
<div><label>Year level</label><select name="year_level" required><option value="">Select year</option><?php foreach(['1st Year','2nd Year','3rd Year','4th Year','5th Year'] as $v) echo '<option>'.e($v).'</option>'; ?></select></div>
<div><label>Password (at least 8 characters)</label><input type="password" name="password" minlength="8" required></div>
</div><button class="btn">Register</button>
</form>
<?php layout_end(); ?>
