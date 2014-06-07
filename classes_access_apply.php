<?php
	include_once('include_fns.php');
	session_start();
	if (!isset($_SESSION['email'])) {
		middlepage("index.php","请先登录");
	}
	if(!isset($_GET['classid'])) {
		middlepage("classes.php","请指定加入的班级");
	}
	$classid = safeGet('classid');
	$email = $_SESSION['email'];
	$admin = get_authority($classid,$email);
	if ($admin != -1) {
		middlepage("classes.php","你已经是该班级的成员了！");
	}
	if(!isset($_POST['reason'])) {
		middlepage("classes.php","你穿越了吧？");
	} else {
		create_newmembernotice($classid, $email, safePost('reason'));
		middlepage("classes.php","申请成功");
	}
?>
