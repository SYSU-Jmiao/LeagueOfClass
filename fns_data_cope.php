<?php
function rand_string($length = 10) {
	$chars = 'abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ0123456789!@#$%^&*()-_[]{}<>~`+=,.;:/?';
	$len = strlen($chars);
	$ret = '';
	for ( $i = 0; $i < $length; $i ++ )  {
		$ret .= $chars[ rand(0, $len - 1) ];
	}
	return $ret;
}
function check_valid_email($email) {
	return preg_match("^[a-zA-Z0-9_\.\-]+@[a-zA-Z0-9\-]+\.[a-zA-Z0-9\-\.]+$^", $email);
}
function check_valid_password($password) {
	$len = strlen($password);
	if ($len < 1) {
		return false;
	}
	$chars = 'abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ0123456789!@#$%^&*()-_[]{}<>~`+=,.;:/?';
	for ( $i = 0; $i < $len; $i ++ )  {
		if (!strpos($chars, $password[$i])) {
			return false;
		}
	}
	return true;
}
function safePost($str) {
	$val = !empty($_POST["$str"]) ? $_POST["$str"]:null;
	// $val = strip_tags($val);
	// 这个好像太严格了
	// $val =htmlentities($val);
	$val = htmlentities($val,ENT_QUOTES,"UTF-8");
	if(!get_magic_quotes_gpc()){
		$val = addslashes($val);
	}
	return $val;
}

function safeGet($str){
	$val = !empty($_GET["$str"]) ? $_GET["$str"]:null;
	if(!get_magic_quotes_gpc()){
		$val = addslashes($val);
	}
	return $val;
}
?>
