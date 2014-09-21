<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN" "http://www.w3.org/TR/html4/loose.dtd">
<html>
<head>
<?php
	@include('meta.php');
	include('before_style.php');
    //include('before_script.php');
    include('style.php');
    include('script.php');
?>


</head>
<body >
<DIV id="ramb" style="position:absolute; left:1px; top:1px; width:1px; height:1px; z-index:1">
<!--LiveInternet counter--><script type="text/javascript"><!--
document.write("<img src='http://counter.yadro.ru/hit?r"+
escape(document.referrer)+((typeof(screen)=="undefined")?"":
";s"+screen.width+"*"+screen.height+"*"+(screen.colorDepth?
screen.colorDepth:screen.pixelDepth))+";u"+escape(document.URL)+
";"+Math.random()+
"' width=1 height=1 alt=''>")//--></script><!--/LiveInternet-->
</DIV>
<div class="full">

	<div class="main">

	<table width="100%" height="100%" cellpadding="0" >
	<tr>
	<td valign="top">
		<?php
			//@include('header.php');
			echo $header;
		?>
		<table width="100%" cellpadding="0">
		<tr>
		<td width="20%" valign="top">
			<?php
				@include('navigation/navigation_fix.php');
                echo $orders_online;
				@include('navigation.php');
			?>
		</td>

		<td width="80%" valign="top">
		<table width="100%" cellpadding="0">
			<tr>
			<td width="3%" background="<?php echo assets_img('05.gif', false) ?>"><img src="<?php echo assets_img('00.gif', false) ?>" width="1" height="1">
			</td>

			<td width="97%" align="left" background="<?php echo assets_img('05.gif', false) ?>">
				<table height="305" width="100%" cellspacing="0" cellpadding="0" border="0">
					<tbody>
					<tr style="height:37px">
						<td>
						<?php
							include('navigation_top.php');
						?>
						</td>
					</tr>
					<tr>
						<?php
							//include('banner.php');
							echo $banner;
						?>
					</tr>

					<tr>
						<?php
							//include('content_contact.php');
							echo $content_contact;
						?>
					</tr>
					</tbody>
				</table>
			</td>
			</tr>

			<tr>
			<td width="3%" valign="top" class="content">&nbsp;</td>
			<td width="97%" align="left" valign="top" class="content">
			<?php
				//include('content_help.php');
			?>
			<?php
				//include('content.php');
	            //include('../../users/breadcrumbs/breadcrumbs.php');


				echo $content;
                if(isset($modules['content'])){                	echo $modules['content'];

                }
	            //include('content_help/social_yandex.php');
	            //include('content_help/social_yandex_new.php');
			?>

			</td>
			</tr>
			<tr>
			<td>&nbsp;</td>
			<td>&nbsp;</td>
			</tr>
		</table>

		</td>
		</tr>
		</table>
    </td>
	</tr>
	<?php
	if(isset($partners)){		echo '<tr>';
		echo '<td>';
			echo $partners;
		echo '</td>';
		echo '</tr>';
	}
	?>
	<tr>
	<td>
		<?php
			include('footer.php');
		?>
	</td>
	</tr>
	</table>
	</div>
</div>
</body>
</html>