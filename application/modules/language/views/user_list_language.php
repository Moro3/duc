<?php


if(isset($language_arr)){
  echo "<div id=\"language_user\">";
  foreach($language_arr as $key=>$value){
    if(is_file($path['dir_root'].'/'.$path['thumb']['micro'].'/'.$value['flag'])){
      echo "<div id=\"user_list\">";
        if($lang_default == $value['id']){
          echo "<a href=\"/\"><img src=\"/".$path['thumb']['micro'].'/'.$value['flag']."\" /></a>";
        }else{
          echo "<a href=\"/".$value['abbr']."\"><img src=\"/".$path['thumb']['micro'].'/'.$value['flag']."\" /></a>";
        }
      echo "</div>";
    }
  }
  echo "</div>";
}

