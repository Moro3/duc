<?php

//echo '<pre>';
//print_r($config);
//echo '</pre>';

echo '<div class="setting">';
echo validation_errors();
echo '<form class="form-horizontal" action="'.$uri['point'].$uri['adverts_setting'].'" method="POST">';
		echo '<div class="floatleft block-100 right padding-5">';
            echo $this->lang->line('adverts_uri');
        echo '</div>';
		echo "<div class=\"floatleft padding-5\">";
            $data['uri'] = array(
              'name' => 'uri',
              'value' => set_value('uri'),
              'class' => 'block-100 padding-3',
              'maxlength' => 10,
            );
            if(set_value('uri')){
               $data['uri']['value'] = set_value('uri');
            }elseif(!empty($config['uri'])){
            	$data['uri']['value'] = $config['uri'];
            }
            echo form_input($data['uri']);

        echo "</div>";
        echo "<div class=\"clear\"></div>";

		echo '<div class="floatleft block-100 right padding-5">';
            echo $this->lang->line('adverts_path_images');
        echo '</div>';
		echo "<div class=\"floatleft padding-5\">";
            $data['path_images'] = array(
              'name' => 'path_images',
              'value' => set_value('path_images'),
              'class' => 'block-300 padding-3',
            );
            if(set_value('path_images')){
               $data['path_images']['value'] = set_value('path_images');
            }elseif(!empty($config['path']['images'])){
            	$data['path_images']['value'] = $config['path']['images'];
            }
            echo form_input($data['path_images']);

        echo "</div>";
        echo "<div class=\"clear\"></div>";

		echo '<div class="padding-5 bg-khaki">';
		    echo '<div class="padding-5 bg-powderblue">';
		    	echo $this->lang->line('adverts_admin_part');
		    echo '</div>';
            /*
			echo '<div class="floatleft block-100 right padding-5">';
	            echo $this->lang->line('adverts_per_page');
	        echo '</div>';
	        */

	    $data['admin_per_page'] = array(
	              'name' => 'admin_per_page',
	              'value' => set_value('admin_per_page'),
	              'class' => 'block-100 padding-3',
	              'maxlength' => 3,
	            );
	    if(form_error($data['admin_per_page']['name'])){	    	echo '<div class="control-group error">';
	    	echo '<label class="control-label" for="inputError">';
	    }elseif($this->input->post($data['admin_per_page']['name'])){	    	echo '<div class="control-group success">';
	    	echo '<label class="control-label" for="inputSuccess">';
	    }else{	    	echo '<div class="control-group">';
	    	echo '<label class="control-label" for="input">';
	    }

	        echo lang('adverts_per_page').'</label>';
			echo '<div class="controls">';
			//echo "<div class=\"floatleft padding-5\">";

	            if(set_value('admin_per_page')){
	               $data['admin_per_page']['value'] = set_value('admin_per_page');
	            }elseif(!empty($config['admin']['per_page'])){
	            	$data['admin_per_page']['value'] = $config['admin']['per_page'];
	            }
	            //var_dump(form_error($data['admin_per_page']['name']));
	            //var_dump($this->input->post($data['admin_per_page']['name']));
	            if(form_error($data['admin_per_page']['name'])){	            	$data['admin_per_page']['id'] = 'inputError';
	            }elseif($this->input->post($data['admin_per_page']['name'])){	            	$data['admin_per_page']['id'] = 'inputSuccess';
	            }
	            echo form_input($data['admin_per_page']);
                echo '<span class="help-inline">'.form_error($data['admin_per_page']['name']).'</span>';
	        echo "</div>";
	     echo "</div>";
	        echo "<div class=\"clear\"></div>";

	        echo '<div class="floatleft block-100 right padding-5">';
	            echo $this->lang->line('adverts_allow_per_page');
	        echo '</div>';
			echo "<div class=\"floatleft padding-5\">";
	            $data['admin_allow_per_page'] = array(
	              'name' => 'admin_allow_per_page',
	              'value' => set_value('admin_allow_per_page'),
	              'class' => 'block-300 padding-3',
	            );
	            if(set_value('admin_allow_per_page')){
	               $data['admin_allow_per_page']['value'] = set_value('admin_allow_per_page');
	            }elseif(!empty($config['admin']['allow_per_page'])){
	            	$data['admin_allow_per_page']['value'] = implode(',',$config['admin']['allow_per_page']);
	            }
	            echo form_input($data['admin_allow_per_page']);

	        echo "</div>";
	        echo "<div class=\"clear\"></div>";

	        echo '<div class="padding-5 bg-powderblue">';
		    	echo $this->lang->line('adverts_user_part');
		    echo '</div>';

			echo '<div class="floatleft block-100 right padding-5">';
	            echo $this->lang->line('adverts_modal_active');
	        echo '</div>';
			echo "<div class=\"floatleft padding-5\">";
	            $data['user_modal_active'] = array(
	              'name' => 'modal_active',
	              'value' => set_value('modal_active'),
	              'class' => 'block-100 padding-3',
	            );
	            if(set_value('modal_active')){
	               $data['user_modal_active']['value'] = set_value('modal_active');
	            }elseif($config['user']['modal']['active'] == 1){
	            	$data['user_modal_active']['value'] = 1;
	            }else{	            	$data['user_modal_active']['value'] = 0;
	            }
	            //echo form_input($data['user_modal_active']);
	            $options = array(
                  '0'  => 'Нет',
                  '1'    => 'Да',
                );

				echo form_dropdown($data['user_modal_active']['name'], $options,$data['user_modal_active']['value']);

	        echo "</div>";
	        echo "<div class=\"clear\"></div>";

	        echo '<div class="floatleft block-100 right padding-5">';
	            echo $this->lang->line('adverts_modal_switch');
	        echo '</div>';
			echo "<div class=\"floatleft padding-5\">";
	            $data['user_modal_switch'] = array(
	              'name' => 'modal_switch',
	              'value' => set_value('modal_switch'),
	              'class' => 'block-100 padding-3',
	            );
	            if(set_value('modal_switch')){
	               $data['user_modal_switch']['value'] = set_value('modal_switch');
	            }elseif($config['user']['modal']['switch'] == 1){
	            	$data['user_modal_switch']['value'] = 1;
	            }else{
	            	$data['user_modal_switch']['value'] = 0;
	            }
	            //echo form_input($data['user_modal_active']);
	            $options = array(
                  '0'  => 'Нет',
                  '1'    => 'Да',
                );

				echo form_dropdown($data['user_modal_switch']['name'], $options,$data['user_modal_switch']['value']);

	        echo "</div>";
	        echo "<div class=\"clear\"></div>";

	        echo '<div class="floatleft block-100 right padding-5">';
	            echo $this->lang->line('adverts_modal_switch_default');
	        echo '</div>';
			echo "<div class=\"floatleft padding-5\">";
	            $data['user_modal_switch_default'] = array(
	              'name' => 'modal_switch_default',
	              'value' => set_value('modal_switch_default'),
	              'class' => 'block-100 padding-3',
	            );
	            if(set_value('modal_switch_default')){
	               $data['user_modal_switch_default']['value'] = set_value('modal_switch_default');
	            }elseif($config['user']['modal']['switch_default'] == 1){
	            	$data['user_modal_switch_default']['value'] = 1;
	            }else{
	            	$data['user_modal_switch_default']['value'] = 0;
	            }
	            //echo form_input($data['user_modal_active']);
	            $options = array(
                  '0'  => 'Отключена',
                  '1'    => 'Включена',
                );

				echo form_dropdown($data['user_modal_switch_default']['name'], $options,$data['user_modal_switch_default']['value']);

	        echo "</div>";
	        echo "<div class=\"clear\"></div>";

			echo '<div class="floatleft block-100 right padding-5">';
	            echo $this->lang->line('adverts_per_page');
	        echo '</div>';
			echo "<div class=\"floatleft padding-5\">";
	            $data['user_per_page'] = array(
	              'name' => 'user_per_page',
	              'value' => set_value('user_per_page'),
	              'class' => 'block-100 padding-3',
	            );
	            if(set_value('user_per_page')){
	               $data['user_per_page']['value'] = set_value('user_per_page');
	            }elseif(!empty($config['user']['per_page'])){
	            	$data['user_per_page']['value'] = $config['user']['per_page'];
	            }
	            echo form_input($data['user_per_page']);

	        echo "</div>";
	        echo "<div class=\"clear\"></div>";

            echo '<div class="floatleft block-100 right padding-5">';
	            echo $this->lang->line('adverts_allow_per_page');
	        echo '</div>';
			echo "<div class=\"floatleft padding-5\">";
	            $data['user_allow_per_page'] = array(
	              'name' => 'user_allow_per_page',
	              'value' => set_value('user_allow_per_page'),
	              'class' => 'block-300 padding-3',
	            );
	            if(set_value('user_allow_per_page')){
	               $data['user_allow_per_page']['value'] = set_value('user_allow_per_page');
	            }elseif(!empty($config['user']['allow_per_page'])){
	            	$data['user_allow_per_page']['value'] = implode(',',$config['user']['allow_per_page']);
	            }
	            echo form_input($data['user_allow_per_page']);

	        echo "</div>";
	        echo "<div class=\"clear\"></div>";

	        echo '<div class="floatleft block-100 right padding-5">';
	            echo $this->lang->line('adverts_modal_time');
	        echo '</div>';
			echo "<div class=\"floatleft padding-5\">";
	            $data['adverts_modal_time'] = array(
	              'name' => 'modal_time',
	              'value' => set_value('modal_time'),
	              'class' => 'block-300 padding-3',
	            );
	            if(set_value('modal_time')){
	               $data['adverts_modal_time']['value'] = set_value('modal_time');
	            }elseif(!empty($config['user']['modal']['time_on'])){
	            	$data['adverts_modal_time']['value'] = $config['user']['modal']['time_on'];
	            }
	            echo form_input($data['adverts_modal_time']);

	        echo "</div>";
	        echo "<div class=\"clear\"></div>";


	        echo '<div class="floatleft block-100 right padding-5">';
	            echo $this->lang->line('adverts_modal_speed');
	        echo '</div>';
			echo "<div class=\"floatleft padding-5\">";
	            $data['adverts_modal_speed'] = array(
	              'name' => 'modal_speed',
	              'value' => set_value('modal_speed'),
	              'class' => 'block-300 padding-3',
	            );
	            if(set_value('modal_speed')){
	               $data['adverts_modal_speed']['value'] = set_value('modal_speed');
	            }elseif(!empty($config['user']['modal']['speed'])){
	            	$data['adverts_modal_speed']['value'] = $config['user']['modal']['speed'];
	            }
	            echo form_input($data['adverts_modal_speed']);

	        echo "</div>";
	        echo "<div class=\"clear\"></div>";

		echo '</div>';

        $data['submit'] = array(
                'name' => 'adverts_setting',
                'value' => 'Сохранить',
                'class' => 'block-100 padding-3',
            );
        echo "<div class='padding-20 block-400 center'>";
        echo form_submit($data['submit']);
        echo "</div>";


echo '</form>';
echo '</div>';