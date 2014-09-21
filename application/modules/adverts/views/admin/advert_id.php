<?php

assets_style('user/public.css', 'duc');
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
if(isset($objects) && is_array($objects)){

	echo '<form action="'.$uri['point'].uri_replace($uri['adverts_id'], array($objects['id']), 'object').'" method="POST">';

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
            }elseif(!empty($objects['date_create'])){            	$data['date']['value'] = date('m/d/Y', $objects['date_create']);
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
            }elseif($objects['show_i'] == 1){            	$data['show']['checked'] = true;
            }else{
               $data['show']['checked'] = false;
            }
            echo form_checkbox($data['show']);

        echo "</div>";
        echo "<div class=\"clear\"></div>";

        echo '<div class="floatleft block-100 right padding-5">';
            echo $this->lang->line('adverts_vip');
        echo '</div>';
        echo "<div class=\"floatleft padding-5\">";
            $data['vip'] = array(
              'name' => 'vip',
              'value' => 1,

            );
            if(set_value('vip')){
               $data['vip']['checked'] = true;
            }elseif($objects['vip'] == 1){
            	$data['vip']['checked'] = true;
            }else{
               $data['vip']['checked'] = false;
            }
            echo form_checkbox($data['vip']);

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
            }elseif(!empty($objects['sort_i'])){            	$data['sort']['value'] = $objects['sort_i'];
            }else{
            	$data['sort']['value'] = 10;
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
                //'value' => set_value('name'),
                'class' => 'block-600 padding-5',
            );
            if(set_value('name')){
            	$data['name']['value'] = htmlspecialchars(set_value('name'));
            }elseif(!empty($objects['name'])){
            	$data['name']['value'] = $objects['name'];
            }else{
            	$data['name']['value'] = '';
            }

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
            if(set_value('description')){
            	$data['description']['value'] = set_value('description');
            }elseif(!empty($objects['description'])){
            	$data['description']['value'] = $objects['description'];
            }else{
            	$data['description']['value'] = '';
            }

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
                'name' => 'adverts_edit',
                'value' => 'Сохранить',
                'class' => 'block-100 padding-3',
            );
        echo "<div class='padding-20 block-400 center'>";
        echo form_submit($data['submit']);
        echo "</div>";

    echo '</form>';

}