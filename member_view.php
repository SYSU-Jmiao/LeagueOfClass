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
	$is_class_manager = $admin <= 1;
	$members = get_members($classid);
?>

<div class="bs-callout bs-callout-info" style="vertical-align: middle">
	<h4 style="display:inline-block">班级成员</h4>
</div>
<br>

<?php if ($is_class_manager) {
	$notices = get_newmembernotice($classid);
	foreach ($notices as $notice) {
?>
	<p style="font-size:18px;display:inline-block;text-indent:1em; color:red;"><?php echo $notice['realname']."申请加入班级" ?></p>
	<br/>
	<p style="font-size:18px;display:inline-block;text-indent:1em; color:red;"><?php echo "附言：".$notice['reason'] ?></p>
	<br/>
	<a style="text-decoration:none;" href=<?php echo "member_access?action=accept&&noticeid=".$notice['noticeid'] ?> ><button type="button" class="btn btn-danger" style="display:inline-block;float:right;width:70px">同意</button></a>
	<a style="text-decoration:none;" href=<?php echo "member_access?noticeid=".$notice['noticeid'] ?> ><button type="button" class="btn btn-primary" style="display:inline-block;float:right;position: relative;right:10px">拒绝</button></a>
	<br/>
	<hr/>
<?php
	}
}
?>

<?php foreach ($members as $member) {
	if ($member['useremail'] == $email) {
?>
<div style="vertical-align: middle">
	<p style="font-size:18px;display:inline-block;text-indent: 1em"><?php echo $member['realname']; ?></p>
	<button type="button" class="btn btn-primary disabled" style="display:inline-block;float:right;position: relative;right:10px">自己</button>
</div>
<hr>
<?php
	} else {
?>
<div style="vertical-align: middle">
	<p style="font-size:18px;display:inline-block;text-indent: 1em"><?php echo $member['realname']; ?></p>
<?php if ($admin == 0){ ?>
	<a style="text-decoration:none;" href=<?php echo "member_remove?email=".$member['useremail']?> ><button type="button" class="btn btn-danger" style="display:inline-block;float:right;width:70px">踢出</button></a>
<?php if ($member['admin'] == 1) { ?>
	<a style="text-decoration:none;" href=<?php echo "member_manage?action=0&&email=".$member['useremail']?> ><button type="button" class="btn btn-primary" style="display:inline-block;float:right;position: relative;right:10px">取消管理员</button></a>
<?php } else { ?>
	<a style="text-decoration:none;" href=<?php echo "member_manage?action=1&&email=".$member['useremail']?> ><button type="button" class="btn btn-primary" style="display:inline-block;float:right;position: relative;right:10px">设置为管理员</button></a>
<?php } ?>
	<hr>
<?php } else if ($admin == 1) { ?>
<?php if ($member['admin'] == 2) { ?>
	<a style="text-decoration:none;" href=<?php echo "member_remove?email=".$member['useremail']?> ><button type="button" class="btn btn-primary" style="display:inline-block;float:right;position: relative;right:10px">踢出</button></a>
<?php } else { ?>
	<button type="button" class="btn btn-primary disabled" style="display:inline-block;float:right;position: relative;right:10px">已是管理员</button>
<?php } ?>
<?php } ?>

</div>
<?php
	}
?>
	<br/>
<?php
}
?>
