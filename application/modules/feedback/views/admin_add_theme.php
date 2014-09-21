<?php

echo "<div id=\"add_theme\">";
    echo "<form action=\"".$uri['module'].$uri['form']."\" method=\"POST\" enctype=\"multipart/form-data\">";
    //print_r($_POST);
    //echo validation_errors();

    echo "<div id=\"name\">";
    echo lang('form_theme_name');

    echo "</div><div id=\"name_value\">";

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
      echo "<br /><span id=\"help\">";
        echo lang('form_theme_name_help');
      echo "</span>";
      echo "</div>";
    echo "<div id=\"clear\"></div>";

    echo "<div id=\"email\">";
    echo lang('form_theme_email');
    echo "</div><div id=\"email_value\">";

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
    echo "<br /><span id=\"help\">";
      echo lang('form_theme_email_help');
    echo "</span>";
      echo "</div>";
    echo "<div id=\"clear\"></div>";

    echo "<div id=\"user\">";
    echo lang('form_theme_user');
    echo "</div><div id=\"user_value\">";

      $set_input['user'] = array(
              'name'        => 'user',
              'id'          => 'user',
              'value'       => '',

            );
      if(set_value($set_input['user']['name'])){
        $set_input['user']['value'] = set_value($set_input['user']['name']);
      }
      echo form_input($set_input['user']);
      echo form_error($set_input['user']['name']);
      echo "<br /><span id=\"help\">";
        echo lang('form_theme_user_help');
      echo "</span>";
      echo "</div>";
    echo "<div id=\"clear\"></div>";


    echo "<div id=\"submit_add\">";
    echo form_submit('add_new',lang('form_theme_add'),'id="submit"');
    echo "</div>";
    echo "</form>";

echo "</div>";




