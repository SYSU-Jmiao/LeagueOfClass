<?php
function middlepage($address, $info) {
?>
<head>
	<meta charset="utf-8">
	<meta http-equiv="refresh" content="1;url=<?php echo $address; ?>" />
	<link rel="shortcut icon" href="img/logo.jpg">
	<title>League of Class - 中间页面</title>
</head>
<body>
	<p><?php echo $info; ?>, 自动跳转中...</a></p>
</body>
<?php
	exit;
}
?>
