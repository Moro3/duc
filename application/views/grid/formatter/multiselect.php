<?php

//------------- ���������� ������� ��� ����� multiselect
/*
�������� ������
  ������������:
    $selector - string || array - ��� ��� ������ �� ���������� ��� ������
  �����������:
  	$field - selector ��� ��������� (��� ������ � ������� ���� �� ����), �� ��������� ".multiselect"
  	$sortable - boolean - ����������
  	$searchable - boolean - �����


*/
$sortable = (isset($sortable)) ? 'true' : 'false';
$searchable = (isset($searchable)) ? 'true' : 'false';

$multiselect = '
	function gridEditMultiselect(field)
	{
              $(".ui-"+field).remove();
              $("."+field).show().multiselect(
              {
              	sortable: '.$sortable.',
              	searchable: '.$searchable.'
              });

	}
';
//������������ ������
assets_script($multiselect, false);
if( ! isset($selector)) $selector = 'multiselect';

	if( ! is_array($selector)) $selector = array($selector);
	echo '<script>
		$grid.bind("jqGridAddEditAfterShowForm", function(event, $form)
		{
		   var row_id = $(this).getGridParam("selrow");
		';

		foreach($selector as $item){
				echo '
				gridEditMultiselect("'.$item.'");
			    ';
			    /*
			    echo'
				    	$(".ui-"+'.$item.').remove();
		              	$("."+'.$item.').show().multiselect(
		              	{
		              		sortable: '.$sortable.',
		              		searchable: '.$searchable.'
		              	});
	            	';
	           */
		}
		echo '});
		</script>';

