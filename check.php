<?php
require './static/conn.php';

$username = trim($_POST['username']);
$password = $_POST['password'];
$captcha = $_POST['captcha'];

if (!$username) {
    exit(json_encode(['code' => 1, 'msg' => '用户名不能为空']));
}
if (!$password) {
    exit(json_encode(['code' => 1, 'msg' => '密码不能为空']));
}
if (!$captcha) {
    exit(json_encode(['code' => 1, 'msg' => '验证码不能为空']));
}

session_start();
if (strtolower($_SESSION["captcha"])  != strtolower($captcha)) {
    exit(json_encode(['code' => 1, 'msg' => '验证码错误']));
}
$admin = $pdo->query("SELECT * from admin where username='{$username}'")->fetch(PDO::FETCH_ASSOC);

if (!$admin) {
    exit(json_encode(['code' => 1, 'msg' => '用户名不存在']));
}

if ($admin['password'] != md5($username . $password)) {
    exit(json_encode(['code' => 1, 'msg' => '密码错误']));
}

$lastlogin = time();
$item = $pdo->exec("UPDATE admin set lastlogin='{$lastlogin}' where id='{$admin['id']}'");
$admin = $pdo->query("SELECT * from admin where  id='{$admin['id']}'")->fetch(PDO::FETCH_ASSOC);
$_SESSION["admin"] = $admin;



exit(json_encode(['code' => 0, 'msg' => '验证成功']));