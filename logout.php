<!DOCTYPE html>
<html lang="zh-cn">
<?php
	include_once('include_fns.php');
	session_start();
	if(!isset($_SESSION['email'])){
		header("Location: index.php");
	} else {
		unset($_SESSION['email']);
		unset($_SESSION['stuid']);
		unset($_SESSION['realname']);
		session_destroy();
		if (isset($_COOKIE['sso'])) {
			delete_cookie($_COOKIE['sso']);
		}
		setcookie("sso", "", time()-3600);
		header("Location: index.php");
	}
?>
</html>
