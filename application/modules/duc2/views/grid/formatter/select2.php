<?php

//------------- Обработчик события для списков select2
/*
Входящие данные
  обязательные:

  опционально:
     $selector - string || array - имя или массив из селекторов для замены


*/


//if( ! isset($selector)) $selector = '#gs_id_teacher';
$selector = '#id_teacher';


$script = '
		$grid.bind("jqGridAddEditAfterShowForm", function(event, $form)
		{
			function format(state) {
				if (!state.id) return state.text; // optgroup
				return "<img class=\'flag\' src=\'images/flags/" + state.id.toLowerCase() + ".png\'/>" + state.text;
			}
			$("'.$selector.'").select2({
				width: "350px",
			});
		});

';
echo '<script>';
	echo $script;
echo '</script>';
//регистрируем скрипт
//assets_script($script, false);