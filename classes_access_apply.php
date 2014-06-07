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
		$manager_array = get_manager($classid);
		$address_array = array();
		foreach ($manager_array as $manager) {
			array_push($address_array, $manager['useremail']);
		}
		send_emails($address_array, "班级联盟：有新的成员申请加入班级", $_SESSION['realname']."想加入你管理的班级，进去<a href=http://172.18.159.225/leagueofclass/main.php?classid=".$classid.">审批</a>吧！");
		middlepage("classes.php","申请成功");
	}
?>
