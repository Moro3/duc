<?php (defined('BASEPATH')) OR exit('No direct script access allowed');



class Replaced extends CI_Driver_Library
{
    public $valid_drivers;
    public $CI;
    public $prefix = array();           // префиксы драйверов
    private $params = array();          // имена параметров
    private $fragment = array();        // найденные фрагменты
  	private $content = '';              // содержимое дл€ парсинга
  	private $mod_content = array();     // раcпарсенные показатели найденных модулей

	private $cache = array();           // кэш фрагментов

    function __construct()
    {
        $this->CI = & get_instance();
        $this->CI->config->load('replaced', TRUE);
        $this->valid_drivers = $this->CI->config->item('type', 'replaced');
        foreach($this->valid_drivers as $value){        	$this->prefix[] = str_replace('replaced_','',$value);
        }
        $this->params = array('name', 'arg');
        log_message('debug', "Replaced Class Initialized");
    }

    /**
    *	возвращает контент с замененными фрагментами
    *   @param string @content - содержимое
    */
	function get_content($content){
	  $this->search_fragments($content);

	  return $this->get_result();
	}

	/**
	*	поиск фрагментов модулей и разбивка их на составл€ющие
	*  @param string $content - содержимое
	*
	*/
	private function search_fragments($content){
	  $this->content = $content;
	  //разбивка на фрагменты
	  $this->split_fragment();
	  //парсер фрагментов
	  $this->parse_fragment();

	}
	/**
	* получение результата
	*
	*	@return - замененное содержимое
	*/
	private function get_result(){
	  if(count($this->mod_content) > 0){

	    // кешируем найденное содержимое
	    /*
	    $this->cache['fragment'][] = $this->fragment;
	    $this->cache['params'][] = $this->params;
	    $this->cache['content'][] = $this->content;
	    $this->cache['mod_content'][] = $this->mod_content;
        */

	      foreach ($this->mod_content as $driver=>$value){

	              foreach($value as $items){
	                if(!empty($items['params'])){
	                  //foreach($items['params'] as $key_param=>$param){
	                    if(is_object($this->$driver)){
	                    	$tpl[$driver][$items['fragment']] = $this->$driver->get_content($items);
	                    }

	                  //}
	                }
	              }


	    }
	  }
	  $content = $this->content;

	  $this->mod_content = array();
	  $this->fragment = array();
	  $this->content = '';

	  if(isset($tpl)){
	    foreach($tpl as $key=>$value){
	      foreach($value as $key2=>$value2){
	        //$content = str_replace($key2, $value2, $content);
	        $content = str_replace($key2, Modules::run('replace/replace/get_content', $value2), $content);
	      }
	    }
	  }
	  //$content = Modules::run('replace/replace/get_content', $content);
      /*
	  echo "<pre>";
	      print_r($this->cache);
	    echo "</pre>";
	    exit();
	  */
	  return $content;
	  //exit();
	}

	//возвращает сгенерированный шаблон модул€
	/*
	function run_modules($modules_tpl, $arg = false){
	    //print_r($modules_tpl);
	    if(!empty($modules_tpl)){
	      $cache_module = '';
	      //foreach($modules_tpl as $items_modules){
	        if(!empty($modules_tpl['module'])) $n_mod[] = $modules_tpl['module'];
	        if(!empty($modules_tpl['controller'])) $n_mod[] = $modules_tpl['controller'];
	        if(!empty($modules_tpl['method'])) $n_mod[] = $modules_tpl['method'];
	        if(is_array($n_mod)) $path_module = implode('/',$n_mod);

	        if(!empty($arg))  $argument = $arg; else $argument = $modules_tpl['arg'];
	        $cache_module .= Modules::run($path_module, $argument);
	      //}

	    }
	    if(isset($cache_module)){
	      return $cache_module;
	    }
	}
	*/

	// разбивка на фрагменты
	function split_fragment(){
	  //print_r($this->prefix);
        //exit;
	  if (!empty($this->content)){
	    if(count($this->prefix) > 0){
	      foreach($this->prefix as $pref){
	        //$pattern = '|'.$this->f_start.''.$pref.' ([^'.$this->f_end.$this->f_start.']*)'.$this->f_end.'|Uis';
            $pattern = $this->$pref->get_pattern_split_fragment();
	        //$parrent = "|".$this->f_start."".$pref.$this->pattern_params."([^".$this->f_end.$this->f_start."]*)".$this->f_end."|Uis";
	        //$parrent = "|".$this->pattern_params."|Uis";
	        //echo "Ўаблон регул€рки дл€ контента: <b>".$pattern."</b><br />";
	        //echo "—одержание: <b>".$this->content."</b><br />";
	        preg_match_all($pattern, $this->content, $arr[$pref]);
	        //echo "<pre>";
	        //  print_r($this->pattern_params);
	        //echo "</pre>";
	        if(is_array($arr[$pref][0]) && $arr[$pref][0] > 0){	        	$res[$pref] = $arr[$pref];
	        }
	      }
	    }
	  }
	  if(isset($res)){
	    $this->fragment = $res;
	  }
	  //echo "<pre>";
	  //print_r($this->fragment);
	  //echo "</pre>";
	  //exit;
	}

	//парсер фрагментов
	function parse_fragment(){

	  if(count($this->fragment) > 0){
	    foreach($this->fragment as $key=>$value){
	      foreach($value[1] as $num=>$item){
	        $pattern = $this->$key->get_pattern_parse_fragment();
	        //echo "$item<br />";
	        //echo "Ўаблон регул€рки дл€ фрагмента: <b>".$pattern."</b><br />";
	        preg_match_all($pattern, $item, $arr[$item]);
	        //$arr_mod[$key][$num] = $arr[$item];
	        $arr_mod[$key][$num]['fragment'] = $value[0][$num];
	        $arr_mod[$key][$num]['params'] = $arr[$item][1];
	        $arr_mod[$key][$num]['value'] = $arr[$item][2];
	      }

	    }
	  }
	  //echo "<pre>";
	  //   print_r($arr_mod);
	  //echo "</pre>";
	  if(isset($arr_mod) && is_array($arr_mod)) $this->mod_content = $arr_mod;

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
		  $pattern = '%('.$params.')[ ]*=[ ]*["|\']*[ ]*([\w]*)[ ]*["|\']*[ ]*%is';
		}else{
		  $pattern = '%%is';
		}
		return $pattern;
    }

}
