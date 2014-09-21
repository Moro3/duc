<title><?php if(isset($title)) echo stripslashes(str_replace ("\"","&quot;",$title)); ?></title>
<meta http-equiv=Content-Type content="text/html; charset=<?=$this->config->item('charset');?>">
<meta name=Description content="<?php if(isset($description)) echo stripslashes(str_replace ("\"","&quot;",$description)); ?>">
<meta name=Keywords content="<?php if(isset($keywords)) echo stripslashes(str_replace ("\"","&quot;",$keywords)); ?>">
<?php
if(is_file(FCPATH."/favicon.ico")){
  echo "<link rel=\"icon\" href=\"/favicon.ico\" type=\"image/x-icon\">";
  echo "<link rel=\"shortcut icon\" href=\"/favicon.ico\" type=\"image/x-icon\">";
}


?>

	<link rel="icon" href="misc/favicon.png" type="image/png">



