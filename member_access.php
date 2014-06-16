<?php
	header("Content-Type:text/html;charset=utf-8"); 
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
	$class_name = get_classname_with_classid($classid);
	if($admin == -1) {
		middlepage("classes.php", "你不在本班级中");
	}
	if ($admin >= 2) {
		middlepage("classes.php","权限不足");
	}
	if (!isset($_GET['noticeid'])) {
		middlepage("classes.php","你穿越了吧？");
	}
	$noticeid = safeGet('noticeid');
	try {
		$notice_info = get_newmembernotice_info($noticeid);
		if (isset($_GET['action']) && safeGet('action')=="accept") {
			accept_newmembernotice($classid, $notice_info['applicant'], 2, $noticeid);
			send_email($notice_info['applicant'],"班级联盟：恭喜你成功加入新的班级！",$class_name."的管理员同意了你的申请！");
		} else {
			reject_newmembernotice($noticeid);
			send_email($notice_info['applicant'],"班级联盟：你的班级加入申请被拒",$class_name."的管理员拒绝了你的申请。");
		}
		middlepage("main.php?page=5","操作成功");
	} catch (Exception $e) {
		middlepage("classes.php",$e->getMessage());
	}
?>
