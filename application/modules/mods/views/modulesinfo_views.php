
<?php
echo "<div class=\"padding:20px;font-size:22px;\">Модули:</div>";
foreach($modules as $item){
  echo "<a href=\"{$uri['admin']}\">$item</a><br />";
}
?>
