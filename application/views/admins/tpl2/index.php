<?php
  	include('doctype.php');
?>
<html xmlns="http://www.w3.org/1999/xhtml" xml:lang="en" lang="en">
<head>
<?php
    include('meta.php');
    include('before_style.php');
    include('before_script.php');
    include('style.php');
    include('script.php');
    assets_img('fon_header.jpg');
    assets_img('fon_footer.gif');

    assets_img('fon_block_tl.gif');
    assets_img('fon_block_line.gif');
    assets_img('fon_block_tr.gif');

?>
</head>
<body>
	<div class='a_full'>
        <div class="wrap">
	 		<div class='a_block_up1'>
		 		<div class='a_block'>
			 		<div class='header'>
		                        <?php
		                            echo $header;
		    			?>
			 		</div>
				</div>
	        </div>

	        <div class='a_block_up2'>
	 			<div class='a_block_up3'>
		 			<div class='a_block'>
			 			<div class='navigation'>
			                <?php
			                            echo $navigation;
			    			?>

			 			</div>

			 			<div class='content'>
			                        <?php
			                            echo $content;
			    			?>

			 			</div>
		 			</div>
	 			</div>
	        </div>

	        <div class='a_block_up3'>
            </div>
	        <div class="empty"></div>
	    </div>

        <div class='a_block_up4'>
 			<div class='a_block'>
	 			<div class='footer'>
	            	<?php
	            		echo $footer;
	    			?>
	 			</div>
 			</div>
        </div>

	</div>
</body>
</html>