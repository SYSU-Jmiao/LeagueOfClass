<?php
	include_once('include_fns.php');
	session_start();
	$classid = -1;
	if(!isset($_SESSION['email'])){
		middlepage("index.php", "请先登录");
	}
	if (!isset($_POST['prepassword'])) {
		middlepage("main.php?page=6","请输入密码");
	}
	$prepassword = safePost('prepassword');
	if (!isset($_POST['password'])) {
		$password = "";
	} else {
		$password = safePost('password');
		if ($password != "") {
			if (!isset($_POST['password2'])) {
				middlepage("main.php?page=6","请重复密码");
			}
			if ($password != safePost('password2')) {
				middlepage("main.php?page=6","两次密码不一致");
			}
		}
	}
	$email = $_SESSION['email'];
	$realname = $_SESSION['realname'];
	if (!isset($_POST['name'])) {
		$name = $realname;
	} else {
		$name = safePost('name');
	}
	try {
		update_profile($email, $prepassword, $password, $name);
		middlepage("main.php","修改资料成功");
	} catch(Exception $e) {
		middlepage("main.php?page=6",$e->getMessage());
	}
?>