<?php
echo '
<script>
	$(document).ready( function(){
		$("#datepicker").datepicker(
		{
			showOtherMonths: true,
			selectOtherMonths: true,
			dateFormat : "mm/dd/yy",
			timeOnlyTitle : "Выберите время",
            timeText      : "Время",
            hourText      : "Часы",
            minuteText    : "Минуты",
            secondText    : "Секунды",
            currentText   : "Текущее",
            closeText     : "Закрыть",
            firstDay      : 1,
            monthNames : ["Январь","Февраль","Март","Апрель","Май","Июнь","Июль","Август","Сентябрь","Октябрь","Ноябрь","Декабрь"],
            monthNamesShort : ["Янв","Фев","Мар","Апр","Май","Июн","Июл","Авг","Сен","Окт","Ноя","Дек"],
            dayNamesMin     : ["Вс","Пн","Вт","Ср","Чт","Пт","Сб"],
            showAnim       : "slideDown",
		});
	});
</script>';


echo validation_errors();
echo '<form action="'.$uri['point'].$uri['adverts_add'].'" method="POST" enctype="multipart/form-data">';


		echo '<div class="floatleft block-100 right padding-5">';
            echo $this->lang->line('adverts_date');
        echo '</div>';
		echo "<div class=\"floatleft padding-5\">";
            $data['date'] = array(
              'name' => 'date',
              'value' => date('m/d/Y'),
              'class' => 'block-100 padding-5',
              'id' => 'datepicker',
            );
            if(set_value('date')){
               $data['date']['value'] = set_value('date');
            }
            echo form_input($data['date']);

        echo "</div>";
        echo "<div class=\"clear\"></div>";

		echo '<div class="floatleft block-100 right padding-5">';
            echo $this->lang->line('adverts_show');
        echo '</div>';
		echo "<div class=\"floatleft padding-5\">";
            $data['show'] = array(
              'name' => 'show',
              'value' => 1,

            );
            if(set_value('show')){
               $data['show']['checked'] = true;
            }else{
               $data['show']['checked'] = false;
            }
            echo form_checkbox($data['show']);

        echo "</div>";
        echo "<div class=\"clear\"></div>";

        echo "<div class=\"floatleft block-100 right padding-5\">";
            echo $this->lang->line('adverts_sort');
        echo "</div>";
		echo "<div class=\"floatleft padding-5\">";

            $data['sort'] = array(
                'name' => 'sort',
                'value' => set_value('sort'),
                'class' => 'block-50 padding-5',
            );
            if(set_value('sort')){
            	$data['sort']['value'] = set_value('sort');
            }else{            	$data['sort']['value'] = 10;
            }
            echo form_input($data['sort']);
        echo "</div>";
        echo "<div class=\"clear\"></div>";

		echo "<div class='floatleft block-100 right padding-5'>";
            echo $this->lang->line('adverts_name');
        echo "</div>";
        echo "<div class='floatleft padding-5'>";
            $data['name'] = array(
                'name' => 'name',
                'value' => set_value('name'),
                'class' => 'block-600 padding-5',
            );

            echo form_input($data['name']);
            //echo form_error($data['name']['name']);
        echo "</div>";
  		echo "<div class=\"clear\"></div>";

		echo "<div class='floatleft block-100 right padding-5'>";
            echo $this->lang->line('adverts_description');
        echo "</div>";
        echo "<div class='floatleft padding-5'>";
            $data['description'] = array(
                'name' => 'description',
                'value' => set_value('description'),
                'class' => 'block-600 padding-5',
            );
            $content = html_entity_decode($data['description']['value']);
            $spaw = new SpawEditor("description", $content, 'utf-8','mini_font','spaw2','100%');
          	//$spaw->setStylesheet("/styles/style.css");
          	//$spaw->addPage(new SpawEditorPage("content_page[".$value['id']."]", $value['title'],$content));
          	$spaw->show();
            //echo form_textarea($data['description']);
            //echo form_error($data['description']['name']);
        echo "</div>";
        echo "<div class=\"clear\"></div>";

        $data['submit'] = array(
                'name' => 'add_adverts',
                'value' => 'добавить',
                'class' => 'block-100 padding-3',
            );
        echo "<div class='padding-20 block-400 center'>";
        echo form_submit($data['submit']);
        echo "</div>";

echo '</form>';