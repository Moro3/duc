<?php

if(is_array($arr_language)){
  echo "<div id=\"all_languages\">";

    echo "<div id=\"menu_list\">";
      echo "<div id=\"id\">".lang('form_language_id')."</div>";
        echo "<div id=\"flag\">".lang('form_language_flag')."</div>";
        echo "<div id=\"name\">".lang('form_language_name')."</div>";

        echo "<div id=\"abbr\">".lang('form_language_abbr')."</div>";
        echo "<div id=\"description\">".lang('form_language_description')."</div>";
        echo "<div id=\"date_create\">".lang('form_language_date_create')."</div>";
        echo "<div id=\"date_update\">".lang('form_language_date_update')."</div>";
        echo "<div id=\"action\">".lang('form_language_action')."</div>";
    echo "<div id=\"clear\"></div>";
    echo "</div>";
  $k = 1;
  foreach($arr_language as $key=>$value){
    $id_post_del = $this->input->post('id_language');
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

        echo "<div id=\"id\"><a href=\"".$uri['module'].uri_replace($uri['edit'],$value['id'],'language')."\">".$value['id']."</a></div>";

        echo "<div id=\"flag\">";
          if(!empty($value['flag'])){
            if (is_file($path['dir_root'].'/'.$path['thumb']['mini'].'/'.$value['flag'])){
              echo "<img src=\"/".$path['thumb']['mini']."/".$value['flag']."\" />";
            }else{
              echo lang('form_language_not_file');
            }
          }else{
            echo lang('form_language_not');
          }

        echo "</div>";

        echo "<div id=\"name\">";
          if(!empty($value['name'])){
            echo "<a href=\"".$uri['module'].uri_replace($uri['edit'],$value['id'],'language')."\">".$value['name']."</a>";
          }else{
            echo lang('form_language_not');
          }
        echo "</div>";

        echo "<div id=\"abbr\">";
          if(!empty($value['abbr'])){
            echo $value['abbr'];
          }else{
            echo lang('form_language_not');
          }
        echo "</div>";

        echo "<div id=\"description\">";
          if(!empty($value['description'])){
            echo lang('form_language_yes');
          }else{
            echo lang('form_language_not');
          }
        echo "</div>";

        echo "<div id=\"date_create\">";
          if(empty($value['date_create'])){
            echo lang('form_language_not_date');
          }else{
            echo date("d-m-Y",$value['date_create']);
          }
        echo "</div>";

        echo "<div id=\"date_update\">";
          if(empty($value['date_update'])){
            echo lang('form_language_not_date');
          }else{
            echo date("d-m-Y",$value['date_update']);
          }
        echo "</div>";

        echo "<div id=\"action\">";
          echo "<div id=\"edit\">";
            echo "<a href=\"".$uri['module'].uri_replace($uri['edit'],$value['id'],'language')."\"><img src=\"/".$path['img']."/page_edit16.png\" title=\"".lang('form_language_edit')."\" /></a>";
          echo "</div>";
          echo "<div id=\"delete\">";
            echo "<form action=\"".$uri['module'].$uri['delete']."\" method=\"POST\" enctype=\"multipart/form-data\">";

            echo form_hidden("id_language[".$value['id']."]",$value['id']);
            $set_input['del'] = array(
                  'name'        => "del_language[".$value['id']."]",
                  'id'          => 'del_language',
                  'value'       => $value['id'],
                  'type'        => 'image',
                  'src'         =>  "/".$path['img']."/page_delete16.png",
                  'title'       => lang('form_language_delete'),
                  'alt'       => lang('form_language_delete'),
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
  echo "</div>";
}

