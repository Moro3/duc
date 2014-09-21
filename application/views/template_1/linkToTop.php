<?php
//==== Cкрипт выводит кнопку поднятия страницы наверх после опускания браузера на определенную высоту

$script = '
    $(document).ready(function() {

	//scroll to top
	$(function() {

	    $("#linkToTop").click( function(e) {
	        e.preventDefault();
	        $("html, body").animate({ scrollTop: 0 }, 1000);
	    });

	    $(window).scroll( function() {
            //alert($(this).scrollTop());
	        if( $(this).scrollTop() > 900 )
	        {
	            $("#linkToTop").show();
	        }
	        else {
	            $("#linkToTop").hide();
	        }
	    });
	});

	}); //end Ready
';
//assets_script($script, false);
echo '<script>';
	echo $script;
echo '</script>';

// Стиль для кнопки
$style = '
   #linkToTop {
	    width: 42px; /* ширина картинки */
	    height: 42px; /* высота картинки */
	    line-height: 42px; /* = высоте картинки */
	    display: block;
	    position: fixed;
	    right: 35px; /* положение справа */
	    bottom: 55px; /* положение снизу */
	    text-decoration: none;
	    background: transparent url("'.assets_img("ToTopLetter.png").'") no-repeat left top;
	    z-index: 100;
	    display: none;
	}
';
//assets_style($style, false);
echo '<style>';
	echo $style;
echo '</style>';

// Сама кнопка
echo '<a href="#" rel="nofollow" title="Наверх" id="linkToTop"></a>';