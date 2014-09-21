<?php


echo "<div id=\"feedback_form\">";
    //echo validation_errors();
      echo "<form action=\"".$uri['module']."\" method=\"POST\" enctype=\"multipart/form-data\">";

        echo "<div id=\"name\">";
          echo lang('user_message_name');
        echo "</div>";
        echo "<div id=\"name_value\">";
          $set_input['name'] = array(
                'name'        => 'name',
                'id'          => 'name',
                'value'       => '',

              );
            if(set_value($set_input['name']['name'])){
              $set_input['name']['value'] = set_value($set_input['name']['name']);
            }
            echo form_input($set_input['name']);
            echo form_error($set_input['name']['name']);

        echo "</div>";
        echo "<div id=\"clear\"></div>";

        echo "<div id=\"theme\">";
          echo lang('user_message_theme');
        echo "</div>";
        echo "<div id=\"theme_value\">";
        $set_select['theme'] = ($theme_current) ? $theme_current: 1 ;
        
         
          if(is_array($theme_arr)){
            foreach($theme_arr as $key=>$value){
              $options['theme'][$value['id']] = $value['name'];
              if(set_select('theme',$value['id'])){
                $set_select['theme'] = $value['id'];
              }
            }
          }else{
            $options['theme'][] = '';
          }

          echo form_dropdown('theme', $options['theme'], $set_select['theme']);
          echo form_error($set_select['theme']['name']);

        echo "</div>";
        echo "<div id=\"clear\"></div>";

        echo "<div id=\"email\">";
          echo lang('user_message_email');
        echo "</div>";
        echo "<div id=\"email_value\">";
          $set_input['email'] = array(
                'name'        => 'email',
                'id'          => 'email',
                'value'       => '',

              );
            if(set_value($set_input['email']['name'])){
              $set_input['email']['value'] = set_value($set_input['email']['name']);
            }
            echo form_input($set_input['email']);
            echo form_error($set_input['email']['name']);
        echo "</div>";
        echo "<div id=\"clear\"></div>";

        echo "<div id=\"message\">";
          echo lang('user_message_message');
        echo "</div>";
        echo "<div id=\"message_value\">";
          $set_input['message'] = array(
                'name'        => 'message',
                'id'          => 'message',
                'value'       => '',

              );
            if(set_value($set_input['message']['name'])){
              $set_input['message']['value'] = set_value($set_input['message']['name']);
            }
            echo form_textarea($set_input['message']);
            echo form_error($set_input['message']['name']);
        echo "</div>";
        echo "<div id=\"clear\"></div>";


        echo "<div id=\"add_message\">";
          $set_input['add_message'] = array(
                'name'        => 'add_message',
                'id'          => 'add_message',
                'value'       => lang('user_message_send'),

              );
          echo form_submit($set_input['add_message']);

        echo "</div>";
        echo "<div id=\"clear\"></div>";

     echo "</form>";
echo "</div>";

