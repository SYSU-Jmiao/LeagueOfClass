<!DOCTYPE html>
<html lang="zh-cn">
<?php
	require_once('include_fns.php');
	session_start();

if (isset($_COOKIE['sso'])) {
	$email = get_email_by_cookie( addslashes($_COOKIE['sso']) );
	if ($email != "") {
		set_session($email);
	}
}

if (isset($_SESSION['email'])) {
	header("Location: classes.php");
	echo "<p><a target='_blank' href='create_class.php'>Create Class</a></p>";
	echo "<p><a target='_blank' href='classes.php'>View Classes</a></p>";
	echo "<p><a href='logout.php'>Log out</a></p>";
} else if (!isset($_POST['email']) || !isset($_POST['password'])) {
?>
<head>
	<meta charset="utf-8">
	<!-- meta http-equiv="X-UA-Compatible" content="IE=edge" -->
	<meta name="viewport" content="width=device-width, initial-scale=1.0">
	<!-- meta name="description" content="" -->
	<!-- meta name="author" content="" -->
	<link rel="shortcut icon" href="img/logo.jpg">

	<title>League of Class - Login</title>

	<!-- Bootstrap core CSS -->
	<link href="./dist/css/bootstrap.css" rel="stylesheet">

	<!-- Custom styles for this template -->
	<link href="./dist/css/signin.css" rel="stylesheet">
	<link href="./dist/css/doc.css" rel="stylesheet">
	<link href="./dist/css/github.min.css" rel="stylesheet">
</head>
<body>
	<div class="container">
		<form class="form-signin" role="form" method="post" action="index.php">
			<h2 class="form-signin-heading">超高校级的登录</h2>
			<input id = "email" type="text" name="email" class="form-control" placeholder="好了啦，邮箱写好呦" required autofocus>
			<input id = "passwd" type="password" name="password" class="form-control" placeholder="这里需要密码啦" required>
			<label class="checkbox"><input type="checkbox" name="remember">不要忘了我</label>
			<button class="btn btn-lg btn-primary btn-block" type="submit" id="login">进去了</button>
		</form>
		<form class="form-signin" role="form" action="register.php">
			<button class="btn btn-lg btn-info btn-block">注册一发</button>
		</form>
	</div>
</body>
<?php
} else {
?>
<head>
	<meta charset="utf-8">
	<meta http-equiv="refresh" content="1;url=index.php"/>
	<title>League of Class - 中间页面</title>
</head>
<body>
<?php
	try {
		$email =safePost('email');
		login($email, safePost('password'));
		if(isset($_POST['remember']) && safePost('remember')=="on") {
			$seed = $email.time().mt_rand(10,500);
			$sso = base64_encode(md5($seed));
			set_cookie($sso, $email, get_user_ip(), time());
			setcookie("sso",$sso,time()+30*24*60*60);
		}
		header("Location: index.php");
	} catch (Exception $e) {
		middlepage("index.php", $e->getMessage());
	}
?>
</body>
<?php
}
?>
</html>