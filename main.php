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
	$realname = $_SESSION['realname'];
	$admin = get_authority($classid,$email);
	if ($admin == -1) {
		unset($_SESSION['classid']);
		middlepage("classes.php", "你不是本班成员");
	}
	if (!isset($_GET['page'])) {
		$page = 1;
	} else {
		$page = safeGet('page');
	}
	if ($page > 6 || $page < 1) {
		$page = 1;
	}
	$is_class_manager = $admin <= 1;
	$class_name = get_classname_with_classid($classid);
?>

<!DOCTYPE html>
<html lang="zh-cn">
<head>
	<meta charset="utf-8">
	<meta http-equiv="X-UA-Compatible" content="IE=edge">
	<meta name="viewport" content="width=device-width, initial-scale=1.0">
	<meta name="description" content="">
	<meta name="author" content="">
	<link rel="shortcut icon" href="img/logo.jpg">
	<title>League of Class - Main Page</title>
	<link href="./dist/css/bootstrap.min.css" rel="stylesheet">
	<link href="./dist/css/offcavans.css" rel="stylesheet">
	<link href="./dist/css/doc.css" rel="stylesheet">
	<link href="./dist/css/github.min.css" rel="stylesheet">
	<script src="./dist./js/holder.min.js"></script>
	<script src="./dist/js/jquery-2.1.1.min.js"></script>
</head>
<body>
<script type="text/javascript">
$(document).ready(function(){
<?php if ($page == 1) { ?>
	$("#class_1").addClass('active');
<?php } else if ($page == 2) { ?>
	$("#class_2").addClass('active');
<?php } else if ($page == 3) { ?>
	$("#class_3").addClass('active');
<?php } else if ($page == 4){ ?>
	$("#class_4").addClass('active');
<?php } else if ($page == 5){ ?>
	$("#class_5").addClass('active');
	$("#main_frame").load("./member_view.php");
	$("#main_frame").hide();
	$("#main_frame").fadeIn();
<?php } else if ($page == 6){ ?>
	$("#class_6").addClass('active');
	$("#main_frame").load("./profile_view.php");
	$("#main_frame").hide();
	$("#main_frame").fadeIn();
<?php } ?>
});
</script>
	<div class="container bs-docs-container">
		<div class="row">
			<div class="col-md-3">
				<div class="bs-sidebar hidden-print affix" role="complementary">
					<div class="text-center">
						<h3><?php echo $realname ?></h3> 
					</div> 
					<ul class="nav bs-sidenav" >
						<li class="" id = "class_1"><a href="?page=1" ><?php echo $class_name; ?></a></li>
						<li class="" id = "class_2"><a href="?page=2" >消息通知</a></li>
						<li class="" id = "class_3"><a href="?page=3" >资源共享</a></li>
						<li class="" id = "class_4"><a href="?page=4" >自由发言</a></li>
						<li class="" id = "class_5"><a href="?page=5" >班级成员</a></li>
					</ul>
					<br>
					<ul class="nav bs-sidenav" >
						<li class="" id="class_6"><a href="?page=6" >修改个人资料</a></li>
						<li class=""><a href="logout.php" >退出登录</a></li>
					</ul>
					<br>
					<a href="classes.php"><button type="button" class="btn btn-link">返回班级选择</button></a>
				</div>
			</div>
			<div class="col-md-9" role="main">
				<div name = "main_frame" id = "main_frame" data-target="#navbarEx" data-offset = "0">

<?php
if ($page == 1) {
?>
<div class="bs-callout bs-callout-info">
	<h4>班级动态</h4>
	<p>建设中？</p>
	<classimg></classimg>
	<classprofile></classprofile>
</div>

<div class="bs-docs-section">
	<p>建设中？</p>
	<p>建设中？</p>
	<p>建设中？</p>
	<p>建设中？</p>
	<p>建设中？</p>
	<p>建设中？</p>
	<p>各种水，各种水</p>
	<p>各种水，各种水</p>
	<p>各种水，各种水</p>
	<p>各种水，各种水</p>
 </div>

<!--ul class="pager">
	<li class="previous disabled"><a href="#">&larr; Older</a></li>
	<li class="next"><a href="#">Newer &rarr;</a></li>
</ul-->
<?php } else if ($page == 2) { ?>
<div class="bs-callout bs-callout-warning">
	<h4>消息通知</h4>
	<p>这里会发布最新的消息通知</p>
<?php
if (!isset($_GET['newsid']) && $is_class_manager) { ?>
	<button class="btn btn-primary btn-sm" data-toggle="modal" data-target="#myModal">发条通知</button>
<?php } ?>
</div>

<?php if (isset($_GET['newsid'])) {
	$toedit = safeGet('newsid');
?>
<form method="post" action=<?php echo "news_edit.php?newsid=".$toedit ?> >
	<div class="modal-dialog">
	<div class="modal-content">
		<div class="modal-header">
			<a style="text-decoration:none;" href="main.php?page=2" ><button type="button" class="close" aria-hidden="true">&times;</button></a>
			<h4 class="modal-title" id="myModalLabel">编辑通知</h4>
		</div>
		<div class="modal-body">
			<p class="help-block">修改通知内容：</p>
			<textarea autofocus="autofocus" name="content" id="news" class="form-control" rows="5"><?php echo get_news_info($toedit)['content'] ?></textarea>
		</div>
		<div class="modal-footer">
			<a style="text-decoration:none;" href="main.php?page=2" ><button type="button" class="btn btn-default">关闭</button></a>
			<input type="submit" value="发送" class="btn btn-primary" />
		<!--button type="button" value="post" class="btn btn-primary">发送</button-->
		</div>
	</div><!-- /.modal-content -->
	</div><!-- /.modal-dialog -->
</form>
<?php } else {// start !isset($_GET['newsid']) ?>

<!-- Modal -->
<form method="post" action="news_create.php">
<div class="modal fade" id="myModal" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
	<div class="modal-dialog">
	<div class="modal-content">
		<div class="modal-header">
			<button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
			<h4 class="modal-title" id="myModalLabel">发通知，发个够！</h4>
		</div>
		<div class="modal-body">
			<p class="help-block">输入通知内容：</p>
			<textarea autofocus="autofocus" name="news" id="news" class="form-control" rows="5"></textarea>
		</div>
		<div class="modal-footer">
			<button type="button" class="btn btn-default" data-dismiss="modal">关闭</button>
			<input type="submit" value="发送" class="btn btn-primary" />
		</div>
	</div><!-- /.modal-content -->
	</div><!-- /.modal-dialog -->
</div><!-- /.modal -->
</form>

<div class="bs-docs-section">
<?php
	$news_array = get_news($classid);
$email_array = array();
foreach ($news_array as $news) {
	array_push($email_array, $news['author']);
}
$realname_array = get_realnames_with_emails($email_array);
for ($i = 0; $i < count($news_array); $i ++) {
	$news = $news_array[$i];
?>
	<h4><?php echo $news['content']."---".$realname_array[$i]."---".$news['posttime'] ?></h4>
<?php if ($is_class_manager) { // begin if($is_class_manager) ?>
	<a style="text-decoration:none;" href=<?php echo "news_remove.php?newsid=".$news['newsid'] ?> ><button class="btn btn-primary btn-sm">删除</button></a>
	<a style="text-decoration:none;" href=<?php echo "main.php?page=2&&newsid=".$news['newsid'] ?> ><button class="btn btn-primary btn-sm">编辑</button></a>
<?php } //end if($is_class_manager) ?>
<?php
} // end foreach
?>
</div>

<?php } // end else !isset($_GET['newsid']) ?>
<?php } else if ($page == 3) { // start if $page =3 ?>

<div class="bs-callout bs-callout-info">
	<h4>资源共享</h4>
	<p>上传资源的时候最好把描述写一下啦~可以通过空格分隔搜索关键字哟~</p>
	<button class="btn btn-primary btn-sm" data-toggle="modal" data-target="#myModal">上传资源</butto>	
</div>

<!-- Modal -->
<form target="_blank" method="post" action="resource_upload.php" enctype='multipart/form-data'>
<div class="modal fade" id="myModal" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
	<div class="modal-dialog">
	<div class="modal-content">
		<div class="modal-header">
		<button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
		<h4 class="modal-title" id="myModalLabel">上传资源啦</h4>
		</div>
		<div class="modal-body">
			<p class="help-block">点击下面的按钮，从本地选择一个文件上传。</p>
			<select id="filelist" name="subject" id="subject" class="span3 btn" placeholder="未登录无法加载" data-original-title="若未登录无法加载">
			<option value="course">课程</option>
			<option value="video">影音</option>
			<option value="software">软件</option>
			<option value="others">其他</option>
		</select>
		<input type="file" name="file" id="exampleInputFile">
		<br/>
		<p>描述下你上传的资源吧XD</p>
		<textarea name="description" rows="3" style="width:228px">我很懒，所以我什么描述都没写</textarea>
		</div>
		<div class="modal-footer">
		<button type="button" class="btn btn-default" data-dismiss="modal">关闭</button>
		<input type="submit" name="submit" value="上传" class="btn btn-primary" />
		</div>
	</div><!-- /.modal-content -->
	</div><!-- /.modal-dialog -->
</div><!-- /.modal -->
</form>

<script type="text/javascript">
		var now_p = 0;
		var speed = 0;
		$(document).ready(function(){
		$("#main_frame_2").load("resource_view.php");
		$("#cla_1 a").click(function(){
			if (now_p != 5){
			$("#main_frame_2").load("resource_view.php");
			$("#main_frame_2").hide();
			$("#main_frame_2").fadeIn(speed);
			var self=$("#cla_1"); 
			self.parent().find('.active').removeClass('active'); 
			self.addClass('active');
			}
			now_p = 5;
			return false;
		});
		$("#cla_2 a").click(function(){
			if (now_p != 6){
			$("#main_frame_2").load("resource_view.php?category=course");
			$("#main_frame_2").hide();
			$("#main_frame_2").fadeIn(speed);
			var self=$("#cla_2"); 
			self.parent().find('.active').removeClass('active'); 
			self.addClass('active');
			}
			now_p = 6;
			return false;
		});
		$("#cla_3 a").click(function(){
			if (now_p != 7){
			$("#main_frame_2").load("resource_view.php?category=video");
			$("#main_frame_2").hide();
			$("#main_frame_2").fadeIn(speed);
			var self=$("#cla_3"); 
			self.parent().find('.active').removeClass('active'); 
			self.addClass('active');
			}
			now_p = 7;
			return false;
		});
		$("#cla_4 a").click(function(){
			if (now_p != 8){
			$("#main_frame_2").load("resource_view.php?category=software");
			$("#main_frame_2").hide();
			$("#main_frame_2").fadeIn(speed);
			var self=$("#cla_4"); 
			self.parent().find('.active').removeClass('active'); 
			self.addClass('active');
			}
			now_p = 8;
			return false;
		});
		$("#cla_5 a").click(function(){
			if (now_p != 9){
			$("#main_frame_2").load("resource_view.php?category=others");
			$("#main_frame_2").hide();
			$("#main_frame_2").fadeIn(speed);
			var self=$("#cla_5"); 
			self.parent().find('.active').removeClass('active'); 
			self.addClass('active');
			}
			now_p = 9;
			return false;
		});
		$("#search").click(function(){
			var search_content = document.getElementById('search-content').value;
			$("#main_frame_2").load("resource_view.php?search="+search_content.replace(' ','%'));
			$("#main_frame_2").hide();
			$("#main_frame_2").fadeIn(speed);
			var self=$("#cla_1"); 
			self.parent().find('.active').removeClass('active'); 
			self.addClass('active');
			now_p=5;
			return false;
		});
		});
 </script>
<div class="col-lg-12">
<div class="col-lg-8">
<ul class="nav nav-tabs">
	<li class="active" id="cla_1"><a href="#">全部</a></li>
	<li id="cla_2"><a href="#">课程</a></li>
	<li id="cla_3"><a href="#">影音</a></li>
	<li id="cla_4"><a href="#">软件</a></li>
	<li id="cla_5"><a href="#">其他</a></li>
</ul>
</div>
<div class="col-lg-4">
	<div class="input-group">
		<input id="search-content" type="text" class="form-control">
		<span class="input-group-btn">
		<button id="search" class="btn btn-default" type="button">搜索</button>
		</span>
	</div>
</div><!-- /.col-lg-6 -->
</div>
<div name = "main_frame_2" id = "main_frame_2" data-target="#navbarEx" data-offset = "0">

</div>

<!--ul class="pager">
	<li class="previous disabled"><a href="#">&larr; Older</a></li>
	<li class="next"><a href="#">Newer &rarr;</a></li>
</ul-->
<?php } else if ($page == 4 ) { // end if $page=3 start if $page=4 ?>
<div class="bs-callout bs-callout-danger">
	<h4>自由发言</h4>
	<p>你可以匿名，但是你不可以删除你说过的话！</p>
<?php if (!isset($_GET['chatid'])) { ?>
	<button class="btn btn-primary btn-sm" data-toggle="modal" data-target="#myModal">我要发言</button> 
<?php } ?>
</div>

<?php if (!isset($_GET['chatid'])) { // start if !isset _GET['chatid'] ?>
<!-- Modal -->
<form method="post" action="chat_create.php" >
<div class="modal fade" id="myModal" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
	<div class="modal-dialog">
	<div class="modal-content">
		<div class="modal-header">
			<button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
			<h4 class="modal-title" id="myModalLabel">发言喽</h4>
		</div>
		<div class="modal-body">
			<p class="help-block">输入打算发的言：</p>
			<textarea autofocus="autofocus" class="form-control" name="chat" rows="5"></textarea>
			<label class="checkbox"><input type="checkbox" name="unknown">匿名</label>
		</div>
		<div class="modal-footer">
			<button type="button" class="btn btn-default" data-dismiss="modal">关闭</button>
			<input type="submit" value="发送" class="btn btn-primary" />
		</div>
	</div><!-- /.modal-content -->
	</div><!-- /.modal-dialog -->
</div><!-- /.modal -->
</form>

<div class="bs-docs-section">
<?php
$chat_array = get_chat($classid);
$email_array = array();
foreach ($chat_array as $chat) {
	array_push($email_array, $chat['author']);
}
$realname_array = get_realnames_with_emails($email_array);
for ($i = 0; $i < count($chat_array); $i ++) {
	$chat = $chat_array[$i];
?>
	<h4><?php echo $chat['content']."---".$realname_array[$i]."---".$chat['posttime'] ?></h4>
<?php if ($is_class_manager) { ?>
	<a style="text-decoration:none;" href=<?php echo "chat_remove.php?chatid=".$chat['chatid'] ?> ><button class="btn btn-primary btn-sm">删除</button></a>
<?php } ?>
	<a style="text-decoration:none;" href=<?php echo "main.php?page=4&&chatid=".$chat['chatid'] ?>  ><button class="btn btn-primary btn-sm">回复</button></a>
<?php }//end foreach ?>
</div>
<?php } else { // else if isset($_GET['chatid'])
$chatid = safeGet('chatid');
?>

<form method="post" action="chat_create.php" >
	<div class="modal-dialog">
	<div class="modal-content">
		<div class="modal-header">
			<a style="text-decoration:none;" href="main.php?page=4" ><button type="button" class="close" aria-hidden="true">&times;</button></a>
			<h4 class="modal-title" id="myModalLabel">发言喽</h4>
		</div>
		<div class="modal-body">
			<p class="help-block">回复：</p>
			<textarea autofocus="autofocus" name="chat" class="form-control" rows="5"><?php echo "回复".get_realname_with_email(get_chat_info($chatid)['author']).":"; ?></textarea>
			<p align="right"><label><input align="right" type="checkbox" name="unknown">匿名<p></label>
		</div>
		<div class="modal-footer">
			<a style="text-decoration:none;" href="main.php?page=4" ><button type="button" class="btn btn-default">关闭</button></a>
			<input type="submit" value="发送" class="btn btn-primary" />
		</div>
	</div><!-- /.modal-content -->
	</div><!-- /.modal-dialog -->
</form>

<?php } ?>

<!--ul class="pager">
	<li class="previous disabled"><a href="#">&larr; Older</a></li>
	<li class="next"><a href="#">Newer &rarr;</a></li>
</ul-->
<?php } // end if $page=4 ?>
				</div>
			</div>
		</div><!--/row-->
	</div><!--/.container-->
	<script src="./dist/js/bootstrap.min.js"></script>
	<script src="./dist/js/offca.js"></script>
</body>
</html>
