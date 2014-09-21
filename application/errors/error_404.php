<!DOCTYPE html>
<html lang="en">
<head>
<title>404 Page Not Found</title>
<style type="text/css">

::selection{ background-color: #E13300; color: white; }
::moz-selection{ background-color: #E13300; color: white; }
::webkit-selection{ background-color: #E13300; color: white; }

body {
	background-color: #fff;
	margin: 40px;
	font: 13px/20px normal Helvetica, Arial, sans-serif;
	color: #4F5155;
	line-height: 1.5;
}

a:link {
	color: #f90;
	text-decoration: none;
}
a:visited {
	color: #e47b22;
	text-decoration: none;
}
a:hover {
	color: #e46900;
	text-decoration: underline;
}

h1 {
	color: #444;
	background-color: transparent;
	border-bottom: 1px solid #D0D0D0;
	font-size: 19px;
	font-weight: normal;
	margin: 0 0 14px 0;
	padding: 10px 0px;
}

code {
	font-family: Consolas, Monaco, Courier New, Courier, monospace;
	font-size: 12px;
	background-color: #f9f9f9;
	border: 1px solid #D0D0D0;
	color: #002166;
	display: block;
	margin: 0;
	padding: 0px 10px;
}

#container {
	padding: 20px;
	border: 1px solid #D0D0D0;
	-webkit-box-shadow: 0 0 8px #D0D0D0;
}

p {
	margin: 12px 15px 12px 15px;
}
.search_main{
	font-size:larger;
}

</style>
</head>
<body>
	<div id="container">
		<a href="/"><img src="<?php echo assets_img('logo2.gif', false) ?>" alt="На главную"></a>
		<h1>
		<?php //echo $heading;
		?>
		Ошибка 404. Страница не найдена.
		</h1>
		<div class="search_main">
		Указанный запрос не существует.
		<br />
		Возможно страница была перемещена или удалена.
		<br />
		Попробуйте поискать её с <a href="/">главной страницы</a>!</div>
		<?php //echo $message;
		 ?>
	</div>
</body>
</html>