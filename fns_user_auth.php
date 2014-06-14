<?php
include_once ('phpmailer/class.phpmailer.php');

function register($realname, $password, $email, $stuid, $accessday) {
	if (!check_valid_password($password)) {
		throw new Exception('password illegal');
	}
	if (!check_valid_email($email)) {
		throw new Exception('email illegal');
	}
	$conn = db_connect();
	$pre_salt = rand_string();
	$post_salt = rand_string();
	$query = "insert into student values('".$email."','".sha1($pre_salt+$password+$post_salt)."','".$realname."','".$stuid."','".$accessday."')";
	$result = $conn->query($query);
	if (!$result) {
		throw new Exception('email occupied');
	}
	$query = "insert into salt values('".$email."','".$pre_salt."','".$post_salt."')";
	$result = $conn->query($query);
	if (!$result) {
		throw new Exception('email occupied');
	}
	return true;
}

function login($email, $password) {
	$conn = db_connect();
	$query = "select * from salt where email='".$email."'";
	$result = $conn->query($query);
	if (!$result) {
		throw new Exception('salt value error');
	}
	if ($result->num_rows != 1) {
		throw new Exception('email not exist (salt)');
	}
	$salt_array = $result->fetch_assoc();
	$query = "select * from student where email='".$email."'";
	$result = $conn->query($query);
	if (!$result) {
		throw new Exception('student database error');
	}
	if ($result->num_rows != 1) {
		throw new Exception('email not exist (salt)');
	}
	$user = $result->fetch_assoc();
	if (sha1($salt_array['pre_salt']+$password+$salt_array['post_salt']) != $user['password']) {
		throw new Exception('password Error');
	}
	$_SESSION['email']    = $user['email'];
	$_SESSION['stuid']    = $user['stuid'];
	$_SESSION['realname'] = $user['realname'];
}

function get_user_ip() {
	if (isset($_SERVER['HTTP_CLIENT_IP']) && $_SERVER['HTTP_CLIENT_IP'] != 'unknown') {
		$ip = $_SERVER['HTTP_CLIENT_IP'];
	} else if (isset($_SERVER['HTTP_X_FORWARDED_FOR']) && $_SERVER['HTTP_X_FORWARDED_FOR'] != 'unknown') {
		$ip = $_SERVER['HTTP_X_FORWARDED_FOR'];
	} else {
		$ip = $_SERVER['REMOTE_ADDR'];
	}
	return $ip;
}

function get_email_by_cookie($sso) {
	$conn = db_connect();
	$query = "SELECT * FROM cookie where sso ='".$sso."'";
	$result = $conn->query($query);
	if (!$result) {
		throw new Exception("查询cookie失败");
	}
	if ($result->num_rows > 0) {
		$row = $result->fetch_assoc();
		return $row['useremail'];
	}
	return "";
}

function set_session($email) {
	$conn = db_connect();
	$query = "SELECT * FROM student where email ='".$email."'";
	$result = $conn->query($query);
	if (!$result) {
		throw new Exception("会话连接失败");
	}
	if ($result->num_rows == 0) {
		throw new Exception("不存在的邮箱地址");
	}
	$row = $result->fetch_assoc();
	$_SESSION['email']    = $row['email'];
	$_SESSION['stuid']    = $row['stuid'];
	$_SESSION['realname'] = $row['realname'];
}

function set_cookie($sso, $email, $ip, $time) {
	db_nonreturn_operation("insert into cookie values('".$sso."','".$email."','".$ip."','".$time."')", "Error:".__FUNCTION__);
}

function delete_cookie($sso) {
	db_nonreturn_operation("delete  from cookie where sso='".$sso."'", "Error:".__FUNCTION__);
}

function update_profile($email, $prepassword, $password, $name) {
	$conn = db_connect();
	$query = "select * from salt where email='".$email."'";
	$result = $conn->query($query);
	if (!$result) {
		throw new Exception('salt value error');
	}
	if ($result->num_rows != 1) {
		throw new Exception('email not exist (salt)');
	}
	$salt_array = $result->fetch_assoc();
	$pre_salt  = $salt_array['pre_salt'];
	$post_salt = $salt_array['post_salt'];
	$query = "select * from student where email='".$email."'";
	$result = $conn->query($query);
	if (!$result) {
		throw new Exception('student database error');
	}
	if ($result->num_rows != 1) {
		throw new Exception('email not exist (salt)');
	}
	$user = $result->fetch_assoc();
	if (sha1($pre_salt+$prepassword+$post_salt) != $user['password']) {
		throw new Exception('password Error');
	}
	if ($password != "") {
		$query = "update student set password='".sha1($pre_salt+$password+$post_salt)."', realname='".$name."' where email='".$email."'";
	} else {
		$query = "update student set realname='".$name."' where email='".$email."'";
	}
	$result = $conn->query($query);
	if (!$result) {
		throw new Exception("修改资料失败");
	}
	$_SESSION['realname'] = $name;
}

/*
 * send a email to $address
 * the subject is $subject, the body is $body
 * if error to send, throw an exception with error message
 */
function send_email($address, $subject, $body) {
	$mail = new PHPMailer();
	$mail->IsSMTP();// telling the class to use SMTP
	$mail->SMTPAuth   = true;// enable SMTP authentication
	$mail->SMTPSecure = "ssl";// sets the prefix to the servier
	$mail->Host       = "smtp.163.com";// sets GMAIL as the SMTP server
	$mail->Port       = 465;// set the SMTP port for the GMAIL server
	$mail->Username   = "leagueofclass@163.com";// GMAIL username
	$mail->Password   = "loc2014";// GMAIL password
	$mail->SetFrom('leagueofclass@163.com', '班级联盟');//设置发件人
	$mail->Body    = $body;//邮件内容
	$mail->Subject = $subject;//邮件主题
	$mail->AddAddress($address, $address);//添加收件人
	//$mail->AddAttachment("images/phpmailer.gif");	  // attachment
	//$mail->AddAttachment("images/phpmailer_mini.gif"); // attachment

	//email post not active
	if (!$mail->Send()) {
		throw new Exception("Error to Send Email:".$mail->ErrorInfo);
	}
	return true;
}

/*
 * send a email to $address_array
 * the subject is $subject, the body is $body
 * if error to send, throw an exception with error message
 */
function send_emails($address_array, $subject, $body) {
	$mail = new PHPMailer();
	$mail->IsSMTP();// telling the class to use SMTP
	$mail->SMTPAuth   = true;// enable SMTP authentication
	$mail->SMTPSecure = "ssl";// sets the prefix to the servier
	$mail->Host       = "smtp.163.com";// sets GMAIL as the SMTP server
	$mail->Port       = 465;// set the SMTP port for the GMAIL server
	$mail->Username   = "leagueofclass@163.com";// GMAIL username
	$mail->Password   = "loc2014";// GMAIL password
	$mail->SetFrom('leagueofclass@163.com', '班级联盟');//设置发件人
	$mail->Body    = $body;//邮件内容
	$mail->Subject = $subject;//邮件主题
	foreach ($address_array as $address) {
		$mail->AddAddress($address, $address);
	}
	//$mail->AddAttachment("images/phpmailer.gif");	  // attachment
	//$mail->AddAttachment("images/phpmailer_mini.gif"); // attachment

	//email post not active
	if (!$mail->Send()) {
		throw new Exception("Error to Send Email:".$mail->ErrorInfo);
	}
	return true;
}

?>
