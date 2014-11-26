<?php if (!defined('BASEPATH')) exit('No direct script access allowed');
// Подключаем наследуемый класс если он не подгужен


if( ! class_exists('mods')){
  include_once ('mods.php');
}

/*
 * Класс Mods_route
 *
 */

class Mods_route extends Mods {

    function __construct(){
        parent::__construct();
        $this->table = 'grid_mods_route';
        $this->MY_table = 'mods_route';
    }

    /**
     * Проверка на уже имеющиеся название поля "uri"
     * @param  string  $uri          строка для проверки
     * @param  array $exception_id   исключающиеся id
     * @return boolean               true - найдено соответсвие, false - соответсвий не найдено
     */
    function matchesUri($uri, $exception_id = false){
    	if(empty($uri)) return true;
     	
    	$where['uri'] = $uri;
    	if($exception_id !== false){
    		if( ! is_array($exception_id)) $exception_id = array($exception_id);
    		$where['not'] = array('id' => $exception_id);
    	} 
    	
    	$res = $this->MY_data(
    		//select
    		array('id', 'uri'),
    		//where
    		$where
    	);

    	//dd($res);
    	if(is_array($res) && count($res) > 0){
    		return true;
    	}else{
    		return false;
    	}
    }

    /**
     * Проверка на уже имеющиеся название поля "name"
     * @param  string  $str          строка для проверки
     * @param  array $exception_id   исключающиеся id
     * @return boolean               true - найдено соответсвие, false - соответсвий не найдено
     */
    function matchesName($str, $exception_id = false){
        if(empty($str)) return true;
        
        $where['name'] = $str;
        if($exception_id !== false){
            if( ! is_array($exception_id)) $exception_id = array($exception_id);
            $where['not'] = array('id' => $exception_id);
        } 
        
        $res = $this->MY_data(
            //select
            array('id', 'name'),
            //where
            $where
        );
        
        if(is_array($res) && count($res) > 0){
            return true;
        }else{
            return false;
        }
    }

    /**
     * Проверка на уже имеющиеся название поля "alias"
     * @param  string  $str          строка для проверки
     * @param  array $exception_id   исключающиеся id
     * @return boolean               true - найдено соответсвие, false - соответсвий не найдено
     */
    function matchesAlias($str, $exception_id = false){
        if(empty($str)) return true;
        
        $where['alias'] = $str;
        if($exception_id !== false){
            if( ! is_array($exception_id)) $exception_id = array($exception_id);
            $where['not'] = array('id' => $exception_id);
        } 
        
        $res = $this->MY_data(
            //select
            array('id', 'alias'),
            //where
            $where
        );
        
        if(is_array($res) && count($res) > 0){
            return true;
        }else{
            return false;
        }
    }


    /**
     * Проверка на валидность поля "uri"
     * @param  string $str строка uri
     * @return boolean      true - валидация пройдена, false - ошибка валидации
     */
    function validationUri($str){
        if( $this->form_validation->uri($str)){
            return true;              
        }
        return false;
    }

    /**
     * Проверка на валидность поля "name"
     * @param  string $str строка name
     * @return boolean      true - валидация пройдена, false - ошибка валидации
     */
    function validationName($str){
        if( $this->form_validation->alpha_space_ru($str)){
            return true;              
        }
        return false;
    }

    /**
     * Проверка на валидность поля "alias"
     * @param  string $str строка alias
     * @return boolean      true - валидация пройдена, false - ошибка валидации
     */
    function validationAlias($str){
        if( $this->form_validation->alpha_dash($str)){
            return true;              
        }
        return false;
    }

}
