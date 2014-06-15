<?php
	header("Content-Type:text/html;charset=utf-8"); 
	include_once("include_fns.php");
	session_start();
	$page = safeGet('page');
	if(!isset($_SESSION['email'])){
		middlepage("index.php", "请先登录");
	}
	$classid = -1;
	if (isset($_SESSION['classid'])) {
		$classid = $_SESSION['classid'];
	}
	if (isset($_GET['classid'])){
		$classid = safeGet('classid');
		$_SESSION['classid'] = $classid;
	}
	if($classid == -1){
		middlepage("classes.php", "请先选择班级");
	}
	$email = $_SESSION['email'];
	$admin = get_authority($classid,$email);
	if ($admin == -1) {
		unset($_SESSION['classid']);
		middlepage("classes.php", "你不是本班成员");
	}
	if (!isset($_POST['subject'])) {
		//$subject = "others";
		middlepage("main.php?page=".$page, "请选择上传资源类型");
	}
	if(!isset($_FILES['file'])){
		middlepage("main.php?page=".$page, "未选择文件");
	}
	$subject = safePost("subject");
	//允许的文件类型
	if ($subject=="course"){
		$allow_type=array("rar","zip","7z","pdf","doc","docx","jpg","png","cpp","asm","img","xls","iso","xlsx","doc","ppt","pptx","txt","jpeg");
	} else if($subject =="video") {
		$allow_type =array("rar","zip","7z","avi","mp4","mp3","hlv","mov","asf","wmv","3gp","mkv","f4v","flv","rmvb","rm","webm");
	} else if ($subject == "software") {
		$allow_type = array("msi","exe","iso","rar","zip","7z","dmg","pvkg","pkg","apk","ipa","xap");
	} else {
		$allow_type=array("rar","zip","7z","pdf","doc","docx","jpg","png","cpp","asm","img","xls","iso","xlsx","doc","ppt","pptx","txt","jpeg","rar","zip","7z","avi","mp4","mp3","hlv","mov","asf","wmv","3gp","mkv","f4v","flv","rmvb","rm","webm","msi","exe","iso","rar","zip","7z","dmg","pvkg","pkg","apk","ipa","xap");
	}
	if (strpos($_FILES['file']['name'],".")===false){
		middlepage("main.php?page=".$page, "非法的文件名！");
	}
	//获取文件的后缀并转换为小写
	$file_end = explode(".",$_FILES["file"]["name"]);
	$file_end = end($file_end);
	$file_end = strtolower($file_end);
	if (!in_array($file_end,$allow_type)) {
		echo "这个文件类型: ". $file_end . "不允许<br />";
		echo "允许的文件格式有：</br>";
		foreach($allow_type as $xxx) {
			echo $xxx . "、 ";
		}
		exit;
	}
	if ($_FILES["file"]["error"] > 0) {
		middlepage("main.php?page=".$page, "文件上传出错！");
	}
	echo "上传: " . $_FILES["file"]["name"] . "<br />";
	echo "类型: " . $_FILES["file"]["type"] . "<br />";
	echo "大小: " . ($_FILES["file"]["size"] / 1024) . " Kb<br />";
	if(!is_dir("./upload")){
		mkdir("./upload");
	}
	if(!is_dir("./upload/$classid")){
		mkdir("./upload/$classid");
	}
	if(!is_dir("./upload/$classid/$subject")){
		mkdir("./upload/$classid/$subject");
	}
	if (file_exists("./upload/$classid/$subject/" . $_FILES["file"]["name"])) {
		middlepage("main.php?page=".$page, $_FILES["file"]["name"]."已经存在");
	} else {
		chmod($_FILES["file"]["tmp_name"], 0777);
		move_uploaded_file($_FILES["file"]["tmp_name"],"./upload/$classid/$subject/" . $_FILES["file"]["name"]);
		echo '<div class= "alert alert-success">';
		echo "上传成功！！！</div>";
		new_resource($_FILES['file']['name'],get_server_datetime(),safePost('description'),$email,$classid,$subject);
		middlepage("main.php?page=".$page,"上传完成");
	}
?>