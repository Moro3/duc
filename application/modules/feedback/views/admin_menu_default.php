<div id="menu">
  <?php
    echo "<div id=\"menu_main\">";
    foreach($get['menu'] as $key_main_menu=>$menu_main){
      if($uri['index'][1] == $menu_main['link']){
        echo "<a id=\"select_menu\" href=\"".$uri['point'].uri_replace($uri['menu'],$menu_main['link'])."\">".$menu_main['name']."</a>";
      }else{
        echo "<a id=\"menu\" href=\"".$uri['point'].uri_replace($uri['menu'],$menu_main['link'])."\">".$menu_main['name']."</a>";
      }
    }
    echo "</div>";
    echo "<div id=\"sub_menu\">";
    if(isset($get['sub_menu'][$uri['index'][1]])){

      foreach($get['sub_menu'][$uri['index'][1]] as $key_sub_menu=>$sub_menu){
        if(isset($uri['index'][2]) && $uri['index'][2] == $sub_menu['link']){
          echo "<a id=\"select_menu\" href=\"".$uri['point'].uri_replace($uri['sub_menu'],array($sub_menu['link']))."\">".$sub_menu['name']."</a>";
        }else{
          echo "<a id=\"menu\" href=\"".$uri['point'].uri_replace($uri['sub_menu'],array($sub_menu['link']))."\">".$sub_menu['name']."</a>";
        }
      }

    }
    echo "</div>";
  ?>
<div style="clear:both;"></div>
</div>

