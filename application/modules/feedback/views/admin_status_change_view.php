<?php
  if(is_array($status)){
    foreach($status as $key=>$value){
      $out = '';
      switch($key){
        case 'not_change':
          if($value == true) $out .= "<div id=\"change_ok\">Нет изменений!</div>";
        break;
        case 'update':
          if($value == true) $out .= "<div id=\"change_ok\">Изменения успешно записаны!</div>";
          if($value == false) $out .= "<div id=\"change_error\">Не удалось записать изменения!</div>";
        break;
        case 'insert':
          if($value == true) $out .= "<div id=\"change_ok\">Запись успешно добавлена!</div>";
          if($value == false) $out .= "<div id=\"change_error\">Не удалось добавить запись!</div>";
        break;
        case 'delete':
          if($value == true) $out .= "<div id=\"change_ok\">Запись успешно удалена!</div>";
          if($value == false) $out .= "<div id=\"change_error\">Не удалось удалить запись!</div>";
        break;
      }
    }
  }
  if(isset($out)) echo "$out";

