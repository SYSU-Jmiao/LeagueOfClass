<?php
	include_once('./../include_fns.php');
	session_start();
	if (!isset($_SESSION['isadmin']) && !isset($_POST['serverpassword']) ){
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
			if (safeGet('action')=='accept'){
				if ($noticeinfo && is_array($noticeinfo) ) {
					create_class($noticeinfo['classname'], get_server_date(), $noticeinfo['applicant']);
				}
				send_email($noticeinfo['applicant'],"班级联盟：恭喜你创建班级成功","你申请创建的班级：".$noticeinfo['classname']."创建成功！");
			} else {
				send_email($noticeinfo['applicant'],"班级联盟：创建班级失败","你申请创建的班级：".$noticeinfo['classname']."未通过审核！\n理由可能如下：\n1.你的资料不完整\n2.你填写的班级名不包含院系关键字\n3.已经有类似的班级被创建");
			}
			delete_create_class_notice(safeGet('noticeid'));
			echo "Success";
		} else {
			$_SESSION['isadmin']=1;
			
			$notice_array = get_create_class_notice();
			if (is_array($notice_array) && count($notice_array) > 0 ) {
?>
			<table border="1">
<?php
				foreach(array_reverse($notice_array) as $notice) {
?>
				<tr>
					<td><?php echo $notice['noticeid'] ?></td>
					<td><?php echo $notice['classname'] ?></td>
					<td><?php echo $notice['reason'] ?></td>
					<td><?php echo $notice['applicant'] ?></td>
					<?php echo "<td><a href=index.php?noticeid=".$notice['noticeid']."&action=accept>Accept</a></td>"; ?>
					<?php echo "<td><a href=index.php?noticeid=".$notice['noticeid']."&action=reject>Reject</a></td>"; ?>
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
