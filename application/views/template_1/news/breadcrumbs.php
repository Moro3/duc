<div class='breadcrumbs'>
<?php
echo "<a href='/'>";
	echo "Главная";
	echo "</a>";
if(isset($segments[1])){	echo "<span class='next'> --> </span>";
	echo "<a href='/news/'>";
	echo "Новости";
	echo "</a>";

	switch($segments[1]){
		case 'id':
			if(isset($segments[2])){				echo "<span class='next'> --> </span>";
				echo $segments[2];
			}

		break;
		default:

	}
}else{	echo "<span class='next'> --> </span>";

	echo "Новости";

}

?>
</div>