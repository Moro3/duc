<?php

/**
	Вывод ошибки при удалении изображений ресайза
 входящие параметры:
   $group - имя группы ресайза
   $resize - имя ресайза
   $error - ошибка при ресайзе

*/

echo '<div style="color:red">';
	echo 'Ошибка при удалении изображений (группа: '.$group.', имя ресайза: '.$resize.')<br />';
	echo '<em>'.$error.'</em>';
echo '</div>';