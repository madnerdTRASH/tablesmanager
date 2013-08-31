<?php if(!class_exists('raintpl')){exit;}?>	<!--
	 footer
	-->
	<div class="container">
		<footer>
			<hr>
			<p class="muted">Table Manager - Sarrailh Rémi Gpl v3 (c) 2013 <br> No Application</p>
		</footer>
	</div>

	<?php if( $lang != 'default' ){ ?>
	<script <?php echo $lang_file;?> ></script>
	
	<?php } ?>
	<script <?php echo $lang_handler;?> ></script>
	<script <?php echo $jquery;?> ></script>
	
	<script src="http://netdna.bootstrapcdn.com/twitter-bootstrap/2.3.1/js/bootstrap.min.js"></script>

 	<?php if( $tables == 'true' ){ ?>
	 <script <?php echo $tables_link;?>></script>
	 <script <?php echo $image_handler;?>></script>
	<?php } ?>

	<script <?php echo $alert_handler;?>></script>



</body>

</html>