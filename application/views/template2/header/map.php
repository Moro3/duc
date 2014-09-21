<table width="270" height="30" cellpadding="0" >
	<tr align="center">
	<td ></td>
	<?php
	   if($this->bufer->pages['uri'] != '/'){
	?>
	<td width="25"><a href="/"><img src="<?php echo assets_img('home.gif', false) ?>" alt="главная" width="11" height="10"></a></td>
	<td width="50" class="menu1"><a href="/" class=menu2>главная</a></td>
	<td width="1" height="1"><img src="<?php echo assets_img('01.gif', false) ?>" width="1" height="30"></td>
	<?php
	   }
	?>
	<td width="25"><a href="mailto:info@helpa.ru"><img src="<?php echo assets_img('mail.gif', false) ?>" alt="почта" width="13" height="9"></a></td>
	<td width="43" class="menu1"><a href="mailto:info@helpa.ru" class=menu2>почта</a></td>
	<!--
	<td width="1"><img src="<?php echo assets_img('01.gif', false) ?>" width="1" height="30"></td>
	<td width="25"><a href="/maps/"><img src="<?php echo assets_img('map.gif', false) ?>" alt="карта сайта" width="10" height="10"></a></td>
	<td width="70" class="menu1"><a href="/maps/" class=menu2>карта сайта</a></td>
	-->
	</tr>
</table>