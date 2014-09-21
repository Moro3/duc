<?php
$img['order']['up'] = 'order_up.gif';
$img['order']['up_sl'] = 'order_up_sel.gif';
$img['order']['dn'] = 'order_dn.gif';
$img['order']['dn_sl'] = 'order_dn_sel.gif';

if(!empty($theme_arr)){
  echo "<div id=\"all_themes\">";
    echo $this->load->module('feedback/feedback_theme')->pagination_theme();
    echo "<div id=\"menu_list\">";
      echo "<div id=\"id\">";
        echo "<div id=\"field_name\">";
          echo lang('form_theme_id');
        echo "</div>";
        echo "<div id=\"order_by\">";
          echo  "<div id=\"by\">";
            if($order['by'] == 'id' && $order['direction'] == 'asc'){
              $img_order = $img['order']['dn_sl'];
            }else{
              $img_order = $img['order']['dn'];
            }
            echo "<a href=\"".$uri['module'].uri_replace($uri['order'],array('id','asc'),'feedback')."\"><img src=\"/".$path['img']."/$img_order\"></a>";
          echo "</div>";
          echo  "<div id=\"by\">";
            if($order['by'] == 'id' && $order['direction'] == 'desc'){
              $img_order = $img['order']['up_sl'];
            }else{
              $img_order = $img['order']['up'];
            }
            echo "<a href=\"".$uri['module'].uri_replace($uri['order'],array('id','desc'),'feedback')."\"><img src=\"/".$path['img']."/$img_order\"></a>";
          echo "</div>";
        echo "</div>";
      echo "</div>";
      echo "<div id=\"show\">";
        echo "<div id=\"field_name\">";
          echo lang('form_theme_show');
        echo "</div>";
        echo "<div id=\"order_by\">";
          echo  "<div id=\"by\">";
            if($order['by'] == 'show' && $order['direction'] == 'asc'){
              $img_order = $img['order']['dn_sl'];
            }else{
              $img_order = $img['order']['dn'];
            }
            echo "<a href=\"".$uri['module'].uri_replace($uri['order'],array('show','asc'),'feedback')."\"><img src=\"/".$path['img']."/$img_order\"></a>";
          echo "</div>";
          echo  "<div id=\"by\">";
            if($order['by'] == 'show' && $order['direction'] == 'desc'){
              $img_order = $img['order']['up_sl'];
            }else{
              $img_order = $img['order']['up'];
            }
            echo "<a href=\"".$uri['module'].uri_replace($uri['order'],array('show','desc'),'feedback')."\"><img src=\"/".$path['img']."/$img_order\"></a>";
          echo "</div>";
        echo "</div>";
      echo "</div>";

        echo "<div id=\"name\">".lang('form_theme_name')."</div>";
        echo "<div id=\"email\">".lang('form_theme_email')."</div>";
      echo "<div id=\"user\">";
        echo "<div id=\"field_name\">";
          echo lang('form_theme_user');
        echo "</div>";
        echo "<div id=\"order_by\">";
          echo  "<div id=\"by\">";
            if($order['by'] == 'user_name' && $order['direction'] == 'asc'){
              $img_order = $img['order']['dn_sl'];
            }else{
              $img_order = $img['order']['dn'];
            }
            echo "<a href=\"".$uri['module'].uri_replace($uri['order'],array('user_name','asc'),'feedback')."\"><img src=\"/".$path['img']."/$img_order\"></a>";
          echo "</div>";
          echo  "<div id=\"by\">";
            if($order['by'] == 'user_name' && $order['direction'] == 'desc'){
              $img_order = $img['order']['up_sl'];
            }else{
              $img_order = $img['order']['up'];
            }
            echo "<a href=\"".$uri['module'].uri_replace($uri['order'],array('user_name','desc'),'feedback')."\"><img src=\"/".$path['img']."/$img_order\"></a>";
          echo "</div>";
        echo "</div>";
      echo "</div>";

        echo "<div id=\"action\">".lang('form_theme_action')."</div>";
    echo "<div id=\"clear\"></div>";
    echo "</div>";
  $k = 1;
  foreach ($theme_arr as $key=>$value){
    $id_post_del = $this->input->post('id_theme');
    if(isset($id_post_del[$value['id']])){
      echo "<div id=\"list_select_del\">";
    }elseif($k == 1){
      echo "<div id=\"list_select\">";
      if ($k == 1) $k = 0; else $k = 1;
    }else{
      echo "<div id=\"list_select2\">";
      if ($k == 1) $k = 0; else $k = 1;
    }

    echo "<div id=\"list\">";

        echo "<div id=\"id\"><a href=\"".$uri['module'].uri_replace($uri['theme'],$value['id'],'feedback')."\">".$value['id']."</a></div>";

        echo "<div id=\"show\">";
          if($value['show_i'] == 1){
            if(is_file($path['dir_root']."/".$path['img']."/green_button16.png")){
              echo "<img src=\"/".$path['img']."/green_button16.png\" />";
            }else{
              echo lang('form_theme_yes');
            }
          }else{
            if(is_file($path['dir_root']."/".$path['img']."/red_button16.png")){
              echo "<img src=\"/".$path['img']."/red_button16.png\" />";
            }else{
              echo lang('form_theme_not');
            }
          }
        echo "</div>";

        echo "<div id=\"name\">";
          if(!empty($value['name'])){
            echo "<a href=\"".$uri['module'].uri_replace($uri['theme'],$value['id'],'feedback')."\">".$value['name']."</a>";
          }else{
            echo lang('form_theme_not');
          }
        echo "</div>";

        echo "<div id=\"email\">";
          if(!empty($value['email'])){
            echo $value['email'];
          }else{
            echo lang('form_theme_not');
          }
        echo "</div>";

        echo "<div id=\"user\">";
          if(!empty($value['user_name'])){
            echo $value['user_name'];
          }else{
            echo lang('form_theme_not');
          }
        echo "</div>";


        echo "<div id=\"action\">";
          echo "<div id=\"edit\">";
            echo "<a href=\"".$uri['module'].uri_replace($uri['theme'],$value['id'],'feedback')."\"><img src=\"/".$path['img']."/page_edit16.png\" title=\"".lang('form_feedback_edit')."\" /></a>";
          echo "</div>";
          echo "<div id=\"delete\">";
            echo "<form action=\"".$uri['module'].$uri['delete']."\" method=\"POST\" enctype=\"multipart/form-data\">";

            echo form_hidden("id_feedback[".$value['id']."]",$value['id']);
            $set_input['del'] = array(
                  'name'        => "del_feedback[".$value['id']."]",
                  'id'          => 'del_feedback',
                  'value'       => $value['id'],
                  'type'        => 'image',
                  'src'         =>  "/".$path['img']."/page_delete16.png",
                  'title'       => lang('form_feedback_delete'),
                  'alt'       => lang('form_feedback_delete'),
                );
             echo form_submit($set_input['del']);
             echo "</form>";
           echo "</div>";
          //echo "<img src=\"/".$path['img']."/page_delete16.png\" title=\"удалить страницу\" />";
        echo "</div>";
        echo "<div id=\"clear\"></div>";


      echo "</div>";
    echo "</div>";

  }
  echo $this->load->module('feedback/feedback_theme')->pagination_theme();
  echo "</div>";
}



