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
	if (isset($_GET['search']) ){
		$all_resource = get_resource_with_search($classid,safeGet('search'));
	} else if (!isset($_GET['category'])){
		$all_resource = get_resource($classid);
	} else {
		$all_resource = get_resource_with_category($classid,safeGet('category'));
	}
	$email_array = array();
	foreach ($all_resource as $resource) {
		array_push($email_array, $resource['uploader']);
	}
	$realname_array = get_realnames_with_emails($email_array);
	for ($i = 0; $i < count($all_resource); $i ++) {
		$resource = $all_resource[$i];
		$filename = $resource['name'];
		$downloadtimes = $resource['downloadtimes'];
		$uploader = $realname_array[$i];
		$description = $resource['description'];
		$category = $resource['category'];
		if ($description == "") {
			$description = "空";
		}
		echo "<a target='_blank' href='resource_download.php?category=".$category."&filename=".$filename."'><h3>".$resource['name']."</a>";
		echo '<h4>上传者:'.$uploader.'</h4>';
		echo '<h4>下载次数:'.$downloadtimes.'</h4>';
		echo '<h4>资源描述:</h4>';
		echo "<p>".$description."</p>";
		if ($is_class_manager) {
?>
		<a style="text-decoration:none;" href=<?php echo "resource_remove.php?category=".$category."&&name=".$filename ?> ><button class="btn btn-primary btn-sm">删除</button></a>
<?php
		}
	}
?>
