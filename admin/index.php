<!DOCTYPE html>
<html lang="zh-cn" >
<head>
	<meta charset="utf-8">
	<meta http-equiv="X-UA-Compatible" content="IE=edge">
	<meta name="viewport" content="width=device-width, initial-scale=1.0">
	<meta name="description" content="">
	<meta name="author" content="">
	<link rel="shortcut icon" href="img/logo.jpg">
	<title>League of Class - Administrator Page</title>
	<link href="./dist/css/bootstrap.min.css" rel="stylesheet">
	<link href="./dist/css/offcavans.css" rel="stylesheet">
	<link href="./dist/css/doc.css" rel="stylesheet">
	<link href="./dist/css/github.min.css" rel="stylesheet">
	<script src="./dist./js/holder.min.js"></script>
	<script src="./dist/js/jquery-2.1.1.min.js"></script>
</head>
<?php
header("Content-Type:text/html;charset=utf-8"); 
include_once ('./../include_fns.php');
session_start();
if (!isset($_SESSION['isadmin']) && !isset($_POST['serverpassword'])) {
	?>
	<form method="post" action="index.php">
			<table>
				<tr>
					<td>password:</td>
					<td><input type='password' name='serverpassword'></td>
				</tr>
				<tr>
					<td></td>
					<td align='right'><input type='submit' value='Submit'></td>
				</tr>
			</table>
		</form>
	<?php
} else {
	if (isset($_POST['serverpassword']) && safePost('serverpassword') != 'admin') {
		echo "serverpassword error";
	} else if (isset($_GET['noticeid']) && isset($_GET['action'])) {
		$noticeinfo = get_create_class_noticeinfo(safeGet('noticeid'));
		if (safeGet('action') == 'accept') {
			if ($noticeinfo && is_array($noticeinfo)) {
				create_class($noticeinfo['classname'], get_server_date(), $noticeinfo['applicant']);
			}
			send_email($noticeinfo['applicant'], "班级联盟：恭喜你创建班级成功", "你申请创建的班级：".$noticeinfo['classname']."创建成功！");
		} else {
			send_email($noticeinfo['applicant'], "班级联盟：创建班级失败", "你申请创建的班级：".$noticeinfo['classname']."未通过审核！\n理由可能如下：\n1.你的资料不完整\n2.你填写的班级名不包含院系关键字\n3.已经有类似的班级被创建");
		}
		delete_create_class_notice(safeGet('noticeid'));
		echo "Success";
	} else {
		$_SESSION['isadmin'] = 1;

		$notice_array = get_create_class_notice();
		if (is_array($notice_array) && count($notice_array) > 0) {
			?>
			<table border="1">
			<?php
			foreach (array_reverse($notice_array) as $notice) {
				?>
										<tr>
											<td><?php echo $notice['noticeid']?></td>
											<td><?php echo $notice['classname']?></td>
											<td><?php echo $notice['reason']?></td>
											<td><?php echo $notice['applicant']?></td>
				<?php echo "<td><a href=index.php?noticeid=".$notice['noticeid']."&action=accept>Accept</a></td>";?>
											<?php echo "<td><a href=index.php?noticeid=".$notice['noticeid']."&action=reject>Reject</a></td>";?>
				</tr>
				<?php
			}
			?>
			</table>
			<?php
		} else {
			echo "No Notice found.";
		}
		?>
					<?php
	}
}
?>
	</html>
