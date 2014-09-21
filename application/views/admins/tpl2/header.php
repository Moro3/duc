<div class="name_panel">
	Административная панель
</div>

<div class="www">
	<a href='/adminka/'>{cfg name='www'}</a>
</div>
<div class="www">
	{cfg name="name" span="color:grey;"}
</div>


<?php
	include('info_user.php');
?>

<div class="top_exit">
          <?php
            echo "<form action=\"\" method=\"post\">";
            echo "<input type=\"hidden\" name=\"close\" value=\"door\">";
            echo "<input  type=\"submit\" name=\"exit_admin\" value=\"".$this->lang->line('admin_exit')."\">";
            echo "</form>";
          ?>
</div>
