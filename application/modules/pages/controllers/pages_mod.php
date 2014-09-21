<?php if (!defined('BASEPATH')) exit('No direct script access allowed');

// Подключаем родительский класс если он не подгужен
if( ! class_exists('pages')){
  include_once ('pages.php');
}

class Pages_mod extends Pages {

  private $prefix = array();          // префиксы модулей
  private $f_start = '{';             // начальный флаг фрагмента
  private $f_end = '}';               // конечный флаг фрагмента
  private $params = array();          // имена свойств
  private $pattern_params = '';       // строка шаблона для поиска параметров в фрагментах
  private $fragment = array();        // найденные фрагменты
  private $content = '';              // содержимое для парсинга
  private $mod_content = array();     // раcпарсенные показатели найденных модулей
  private $cfg_params = array();          // параметры для замены из конфигурационных файлов


  function __construct()
	{
        parent::__construct();


        $this->prefix = array('mdc','cfg');
        $this->params = array('name','arg', 'style', 'js', 'css', 'ajax');
        $this->pattern_params = $this->generate_pattern_param();
        $site = $this->config->item('site');
        /*
        $this->cfg_params = array(
                      "num_tel" => $site['num_tel'],
                      "num_tel2" => $site['num_tel2'],
                      "num_tel_7" => $site['num_tel_7'],
                      "num_tel2_7" => $site['num_tel2_7'],
                      "code_tel_495" => $site['code_tel_495'],
                      "code_tel_499" => $site['code_tel_499'],
                      "num_fax" => $site['num_fax'],
                      "address" => $site['address'],
                      "postal_code" => $site['postal_code'],
                      "city" => $site['city'],
                      "email" => $site['email'],
                      "email2" => $site['email2'],
                      "www" => $site['www'],
                      "name" => $site['name'],
                      "short_name" => $site['short_name'],
            );
        */
        $this->cfg_params = $site;
	}

  // возвращает подключеные модули
  //arg: $id_page -  id или массив id страниц
  //return: array[$id] - $id - id страницы
  function get_modules_tpl($id_page){
    $id_modules = $this->pages_mod_model->get_id_modules_tpl ($id_page);
    //echo "<pre>модуль ".$id_page.":";
    //return($id_modules);
    //echo "</pre>";
    if(is_array($id_modules)){
      foreach($id_modules as $key=>$value){
        $arr_modules[] = $this->modules_tpl_model->get_modules($value['id_module_tpl']);
      }
    }
    if(isset($arr_modules)) return $arr_modules;
    return false;
  }

  // возвращает имена модулей типа 'контентный'
  // id контентного модкля равен 2
  //return: array[$id] - $id - id страницы
  function get_mod_content(){
    $id_modules = $this->pages_mod_model->get_name_mod_content ();

    if(is_array($id_modules)){
      foreach($id_modules as $key=>$value){
        $arr_modules[] = $this->modules_tpl_model->get_modules($value['id']);
      }
    }
    if(isset($arr_modules)) return $arr_modules;
    return false;
  }

  //возвращает контент с замененными фрагментами
  function get_content($content){
    $this->search_fragments($content);
    return $this->get_result();
  }

  // поиск фрагментов модулей и разбивка их на составляющие
  function search_fragments($content){
    $this->content = $content;
    //разбивка на фрагменты
    $this->split_fragment();
    //парсер фрагментов
    $this->parse_fragment();

  }
  // получение результата
  function get_result(){
    if(count($this->mod_content) > 0){
      echo "<pre>";
        print_r($this->mod_content);
      echo "</pre>";
      //exit();
        foreach ($this->mod_content as $key=>$value){

                foreach($value as $items){
                  if(!empty($items['params'])){
                    //foreach($items['params'] as $key_param=>$param){
                      if($key == 'mdc'){
                            $key_name_tpl = array_keys($items['params'],'name');
                          $key_arg = array_keys($items['params'],'arg');
                          //echo "<b>$key_name_tpl</b>";
                          if(isset($key_name_tpl[0]) && !empty($items['value'][$key_name_tpl[0]])){
                            $name_tpl = $items['value'][$key_name_tpl[0]];
                            if(isset($key_arg[0]) && !empty($items['value'][$key_arg[0]])){
                              $arg = $items['value'][$key_arg[0]];
                            }else{
                              $arg = '';
                            }
                            $modules = $this->modules_tpl_model->get_modules_content($name_tpl);
                            $tpl[$key][$items['fragment']] = $this->run_modules($modules, $arg);
                            //print_r($arr_modules);
                          }
                      }elseif($key == 'cfg'){
                          $key_name_tpl = array_keys($items['params'],'name');
                          if(isset($key_name_tpl[0]) && !empty($items['value'][$key_name_tpl[0]])){
                              if(isset($this->cfg_params[$items['value'][$key_name_tpl[0]]])){
                                  $tpl[$key][$items['fragment']] = $this->cfg_params[$items['value'][$key_name_tpl[0]]];
                              }else{
                                  $tpl[$key][$items['fragment']] = '';
                              }

                          }
                      }
                    //}
                  }
                }


      }
    }
    $content = $this->content;
    if(isset($tpl)){
      foreach($tpl as $key=>$value){
        foreach($value as $key2=>$value2){
          $content = str_replace($key2, $value2, $content);
        }
      }
    }
    return $content;
    //exit();
  }

  //возвращает сгенерированный шаблон модуля
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

  // разбивка на фрагменты
  function split_fragment(){
    if (!empty($this->content)){
      if(count($this->prefix) > 0){
        foreach($this->prefix as $pref){
          $pattern = '|'.$this->f_start.''.$pref.' ([^'.$this->f_end.$this->f_start.']*)'.$this->f_end.'|Uis';
          //$parrent = "|".$this->f_start."".$pref.$this->pattern_params."([^".$this->f_end.$this->f_start."]*)".$this->f_end."|Uis";
          //$parrent = "|".$this->pattern_params."|Uis";
          //echo "Шаблон регулярки для контента: <b>".$pattern."</b><br />";
          //echo "Содержание: <b>".$this->content."</b><br />";
          preg_match_all($pattern, $this->content, $arr[$pref]);
          //echo "<pre>";
          //  print_r($this->pattern_params);
          //echo "</pre>";
        }
      }
    }
    if(isset($arr)){
      $this->fragment = $arr;
    }
    //echo "<pre>";
    //print_r($this->fragment);
    //echo "</pre>";
  }

  //парсер фрагментов
  function parse_fragment(){

    if(count($this->fragment) > 0){
      foreach($this->fragment as $key=>$value){
        foreach($value[1] as $num=>$item){
          $pattern = "%".$this->pattern_params."%is";
          //echo "$item<br />";
          //echo "Шаблон регулярки для фрагмента: <b>".$pattern."</b><br />";
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

  //функция генерирует строку шаблона для извлечения параметров из фрагментов
  function generate_pattern_param(){
    //$pattern = '';
    if(count($this->params) > 0){
      foreach($this->params as $item){
        //$pattern .= '[]*'.$item.'[]*=[]*[\"]?([^'.$this->f_end.$this->f_start.'\"\']*)[\"]?';
      }
      $params = implode("|",$this->params);
    }
    if(!empty($params)){
      $pattern = '('.$params.')[ ]*=[ ]*["|\']*[ ]*([\w]*)[ ]*["|\']*[ ]*';
    }else{
      $pattern = '';
    }
    return $pattern;
  }
}








