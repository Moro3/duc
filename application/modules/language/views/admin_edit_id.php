<?php

echo "<div id=\"id_language\">";
  if(isset($arr_language) && is_array($arr_language)){
    echo validation_errors();
    echo Modules::run("pages/pages/status_change");
    $k = 1;
    foreach($arr_language as $key=>$value){
      echo "<div id=\"id_block\">";
        echo "<div id=\"date_create\">".lang('form_language_date_create')."</div>";
        echo "<div id=\"date_create_value\">";
        if(strlen($value['date_create']) > 5){
          echo "<span id=\"know\">";
          echo date("d-m-Y",$value['date_create']);
          echo "<br />";
          echo date("H:i:s",$value['date_create']);
          echo "</span>";
        }else{
          echo "<span id=\"noknow\">".lang('form_language_no_know')."</span>";
        }
        echo "</div>";

        echo "<div id=\"date_update\">".lang('form_language_date_update')."</div>";
        echo "<div id=\"date_update_value\">";
        if(strlen($value['date_update']) > 5){
          echo "<span id=\"know\">";
          echo date("d-m-Y",$value['date_update']);
          echo "<br />";
          echo date("H:i:s",$value['date_update']);
          echo "</span>";
        }else{
          echo "<span id=\"noknow\">".lang('form_language_no_know')."</span>";
        }
        echo "</div>";
        echo "<div id=\"clear\"></div>";

        echo "<form action=\"".$uri['module'].$uri['id']."\" method=\"POST\" enctype=\"multipart/form-data\">";
        echo "<div id=\"id\">".lang('form_language_id')."</div>";
        echo form_hidden("id", $value['id']);

        echo "<div id=\"id_value\">".$value['id']."</div>";
        echo "<div id=\"clear\"></div>";

        echo "<div id=\"name\">".lang('form_language_name')."</div>";
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
            echo form_error($set_input['name']['name']);
        echo "</div>";
        echo "<div id=\"clear\"></div>";

        echo "<div id=\"abbr\">".lang('form_language_abbr')."</div>";
        echo "<div id=\"abbr_value\">";
           $set_input['abbr'] = array(
                'name'        => 'abbr',
                'id'          => 'abbr',
                'value'       => $value['abbr'],

              );
            if(set_value($set_input['abbr']['name'])){
              $set_input['abbr']['value'] = set_value($set_input['abbr']['name']);
            }
            echo form_input($set_input['abbr']);
            echo "<div id=\"clear\"></div>";
            echo "<div id=\"help\">";
              echo lang('form_language_abbr_help');
            echo "</div>";
            echo form_error($set_input['abbr']['name']);
        echo "</div>";
        echo "<div id=\"clear\"></div>";

        echo "<div id=\"description\">";
            echo lang('form_language_description');
        echo "</div>";
        echo "<div id=\"description_value\">";
            $set_input['description'] = array(
                    'name'        => 'description',
                    'id'          => 'description',
                    'value'       => $value['description'],
                  );
            if(set_value($set_input['description']['name'])){
              $set_input['description']['value'] = set_value($set_input['description']['name']);
            }
            echo form_textarea($set_input['description']);
            echo "<div id=\"help\">";
              echo lang('form_language_description_help');
            echo "</div>";
            echo form_error($set_input['description']['name']);
        echo "</div>";
        echo "<div id=\"clear\"></div>";

        echo "<div id=\"action\">";
          echo "<div id=\"save\"></div>";
          echo "<div id=\"save_value\"><input id=\"save\" type=\"submit\" name=\"edit_id\" value=\"".lang('form_language_save')."\" />";
          echo "</div>";
          echo "<div id=\"clear\"></div>";
        echo "</div>";

        echo "</form>";
      echo "</div>";

      echo "<div id=\"img_block\">";
        echo "<div id=\"edit_language_img\">";
          if(isset($flag_language)) echo $flag_language;

        echo "</div>";
        echo "<div id=\"clear\"></div>";
      echo "</div>";

      echo "<div id=\"img_block\">";
        echo "<div id=\"edit_language_img\">";
          if(isset($arms_language)) echo $arms_language;

        echo "</div>";
        echo "<div id=\"clear\"></div>";
      echo "</div>";

    }

  }
echo "<div id=\"clear\"></div>";
echo "</div>";

