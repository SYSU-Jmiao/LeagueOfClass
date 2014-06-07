<?php
	include_once("include_fns.php");
	session_start();
	if(!isset($_SESSION['email'])){
		middlepage("index.php", "请先登录");
	}
	$classid = -1;
	if (isset($_SESSION['classid'])) {
		$classid = $_SESSION['classid'];
	}
	if (isset($_GET['classid'])){
		$classid = safeGet('classid');
		$_SESSION['classid'] = $classid;
	}
	if($classid == -1){
		middlepage("classes.php", "请先选择班级");
	}
	$email = $_SESSION['email'];
	$admin = get_authority($classid,$email);
	if ($admin == -1) {
		unset($_SESSION['classid']);
		middlepage("classes.php", "你不是本班成员");
	}
	if ($admin > 1) {
		middlepage("main.php?page=3","权限不足");
	}
	if (!isset($_GET['category']) || !isset($_GET['name'])) {
		middlepage("main.php?page=3", "你穿越了吧？");
	} else {
		$category = safeGet('category');
		$filename = safeGet('name');
		$ext = "./upload/$classid/$category/$filename";
		if (file_exists($ext)) {
			unlink ($ext);
		}
		delete_resource($classid,$category,$filename);
		middlepage("main.php?page=3", "删除成功！");
	}
?>
