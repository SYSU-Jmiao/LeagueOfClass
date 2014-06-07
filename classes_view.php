<?php
	include_once('include_fns.php');
	session_start();
	if (!isset($_SESSION['email'])) {
		middlepage("index.php","请先登录");
	}
	$email = $_SESSION['email'];
	$realname = $_SESSION['realname'];
	if (!isset($_GET['search'])) {
		$search = "";
	} else {
		$search = safeGet('search');
	}
	if ($search == 'mine') {
		$all_class = get_my_classes($email);
?>
<div class="bs-callout bs-callout-info">
	<h4>已加入的班级</h4>
</div>
<br>
<?php
	} else {
		$select_class = get_search_classes($search);
		$my_class = get_my_classes($email);
		$all_class = array();
		$other_class = array();
		for ($i = 0; $i < count($select_class); $i ++) {
			$todo = $select_class[$i];
			$flag = 0;
			foreach ($my_class as $mine) {
				if ($mine['classid'] == $todo['classid']) {
					$todo['admin'] = $mine['admin'];
					$flag = 1;
					break;
				}
			}
			if ($flag == 1) {
				array_push($all_class, $todo);
			} else {
				array_push($other_class, $todo);
			}
		}
		foreach ($other_class as $other) {
			array_push($all_class, $other);
		}
	}
	foreach($all_class as $classes) {//start foreach
?>
		<div  style="vertical-align: middle">
			<img class="img-rounded" src="./img/miao.png" 
			width="50px" height="50px" style="display:inline-block;">
			<p style="font-size:18px;display:inline-block;
			text-indent: 1em"><?php echo $classes['classname'] ?></p>
<?php if (isset($classes['admin']) &&  $classes['admin'] >= 0 && $classes['admin'] <= 2) { ?>
			<a style="text-decoration:none;" href=<?php echo "main.php?classid=".$classes['classid'] ?> ><button type="button" class="btn btn-success" style="display:inline-block;float:right;margin-top:20px;width:70px">进入</button></a>
<?php } else { ?>
			<a style="text-decoration:none;" href=<?php echo "classes.php?action=".$classes['classid'] ?> ><button type="button" class="btn btn-primary" style="display:inline-block;float:right;margin-top:20px;width:80px">申请加入</button></a>
<?php } ?>
			<hr>
		</div>
<?php
	}//end foreach
