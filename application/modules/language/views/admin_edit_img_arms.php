<div id="edit_img">
<?php
  $file_name_db = 'arms';


  if(is_array($arr_language)){

    if(isset($error)) echo $error;
    foreach($arr_language as $key=>$value){
       $data = array(
              'name'        => "del_".$file_name_db,
              'id'          => 'del_photo',
              'type'        => 'image',
              'value'       => $value['id'],
              'maxlength'   => '',
              'size'        => '',
              'src'         => "/".$path['img']."/delete16.png",
              'alt'         => lang('form_language_delete'),
              'title'       => lang('form_language_delete'),
            );
       echo "<form action=\"".$uri['module'].$uri['id']."\" method=\"POST\" enctype=\"multipart/form-data\" >";
       echo "<div id=\"file\">";
          echo lang('form_language_arms').":<br/>";
          if(!empty($value[$file_name_db])){
            if(is_file($path['dir_root']."/".$path['thumb']['small']."/".$value[$file_name_db])){
               echo "<img src=\"/".$path['thumb']['small']."/".$value[$file_name_db]."\">";

            }else{
               echo lang('form_language_not_file')." \"".$value[$file_name_db]."\"";
            }

          }else{
            echo "<span id=\"text\">".lang('form_language_not')."</span>";
          }
       echo "</div>";
      if(!empty($value[$file_name_db])){
        echo "<div id=\"file_del\">";
        echo form_input($data);
        echo "</div>";
      }
      echo "<div id=\"clear\"></div>";
      $data = array(
              'name'        => $file_name_db,
              'id'          => 'img',
              'type'        => 'file',
              'maxlength'   => '50',
              //'size'        => '50',

            );
      echo "<div id=\"file_load\">";
      echo form_input($data);

      echo form_submit('load_'.$file_name_db, lang('form_language_load'), 'id="submit_img"');
      echo "</div>";
      echo form_close();
    }
  }

?>
</div>



