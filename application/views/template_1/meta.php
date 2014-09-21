<?php //header('Content-type: text/html; charset=utf-8')
?>
<meta http-equiv='Content-Type' content='text/html; charset=utf-8'>
<META Http-Equiv='Cache-Control' Content='no-cache'>
<META Http-Equiv='Pragma' Content='no-cache'>
<META Http-Equiv='Expires' Content='0'>
<?php
  $title = $this->meta->get_param('title');
  $keywords = $this->meta->get_param('keywords');
  $description = $this->meta->get_param('keywords');
  if(!empty($title)){
     echo "<title>".htmlspecialchars($title)."</title>"; 
  }else{
     echo "<title>ГБОУ ДЮЦ Государственное образовательное учреждение Детско-юношеский центр</title>"; 
  }
  if(!empty($description)){
     echo "<meta name=\"description\" content=\"".htmlspecialchars($description)."\">"; 
  }else{  
     echo "<meta name=\"description\" content=\"Детско-юношеский центр, ГБОУ ДЮЦ, государственное образоветельное учрждение, ул. Дорожная, ДЮЦ, Чертаново ДЮЦ, Чертаново ГОУ ДЮЦ, Чертаново Детско-юношеский центр\">";
  }
  if(!empty($keywords)){
     echo "<meta name=\"keywords\" content=\"".htmlspecialchars($keywords)."\">"; 
  }else{  
     echo "<meta name=\"keywords\" content=\"Детско-юношеский центр, ГБОУ ДЮЦ, государственное образоветельное учрждение, ул. Дорожная, ДЮЦ, Чертаново ДЮЦ, Чертаново ГОУ ДЮЦ, Чертаново Детско-юношеский центр\">";
  }
  
