<div class='block_left'>
 	<?php
 		include('block_left/index.php');
 	?>
</div>

<div class='block_center_inside'>
    <?php
 		if(isset($contents['content'])){
        	echo $contents['content'];
    	}
    	if(isset($modules['content'])){    		echo $modules['content'];
    	}

 	?>
</div>

