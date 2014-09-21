<?php
//var_dump($message_arr);
if(!empty($theme_arr)){
  echo "<div id=\"id_theme\">";
  echo validation_errors();
  echo Modules::run("feedback/feedback_theme/status_change");
  foreach ($theme_arr as $key=>$value){
    echo "<div id=\"id_list\">";
      echo "<form action=\"".$uri['module'].uri_replace($uri['id'],$value['id'],'feedback')."\" method=\"POST\" enctype=\"multipart/form-data\">";

        echo "<div id=\"id\">";
          echo lang('form_theme_id');
          echo form_hidden('id', $value['id']);
        echo "</div>";
        echo "<div id=\"id_value\">".$value['id']."</div>";
        echo "<div id=\"clear\"></div>";

        echo "<div id=\"show\">".lang('form_theme_show')."</div>";
        echo "<div id=\"show_value\">";
        $set_input['show'] = array(
                'name'        => 'show',
                'id'          => 'show_value',
                'value'       => 1,
                'checked'     => FALSE
              );
          if($value['show_i'] == 1){
            $set_input['show']['checked'] = TRUE;
          }
          if(set_value($set_input['show']['name'])){
            $set_input['show']['checked'] = TRUE;
          }elseif(!set_value($set_input['show']['name']) && set_value('id')){
            $set_input['show']['checked'] = FALSE;
          }elseif($value['show_i'] == 1){
            $set_input['show']['checked'] = TRUE;
          }
          echo form_checkbox($set_input['show']);
          echo form_error($set_input['show']['name']);
        echo "</div>";
        echo "<div id=\"clear\"></div>";

        echo "<div id=\"name\">";
          echo lang('form_theme_name');
        echo "</div>";
        echo "<div id=\"name_value\">";
          $set_input['name'] = array(
                'name'        => 'name',
                'id'          => 'name',
                'value'       => $value['name'],

              );
            if(set_value($set_input['name']['name'])){
              $set_input['name']['value'] = set_value($set_input['name']['name']);
            }
            echo form_input($set_input['name']);
            echo "<br /><span id=\"help\">";
              echo lang('form_theme_name_help');
            echo "</span>";
            echo form_error($set_input['name']['name']);

        echo "</div>";
        echo "<div id=\"clear\"></div>";

        echo "<div id=\"email\">";
          echo lang('form_theme_email');
        echo "</div>";
        echo "<div id=\"email_value\">";
          $set_input['email'] = array(
                'name'        => 'email',
                'id'          => 'email',
                'value'       => $value['email'],

              );
            if(set_value($set_input['email']['name'])){
              $set_input['email']['value'] = set_value($set_input['email']['name']);
            }
            echo form_input($set_input['email']);
            echo "<br /><span id=\"help\">";
              echo lang('form_theme_email_help');
            echo "</span>";
            echo form_error($set_input['email']['name']);
        echo "</div>";
        echo "<div id=\"clear\"></div>";

        echo "<div id=\"user\">";
          echo lang('form_theme_user');
        echo "</div>";
        echo "<div id=\"user_value\">";
          $set_input['user'] = array(
                'name'        => 'user',
                'id'          => 'user',
                'value'       => $value['user_name'],

              );
            if(set_value($set_input['user']['name'])){
              $set_input['user']['value'] = set_value($set_input['user']['name']);
            }
            echo form_input($set_input['user']);
            echo "<br /><span id=\"help\">";
              echo lang('form_theme_user_help');
            echo "</span>";
            echo form_error($set_input['user']['name']);
        echo "</div>";
        echo "<div id=\"clear\"></div>";

        echo "<div id=\"action\">";
           $set_input['save'] = array(
                'name'        => 'save_id',
                'id'          => 'save',
                'value'       => lang('form_theme_save'),
                'type'        => 'image',
                'src'         =>  "/".$path['img']."/page_edit32.png",
                'title'       => lang('form_theme_save'),
                'alt'         => lang('form_theme_save'),
              );
            echo form_submit($set_input['save']);

          //echo "<a href=\"".$uri['module']."/".$get['menu']['pages']['link']."/edit_pages/".$value['id']."\"><img src=\"/".$path['img']."/page_edit32.png\" title=\"редактировать страницу\" /></a>";
            $set_input['del'] = array(
                'name'        => "del_content[".$value['id']."]",
                'id'          => 'del_page',
                'value'       => lang('form_theme_delete'),
                'type'        => 'image',
                'src'         =>  "/".$path['img']."/page_delete32.png",
                'title'       => lang('form_theme_delete'),
                'alt'         => lang('form_theme_delete'),
              );
            echo form_submit($set_input['del']);

          //echo "<img src=\"/".$path['img']."/page_delete32.png\" title=\"удалить страницу\" />";
        echo "</div>";
        echo "<div id=\"clear\"></div>";
        echo "</form>";

    echo "</div>";

  }
  echo "</div>";
}



