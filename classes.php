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

<?php
	include_once('include_fns.php');
	session_start();
	if (!isset($_SESSION['email'])) {
		middlepage("index.php","请先登录");
	}
	$email = $_SESSION['email'];
	$realname = $_SESSION['realname'];
	
?>
<body>

<div class="container bs-docs-container">
<div class="row">
	<div class="col-md-3">
		<div class="bs-sidebar hidden-print affix" role="complementary">
		<div class="text-center">
			<h3><?php echo $realname ?></h3> 
		</div> 
		<ul class="nav bs-sidenav" >
			<li class="active" id = "class_3"><a href="#" >已加入班级</a></li>
			<li class="" id = "class_4"><a href="#" >全部班级</a></li>
		</ul>
		<br>
		<ul class="nav bs-sidenav" >
			<li class="" id = "class_1"><a href="#" >修改个人资料</a></li>
			<li class=""><a href="logout.php" >退出登录</a></li>
		</ul>		
		</div>
	</div>
<div class="col-md-9">

<?php if (isset($_GET['action']) ) { // start if aciont is post ?>
<script type="text/javascript">
	$(document).ready(function(){
		$("#class_3").removeClass();
	});
</script>


<?php
	$action = safeGet('action');
	if ($action == 'create_class') { // start if create class
?>
<div class="col-md-9">
<!-- Modal -->
<form method="post" action="classes_create_apply.php">
<div>
	<div class="modal-dialog">
	<div class="modal-content">
		<div class="modal-header">
			<a style="text-decoration:none;" href="classes.php"><button type="button" class="close">&times;</button></a>
			<h4 class="modal-title" id="myModalLabel">创建班级</h4>
		</div>
		<div class="modal-body">
			<p class="help-block">创建班级：<input autofocus="autofocus" type="text" name="classname"></p>
			<p class="help-block">输入申请创建班级的理由：</p>
			<textarea name="reason" class="form-control" rows="5"></textarea>
		</div>
		<div class="modal-footer">
			<a style="text-decoration:none;" href="classes.php"><button type="button" class="btn btn-default">关闭</button></a>
			<input type="submit" value="发送" class="btn btn-primary" />
		</div>
	</div><!-- /.modal-content -->
	</div><!-- /.modal-dialog -->
</div><!-- /.modal -->
</form>
</div>

<?php } else { // else if access class
$classname = get_classname_with_classid($action);
?>

<div class="col-md-9">
<!-- Modal -->
<form method="post" action=<?php echo "classes_access_apply.php?classid=".$action ?> >
<div>
	<div class="modal-dialog">
	<div class="modal-content">
		<div class="modal-header">
			<a style="text-decoration:none;" href="classes.php"><button type="button" class="close">&times;</button></a>
			<h4 class="modal-title" id="myModalLabel">申请加入班级：<?php echo $classname ?></h4>
		</div>
		<div class="modal-body">
			<p class="help-block">申请加入班级的理由：</p>
			<textarea name="reason" class="form-control" rows="5"></textarea>
		</div>
		<div class="modal-footer">
			<a style="text-decoration:none;" href="classes.php"><button type="button" class="btn btn-default">关闭</button></a>
			<input type="submit" value="发送" class="btn btn-primary" />
		</div>
	</div><!-- /.modal-content -->
	</div><!-- /.modal-dialog -->
</div><!-- /.modal -->
</form>
</div>

<?php } ?>


<?php } else { // start if action is not post ?>

<script type="text/javascript">
	var Now_Page = 0;
	$(document).ready(function(){
		$("#main_frame").load("./classes_view.php?search=mine");
		$("#class_1 a").click(function(){
		if (Now_Page != 1){
			$("#main_frame").load("./profile_view.php");
			$("#main_frame").hide();
			$("#main_frame").fadeIn();
			$("#class_1").removeClass();
			$("#class_3").removeClass();
			$("#class_4").removeClass();
			$("#class_1").addClass('active');
		}
		Now_Page = 1;
		return false;
		});
		$("#class_3 a").click(function(){
		if (Now_Page != 3){
			$("#main_frame").load("./classes_view.php?search=mine");
			$("#main_frame").hide();
			$("#main_frame").fadeIn();
			$("#class_1").removeClass();
			$("#class_3").removeClass();
			$("#class_4").removeClass();
			$("#class_3").addClass('active');
		}
		Now_Page = 3;
		return false;
		});
		$("#class_4 a").click(function(){
		
		if (Now_Page != 4){
			$("#main_frame").load("./classes_view_category.php");
			$("#main_frame").hide();
			$("#main_frame").fadeIn();
			$("#class_1").removeClass();
			$("#class_3").removeClass();
			$("#class_4").removeClass();
			$("#class_4").addClass('active');
		}
		Now_Page = 4;
		return false;
		});
	});
</script>

	<div class="col-md-12">
		<div name = "main_frame" id = "main_frame" data-target="#navbarEx" data-offset = "0">

		</div>
		<div class="text-right">
			<a href="?action=create_class" style="font-size:16px; Line-height:3.5"> 还没有我的班级？创建一个 </a>
		</div>
	</div>

<?php } ?>

</div><!--bs-docs-section-->

</div><!--row-->

</div>


</body>
</html>
