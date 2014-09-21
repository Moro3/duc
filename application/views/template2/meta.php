<?php //header('Content-type: text/html; charset=utf-8')
?>
<meta http-equiv='Content-Type' content='text/html; charset=utf-8'>
<META Http-Equiv='Cache-Control' Content='no-cache'>
<META Http-Equiv='Pragma' Content='no-cache'>
<META Http-Equiv='Expires' Content='0'>
<LINK REL="icon" HREF="/favicon.ico" TYPE="image/x-icon"><LINK REL="SHORTCUT ICON" href="/favicon.ico" TYPE="image/x-icon">

<?php
  $title = $this->meta->get_param('title');
  $keywords = $this->meta->get_param('keywords');
  $description = $this->meta->get_param('description');
  if(!empty($title)){
     echo '<title>'.$title.'</title>';
  }else{
     echo '<title></title>';
  }
  if(!empty($description)){
     echo '<meta name="description" content="'.htmlspecialchars($description).'">';
  }else{
     echo '<meta name="description" content="">';
  }
  if(!empty($keywords)){
     echo '<meta name="keywords" content="'.htmlspecialchars($keywords).'">';
  }else{
     echo '<meta name="keywords" content="">';
  }

