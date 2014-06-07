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
	if ($admin == -1) {
		unset($_SESSION['classid']);
		middlepage("classes.php", "你不是本班成员");
	}
	if($admin  > 1 ) {
		middlepage("view_class_chat.php", "权限不足");
	}
	if(!isset($_GET['chatid'])) {
		middlepage("main.php?page=4", "未选择消息");
	}
	$toremove = safeGet('chatid');
	if (!find_chat($classid, $toremove)) {
		middlepage("main.php?page=4","你穿越了吧？");
	} else {
		remove_chat($toremove);
		middlepage("main.php?page=4","成功删除");
	}
?>
