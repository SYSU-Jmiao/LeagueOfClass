<?php
	include_once('include_fns.php');
	session_start();
	$classid = -1;
	if(!isset($_SESSION['email'])){
		middlepage("index.php", "请先登录");
	}
	if (isset($_SESSION['classid'])) {
		$classid = $_SESSION['classid'];
	}
	if (isset($_GET['classid'])){
		$classid = safeGet('classid');
		$_SESSION['classid'] = $classid;
	}
	if($classid == -1){
		middlepage("classes.php", "请选择班级");
	}
	$email = $_SESSION['email'];
	$admin = get_authority($classid,$email);
	if($admin == -1) {
		middlepage("classes.php", "你不在本班级中");
	}
	if ($admin != 0) {
		middlepage("main.php","权限不足！");
	}
	if (!isset($_GET['email'])) {
		middlepage("main.php", "你穿越了吧？");
	}
	$his_email = safeGet('email');
	$his_admin = get_authority($classid,$his_email);
	if ($his_admin == -1 || $his_admin==0) {
		middlepage("main.php","你穿越了吧？");
	}
	if (!isset($_GET['action']) || safeGet('action')==0) {
		remove_manager($classid,$his_email);
	} else {
		make_manager($classid,$his_email);
	}
	middlepage("main.php?page=5","操作成功！");
?>
