<?php
//var_dump($message_arr);
$img['order']['up'] = 'order_up.gif';
$img['order']['up_sl'] = 'order_up_sel.gif';
$img['order']['dn'] = 'order_dn.gif';
$img['order']['dn_sl'] = 'order_dn_sel.gif';

if(!empty($message_arr)){
  echo "<div id=\"all_feedbacks\">";
    echo $this->load->module('feedback/feedback_message')->pagination_message();
    echo "<div id=\"menu_list\">";
      echo "<div id=\"id\">";
        echo "<div id=\"field_name\">";
          echo lang('form_message_id');
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
      echo "<div id=\"theme\">";
          echo "<div id=\"field_name\">";
            echo lang('form_message_theme');
          echo "</div>";
          echo "<div id=\"order_by\">";
            echo  "<div id=\"by\">";
              if($order['by'] == 'id_theme' && $order['direction'] == 'asc'){
                $img_order = $img['order']['dn_sl'];
              }else{
                $img_order = $img['order']['dn'];
              }
              echo "<a href=\"".$uri['module'].uri_replace($uri['order'],array('id_theme','asc'),'feedback')."\"><img src=\"/".$path['img']."/$img_order\"></a>";
            echo "</div>";
            echo  "<div id=\"by\">";
              if($order['by'] == 'id_theme' && $order['direction'] == 'desc'){
                $img_order = $img['order']['up_sl'];
              }else{
                $img_order = $img['order']['up'];
              }
              echo "<a href=\"".$uri['module'].uri_replace($uri['order'],array('id_theme','desc'),'feedback')."\"><img src=\"/".$path['img']."/$img_order\"></a>";
            echo "</div>";
          echo "</div>";

      echo "</div>";
        echo "<div id=\"name\">".lang('form_message_name')."</div>";

      echo "<div id=\"email\">";
          echo "<div id=\"field_name\">";
            echo lang('form_message_email');
          echo "</div>";
          echo "<div id=\"order_by\">";
            echo  "<div id=\"by\">";
              if($order['by'] == 'email' && $order['direction'] == 'asc'){
                $img_order = $img['order']['dn_sl'];
              }else{
                $img_order = $img['order']['dn'];
              }
              echo "<a href=\"".$uri['module'].uri_replace($uri['order'],array('email','asc'),'feedback')."\"><img src=\"/".$path['img']."/$img_order\"></a>";
            echo "</div>";
            echo  "<div id=\"by\">";
              if($order['by'] == 'email' && $order['direction'] == 'desc'){
                $img_order = $img['order']['up_sl'];
              }else{
                $img_order = $img['order']['up'];
              }
              echo "<a href=\"".$uri['module'].uri_replace($uri['order'],array('email','desc'),'feedback')."\"><img src=\"/".$path['img']."/$img_order\"></a>";
            echo "</div>";
          echo "</div>";
      echo "</div>";

      echo "<div id=\"message\">".lang('form_message_message')."</div>";
      echo "<div id=\"date\">";
        echo "<div id=\"field_name\">";
            echo lang('form_message_date');
          echo "</div>";
          echo "<div id=\"order_by\">";
            echo  "<div id=\"by\">";
              if($order['by'] == 'date' && $order['direction'] == 'asc'){
                $img_order = $img['order']['dn_sl'];
              }else{
                $img_order = $img['order']['dn'];
              }
              echo "<a href=\"".$uri['module'].uri_replace($uri['order'],array('date','asc'),'feedback')."\"><img src=\"/".$path['img']."/$img_order\"></a>";
            echo "</div>";
            echo  "<div id=\"by\">";
              if($order['by'] == 'date' && $order['direction'] == 'desc'){
                $img_order = $img['order']['up_sl'];
              }else{
                $img_order = $img['order']['up'];
              }
              echo "<a href=\"".$uri['module'].uri_replace($uri['order'],array('date','desc'),'feedback')."\"><img src=\"/".$path['img']."/$img_order\"></a>";
            echo "</div>";
          echo "</div>";
      echo "</div>";
        //echo "<div id=\"ip\">".lang('form_message_ip')."</div>";
        echo "<div id=\"action\">".lang('form_message_action')."</div>";
    echo "<div id=\"clear\"></div>";
    echo "</div>";
  $k = 1;
  foreach ($message_arr as $key=>$value){
    $id_post_del = $this->input->post('id_feedback');
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

        echo "<div id=\"id\"><a href=\"".$uri['module'].uri_replace($uri['message'],$value['id'],'feedback')."\">".$value['id']."</a></div>";

        echo "<div id=\"theme\">";
          if(!empty($value['name_theme'])){
            echo "".$value['name_theme']."";
          }else{
            echo lang('form_feedback_not');
          }
        echo "</div>";

        echo "<div id=\"name\">";
          if(!empty($value['name'])){
            echo "<a href=\"".$uri['module'].uri_replace($uri['message'],$value['id'],'feedback')."\">".$value['name']."</a>";
          }else{
            echo lang('form_feedback_not');
          }
        echo "</div>";

        echo "<div id=\"email\">";
          if(!empty($value['email'])){
            echo $value['email'];
          }else{
            echo lang('form_feedback_not');
          }
        echo "</div>";

        echo "<div id=\"message\">";
          if(!empty($value['message'])){
            echo "<a href=\"".$uri['module'].uri_replace($uri['message'],$value['id'],'feedback')."\">".character_limiter(htmlspecialchars($value['message']), 20)."</a>";
          }else{
            echo "<a href=\"".$uri['module'].uri_replace($uri['message'],$value['id'],'feedback')."\">".lang('form_feedback_not')."</a>";;
          }
        echo "</div>";

        echo "<div id=\"date\">";
          if(empty($value['date'])){
            echo lang('form_feedback_not_date');
          }else{
            echo date("d-m-Y",$value['date']);
            echo "<br />";
            echo date("H:i:s",$value['date']);
          }
        echo "</div>";
        /*
        echo "<div id=\"ip\">";
          if(!empty($value['ip'])){
            echo $value['ip'];
          }else{
            echo lang('form_feedback_not');
          }
        echo "</div>";
        */
        echo "<div id=\"action\">";
          echo "<div id=\"edit\">";
            echo "<a href=\"".$uri['module'].uri_replace($uri['message'],$value['id'],'feedback')."\"><img src=\"/".$path['img']."/page_edit16.png\" title=\"".lang('form_message_edit')."\" /></a>";
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
                  'title'       => lang('form_message_delete'),
                  'alt'       => lang('form_message_delete'),
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
  echo $this->load->module('feedback/feedback_message')->pagination_message();
  echo "</div>";
}




