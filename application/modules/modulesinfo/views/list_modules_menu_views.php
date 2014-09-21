<div class="modules">
<?php
assets_style('admin/menu_left.css');

echo "<div class=\"heading\">".$lang['admin']['modules']."</div>";
echo '<ul>';
	foreach($modules as $item){

	  if($menu['select'] == $item['name']){
	    echo '<li class="select">'.$item['short_description'].'</li>';
	  }else{
	    echo '<li><a href="'.$uri['admin'].$uri['mod'].'/'.$item['folder'].'/">'.$item['short_description'].'</a></li>';
	  }
	}
echo '</ul>';
?>

</div>





