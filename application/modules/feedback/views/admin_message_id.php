<?php
//var_dump($message_arr);
if(!empty($message_arr)){
  echo $this->load->module('feedback/feedback_message')->pagination_message_id();
  echo "<div id=\"id_feedback\">";

  foreach ($message_arr as $key=>$value){
    echo "<div id=\"id_list\">";

        echo "<div id=\"id\">";
          echo lang('form_message_id');
        echo "</div>";
        echo "<div id=\"id_value\">".$value['id']."</div>";
        echo "<div id=\"clear\"></div>";

        echo "<div id=\"theme\">";
          echo lang('form_message_theme');
        echo "</div>";
        echo "<div id=\"theme_value\">";
          if(!empty($value['name_theme'])){
            echo "".$value['name_theme']."";
          }else{
            echo lang('form_message_not');
          }
        echo "</div>";
        echo "<div id=\"clear\"></div>";

        echo "<div id=\"name\">";
          echo lang('form_message_name');
        echo "</div>";
        echo "<div id=\"name_value\">";
          if(!empty($value['name'])){
            echo "".$value['name']."";
          }else{
            echo lang('form_message_not');
          }
        echo "</div>";
        echo "<div id=\"clear\"></div>";

        echo "<div id=\"email\">";
          echo lang('form_message_email');
        echo "</div>";
        echo "<div id=\"email_value\">";
          if(!empty($value['email'])){
            echo $value['email'];
          }else{
            echo lang('form_message_not');
          }
        echo "</div>";
        echo "<div id=\"clear\"></div>";

        echo "<div id=\"message\">";
          echo lang('form_message_message');
        echo "</div>";
        echo "<div id=\"message_value\">";
          if(!empty($value['message'])){
            echo htmlspecialchars($value['message']);
          }else{
            echo lang('form_message_not');
          }
        echo "</div>";
        echo "<div id=\"clear\"></div>";

        echo "<div id=\"date\">";
          echo lang('form_message_date');
        echo "</div>";
        echo "<div id=\"date_value\">";
          if(empty($value['date'])){
            echo lang('form_message_not_date');
          }else{
            echo date("d-m-Y",$value['date']);
            echo "<br />";
            echo date("H:i:s",$value['date']);
          }
        echo "</div>";
        echo "<div id=\"clear\"></div>";

        echo "<div id=\"ip\">";
          echo lang('form_message_ip');
        echo "</div>";
        echo "<div id=\"ip_value\">";
          if(!empty($value['ip'])){
            echo $value['ip'];
          }else{
            echo lang('form_message_not');
          }
        echo "</div>";

        echo "<div id=\"clear\"></div>";
    echo "</div>";

  }
  echo "</div>";
}




