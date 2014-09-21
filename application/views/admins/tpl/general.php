<!DOCTYPE HTML PUBLIC "-//W3C//DTD XHTML 4.01 Transitional//EN" "http://www.w3.org/TR/html4/loose.dtd">
<HTML>
<HEAD>
    <TITLE><?=$set_site['site_name'] ? $set_site['site_name'] : '' ?> - <?php if(isset($title)) echo $title; ?></TITLE>
<?php
    include_once('meta.php');
    
?>


</head>
<body>
   <div class="top">
      <div class="top_panel">
        <?php
          $this->lang->load('admin', 'english');
          echo "<div>".$this->lang->line('admin_panel')."</div>";
          echo "<span class=\"top_panel_name\">Сайт:</span>&nbsp;<span class=\"top_panel_text\"><a href=\"".rtrim($this->config->item('base_url'),'/')."{$uri['admin']}\">".$this->config->item('base_url')."</a></span><br />";
          echo "<span class=\"top_panel_name\">Пользователь:</span> <span class=\"top_panel_text\">Администратор</span><br />";
        ?>
      </div>
      <div class="top_status">
        <?php
          //echo "<span class=\"top_status_name\">".$this->lang->line('admin_status').":&nbsp;<span class=\"top_status_text\">on-line</span><br />";
          //echo "<span class=\"top_status_name\">".$this->lang->line('admin_online_system').":&nbsp;<span class=\"top_status_text\">(4)</span><br />";
          //echo "<span class=\"top_status_name\">".$this->lang->line('admin_notice').":&nbsp;<span class=\"top_status_text\">(6)</span><br />";
          //echo "<span class=\"top_status_name\">".$this->lang->line('admin_message').":&nbsp;<span class=\"top_status_text\">(2)</span><br />";
        ?>
        <script language="JavaScript">
          <!--
            document.write ('<br>');
            document.write ('Ширина браузера: <span class=\"top_status_text\">' + document.body.clientWidth + '</span>px<br />');
            document.write ('Высота браузера: <span class=\"top_status_text\">' + document.body.clientHeight + '</span>px');
          //-->
        </script>


      </div>
      <div class="top_exit">
          <?php
            echo "<form action=\"\" method=\"post\">";
            echo "<input type=\"hidden\" name=\"close\" value=\"door\">";
            echo "<input  type=\"submit\" name=\"exit_admin\" value=\"".$this->lang->line('admin_exit')."\">";
            echo "</form>";
          ?>
      </div>


   </div>
  <table id="tb_contents">
  <tr>
  <td id="td_left">
   <div class="block_left">
      <div class="setting">
          <?php
            /*
            echo "<div class=\"heading\">".$this->lang->line('admin_setting_site')."</div>";

            echo "<span class=\"setting_name\">- Параметры сайта</span><br />";
            echo "<span class=\"setting_name\">- Управление дизайном</span><br />";
            */
          ?>
      </div>



        <?php
            echo Modules::run('modulesinfo/list_modules_menu');

        ?>


      <div class="monitoring">
        <?php
            /*
            echo "<div class=\"heading\">".$this->lang->line('admin_monitoring')."</div>";

            echo "<span class=\"monitoring_name\">- Пользователи on-line</span><br />";
            echo "<span class=\"monitoring_name\">- Уведомления системы</span><br />";
            echo "<span class=\"monitoring_name\">- Сообщения</span><br />";
            */
          ?>
      </div>

      <div class="monitoring">
        <?php
            /*
            echo "<div class=\"heading\">".$this->lang->line('admin_config')."</div>";

            echo "<span class=\"monitoring_name\">- <a href=\"/{$uri['admin']}scheme_m/\">Схема</a></span><br />";
            */

          ?>
      </div>

   </div>
  </td>
  <td id="td_content">
   <div class="block_content">
     <?php
      if(!empty($content)) echo $content;
      echo Modules::run('admin/contents');
      //echo Modules::run('scheme_m');
      //echo Modules::run('menu/updatemode');
      //echo Modules::run('menu/menu_panel');
      //echo Modules::run('menu/_all_tree');
      //echo "<pre>";
      //print_r($this->control_uri->get_uri_use());
      //echo "</pre>";

      ?>
   </div>
  </td>
  </tr>
  </table>

  <div class="footer">
      <div class="f_panel">
        <?php
          echo "&copy; ".date("Y")."<br />";
          
        ?>
      </div>
   </div>
</body>
</html>




