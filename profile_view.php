<?php
	include_once('include_fns.php');
	session_start();
	$classid = -1;
	if(!isset($_SESSION['email'])){
		middlepage("index.php", "请先登录");
	}
	$email = $_SESSION['email'];
	$realname = $_SESSION['realname'];
?>

<form method="post" action="profile_edit.php">
	<div class="bs-callout bs-callout-info" style="vertical-align: middle">
		<h4 style="display:inline-block">修改个人资料</h4>
	</div>
	<br>
	<div style="vertical-align: middle">
	<h4 style="display:inline-block">修改名字</h4>
		<input type="text" name="name" class="form-control" placeholder=<?php echo $realname ?> style="width: 300px">
		<hr>
	</div>
	<div  style="vertical-align: middle">
		<h4 style="display:inline-block">修改密码</h4>
		<input type="password" name="prepassword" class="form-control" placeholder="输入现在的密码" style="width: 300px">
		<br>
		<input type="password" name="password" class="form-control" placeholder="输入想改成的密码" style="width: 300px">
		<br>
		<input type="password" name="password2" class="form-control" placeholder="再次输入想改成的密码" style="width: 300px">
		<hr>
	</div>
	<br>
	<input type="submit" value="保存" class="btn btn-primary" />
</form>
