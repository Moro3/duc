<div class="modules_main">
<?php
assets_style('nav_modules_main.css');

echo "<div class=\"mod_header\">".$lang['admin']['modules'].":</div>";
//echo "<div>";
foreach($modules as $item){
  echo "<div class=\"mod_name\">";
  echo "<a href=\"{$uri['admin']}{$uri['mod']}/".$item['name']."/\">";
  echo '<img src="'.assets_img('admin/folder_process.png', false).'" style="border:0;" />';
  echo "</a>";
  echo "<br /><a href=\"{$uri['admin']}{$uri['mod']}/".$item['name']."/\">";
  echo $item['description'];
  echo "</a>";
  echo "</div>";

}
//echo "<span class=\"modules_name\">- <a href=\"{$uri['admin']}page_manager/str/\">Страницы</a></span><br />";
//echo "</div>";
?>

</div>






