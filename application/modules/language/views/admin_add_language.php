<?php

echo "<div id=\"add\">";
    echo "<form action=\"".$uri['module'].$uri['form']."\" method=\"POST\" enctype=\"multipart/form-data\">";
    //print_r($_POST);
    //echo validation_errors();

    echo "<div id=\"name\">";
    echo lang('form_language_name');
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
        echo lang('form_language_name_help');
      echo "</span>";
      echo "</div>";
    echo "<div id=\"clear\"></div>";

    echo "<div id=\"abbr\">";
    echo lang('form_language_abbr');
    echo "</div><div id=\"abbr_value\">";

      $set_input['abbr'] = array(
              'name'        => 'abbr',
              'id'          => 'abbr',
              'value'       => '',

            );
      if(set_value($set_input['abbr']['name'])){
        $set_input['abbr']['value'] = set_value($set_input['abbr']['name']);
      }
      echo form_input($set_input['abbr']);
      echo form_error($set_input['abbr']['name']);
    echo "<br /><span id=\"help\">";
      echo lang('form_language_abbr_help');
    echo "</span>";
      echo "</div>";
    echo "<div id=\"clear\"></div>";



    echo "<div id=\"submit_add\">";
    echo form_submit('add_new',lang('form_language_add'),'id="submit"');
    echo "</div>";
    echo "</form>";

echo "</div>";



