<?php
	include_once('include_fns.php');
	session_start();
	if (!isset($_SESSION['email'])) {
		middlepage("index.php", "请先登录");
	}
	if (!isset($_POST['classname']) || !isset($_POST['reason'])) {
		middlepage("你穿越了吧？");
	}
	create_create_class_notice(safePost('classname'), safePost('reason'), $_SESSION['email']);
	middlepage("index.php","请求提交成功");
?>
