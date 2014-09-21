<?php
assets_style('users/breadcrumbs/breadcrumbs.css', false);

/* �������� ���������
  * Breadcrumbs - ������� ���� �� �������� (������� ������)
	* @param array - ������, ��������� ��� ������������ ����
	*				num	 - ���� � ������� ���������� ����� ����� ���������� ����
	*				sub_num - ���������� ����� ��� ������ � ������ ����� (����� ������� �������������� ���� ��� ��������� ���� �� ������ ����� ����)
	*                	[num][sub_num] = array(	'name' - ��������
	*                					'link' - ������
	*                					'current_path' - boolean (��������� �� ������ � �������� ����)
	*                					'last'  - boolean (�������� �� ������ ��������� � ����)
	*                           	)


*/
echo 'breadcrumbs';
echo '<div class="breadcrumbs_objects">';
	if(isset($objects) && is_array($objects)){		foreach($objects as $key=>$value){			//echo '<pre>';
			//print_r($value);
			//echo '</pre>';
			foreach($value as $items){                if(isset($items['current_path']) && $items['current_path'] == true && @$items['last'] !== true){
					echo '<span class="l_active">';
					echo '<a href="'.$items['link'].'">';
	   					echo $items['name'];
	   				echo '</a>';
   				}elseif(isset($items['current_path']) && $items['current_path'] == true && isset($items['last']) && $items['last'] === true){
   				   echo '<span class="l_passive">';
                   echo $items['name'];
                   echo '</span>';
   				}else{
   				}

			}
			if(!isset($items['last']) || $items['last'] !== true){
				echo ' <span class="next">>></span> ';
			}
		}
	}

echo '</div>';

