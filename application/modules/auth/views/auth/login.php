<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.0 Transitional//EN">
<html>
<head>
    <title>
     <?php echo $title;?>
    </title>
    <meta http-equiv=Content-Type content="text/html; charset=<?=$this->config->item('charset');?>">
</head>
<body>
<div class='mainInfo'>

	<div class="pageTitle">Авторизация</div>
    <div class="pageTitleBorder"></div>
	<p>Войдите в систему используя для входа ваш e-mail адрес и пароль.</p>

	<div id="infoMessage"><?php echo $message;?></div>

    <?php
     //echo form_open("auth/login");
     echo "<form action=\"".$uri['page_auth']."\" method=\"post\" enctype=\"multipart/form-data\">";
    ?>

      <p>
      	<label for="email">Email:</label>
      	<?php echo form_input($email);?>
      </p>

      <p>
      	<label for="password">Password:</label>
      	<?php echo form_input($password);?>
      </p>

      <p>
	      <label for="remember">Запомнить меня:</label>
	      <?php echo form_checkbox('remember', '1', FALSE);?>
	  </p>


      <p><?php echo form_submit('submit', 'Login');?></p>


    <?php echo form_close();?>

</div>
</body>
</html>
