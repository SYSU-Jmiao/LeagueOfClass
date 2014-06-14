<?php
	include_once('include_fns.php');
	session_start();
	$classid = -1;
	$page = safeGet('page');
	if(!isset($_SESSION['email'])){
		middlepage("index.php", "请先登录");
	}
	if (isset($_SESSION['classid'])) {
		$classid = $_SESSION['classid'];
	}
	if (isset($_GET['classid'])){
		$classid = $_GET['classid'];
		$_SESSION['classid'] = $classid;
	}
	if($classid == -1){
		middlepage("classes.php", "请选择班级");
	}
	$email = $_SESSION['email'];
	$admin = get_authority($classid,$email);
	if ($admin == -1) {
		unset($_SESSION['classid']);
		middlepage("classes.php", "你不是本班成员");
	}
	if($admin  > 1 ) {
		middlepage("main.php?page".$page, "权限不足");
	}
	if(!isset($_GET['newsid'])) {
		middlepage("main.php?page".$page, "未选择消息");
	}
	$toremove = safeGet('newsid');
	if (!find_news($classid, $toremove)) {
		middlepage("main.php?page".$page,"你穿越了吧？");
	}else {
		remove_news($toremove);
		middlepage("main.php?page".$page,"成功删除");
	}
?>
