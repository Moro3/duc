<?php (defined('BASEPATH')) OR exit('No direct script access allowed');


class Replaced_page extends CI_Driver
{
    private $prefix = 'page';          // префикс модул€
    private $params = array();          // имена параметров
    private $cfg_params = array();          // параметры дл€ замены из конфигурационных файлов
    private $f_start = '{';             // начальный флаг фрагмента
  	private $f_end = '}';               // конечный флаг фрагмента
    private $access;                    //разрешени€ дл€ параметров
    private $uri_correct = true;     //по умолчанию значение uri корректное (т.е. с учетом слешей с обоих сторон)

    public $CIC;

    function __construct()
	{
    	$this->load_data();
        $this->CIC = & get_instance();

    }

    /**
    * «агрузка переменных по умолчанию
    *
    */
    function load_data(){    	$this->params = array('name', 'value', 'arg', 'style', 'id', 'css', 'ajax', 'span', 'div', 'tag', 'class', 'title');
		$this->access = array(
		                 'value' => array('id', 'name', 'uri', 'content.id', 'icon', 'description'),
		);
		$this->cfg_params = array();
		$this->uri_correct = true;
    }

    /**
    * возвращает список допустимых значений
    */
    public function get_allow_value(){    	if(isset($this->access['value'])) return $this->access['value'];
    	return false;
    }

    function get_content($params){
    	$key_name_tpl = array_keys($params['params'],'name');
        if(isset($key_name_tpl[0]) && !empty($params['value'][$key_name_tpl[0]])){
            $data = $this->get_data($params['value'][$key_name_tpl[0]]);

        }

        //получаем параметр value
        $key_value_tpl = array_keys($params['params'],'value');
        //по умолчанию записываем параметр value = uri
        //если параметр value задан, провер€ем на доступ и записываем данное значение
        $value = 'uri';
        if(isset($key_value_tpl[0]) && !empty($params['value'][$key_value_tpl[0]])){
            if(in_array($params['value'][$key_value_tpl[0]], $this->access['value']))
            $value = $params['value'][$key_value_tpl[0]];
            $this->uri_correct = false;
        }
        //провер€ем есть ли данное поле в данных и присваиваем его в результат
        if(is_array($data)){        	if(isset($data[$value])) {        		$result = $data[$value];
        		//$result = Modules::run('replace/replace/get_content', $data[$value]);
        	}
        	if($value == 'uri' && $this->uri_correct === true) $result = Modules::run('pages/pages/getFieldUri', $result);
        }

        //обнул€ем данные на значени€ по умолчанию
        $this->load_data();
        //если результат пустой, выходим
        if(empty($result)) return;

		//выбираем атрибуты дл€ тегов
		$attr = 'style="';
        if($key_style_tpl = array_keys($params['params'],'style')){        	$attr .= rtrim($params['value'][$key_style_tpl[0]],';').';';
        }
        if($key_style_tpl = array_keys($params['params'],'span')){
        	$attr .= rtrim($params['value'][$key_style_tpl[0]],';').';';
        }
        if($key_style_tpl = array_keys($params['params'],'div')){
        	$attr .= rtrim($params['value'][$key_style_tpl[0]],';').';';
        }
        $attr .= '"';
        if($key_style_tpl = array_keys($params['params'],'class')){
        	$attr .= ' class="'.$params['value'][$key_style_tpl[0]].'"';
        }
        if($key_style_tpl = array_keys($params['params'],'id')){
        	$attr .= ' id="'.$params['value'][$key_style_tpl[0]].'"';
        }

        //окружаем тегами результат и присваиваем атрибуты
        if($key_style_tpl = array_keys($params['params'],'tag')){        	$result = '<'.$params['value'][$key_style_tpl[0]].' '.$attr.'>'.$result.'</'.$params['value'][$key_style_tpl[0]].'>';
        }elseif($key_style_tpl = array_keys($params['params'],'span')){
            $result = '<span '.$attr.'>'.$result.'</span>';
        }elseif($key_style_tpl = array_keys($params['params'],'div')){
        	$result = '<div '.$attr.'>'.$result.'</div>';
        }elseif($key_style_tpl = array_keys($params['params'],'style')){
        	$result = '<div '.$attr.'>'.$result.'</div>';
        }else{
        	$result = $result;
        }

        return $result;
    }

    /**
    * ¬озвращает параметры страницы по еЄ id или названию uri
    * @param string||numeric $name - id или uri страницы
    *
    * @return array - возвращает данные о странице с разрешенными параметрами
    */
    private function get_data($name){    	return Modules::run('pages/pages/get_data_template', $name);
    }

	/**
	*	¬озвращает шаблон дл€ поиска фрагментов вставки
	*
	*/
    function get_pattern_split_fragment(){    	return '|'.$this->f_start.''.$this->prefix.' ([^'.$this->f_end.$this->f_start.']*)'.$this->f_end.'|Uis';
    }

	/**
	*	¬озвращает шаблон дл€ поиска фрагментов параметров
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
		  $pattern = '%('.$params.')[ ]*=[ ]*["|\']*[ ]*([\w-_\.:;]*)[ ]*["|\']*[ ]*%is';
		  //$pattern = '%('.$params.')[ ]*=[ ]*["|\']([\w-_\. :;]*)["|\'][ ]*%is';

		}else{
		  $pattern = '%%is';
		}
		return $pattern;
    }


}