<td id="pic_td1"

<?php
	/*
	if(!empty($contents['page']['img_fon'])){		echo 'style="background: transparent url(\''.assets_img($contents['page']['img_fon'], false).'\')  no-repeat 50%;"';
	}else{		echo 'style="background: transparent url(\''.assets_img('pic1.jpg', false).'\')  no-repeat 50%;"';
	}
	*/
	echo 'style="background: transparent url(\''.assets_img('pic1.jpg', false).'\')  no-repeat 50%;"';
?>
 colspan="7">
<?php
	//echo urlencode('Няни-гувернантки');
	//echo '<br />';
	//echo rawurlencode('Няни-гувернантки');
?>
<div class="banner">
<?php
	//print_r($contents['page']);

	echo '<div id="pic_text1">';
	echo '<p>';
	/*
	if(isset($contents['page']['text1'])){
  			echo str_replace("\n",'<br />',$contents['page']['text1']);
  	}else{  		echo 'Обеспечиваем услуги по патронажу<br />престарелых на дому<br />Осуществляем подбор квалифицированного<br />персонала в семьи';
  	}
  	*/
  	echo 'Обеспечиваем услуги по патронажу<br />престарелых на дому<br />Осуществляем подбор квалифицированного<br />персонала в семьи';

  	echo '</p>';
  	echo '</div>';
  	echo '<div id="pic_text2">';
  	//echo '<div class=slogan2>';
  	/*
  	if(isset($contents['page']['text2'])){
  		echo str_replace("\n",'<br />',$contents['page']['text2']);
  	}else{  		echo 'НИ ОДНОГО ДНЯ<br />БЕЗ ДОБРОГО ДЕЛА!';
  	}
  	*/
  	echo 'НИ ОДНОГО ДНЯ<br />БЕЗ ДОБРОГО ДЕЛА!';
  	//echo '</div>';
    echo '</div>';

?>

	<div id="family_pic">
	<img 1height="187" 1width="242" border="0" alt="" src="<?php echo assets_img('family1.png', false) ?>" id="family_img">
	</div>
</div>

</td>