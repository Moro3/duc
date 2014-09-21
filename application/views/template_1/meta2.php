<meta http-equiv='Content-Type' content='text/html; charset=utf-8'>
<META Http-Equiv='Cache-Control' Content='no-cache'>
<META Http-Equiv='Pragma' Content='no-cache'>
<META Http-Equiv='Expires' Content='0'>
<?php
  if(isset($id_page)){
    $current_pages_data = $pages->get_data_id($id_page);
    if(isset($current_pages_data['title'])){
      echo "<title>".htmlspecialchars($current_pages_data['title'])."</title>";
    }
    if(isset($current_pages_data['description'])){
      echo "<meta name=\"description\" content=\"".htmlspecialchars($current_pages_data['description'])."\">";
    }
    if(isset($current_pages_data['keywords'])){
      echo "<meta name=\"keywords\" content=\"".htmlspecialchars($current_pages_data['keywords'])."\">";
    }
  }else{
     echo "<title>ГБОУ ДЮЦ Государственное образовательное учреждение Детско-юношеский центр</title>";
     echo "<meta name=\"description\" content=\"Детско-юношеский центр, ГБОУ ДЮЦ, государственное образоветельное учрждение, ул. Дорожная, ДЮЦ, Чертаново ДЮЦ, Чертаново ГОУ ДЮЦ, Чертаново Детско-юношеский центр\">";
     echo "<meta name=\"keywords\" content=\"Детско-юношеский центр, ГБОУ ДЮЦ, государственное образоветельное учрждение, ул. Дорожная, ДЮЦ, Чертаново ДЮЦ, Чертаново ГОУ ДЮЦ, Чертаново Детско-юношеский центр\">";
  }
