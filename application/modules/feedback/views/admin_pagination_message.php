<?php


  if(!empty($pagination['total_page']) && $pagination['total_page'] > 1){
    echo "<div id=\"pagination\">";
    echo "<div id=\"name\">";
      echo lang('form_pagination_pages');
    echo "</div>";
    for($i=1; $i<=$pagination['total_page']; $i++){
      if($i == $pagination['cur_page']){
        echo "<div id=\"page_select\">";
        echo "".$i."";
      }else{
        echo "<div id=\"page\">";
        echo "<a href=\"".$uri['module'].uri_replace($uri['message'],$i,'feedback')."\">".$i."</a>";
      }
      echo "</div>";
    }

    echo "<div id=\"clear\"></div>";
    echo "</div>";
  }


