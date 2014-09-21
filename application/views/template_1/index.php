<!DOCTYPE HTML PUBLIC "-//W3C//DTD XHTML 4.01 Transitional//EN" "http://www.w3.org/TR/html4/loose.dtd">
<html>
<head>
<?php
    include('meta.php');
    //include('before_style.php');
    //include('before_script.php');
    include('style.php');
    include('script.php');
    assets_img('fon_header.jpg');
    assets_img('fon_footer.gif');

    assets_img('fon_block_tl.gif');
    assets_img('fon_block_line.gif');
    assets_img('fon_block_tr.gif');

    //-- фон под блок контента
    assets_img('fon/smile00005.gif');

?>
</head>
<body>
	<div style="display: none;">
    	<div class="b-modal" id="exampleModal">
        	<div class="b-modal_close arcticmodal-close">X</div>
        	<?php
				echo $modal_popup;
			?>
    	</div>
	</div>


	<div class='full'>

	 		<div class='block_up1'>
		 		<div class='header'>
	                        <?php
	                            echo $header;
	    			?>
		 		</div>
	        </div>

           <div class='nav_bar'>
             <nav>
		        <div class='block_up2'>

		 			<div class='navigation'>

		            <?php
		                            echo Modules::run('adverts/adverts/tpl_user_switch');
		                            echo $navigation;
		    			?>

		 			</div>

		        </div>
            </nav>
         </div>

	        <div class='block_up3'>
	 			<div class='content'>
	                        <?php
	                            echo $content;
	    			?>


	 			</div>
	        </div>



        <div class='block_up4'>
 			<div class='footer'>
            	<?php
            		echo $footer;
    			?>
 			</div>
        </div>

	</div>
</body>
</html>