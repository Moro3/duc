<?php (defined('BASEPATH')) OR exit('No direct script access allowed');


class Replaced_lnk extends CI_Driver
{
    private $prefix = 'lnk';          // префикс модуля
    private $params = array();          // имена параметров
    private $cfg_params = array();          // параметры для замены из конфигурационных файлов
    private $f_start = '{';             // начальный флаг фрагмента
  	private $f_end = '}';               // конечный флаг фрагмента

    public $CIC;

    function __construct()
	{
    	$this->params = array('name', 'type', 'value', 'style', 'css', 'class');

		$this->p_names = array(
							'cfg', 'page', 'file'
		);
        $this->CIC = & get_instance();
        $this->CIC->config->load('site', TRUE);
        //print_r();
    	$site = $this->CIC->config->item('site');
        foreach($site as $key=>$items){        	$this->add($key, $items);
        }

    }

    /**
    * Добавление параметра
    *
    */
    function add($name, $value){    	if(is_array($name)){    		foreach($name as $key=>$items){
    			$this->add($key, $items);
    		}
    	}
    	$this->cfg_params[$name] = $value;
    }
    /**
    * Получение параметра
    *
    */
    function get($name){
    	if(isset($this->cfg_params[$name])){
    		return $this->cfg_params[$name];
    	}
    	return NULL;
    }
    /**
    * Удаление параметра
    *
    */
    function del($name){
    	if(isset($this->cfg_params[$name])){
    		unset($this->cfg_params[$name]);
    	}
    }

    function get_content($params){        //echo $params;
        //exit;
    	$key_name_tpl = array_keys($params['params'],'name');
        if(isset($key_name_tpl[0]) && !empty($params['value'][$key_name_tpl[0]])){
            if(isset($this->cfg_params[$params['value'][$key_name_tpl[0]]])){
                $result = $this->cfg_params[$params['value'][$key_name_tpl[0]]];
            }else{
                $result = '';
            }

        }
        if($key_style_tpl = array_keys($params['params'],'style')){            //print_r($params);
        	//exit;
        	if( ! array_keys($params['params'],'span') || ! array_keys($params['params'],'tag')){        		$result = '<div style="'.$params['value'][$key_style_tpl[0]].'">'.$result.'</div>';
        	}else{
        		$result = $result.' style="'.$params['value'][$key_style_tpl[0]].'"';
        	}
        }
        if($key_style_tpl = array_keys($params['params'],'span')){
        	$result = '<span style="'.$params['value'][$key_style_tpl[0]].'">'.$result.'</span>';
        }
        if($key_style_tpl = array_keys($params['params'],'tag')){        	$result = '<'.$params['value'][$key_style_tpl[0]].'>'.$result.'</'.$params['value'][$key_style_tpl[0]].'>';
        }
        return $result;
    }

	/**
	*	Возвращает шаблон для поиска фрагментов вставки
	*
	*/
    function get_pattern_split_fragment(){    	return '|'.$this->f_start.''.$this->prefix.' ([^'.$this->f_end.$this->f_start.']*)'.$this->f_end.'|Uis';
    }

	/**
	*	Возвращает шаблон для поиска фрагментов параметров
	*
	*/

    function get_pattern_parse_fragment(){
    	//$pattern = '';
		if(count($this->params) > 0){
		  foreach($this->params as $item){
		    //$pattern .= '[]*'.$item.'[]*=[]*[\"]?([^'.$this->f_end.$this->f_start.'\"\']*)[\"]?';
		  }
		  $params = implode("|",$this->params);
		}
		if(!empty($params)){
		  $pattern = '%('.$params.')[ ]*=[ ]*["|\']*[ ]*([\w-:;]*)[ ]*["|\']*[ ]*%is';
		}else{
		  $pattern = '%%is';
		}
		return $pattern;
    }


}