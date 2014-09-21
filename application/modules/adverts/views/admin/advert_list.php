<?php

assets_style('user/public.css', 'duc');
echo '<script type="text/javascript">
                function checkAll(oForm, cbName, checked)
                {
                for (var i=0; i < oForm[cbName].length; i++) oForm[cbName][i].checked = checked;
                }
                </script>
             ';

if(isset($objects) && is_array($objects)){	echo '<div class="duc_table">';

	echo '<form action="'.$uri['point'].$uri['adverts_list'].'" method="POST">';

	echo '<table class="tablesorter">';
	echo '<thead>';
		echo '<tr>';
			echo '<th width="40px">';
				echo 'ID';
				echo Modules::run('adverts/adverts/tpl_admin_order', 'ids');
			echo '</th>';
			echo '<th width="90px">';

                $date['all_select'] = array(
                    'name'        => 'total_select',
                    'value'       => 'checkbox',
                    'checked'     => FALSE,
                    'style'       => 'margin:10px',
                );
                $js = 'onClick="checkAll(this.form,\'checkbox_array[]\',this.checked)"';
                echo form_checkbox($date['all_select'], '', TRUE, $js);
                echo 'Выделить';
            echo '</th>';
			echo '<th width="80px">';
				echo 'Дата';
				echo Modules::run('adverts/adverts/tpl_admin_order', 'date');
			echo '</th>';
			echo '<th width="80px">';
				echo 'Порядок';
				echo Modules::run('adverts/adverts/tpl_admin_order', 'sort');
			echo '</th>';
			echo '<th width="70px">';
				echo 'Опубл.';
				echo Modules::run('adverts/adverts/tpl_admin_order', 'show');
			echo '</th>';
			echo '<th  width="50px">';
				echo 'VIP';
				echo Modules::run('adverts/adverts/tpl_admin_order', 'vip');
			echo '</th>';
			echo '<th width="170px">';
				echo 'Имя';
				echo Modules::run('adverts/adverts/tpl_admin_order', 'name');
			echo '</th>';
			echo '<th>';
				echo 'Описание';
			echo '</th>';
			echo '<th width="40px">';
				echo 'Редакт.';
			echo '</th>';
		echo '</tr>';
	echo '</head>';
	echo '<tbody>';
	$k = 1;
	foreach($objects as $key=>$item){		$k++;
		if(gettype($k/2) == 'double'){			echo '<tr class="odd">';
        }else{
			echo '<tr>';
		}
			echo '<td>';
				echo '<a href="'.$uri['point'].uri_replace($uri['adverts_id'], array($item['id']), 'object').'">';
				echo $item['id'];
				echo '</a>';
			echo '</td>';
			echo '<td>';
			    $date['select'] = array(
                        'name'        => 'checkbox_array[]',
                        'value'       => $item['id'],
                        'checked'     => FALSE,
                        'style'       => 'margin:10px',
                    );

                    echo form_checkbox($date['select']);
			echo '</td>';
			echo '<td>';
				echo date('d-m-Y',$item['date_create']);
			echo '</td>';
			echo '<td>';
				echo $item['sort_i'];
			echo '</td>';
			echo '<td>';
				if($item['show_i'] == 1){					echo "<img src=\"".assets_img('admin/green_button16.png', false)."\">";
				}else{					echo "<img src=\"".assets_img('admin/red_button16.png', false)."\">";
				}
			echo '</td>';
			echo '<td>';
				if($item['vip'] == 1){
					echo "<img src=\"".assets_img('admin/green_button16.png', false)."\">";
				}else{
					echo "<img src=\"".assets_img('admin/red_button16.png', false)."\">";
				}
			echo '</td>';
			echo '<td>';
				echo $item['name'];
			echo '</td>';
			echo '<td>';
				echo word_limiter(strip_tags($item['description']), 4);
			echo '</td>';
			echo '<td>';
				echo '<a href="'.$uri['point'].uri_replace($uri['adverts_id'], array($item['id']), 'object').'" title="редактировать">';
				echo '<img src="'.assets_img('admin/edit-16.png', false).'" />';
				echo '</a>';
                echo '&nbsp;&nbsp;&nbsp;';
				echo '<a href="'.$uri['point'].uri_replace($uri['adverts_copy'], array($item['id']), 'object').'" title="сделать копию">';
				echo '<img src="'.assets_img('admin/copy-16.png', false).'" />';
				echo '</a>';
                /*
				$data['submit_copy'] = array(
	                'name' => 'action_copy',
	                'value' => 'сделать копию',
	                'class' => 'block-100 padding-3',
	            );
	            $data['submit_copy'] = array(
	            'type' => 'image',
              'name'        => 'action_copy',
              //'id'          => 'username',
              'title'       => 'сделать копию',
              'value'       => $item['id'],
              //'maxlength'   => '100',
              //'size'        => '50',
              //'style'       => 'width:50%',
              'src'         => assets_img('admin/copy-16.png', false),
            );

	        	echo form_input($data['submit_copy']);
                */
			echo '</td>';
		echo '</tr>';
	}
	echo '</tbody>';
	echo '</table>';
	echo "<div class='padding-20 block-700 left'>";
	        echo ' Что сделать с выбранными элементами: ';
	        $options = array(
	                  'none'  => '',
	                  'on'  => 'Включить',
	                  'off'    => 'Отключить',
	                  'vip_on'  => 'Включить в VIP',
	                  'vip_off'    => 'Исключить из VIP',
	                  'delete'   => 'Удалить',
	                );
			echo form_dropdown('action_select', $options, 'none');

			$data['submit'] = array(
	                'name' => 'action_adverts',
	                'value' => 'применить',
	                'class' => 'block-100 padding-3',
	            );

	        echo form_submit($data['submit']);
	        echo "</div>";
    echo '</form>';
	echo '</div>';
}