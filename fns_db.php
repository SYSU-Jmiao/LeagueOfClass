<?php
function db_connect() {

	$result = new mysqli("localhost", "jmiao", "", "leagueofclass");
	if (mysqli_connect_errno()) {
		printf("Connect failed: %s\n", mysqli_connect_error());
	}
	if (!$result) {
		throw new Exception('Error to Connect to The Database');
	} else {
		return $result;
	}
}
function db_nonreturn_operation($query, $exception) {
	$conn = db_connect();
	if (!$conn->query($query)) {
		throw new Exception($exception);
	}
}
function db_single_return_operation($query, $exception) {
	$conn = db_connect();
	$result = $conn->query($query);
	if (!$result) {
		throw new Exception($exception);
	}
	if ($result->num_rows == 0) {
		throw new Exception("no element.");
	}
	$row = $result->fetch_assoc();
	foreach ($row as $ret);
	return $ret;
}
function db_single_array_return_operation($query, $exception) {
	$conn = db_connect();
	$result = $conn->query($query);
	if (!$result) {
		throw new Exception($exception);
	}
	if ($result->num_rows == 0) {
		throw new Exception("no element.");
	}
	return $result->fetch_assoc();
}
function db_array_return_operation($query, $exception) {
	$conn = db_connect();
	$result = $conn->query($query);
	if (!$result) {
		throw new Exception($exception);
	}
	$ret = array();
	for ($i = 0; $i < $result->num_rows; ++$i) {
		$ret[$i] = $result->fetch_assoc();
	}
	return $ret;
}
function create_salt_value($useremail, $pre_salt, $post_salt) {
	db_nonreturn_operation("insert into salt values(".$useremail.",".$pre_salt.",".$post_salt.")", "Error:".__FUNCTION__);
}
function get_server_datetime() {
	return db_single_return_operation("select now()", "Error:".__FUNCTION__);
}
function get_server_date() {
	return db_single_return_operation("select curdate()", "Error:".__FUNCTION__);
}
function get_my_classes($email) {
	$conn = db_connect();
	$result = $conn->query("select classid, admin from authority where useremail='".$email."'");
	if (!$result) {
		throw new Exception("获取我的班级失败");
	}
	$my_class = array();
	for ($i = 0; $i < $result->num_rows; ++$i) {
		$my_class[$i] = $result->fetch_assoc();
	}
	for ($i = 0; $i < count($my_class); ++$i) {
		$query = "select * from class where classid='".$my_class[$i]['classid']."'";
		$result = $conn->query($query);
		if ($result && $result->num_rows > 0) {
			$row = $result->fetch_assoc();
			$my_class[$i]['classname'] = $row['classname'];
			$my_class[$i]['createday'] = $row['createday'];
		}
	}
	return $my_class;
}
function get_all_classes() {
	return db_array_return_operation("select * from class", "Error:".__FUNCTION__);
}
function get_search_classes($category) {
	return db_array_return_operation("select * from class where classname like '%".$category."%'", "Error:".__FUNCTION__);
}
function create_create_class_notice($classname, $reason, $useremail) {
	db_nonreturn_operation("insert into createclassnotice values(null,'".$classname."','".$useremail."','".$reason."')", "Error:".__FUNCTION__);
}
function get_create_class_notice() {
	return db_array_return_operation("select * from createclassnotice", "Error:".__FUNCTION__);
}
function create_class($classname, $createday, $creator) {
	$conn = db_connect();
	$result = $conn->query("insert into class values(null,'".$classname."','".$createday."')");
	if (!$result) {
		throw new Exception('Error to create class');
	}
	$query = "insert into authority values('".$creator."','".$conn->insert_id."',0)";
	$result = $conn->query($query);
	if (!$result) {
		throw new Exception('Error to create class');
	}
}
function get_create_class_noticeinfo($noticeid) {
	return db_single_array_return_operation("select * from createclassnotice where noticeid='".$noticeid."'", "Error:".__FUNCTION__);
}
function delete_create_class_notice($noticeid) {
	db_nonreturn_operation("delete from createclassnotice where noticeid=".$noticeid, "Error:".__FUNCTION__);
}
function get_authority($classid, $email) {
	try {
		$ret = db_single_return_operation("select admin from authority where useremail='".$email."' and classid='".$classid."'", "Error:".__FUNCTION__);
		return $ret;
	} catch (Exception $e) {
		return -1;
	}
}
function get_news($classid) {
	return db_array_return_operation("select newsid, content, author, posttime from classnews where classid='".$classid."' order by posttime desc", "Error:".__FUNCTION__);
}
function find_news($classid, $newsid) {
	$array = db_array_return_operation("select * from classnews where $classid='".$classid."' and newsid='".$newsid."'", "Error:".__FUNCTION__);
	return count($array) == 1;
}
function remove_news($newsid) {
	db_nonreturn_operation("delete from classnews where newsid='".$newsid."'", "Error:".__FUNCTION__);
}
function get_news_info($newsid) {
	return db_single_array_return_operation("select * from classnews where newsid='".$newsid."'", "Error".__FUNCTION__);
}
function update_news($toedit, $content, $time) {
	db_nonreturn_operation("update classnews set content='".$content."', posttime='".$time."' where newsid='".$toedit."'", "Error:".__FUNCTION__);
}
function create_news($classid, $email, $content, $posttime) {
	db_nonreturn_operation("insert into classnews values(null,'".$classid."','".$email."','".$content."','".$posttime."')", "Error:".__FUNCTION__);
}
function get_chat($classid) {
	return db_array_return_operation("select chatid, content, author, posttime from classchat where classid='".$classid."' order by posttime desc", "Error:".__FUNCTION__);
}
function find_chat($classid, $chatid) {
	$array = db_array_return_operation("select * from classchat where $classid='".$classid."' and chatid='".$chatid."'", "Error:".__FUNCTION__);
	return count($array) == 1;
}
function remove_chat($chatid) {
	db_nonreturn_operation("delete from classchat where chatid='".$chatid."'", "Error:".__FUNCTION__);
}
function get_chat_info($chatid) {
	return db_single_array_return_operation("select * from classchat where chatid='".$chatid."'", "Error".__FUNCTION__);
}
function create_chat($classid, $email, $content, $posttime) {
	db_nonreturn_operation("insert into classchat values(null,'".$classid."','".$email."','".$content."','".$posttime."')", "Error:".__FUNCTION__);
}
function new_resource($name, $postdate, $description, $uploader, $classid, $category) {
	db_nonreturn_operation("insert into resource values('".$name."','".$postdate."','".$description."',0,'".$uploader."','".$classid."','".$category."')", "Error:".__FUNCTION__);
}
function get_resource($classid) {
	return db_array_return_operation("select * from resource where classid='".$classid."'", "Error:".__FUNCTION__);
}
function check_resource($filename, $classid, $category) {
	return db_single_array_return_operation("select * from resource where name='".$filename."' and classid='".$classid."' and category='".$category."'", "Error".__FUNCTION__);
}
function increase_downloadtimes($category, $filename, $classid) {
	db_nonreturn_operation("update resource set downloadtimes=downloadtimes+1  where category='".$category."' and name = '".$filename."' and $classid='".$classid."'", "Error:".__FUNCTION__);
}
function get_classname_with_classid($classid) {
	return db_single_return_operation("select classname from class where classid='".$classid."'", "Error:".__FUNCTION__);
}
function create_newmembernotice($classid, $useremail, $reason) {
	db_nonreturn_operation("insert into newmembernotice values(null,'".$classid."','".$useremail."','".$reason."')", "Error:".__FUNCTION__);
}
function get_newmembernotice($classid) {
	$conn = db_connect();
	$query = "select * from newmembernotice where classid='".$classid."'";
	$result = $conn->query($query);
	$ret = array();
	for ($i = 0; $i < $result->num_rows; ++$i) {
		$ret[$i] = $result->fetch_assoc();
	}
	for ($i = 0; $i < count($ret); ++$i) {
		$query = "select realname from student where email='".$ret[$i]['applicant']."'";
		$result = $conn->query($query);
		$ret[$i]['realname'] = $result->fetch_assoc()['realname'];
	}
	return $ret;
}
function accept_newmembernotice($classid, $applicant, $admin, $noticeid) {
	$conn = db_connect();
	if (!$conn->query("insert into authority values('".$applicant."','".$classid."','".$admin."')")) {
		throw new Exception('Error to access class');
	}
	if (!$conn->query("delete from newmembernotice where noticeid='".$noticeid."'")) {
		throw new Exception("Error to delete notice");
	}
}
function reject_newmembernotice($noticeid) {
	db_nonreturn_operation("delete from newmembernotice where noticeid='".$noticeid."'", "Error:".__FUNCTION__);
}
function get_newmembernotice_info($noticeid) {
	return db_single_array_return_operation("select * from newmembernotice where noticeid='".$noticeid."'", "Error:".__FUNCTION__);
}
function get_realnames_with_emails($email_array) {
	$conn = db_connect();
	$realname_array = array();
	foreach ($email_array as $email) {
		$query = "select realname from student where email='".$email."'";
		$result = $conn->query($query);
		if ($result->num_rows == 0) {
			array_push($realname_array, "匿名");
		} else {
			array_push($realname_array, $result->fetch_assoc()['realname']);
		}
	}
	return $realname_array;
}
function get_realname_with_email($email) {
	return db_single_return_operation("select realname from student where email='".$email."'", "Error:".__FUNCTION__);
}
function get_resource_with_category($classid, $category) {
	return db_array_return_operation("select * from resource where classid='".$classid."' and category='".$category."'", "Error:".__FUNCTION__);
}
function get_resource_with_search($classid, $search) {
	return db_array_return_operation("select * from resource where classid='".$classid."' and (name like '%".$search."%' or description like '%".$search."%')", "Error:".__FUNCTION__);
}
function delete_resource($classid, $category, $filename) {
	db_nonreturn_operation("delete from resource where classid='".$classid."' and category='".$category."' and name='".$filename."'", "Error:".__FUNCTION__);
}
function get_members($classid) {
	$conn = db_connect();
	$query = "select useremail, admin from authority where classid='".$classid."'";
	$result = $conn->query($query);
	$member_array = array();
	for ($i = 0; $i < $result->num_rows; ++$i) {
		$member_array[$i] = $result->fetch_assoc();
	}
	for ($i = 0; $i < count($member_array); ++$i) {
		$query = "select realname from student where email='".$member_array[$i]['useremail']."'";
		$result = $conn->query($query);
		$member_array[$i]['realname'] = $result->fetch_assoc()['realname'];
	}
	return $member_array;
}
function make_manager($classid, $tomanage) {
	db_nonreturn_operation("update authority set admin=1 where classid='".$classid."' and useremail='".$tomanage."'", "Error:".__FUNCTION__);
}
function remove_manager($classid, $tomanage) {
	db_nonreturn_operation("update authority set admin=2 where classid='".$classid."' and useremail='".$tomanage."'", "Error:".__FUNCTION__);
}
function remove_member($classid, $to_remove) {
	db_nonreturn_operation("delete from authority where classid='".$classid."' and useremail='".$to_remove."'", "Error:".__FUNCTION__);
}
function get_manager($classid) {
	return db_array_return_operation("select * from authority where classid='".$classid."' and admin<2", "Error:".__FUNCTION__);
}
?>
