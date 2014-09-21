<?php if (!defined('BASEPATH')) exit('No direct script access allowed');

/**
 * Класс Breadcrumbs - основной класс для работы с недвижимостью
 *
 *
 */

class Breadcrumbs extends MY_Controller {

    //данные
    private $data;

    //имя пути по умолчанию
    // (для предотвращений коллизий с названиями рекомендуется указать что-то нелогичное)
    private $default_name = 'jnH!Bhb1';

    function __construct(){
        parent::__construct();
    }

    /**
    * Добавление данных для пути
    *	@param array $data - массив с основными данными для пути (name, link, current_path, last)
    *                       name - имя пути
    *						link - ссылка на путь
    *	                    current_path - входит ли путь в текщий
    *
    */
    public function add($data, $name = false){        $name = $this->get_name($name);

		if(isset($data['name']) && isset($data['link']) && isset($data['current_path'])){			$count_path = (isset($this->data[$name]) && is_array($this->data[$name])) ? count($this->data[$name]) : 0;
			$i = $count_path++;
			/*
			if($count_path > 0){				$i = $count_path++;
			}else{				$i = 1;
			}
            */
			$this->data[$name][$i++][] = $data;
		}

    }

    /**
    * 	Удаление пути по его имени
    *	Так же можно удалить часть пути если известен номер требуемой части
    *   @param string $name - имя пути
    *	@param integer $number - номер пути
    */
    public function del($name = false, $number = false){        $name = $this->get_name($name);
        if(isset($this->data[$name])){        	if(is_numeric($number)){        		if(isset($this->data[$name][$number])){        			unset($this->data[$name][$number]);
        		}
        	}else{        		unset($this->data[$name]);
        	}
        }
    }

    /**
    * 	Возвращает данные пути
    *
    */
    private function get_data($name = false){    	$name = $this->get_name($name);
    	if(isset($this->data[$name])){    		return $this->data[$name];
    	}
    	return false;
    }

    /**
    * 	Возвращает текущее имя
    *   По умолчанию имя задается настройками свойства default_name
    *	но если требуется именовать хлебные крошки, то можно присвоить свое имя
    */
    private function get_name($name = false){    	if($name === false) $name = $this->default_name;
    	return $name;
    }

    /**
    * 	Возвращает сгенерированный шаблон
    *   @param string $name_template - имя шаблона (имя файла относительно представления)
    *	@param string $name - имя пути если задавалось
    *
    *	@return string - строка шаблона (html)
    */
    public function get_template($name_template, $name = false){    	//$name = $this->get_name($name);
    	$data['objects'] = $this->get_data($name);

		return $this->load->view($name_template, $data, true);
    }
}
