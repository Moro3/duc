<?php

//------------- ���������� ������� ��� ������� select2
/*
�������� ������
  ������������:

  �����������:
     $selector - string || array - ��� ��� ������ �� ���������� ��� ������


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
//������������ ������
//assets_script($script, false);