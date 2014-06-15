<?php
	include_once("include_fns.php");
	session_start();
	$classid = -1;
	$page=safeGet('page');
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
if(isset($_GET['category']) && isset($_GET['filename'])) {
	$category = safeGet('category');
	$filename = safeGet('filename');

	increase_downloadtimes($category,$filename,$classid);
	
	if(file_exists("./upload/".$classid."/".$category."/".$filename)){
		$ext = pathinfo("./upload/".$classid."/".$category."/".$filename,PATHINFO_EXTENSION);
		$ua = $_SERVER['HTTP_USER_AGENT'];
		if(preg_match("/MSIE/",$ua)) {
			$filename = rawurlencode($filename);
		}
		if($ext == "pdf"){
			header("Content-Type:application/pdf");
			header("Content-Disposition:inline;filename=".$filename);
		}
		else if($ext=="jpg" or $ext=="png"){
			header("Content-Type:image");
			header("Content-Disposition:inline;filename=".$filename);
		}else{
			header("Content-Type:application/octet-stream");
			header("Content-Disposition:attachment;filename=".$filename);
		}
		readfile("./upload/".$classid."/".$category."/".$filename);
	}
} else {
	echo "你穿越了？";
}

?>
