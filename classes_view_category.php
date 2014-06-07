<script type="text/javascript">
	var now_p = 0;
	var speed = 0;
	$(document).ready(function(){
	$("#main_frame_2").load("classes_view.php?search=软院");
	$("#cla_1 a").click(function(){
		if (now_p != 5){
			$("#main_frame_2").load("classes_view.php?search=信科");
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
			$("#main_frame_2").load("classes_view.php?search=软院");
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
			$("#main_frame_2").load("classes_view.php?search=传社");
			$("#main_frame_2").hide();
			$("#main_frame_2").fadeIn(speed);
			var self=$("#cla_3"); 
			self.parent().find('.active').removeClass('active'); 
			self.addClass('active');
		}
		now_p = 7;
		return false;
	});
});
</script>

<!--全部班级-->
<div class="bs-callout bs-callout-info">
	<h4 style="display:inline-block;">全部班级</h4>
</div>
<ul class="nav nav-pills" >
	<li class="active" id="cla_2"><a href="#">软院</a></li>
	<li class="" id="cla_1"><a href="#">信科院</a></li>
	<li class="" id="cla_3"><a href="#">传设院</a></li>
</ul>
<br><br>
<div name = "main_frame_2" id = "main_frame_2" data-target="#navbarEx" data-offset = "0">

</div>
