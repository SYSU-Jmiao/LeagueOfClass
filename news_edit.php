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
		middlepage("main.php?page=2", "权限不足");
	}
	if(!isset($_GET['newsid'])) {
		middlepage("main.php?page=2", "未选择消息");
	}
	$toedit = safeGet('newsid');
	if (!find_news($classid, $toedit) || !isset($_POST['content'])) {
		middlepage("main.php?page=2","你穿越了吧？");
	} else {
		update_news($toedit, safePost('content'), get_server_datetime());
		middlepage("main.php?page=2","编辑成功");
	}
?>
