<?php if(!class_exists('raintpl')){exit;}?><?php $tpl = new RainTPL;$tpl_dir_temp = self::$tpl_dir;$tpl->assign( $this->var );$tpl->draw( dirname("header") . ( substr("header",-1,1) != "/" ? "/" : "" ) . basename("header") );?>


<!--
 index
-->
<!--Body content-->

<style>
.off {background-color: #000;}

.white {background-color: #fff;}

.red {background-color: #f00;}

.green {background-color: #0f0;}

.blue {background-color: #00f;}

.yellow {background-color: #ff0;}

.pink {background-color: #F6A8B6;}

.cyan {background-color: #05EDFF;}



</style>

	<div class="container">
		

		<table class="table table-striped  table-bordered">
			<tr>
			<th>Name</th>
			<th>Off</th><th>White</th><th>Red</th><th>Green</th><th>Blue</th><th>Yellow</th><th>Pink</th><th>Cyan</th>
			</tr>
			<?php $counter1=-1; if( isset($leds_value) && is_array($leds_value) && sizeof($leds_value) ) foreach( $leds_value as $key1 => $value1 ){ $counter1++; ?>

			<tr>
			<td><h1><?php echo $value1["name"];?><sup><code><?php echo $value1["code"];?></code></sup></h1></td>
			<td id="-0-<?php echo $value1["code"];?>"   class="color_change off"></td>
			<td id="-1-<?php echo $value1["code"];?>" class="color_change white"></td>
			<td id="-2-<?php echo $value1["code"];?>"   class="color_change red"></td>
			<td id="-3-<?php echo $value1["code"];?>" class="color_change green"></td>
			<td id="-4-<?php echo $value1["code"];?>"  class="color_change blue"></td>
			<td id="-5-<?php echo $value1["code"];?>"  class="color_change yellow"></td>
			<td id="-6-<?php echo $value1["code"];?>"  class="color_change pink"></td>
			<td id="-7-<?php echo $value1["code"];?>"  class="color_change cyan"></td>		
			</tr>
			<?php } ?>

			</table>
	 	</div>

<script src="http://ajax.googleapis.com/ajax/libs/jquery/1.9.1/jquery.min.js"></script>
<script>
$(".color_change").click(function() {
command = this.id.split("-");
color = command[1];
code = command[2];
$.ajax({
			type: "POST",
			url: "actions.php",
			data: { code:code, color: color}
			}).done(function( html ) {
			$('#alert_zone').html("<div class='alert alert-success fade in'><button type='button' class='close' data-dismiss='alert'>&times;</button><span id='alert-text'>"+html+"</span></div>");
});
window.setTimeout(function() { $(".alert").alert('close'); }, 5000);

});



</script>
 	
<?php $tpl = new RainTPL;$tpl_dir_temp = self::$tpl_dir;$tpl->assign( $this->var );$tpl->draw( dirname("footer") . ( substr("footer",-1,1) != "/" ? "/" : "" ) . basename("footer") );?>