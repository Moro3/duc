<?php if (!defined('BASEPATH')) exit('No direct script access allowed');

/**
 *  HTree Adjacency Model
 *
*/


class Adjacency_model extends CI_Model {
    private $_level_name = 'level_';
    private $_level_start = 1; // с какой отметки считать первый уровень
    //public $order;
    public $key_cache;
    public $setting = array();

    function __construct(){
      parent::__construct();
      //$this->_get_num_nodes_cycle();
      //$CI =& get_instance(); // to access CI resources, use $CI instead of $this
      //$CI->load->library('order_arj');
      //$this->order =& $CI->order_arj;

      //$this->load->library('order_arj');
      //$this->order = $this->order_arj;
    }

/*/////////////////////////////////////////
|  Основные методы извлечения данных и записью в кеш
|
*//////////////////////////////////////////


    function _get_node_all(){
      if (isset($this->condition_field)){
        if(is_array($this->condition_field)){
          foreach($this->condition_field as $key=>$value){
            $this->db->where($this->table.'.'.$key, $value);
          }
        }
      }
      $this->db->select($this->table.'.'.$this->parent_field);
      $this->db->distinct();
      $this->db->from($this->table);
      $this->db->where($this->table.'.'.$this->parent_field.' !=','0');
      $query = $this->db->get();
      return $query->num_rows();
    }

    // кол-во всех узлов
    function _get_num_nodes_all(){
      if(!isset($this->setting[$this->table][$this->key_cache]['max_nodes'])){

        $this->db->select('count(*)');
        //$this->db->distinct();
        $this->db->from($this->table);
        $this->_add_condition($this->table);
        $query = $this->db->get();
        $row = $query->row_array();
        //print_r($query->row_array());
        $this->setting[$this->table][$this->key_cache]['max_nodes'] = $row['count(*)'];
      }
      return $this->setting[$this->table][$this->key_cache]['max_nodes'];
    }

    // все листья дерева, т.е. узлы без детей
    function _get_num_nodes_leaf(){
      if (!isset($this->setting[$this->table][$this->key_cache]['max_nodes_leaf'])){
        //$this->db->select('t1.'.$this->name_field);
        $this->db->select('t1.'.$this->id_field);
        $this->db->from($this->table.' AS t1');
        $this->db->join($this->table.' AS t2', 't1.'.$this->id_field.' = t2.'.$this->parent_field.'', 'left', FALSE);
        $this->db->where('t2.'.$this->id_field, null);
        $this->_add_condition('t1');

        $query = $this->db->get();
        $this->setting[$this->table][$this->key_cache]['max_nodes_leaf'] = $query->num_rows();
      }
      return $this->setting[$this->table][$this->key_cache]['max_nodes_leaf'];
    }

    // все листья определенного узла, т.е. детей без внуков
    function _get_nodes_leaf($node){

        $this->db->select('t1.'.$this->name_field);
        $this->db->select('t1.'.$this->id_field);
        $this->_add_select('t1');
        $this->db->from($this->table.' AS t1');
        $this->db->join($this->table.' AS t2', 't1.'.$this->id_field.' = t2.'.$this->parent_field.'', 'left', FALSE);
        $this->db->where('t2.'.$this->parent_field, $node);
        $this->db->where('t2.'.$this->id_field, null);
        $this->_add_condition('t1');

        $query = $this->db->get();


      return $query->result_array();;
    }

    // все узлы дерева не листья (т.е. узлы с детьми)
    function _nodes_no_leaf(){

      $this->db->select('t1.'.$this->parent_field);
      $this->db->select('t1.'.$this->id_field);
      $this->db->select('t1.'.$this->name_field);
      $this->db->distinct();
      $this->db->from($this->table.' AS t1');
      $this->db->join($this->table.' AS t2', 't1.'.$this->id_field.' = t2.'.$this->parent_field.'', 'left', FALSE);
      $this->db->where('t2.'.$this->id_field.' IS NOT NULL', null, FALSE);
      $this->_add_condition('t1');

      $query = $this->db->get();
      $this->setting[$this->table][$this->key_cache]['nodes_no_leaf'] = $query->result_array();

    }

    // кол-во узлов дерева не листья
    function _get_num_nodes_no_leaf(){
      if(!isset($this->setting[$this->table][$this->key_cache]['max_nodes_no_leaf'])){
        if(!isset($this->setting[$this->table][$this->key_cache]['nodes_no_leaf'])){
          $this->_nodes_no_leaf();
        }
        $this->setting[$this->table][$this->key_cache]['max_nodes_no_leaf'] = count($this->setting[$this->table][$this->key_cache]['nodes_no_leaf']);
      }
      return $this->setting[$this->table][$this->key_cache]['max_nodes_no_leaf'];
    }
    // все узлы дерева не листья
    function _get_nodes_no_leaf(){
      if(!isset($this->setting[$this->table][$this->key_cache]['nodes_no_leaf'])){
        $this->_nodes_no_leaf();
      }
      return $this->setting[$this->table][$this->key_cache]['nodes_no_leaf'];
    }


/*/////////////////////////////////////////
|  Работа с разорванными узлами
|  узлы у которых путь не приводит к корню
*//////////////////////////////////////////
    // разорванные узлы дерева
    function _nodes_torn(){

      $this->db->select('t1.'.$this->parent_field);
      $this->db->select('t1.'.$this->id_field);
      $this->_add_select('t1');
      //$this->db->distinct();
      $this->db->from($this->table.' AS t1');
      //$this->db->join($this->table.' AS t2', 't1.'.$this->parent_field.' = t2.'.$this->id_field.'', 'left', FALSE);
      $this->db->join($this->table.' AS t2', 't1.'.$this->parent_field.' = t2.'.$this->id_field.' '.$this->_get_condition_join('t2') , 'left', FALSE);
      $this->db->where('t2.'.$this->id_field, null);
      $this->db->where('t1.'.$this->parent_field.' !=', 0);
      //$this->_add_condition('t1');
      $this->_add_condition('t1');

      $query = $this->db->get();
      $this->setting[$this->table][$this->key_cache]['nodes_torn'] = $query->result_array();
    }

    // возвращает кол-во разорванных узлов
    function _get_num_nodes_torn(){
      if (!isset($this->setting[$this->table][$this->key_cache]['max_nodes_torn'])){
        if(!isset($this->setting[$this->table][$this->key_cache]['nodes_torn'])){
          $this->_nodes_torn();
        }
        $this->setting[$this->table][$this->key_cache]['max_nodes_torn'] = count($this->setting[$this->table][$this->key_cache]['nodes_torn']);
      }

      return $this->setting[$this->table][$this->key_cache]['max_nodes_torn'];
    }

    // возвращает разорванные узлы дерева
    function _get_nodes_torn(){

        if(!isset($this->setting[$this->table][$this->key_cache]['nodes_torn'])){
          $this->_nodes_torn();
        }

      if(isset($this->setting[$this->table][$this->key_cache]['nodes_torn'])){
        return $this->setting[$this->table][$this->key_cache]['nodes_torn'];
      }
      return false;
    }
/*--------- конец работы с разорванными узлами ----------*/




/*////////////////////////////////////////
| Работа с уровнем
|
*/////////////////////////////////////////
    // подсчет уровней
    function _level_create(){
      if($this->_get_num_cycle() == 0){
        $arr_nodes = &$this->_get_nodes_no_leaf();
        //print_r($arr_nodes);
        if(is_array($arr_nodes)){
          foreach($arr_nodes as $key=>$value){
            $this->_write_level($value[$this->id_field],$this->_level_start);
          }
        }
      }
    }
    // определение уровня и его запись в кэш
    function _write_level($item, $lev){
      $arr_nodes = &$this->_arr_convert_nodes_no_leaf();
      if(isset($arr_nodes[$item])){
        if($arr_nodes[$item] == 0){
          $this->_set_level($item,$this->_level_start);
        }else{
          $parent = $arr_nodes[$item];
          $this->_write_level($parent,$lev);
          $lev++;
          $this->_set_level($item,$lev);
        }
      }else{
        $key_id = array_keys($arr_nodes,$item);
        //print_r ($key_id);
        echo "Не найдена связь узла {$key_id[0]} с другим узлом и узел не является корневым!<br />";
      }
    }
    // установка уровня для определенного узла в кэш
    function _set_level($item,$lev){
      $this->setting[$this->table][$this->key_cache]['level'][$item] = $lev;
    }
    // возвращает уровень узла
    function _get_level($id){
      if (isset($this->setting[$this->table][$this->key_cache]['level'][$id])){
        return $this->setting[$this->table][$this->key_cache]['level'][$id];
      }
      if(isset($this->setting[$this->table][$this->key_cache]['level'])){
        $lev = $this->_search_level_on_parent($id);
        if($lev != false) {
          $this->_set_level($id,$lev);
          return $lev;
        }
      }else{
        $this->_level_create();
        if (isset($this->setting[$this->table][$this->key_cache]['level'][$id])){
          return $this->setting[$this->table][$this->key_cache]['level'][$id];
        }
        if(isset($this->setting[$this->table][$this->key_cache]['level'])){
          $lev = $this->_search_level_on_parent($id);
          if($lev != false) {
            $this->_set_level($id,$lev);
            return $lev;
          }
        }

          $this->_set_root_leaf();
          $lev_leaf = $this->_search_root_leaf($id);
          if($lev_leaf != false){

            $this->_set_level($id,$this->_level_start);
            return $this->_level_start;
          }else{
            echo "уровень не определен";
            return false;
          }

      }
    }
    // поиск узла по родителю
    // параметры: $node - узел уровень которого требуется найти
    // return: уровень узла $node
    function _search_level_on_parent($node){
      $parent = $this->_get_parent($node);
        if($parent != false){
          if($parent == 0){
            $this->_set_level($node,$this->_level_start);
            return $this->_level_start;
          }elseif (isset($this->setting[$this->table][$this->key_cache]['level'][$parent])){
            $lev = $this->setting[$this->table][$this->key_cache]['level'][$parent] + 1;
            $this->_set_level($node,$lev);
            return $lev;
          }else{
            echo "уровень родителя не определен 2!";
            return false;
          }
        }else{
          echo "Родитель узла $node не определен!";
          return false;
        }
    }
    // возвращает кол-во уровней
    function _get_max_level(){
      if (!isset($this->setting[$this->table][$this->key_cache]['max_level'])){
        if(!isset($this->setting[$this->table][$this->key_cache]['level'])){
          $this->_level_create();
        }
        if(!isset($this->setting[$this->table][$this->key_cache]['level'])){
          if($this->_get_num_nodes_no_leaf() < 1){
            if($this->_get_num_nodes_leaf() >= 1){
              $this->setting[$this->table][$this->key_cache]['max_level'] = 1;
            }else{
              $this->setting[$this->table][$this->key_cache]['max_level'] = 0;
            }
          }else{
            $this->error('max_level','не удалось вычислить кол-во уровней',3);
            $this->setting[$this->table][$this->key_cache]['max_level'] = 0;
          }
        }else{
          $this->setting[$this->table][$this->key_cache]['max_level'] = (max($this->setting[$this->table][$this->key_cache]['level'])+2) - $this->_level_start;
        }
      }
      return $this->setting[$this->table][$this->key_cache]['max_level'];
    }
/*--------- конец работы с уровнями ----------*/


/*////////////////////////////////////////
| Работа с корнями дерева
| корень - узел у которого родитель = 0
*////////////////////////////////////////
    // поиск узла по корню, которые не имеют детей
    // params: $node - узел который требуется найти
    // return: boolean
    function _search_root_leaf($node){
      if (isset($this->setting[$this->table][$this->key_cache]['root_leaf'][$node])){
        return true;
      }
      return false;
    }

    // определения корней которые НЕ имеют детей
    // т.е. корни листья
    function _set_root_leaf(){
      if(!isset($this->setting[$this->table][$this->key_cache]['root_leaf'])){
        $this->db->select('t1.'.$this->name_field);
        $this->db->select('t1.'.$this->id_field);
        $this->db->from($this->table.' AS t1');
        $this->db->join($this->table.' AS t2', 't1.'.$this->id_field.' = t2.'.$this->parent_field.'', 'left', FALSE);
        $this->db->where('t1.'.$this->parent_field, 0);
        $this->db->where('t2.'.$this->id_field, null);
        $this->_add_condition('t1');

        $query = $this->db->get();
        $arr_result = $query->result_array();
        foreach($arr_result as $item){
          $this->setting[$this->table][$this->key_cache]['root_leaf'][$item[$this->id_field]] = $item[$this->name_field];
        }
        //$this->setting[$this->table][$this->key_cache]['root_leaf'] = $query->result_array();
      }
    }
    // определение корней которые имеют детей
    // т.е. корни с узлами
    function _set_root_with_children(){

    }
/*--------- конец работы с корнями ----------*/



/*/////////////////////////////////////
| Работа с кэшем
|
*/////////////////////////////////////
    // установка параметров кэша
    function _set_cache($name,$value, $array_num = false){

      switch($name){
        case 'cycle':
          if(is_array($value)){
            $this->setting[$this->table][$this->key_cache][$name][] = $value;
          }else{
            $this->setting[$this->table][$this->key_cache][$name][] = Array($value);
          }
        break;
        case 'tmp_cycle':
          if ($arr = $this->_get_cache($name)){
            $arr_merge = array_merge($arr,array($value));
            $this->setting[$this->table][$this->key_cache][$name] = $arr_merge;
          }else{
            $this->setting[$this->table][$this->key_cache][$name][] = $value;
          }
        break;
        case 'num_cycle':

        break;
      }
    }
    // получение параметров кэша
    function _get_cache($name){
      switch($name){
        case 'cycle':
          if(isset($this->setting[$this->table][$this->key_cache][$name])){
            return $this->setting[$this->table][$this->key_cache][$name];
          }else{
            return false;
          }
        break;
        case 'tmp_cycle':
          if(isset($this->setting[$this->table][$this->key_cache][$name])){
            return $this->setting[$this->table][$this->key_cache][$name];
          }else{
            return false;
          }
        break;
        case 'num_cycle':
          if(isset($this->setting[$this->table][$this->key_cache][$name])){
            return $this->setting[$this->table][$this->key_cache][$name];
          }else{
            return false;
          }
        break;
        default:

      }
    }
    // возвращает весь кеш
    function _get_all_cache(){
      if(isset($this->setting)){
            return $this->setting;
          }else{
            return false;
          }
    }
    // удаление параметров кэша
    function _del_cache($name){
      if(isset($this->setting[$this->table][$this->key_cache][$name]))
      unset($this->setting[$this->table][$this->key_cache][$name]);
    }
    // удаление всего кэша
    function _del_all_cache(){
      if(isset($this->setting[$this->table][$this->key_cache]))
      unset($this->setting[$this->table][$this->key_cache]);
    }
/*--------- конец работы с кэшем ----------*/

/*////////////////////////////////////
| Вспомогательные утилиты
|
*//////////////////////////////////////

    // конвертация многомерного массива из узлов с детьми в одномерный
    // т.е. где $key = номер узла, $value = родитель узла
    function _arr_convert_nodes_no_leaf(){
      if(!isset($this->setting[$this->table][$this->key_cache]['nodes_parent_no_leaf'])){
        $nodes = &$this->_get_nodes_no_leaf();
        foreach($nodes as $key=>$value){
          $arr_nodes[$value[$this->id_field]] = $value[$this->parent_field];
        }
        if (isset($arr_nodes)) $this->setting[$this->table][$this->key_cache]['nodes_parent_no_leaf'] = $arr_nodes;
      }
      if(isset($this->setting[$this->table][$this->key_cache]['nodes_parent_no_leaf']))
      return $this->setting[$this->table][$this->key_cache]['nodes_parent_no_leaf'];
      return false;
    }

    // преобразование многомерного массива в одномерный
    // если встречаются одинаковые ключи, то перезаписывается последним
    function _convert_uniarr(&$arr){
      if (is_array($arr) && count($arr) > 0){
        foreach($arr as $key=>$value){
          if (is_array($value)){
            $uniarr[$value[$this->id_field]] = $value[$this->parent_field];
            //foreach($value as $key2=>$value2){
              //$uniarr[$key2] = $value2;
            //}
          }else return false;
        }
      }else return false;
      return $uniarr;
    }

    // обход массива на предмет обнаружения связей
    // params: $array - массив для перебора, $node - узел вхождения, $root - узел завершения (0 - корень)
    // return: все найденный связи в виде массива
    function _get_rewalk_array($array, $node, &$arr = array(), $root = 0){

      if(isset($array[$node])){
        if($node == $array[$node]) return false;
        $arr[] = $node;
        //echo "выход до рекурсии: <br />";
        //print_r($arr)."<br />";
        if($array[$node] != $root){
          if($this->_get_rewalk_array($array, $array[$node], $arr, $root) == false) return false;
        }
        //echo "выход после рекурсии: <br />";
        //print_r($arr)."<br />";
      }
      if (isset($arr))return $arr;
      return false;
    }


/*--------- конец работы с утилитами ----------*/



/*//////////////////////////////////////////////
| Работа с циклами
|
*///////////////////////////////////////////////
    // цикличные узлы дерева
    function _nodes_cycle(){

      $this->db->select('t1.'.$this->parent_field);
      $this->db->select('t1.'.$this->id_field);
      //$this->db->distinct();
      $this->db->from($this->table.' AS t1');
      //$this->db->join($this->table.' AS t2', 't1.'.$this->parent_field.' = t2.'.$this->id_field.'', 'left', FALSE);
      $this->db->join($this->table.' AS t2', 't1.'.$this->id_field.' = t2.'.$this->parent_field.' AND t1.'.$this->parent_field.' = t2.'.$this->id_field.' '.$this->_get_condition_join('t2') , 'left', FALSE);
      $this->db->where('t2.'.$this->id_field.' IS NOT NULL', null, FALSE);
      //$this->db->where('t1.'.$this->parent_field.' !=', 0);
      //$this->_add_condition('t1');
      $this->_add_condition('t1');

      $query = $this->db->get();
      $this->setting[$this->table][$this->key_cache]['nodes_cycle'] = $query->result_array();
    }

    /*
    ВОЗВРАЩАЕТ ТОЛЬКО ЗАЦИКЛЕННЫЕ НАПРЯМУЮ
    // возвращает кол-во цикличных узлов (т.е. кол-во узлов участвующих в циклах)
    function _get_num_nodes_cycle(){
      if(!isset($this->setting[$this->table][$this->key_cache]['max_nodes_cycle'])){
        if(!isset($this->setting[$this->table][$this->key_cache]['nodes_cycle'])){
          $this->_nodes_cycle();
        }
        $this->setting[$this->table][$this->key_cache]['max_nodes_cycle'] = count($this->setting[$this->table][$this->key_cache]['nodes_cycle']);
      }
      return $this->setting[$this->table][$this->key_cache]['max_nodes_cycle'];
    }
    */
    /*
    ВОЗВРАЩАЕТ ТОЛЬКО ЗАЦИКЛЕННЫЕ НАПРЯМУЮ
    // возвращает цикличные узлы
    function _get_nodes_cycle(){
      if(!isset($this->setting[$this->table][$this->key_cache]['nodes_cycle'])){
        $this->_nodes_cycle();
      }
      if (isset($this->setting[$this->table][$this->key_cache]['nodes_cycle'])){
        return $this->setting[$this->table][$this->key_cache]['nodes_cycle'];
      }
      return false;
    }
    */
    // возвращает кол-во цикличных узлов (т.е. кол-во узлов участвующих в циклах)
    function _get_num_nodes_cycle(){
      if(!isset($this->setting[$this->table][$this->key_cache]['max_nodes_cycle'])){
        if(!isset($this->setting[$this->table][$this->key_cache]['cycle'])){
          $this->_cycle_check();
        }
        $nn_nodes = 0;
        if(isset($this->setting[$this->table][$this->key_cache]['cycle'])){
          foreach ($this->setting[$this->table][$this->key_cache]['cycle'] as $key=>$value){
            $nn_nodes += count($value);
          }
        }
        $this->setting[$this->table][$this->key_cache]['max_nodes_cycle'] = $nn_nodes;
      }
      return $this->setting[$this->table][$this->key_cache]['max_nodes_cycle'];
    }

    // возвращает цикличные узлы
    function _get_nodes_cycle(){
      if(!isset($this->setting[$this->table][$this->key_cache]['cycle'])){
        $this->_cycle_check();
      }
      if (isset($this->setting[$this->table][$this->key_cache]['cycle'])){
        foreach($this->setting[$this->table][$this->key_cache]['cycle'] as $key=>$value){
          $arr_nodes[] = $this->get_node($value);
        }
        if(isset($arr_nodes)) return $arr_nodes;
      }
      return false;
    }

    // возвращает кол-во циклов (т.е. кол-во невзаимосвязанных циклов)
    function _get_num_cycle(){
      if (!isset($this->setting[$this->table][$this->key_cache]['max_cycle'])){
        $this->_cycle_check();
      }
      if(isset($this->setting[$this->table][$this->key_cache]['max_cycle'])){
        return $this->setting[$this->table][$this->key_cache]['max_cycle'];
      }else{
        return 0;
      }
    }
    // проверка на зацикливание
    function _cycle_check(){
      $arr = $this->_convert_uniarr($this->_get_nodes_no_leaf());

      //print_r($arr);
      if(is_array($arr)){
        foreach($arr as $key=>$value){
           $this->_cycle_search($arr,$key, $key);
        }
      }

      if (isset($this->setting[$this->table][$this->key_cache]['cycle'])){
        if(is_array($this->setting[$this->table][$this->key_cache]['cycle'])){
          $num = count($this->setting[$this->table][$this->key_cache]['cycle']);
        }
      }
      if(!isset($num)) $num = 0;
      $this->setting[$this->table][$this->key_cache]['max_cycle'] = $num;
    }
    // поиск циклов и запись их в кеш
    function _cycle_search(&$arr, $start, $key){
        //$tmp['end'] = $value;
      if($start == $arr[$key]){
        //$this->_set_cache('cycle_on',1);
        if(!$this->_get_search_cycle($arr[$key])){
          $this->_set_cache('tmp_cycle',$arr[$key]);
          $this->_set_cache('cycle',$this->_get_cache('tmp_cycle'));
        }
      }else{
        if(isset($arr[$arr[$key]])){
          $this->_set_cache('tmp_cycle',$arr[$key]);
          //$this->setting[$this->table][$this->key_cache]['cycle_all'][$level][] = $arr[$key];
          //$level ++;
          $this->_cycle_search($arr,$start, $arr[$key]);
          //$level --;
        }
      }
      $this->_del_cache('tmp_cycle');
    }
    // возврат найденного цикла
    function _get_search_cycle($item){
      if (isset($this->setting[$this->table][$this->key_cache]['cycle'])){
        if (is_array($this->setting[$this->table][$this->key_cache]['cycle'])){
          foreach($this->setting[$this->table][$this->key_cache]['cycle'] as $key=>$value){
            if(in_array($item,$value)){
              return true;
            }
          }
        }
      }
      return false;
    }
/*--------- конец работы с циклами ----------*/


/*//////////////////////////////////////////////
| Работа с дополнительными параметрами
|
*///////////////////////////////////////////////

    // дополнительные условия к запросам
    function _add_condition($table, $add_join = false){
      if (isset($this->condition_field)){
        if(is_array($this->condition_field)){
          $k = 0;
          $this->condition_join = '';
          foreach($this->condition_field as $key=>$value){
            if($add_join == 'join'){
              if($k == 1){
                $this->condition_join .= ' AND ';
              }
              $this->condition_join .= '`'.$key.'` = `'.$value.'`';
              $k = 1;
            }else{
              	if($table != $this->table){
                	if(is_array($value)){                		$this->db->where_in($table.'.'.$key, $value);
                	}else{                		$this->db->where($table.'.'.$key, $value);
                	}
              	}else{
                	if(is_array($value)){
                		$this->db->where_in($this->table.'.'.$key, $value);
                	}else{
                		$this->db->where($this->table.'.'.$key, $value);
                	}
              	}
            }
          }
        }
      }
    }

    // дополнительные условия к запросам JOIN
    function _get_condition_join($table){
      $this->condition_join = '';
      if (isset($this->condition_field)){
        if(is_array($this->condition_field)){

          foreach($this->condition_field as $key=>$value){
             $this->condition_join .= ' AND ';
             $this->condition_join .= ''.$table.'.'.$key.' = '.$value.'';
          }
        }
      }
      return $this->condition_join;
    }

    // дополнительные условия SELECT
    function _add_select($table, $add_join = false){
      if(isset($this->method_order) && $this->method_order == 'arj' && isset($this->order_field)){
        if($table != $this->table){
          $this->db->select($table.'.'.$this->order_field);
        }else{
          $this->db->select($this->table.'.'.$this->order_field);
        }
      }

      if (isset($this->select_field)){
        foreach ($this->select_field as $field){
          if($table != $this->table){
            $this->db->select($table.'.'.$field);
          }else{
            $this->db->select($this->table.'.'.$field);
          }
        }
      }
    }

    // дополнительные условия к SET
    function _add_set ($table){
      if (isset($this->condition_field)){
        if(is_array($this->condition_field)){
          foreach($this->condition_field as $key=>$value){
             $this->db->set($key, $value);
          }
        }
      }
    }

    // дополнительные условия к WHERE
    function _add_where ($table){
      if (isset($this->condition_field)){
        if(is_array($this->condition_field)){
          foreach($this->condition_field as $key=>$value){
             $this->db->where($key, $value);
          }
        }
      }
    }
/*--------- конец работы с дополнительными параметрами ----------*/


/*//////////////////////////////////////////////
| Работа с приложением
| Основные методы работы используемы в классе посредника
*///////////////////////////////////////////////
    /*
    // возвращает родителя
    // params: id узла
    // return: (integer) id - родителя
    function _get_parent($id){
      if(is_numeric($id)){
        if(isset($this->setting[$this->table][$this->key_cache]['nodes_parent_no_leaf'][$id])){
          return $this->setting[$this->table][$this->key_cache]['nodes_parent_no_leaf'][$id];
        }else{
          $this->db->select($this->parent_field);
          $this->db->from($this->table);
          $this->db->where($this->id_field, $id);
          $this->_add_condition($this->table);
          $query = $this->db->get();
          $row = $query->row_array();
          if(isset($row[$this->parent_field])){
            return  $row[$this->parent_field];
          }else{
            return false;
          }
        }
      }
    }
    */
    //возвращает весь список меню
    function get_all(){
          $this->db->select($this->id_field.','.
                            $this->parent_field.','.
                            $this->order_field.','.
                            $this->name_field
                            );
          $this->_add_select($this->table);
          $this->db->from($this->table);
          $this->_add_condition($this->table);
          $query = $this->db->get();
          $row = $query->result_array();
          if(isset($row)){
            return  $row;
          }else{
            return false;
          }
    }

    // возвращает родителя
    // поиск узла в списка родителей и выдача того
    // если поиск неудачный, делаем запрос на выдачу родителя
    // делаем запрос на выдачу детей найденого родителя и записываем данные в кеш
    // возвращаем родителя
    function _get_parent($id){
      if(is_numeric($id)){
        if(isset($this->setting[$this->table][$this->key_cache]['nodes_parent_no_leaf'][$id])){
          return $this->setting[$this->table][$this->key_cache]['nodes_parent_no_leaf'][$id];
        }else{
          if (isset($this->setting[$this->table][$this->key_cache]['nodes_child_parent'])){
            foreach($this->setting[$this->table][$this->key_cache]['nodes_child_parent'] as $key=>$value){
              if(isset($value[$id])){
                return $key;
              }
            }
          }

          $this->db->select($this->parent_field);
          $this->db->from($this->table);
          $this->db->where($this->id_field, $id);
          $this->_add_condition($this->table);
          $query = $this->db->get();
          $row = $query->row_array();
          if(isset($row[$this->parent_field])){
            $this->get_child($row[$this->parent_field]);
            return  $row[$this->parent_field];
          }else{
            return false;
          }
        }
      }
    }
    // возвращает всех родителей дерева
    function get_all_parent(){
      $this->_nodes_no_leaf();
      $this->_arr_convert_nodes_no_leaf();
      //$this->_get_level($node);
      if(isset($this->setting[$this->table][$this->key_cache]['nodes_parent_no_leaf'])){
        $arr_parent = array(0);
        $arr_parent = array_merge($arr_parent,array_keys($this->setting[$this->table][$this->key_cache]['nodes_parent_no_leaf']));
        return $arr_parent;
      }else{
        return false;
      }
    }

    //возвращает все корневые узлы
    function get_root(){
      $this->db->select($this->table.'.'.$this->id_field);
      $this->db->select($this->table.'.'.$this->order_field);
          $this->db->from($this->table);
          $this->db->where($this->table.'.'.$this->parent_field, '0');
          $this->_add_condition($this->table);
          $query = $this->db->get();
          //var_dump($query);
          $row = $query->result_array();

          if(isset($row) && is_array($row)){

            $row = $this->get_array_order($row);
            foreach($row as $key=>$value){
              $arr[] = $value[$this->id_field];
            }
          }
          if(isset($arr)) return $arr;
          return false;

    }

    // возвращает детей
    function get_child($id){

      if(!is_array($id)){
        if (isset($this->setting[$this->table][$this->key_cache]['nodes_child_parent'][$id])){
          return $this->get_array_order($this->setting[$this->table][$this->key_cache]['nodes_child_parent'][$id]);
        }
        $id = array($id);
      }

      foreach($id as $parents){
        if (isset($this->setting[$this->table][$this->key_cache]['nodes_child_parent'][$parents])){
          $arr_parent[$parents] = $this->setting[$this->table][$this->key_cache]['nodes_child_parent'][$parents];
        }else{
          $id_for_query[] = $parents;
        }
      }

      if(isset($id_for_query) && is_array($id_for_query)){
        $this->db->select($this->table.'.'.$this->parent_field.','.$this->table.'.'.$this->name_field.','.$this->table.'.'.$this->id_field, FALSE);
        $this->_add_select($this->table);
        $this->db->from($this->table);
        $this->db->where_in($this->table.'.'.$this->parent_field,$id_for_query);
        $this->_add_condition($this->table);
        $query = $this->db->get();

        $arr_child = $query->result_array();

        if(is_array($arr_child)){
          foreach($arr_child as $key=>$value){
            $arr_parent[$value[$this->parent_field]][] = $value;
          }
        }else{
          return false;
        }
      }
        if(isset($arr_parent)){
          $arr_result = array();
          foreach($arr_parent as $key_parent=>$value_parent){
            $arr_order = $this->get_array_order($value_parent);
            $arr_result = array_merge($arr_result,$arr_order);
          }
        }else{
          return false;
        }

        if (count($arr_result > 0)){
          foreach($arr_result as $items){
            $this->setting[$this->table][$this->key_cache]['nodes_child_parent'][$items[$this->parent_field]][$items[$this->id_field]] = $items;
          }
          return $arr_result;
        }
        return false;
    }
    // возвращает братьев узла
    function get_sibling($node){
      $parent = $this->_get_parent($node);
      if($parent !== false){
        return $this->get_child($parent);
      }
      return false;
    }
    // возвращает впереди стоящего брата
    function get_prev_sibling($node){
      $sibling = $this->get_sibling($node);
      if(isset($sibling) && is_array($sibling)){

        foreach ($sibling as $key=>$value){
          if($node == $value[$this->id_field]){
            if(isset($prev)) return $prev;
          }
          $prev = $value[$this->id_field];
        }
      }
      return false;
    }
    // возвращает после стоящего брата
    function get_next_sibling($node){
      $sibling = $this->get_sibling($node);
      if(isset($sibling) && is_array($sibling)){
        $next = 0;
        foreach ($sibling as $key=>$value){
          if($next == 1) return $value[$this->id_field];
          if($node == $value[$this->id_field]){
            $next = 1;
          }
        }
      }
      return false;
    }
    // возвращает путь до узла
    function get_path($node){
      $this->_nodes_no_leaf();
      $this->_arr_convert_nodes_no_leaf();
      //$this->_get_level($node);
      if(isset($this->setting[$this->table][$this->key_cache]['nodes_parent_no_leaf'][$node])){
        $arr = $this->_get_rewalk_array($this->setting[$this->table][$this->key_cache]['nodes_parent_no_leaf'],$node);
        $arr = array_values(array_reverse($arr));
      }else{
        $parent = $this->_get_parent($node);
        // если родитель равен 0, то устанавливаем одно значения для пути(сам узел)
        if($parent === 0){
          $arr[] = $node;
        }elseif($parent !== false){
          // если родитель
          if(isset($this->setting[$this->table][$this->key_cache]['nodes_parent_no_leaf'][$parent])){
            $arr = $this->_get_rewalk_array($this->setting[$this->table][$this->key_cache]['nodes_parent_no_leaf'],$parent);
          }
          if (isset($arr) && is_array($arr)){
            $arr = array_values(array_reverse($arr));
            $arr[] = $node;
          }else{
            return false;
          }
        }else{
          return false;
        }
      }
      //print_r($arr);
      //exit;
      //если есть массив пути, ищем имя меню и записываем в массив к родителю
      if(isset($arr) && is_array($arr)){

          /*
           * Теряется информация если ищется одиночный путь при наличии нескольких меню
           * возможная проблема - алгоритм поиска пути в кеш
           */
        // ищем информацию в кеш
//        if (isset($this->setting[$this->table][$this->key_cache]['nodes_no_leaf'])){
//          foreach ($arr as $num_key=>$id_key){
//            foreach($this->setting[$this->table][$this->key_cache]['nodes_no_leaf'] as $key=>$value){
//
//              if($value[$this->id_field] == $id_key){
//                $arr_ass[] = array($this->id_field => $id_key, $this->name_field => $value[$this->name_field]);
//                break;
//              }else{
//                $arr_ass[] = array($this->id_field => $id_key, $this->name_field => $this->get_name_node($id_key));
//                break;
//              }
//            }
//          }
//        //если нет кеша делаем запрос
//        }else{
          foreach ($arr as $num_key=>$id_key){
            $arr_ass[] = array($this->id_field => $id_key, $this->name_field => $this->get_name_node($id_key));
          }
//        }
      }
      if(isset($arr_ass)){
          //print_r($arr_ass);
          //exit;
          return $arr_ass;
      }
      return false;
    }
    // возвращает имя узла
    // params: $node - узел
    // return: имя узла
    function get_name_node($node){
      if(isset($this->setting[$this->table][$this->key_cache]['nodes_no_leaf'])){
        foreach($this->setting[$this->table][$this->key_cache]['nodes_no_leaf'] as $key=>$value){
          if(isset($value[$this->id_field]) && $value[$this->id_field] == $node){
             if(!empty($value[$this->name_field])) return $value[$this->name_field];
          }
        }
      }
        $this->db->select($this->table.'.'.$this->name_field, FALSE);
        $this->db->from($this->table);
        $this->db->where($this->table.'.'.$this->id_field,$node);
        $this->_add_condition($this->table);
        $query = $this->db->get();
        $row = $query->row_array();
        if (isset($row[$this->name_field]))  return $row[$this->name_field];
        //exit($this->db->last_query());
        return false;
    }
    // возвращает id узла по его имени
    // params: $name - имя узела
    // return: id узла (первый из найденного)
    function get_id_node($name){
      if(isset($this->setting[$this->table][$this->key_cache]['nodes_no_leaf'])){
        foreach($this->setting[$this->table][$this->key_cache]['nodes_no_leaf'] as $key=>$value){
          if(isset($value[$this->name_field]) && $value[$this->name_field] == $name){
             return $value[$this->id_field];
          }
        }
      }
        $this->db->select($this->table.'.'.$this->id_field, FALSE);
        $this->db->from($this->table);
        $this->db->where($this->table.'.'.$this->name_field,$name);
        $this->_add_condition($this->table);
        $query = $this->db->get();
        $row = $query->row_array(0);
        if (isset($row[$this->id_field]))  return $row[$this->id_field];
        return false;
    }

    // возвращает все найденные id узла по его имени
    // params: $name - имя узела
    // return: array - массив id найденных узлов
    function get_id_nodes($name){
      if(isset($this->setting[$this->table][$this->key_cache]['nodes_no_leaf'])){
        foreach($this->setting[$this->table][$this->key_cache]['nodes_no_leaf'] as $key=>$value){
          if(isset($value[$this->name_field]) && $value[$this->name_field] == $name){
             return $value[$this->id_field];
          }
        }
      }
        $this->db->select($this->table.'.'.$this->id_field, FALSE);
        $this->db->from($this->table);
        $this->db->where($this->table.'.'.$this->name_field,$name);
        $this->_add_condition($this->table);
        $query = $this->db->get();
        $rows = $query->result_array();
        foreach($rows as $items){        	if (isset($items[$this->id_field])){        		$res[] = $items[$this->id_field];
        	}
        }
        if (isset($res))  return $res;
        return false;
    }

    // возвращает данные узла(ов)
    // params: $node - узел или массив с узлами
    // return: массив с данными узлов
    function get_node($node){
      if(!is_array($node)){
        /*
        if(isset($this->setting[$this->table][$this->key_cache]['nodes_no_leaf'])){
          foreach($this->setting[$this->table][$this->key_cache]['nodes_no_leaf'] as $key=>$value){
            if(isset($value[$this->id_field]) && $value[$this->id_field] == $node){
               return $value[$this->name_field];
            }
          }
        }
        */
        $node = array($node);
      }
        $this->db->select($this->table.'.'.$this->name_field, FALSE);
        $this->db->select($this->table.'.'.$this->id_field, FALSE);
        $this->db->select($this->table.'.'.$this->parent_field, FALSE);
        $this->_add_select($this->table);
        $this->db->from($this->table);
        $this->db->where_in($this->table.'.'.$this->id_field,$node);
        $this->_add_condition($this->table);
        $query = $this->db->get();
        return $query->result_array();
        return false;
    }
    // является ли узел листом дерева
    // @return boolean
    function check_is_leaf($node){
      if(isset($this->setting[$this->table][$this->key_cache]['nodes_no_leaf'])){
        foreach($this->setting[$this->table][$this->key_cache]['nodes_no_leaf'] as $key=>$value){
          if(isset($value[$this->id_field]) && $value[$this->id_field] == $node){
             return false;
          }
        }
      }
        $this->db->select($this->table.'.'.$this->id_field, FALSE);
        $this->db->from($this->table);
        $this->db->where($this->table.'.'.$this->parent_field,$node);
        $this->_add_condition($this->table);
        $query = $this->db->get();
        $row = $query->result_array();
        if(count($row) > 0) return false;
        return true;
    }

    // имеет ли узел детей
    // @return boolean
    function check_is_children($node){
      if(!isset($this->setting[$this->table][$this->key_cache]['nodes_no_leaf'])){
        $this->_nodes_no_leaf();
        $this->_arr_convert_nodes_no_leaf();
      }
      if(isset($this->setting[$this->table][$this->key_cache]['nodes_no_leaf'])){
        foreach($this->setting[$this->table][$this->key_cache]['nodes_no_leaf'] as $key=>$value){
          if(isset($value[$this->id_field]) && $value[$this->id_field] == $node){
             return true;
          }
        }
      }
      return false;
    }
    // возвращает кол-во детей узла
    function get_num_children($node){
      $children = $this->get_child($node);
      if ($children){
        return count($children);
      }else return false;
    }
    // возвращает все узлы с детьми
    function get_node_with_child(){
      return $this->_get_nodes_no_leaf();
    }


    /*//////////////////////////////////////////////
    | Построение дерева
    *//////////////////////////////////////////////
    // построение дерева с узлами содержащих детей
    function get_tree_node_with_child(){
      $node_with_child = $this->get_node_with_child();
      if(is_array($node_with_child)){
        $arr_tree = $this->_get_arr_tree($node_with_child);
      }
      return $arr_tree;
    }

    // построение дерева на основе зависимости id
    // params: $array - массив узлов с детьми
    //         $parent - узел с которого начинать перебор
    //         $array_node_parent - массив узлов с детьми, в которых нужно открыть детей (? - без детей)
    function build_tree(&$array, $parent = 0, $array_node_parent = '?'){
      if (isset($array) && is_array($array) >  0){
        foreach($array as $key=>$value){
          if (isset($value[$this->parent_field]) && $value[$this->parent_field] == $parent){
            $arr[$value[$this->id_field]] = $value;
            $child = $this->build_tree($array, $value[$this->id_field], $array_node_parent);
            if($child){
              $arr[$value[$this->id_field]]['child'] = $child;
            }

            //$arr[$value[$this->id_field]] = $value;
            if(isset($array[$this->parent_field])){

            }
          }
        }
      }
      //print_r($arr);
      if(isset($arr))return $arr;
    }

    // построение списка дерева на основе родителей и id
    // на выходе array[parent_id][id] = value parent_id - родитель, id - его ребенок, value - информация о id
    // params: $array - массив узлов с детьми
    //         $parent - узел с которого начинать перебор    //
    function tree_parent($parent = 0){
      $all_node = $this->get_all();
      if(is_array($all_node)){
        foreach($all_node as $key=>$value){
          $arr[$value[$this->parent_field]][$value[$this->id_field]] = $value;
        }
      }
      if(isset($arr)){
       // создаем новый массив с учетом сортировки
       foreach($arr as $k=>$v){
         //получаем отсортированный массив детей родителя
         $sort_arr = $this->get_array_order(array_values($v));
         //ставим в отсортированный массив ключи с id
         foreach($sort_arr as $items){
           $sort_id[$items[$this->id_field]] = $items;
         }
         //записываем родителя с отсортированными детьми где ключи = id
         $arr2[$k] = $sort_id;
         //удаляем предыдущий отсоортированный массив с ключами что бы не смешивался с последующими
         unset($sort_id);
       }
       return $arr2;
      }
      return false;
    }

    // построение дерева без учета зависимости (т.е. разорванных деревьев)
    // params: $array - массив узлов с детьми
    //
    function build_tree_torn(&$array, $parent = '?'){
      if (isset($array) && is_array($array) >  0){
        foreach($array as $key=>$value){
          if (isset($value[$this->parent_field])){
            if($parent == '?'){
              $parent = $value[$this->parent_field];
            }
            if($value[$this->parent_field] == $parent){
              $arr[$value[$this->id_field]] = $value;
              $child = $this->build_tree($array, $value[$this->id_field]);
              if($child){
                $arr[$value[$this->id_field]]['child'] = $child;
              }
            }
          }
        }
      }
      //print_r($arr);
      if(isset($arr))return $arr;
    }
    // получение массива всего дерева (от определенного уровня - не работает)
    function get_tree_level($level = 0){
      $num_level = $this->_get_max_level();

      if($num_level >= 2){
        for($i=1;$i<=$num_level;$i++){

          //if($i+1 <= $num_level){
            $this->db->select("t$i.".$this->id_field." AS ".$this->_level_name.$i, FALSE);
            //$this->db->select("t$i.".$this->id_field." AS id_".$i, FALSE);
            //$this->db->select("t$i.".$this->order_field." AS order_".$i, FALSE);
            //$this->db->select("t$i.".$this->parent_field." AS parent_".$i, FALSE);
            //$this->db->select("t$i.".$this->name_field." AS name_".$i, FALSE);
          //}
          if($i != 1){
            //echo $this->table.' AS t'.$i;
            //echo '<br />t'.$i.'.'.$this->parent_field.' = t'.($i-1).'.'.$this->id_field.'';
            $this->db->join($this->table.' AS t'.$i, 't'.$i.'.'.$this->parent_field.' = t'.($i-1).'.'.$this->id_field.'', 'left', FALSE);
            //$this->db->where("t".($i-1).".".$this->_level_name.$i." IS NOT NULL", null, FALSE);
          }else{
            $this->db->from($this->table.' AS t'.$i);
          }

          //$this->db->where('t2.'.$this->id_field.' IS NOT NULL', null, FALSE);
        }
        $this->db->where('t1.'.$this->parent_field, 0, FALSE);
        //$this->db->where('t0.'.$this->name_field.' !=' , null, FALSE);


      }else{
        $this->db->select("t1.".$this->name_field." AS ".$this->_level_name."1", FALSE);
        $this->db->select("t1.".$this->id_field." AS id_1", FALSE);
        $this->db->select("t1.".$this->order_field." AS order_1", FALSE);
        $this->db->select("t1.".$this->parent_field." AS parent_1", FALSE);
        $this->db->select("t1.".$this->name_field." AS name_1", FALSE);
        $this->db->from($this->table.' AS t1');
      }
      $this->_add_condition('t1');
      $query = $this->db->get();
      //$query = $this->db->get();
      return $query->result_array();
    }


    /*//////////////////////////////////////////////
    | Манипуляции с узлами
    *//////////////////////////////////////////////

    // добавить новый узел в корень
    // params: $node_name - имя узла,
    //         $after_node - после узла (опционально), если не указано то в начале корня
    function new_node($node_name, $node_parent = 0, $node_target = 0, $direct = 'after'){
      if(!empty($node_name)){
        if($node_parent == '?'){
          if($node_target != 0){
            $node_parent = $this->_get_parent($node_target);
          }else{
            $node_parent = 0;
          }
        }

        if($this->query_insert_node($node_name, $node_parent));
        $node_source = $this->db->insert_id();
        if(!empty($node_source)){
          $node_sibling = $this->get_sibling($node_source);
          if(is_object($this->order)){
            if ($node_target == 0){
              if($direct == 'before'){
                $update_order = $this->order->get_change_top($node_sibling, $node_source);
              }else{
                $update_order = $this->order->get_change_end($node_sibling, $node_source);
              }
            }else{
              $update_order = $this->order->get_change_assign($node_sibling, $node_source, $node_target, $direct);
            }
            if(is_array($update_order)){
              $this->query_update_order($update_order);
            }
          }
        }
      }else{
        return false;
      }
    }
    // перемещение узла к родителю
    // params: $node = узел или массив узлов для перемещения(одного родителя)
    //         $node_parent - узел родителя
    //                        ? - неизвестный (тогда родитель будет братся от $node_target если таковой установлен)
    //         $node_target - узел направления
    //         $direct - направление смещения, after(default) - после узла направления
    //                                         before - перед узлом направления
    function update_node($node,$node_parent,$node_target,$direct){
      if(!is_array($node)) {
        $node = array($node);
      }

      $node_one = $node[0];

      $parent_current = $this->_get_parent($node_one);

      if($node_parent == '?'){
          if($node_target != 0 && $node_target != '?'){
            $node_parent = $this->_get_parent($node_target);
          }else{
            $node_parent = $parent_current;
          }
      }

      if($parent_current != $node_parent){
        $node_sibling_current = $this->get_sibling($node_one);
        if(is_object($this->order)){
          $update_order_current = $this->order->get_change_end($node_sibling_current, $node);
          if(is_array($update_order_current)){
            $this->query_update_order($update_order_current);
          }
        }
        if($this->query_update_node($node, $node_parent));
        // здесь возможно нужно будет очистить кэш,
        // т.к. обновленная запись может исказить результаты кэширования
        $this->_del_all_cache();
      }
      $node_sibling = $this->get_sibling($node_one);
      if(is_object($this->order)){
        if($node_target == '?'){
          if($direct == 'before'){
            $node_prev = $this->get_prev_sibling($node_one);
            if($node_prev != false) $node_target = $node_prev;
            else $node_target = 0;
          }else{
            $node_next = $this->get_next_sibling($node_one);
            if($node_next != false) $node_target = $node_next;
            else $node_target = 0;
          }
        }
        //echo "принятые узлы: ";
        //print_r($node);
        //echo "<br />";
        //echo "последовательность узлов до : ";
        //print_r($node_sibling);
        //echo "<br />";
        //echo "узел назначения: $node_target";
        //echo "<br />";
        //echo "Куда? : $direct";
        //echo "<br />";
        if ($node_target == 0){
          if($direct == 'before'){
            $update_order = $this->order->get_change_top($node_sibling, $node);
          }else{
            $update_order = $this->order->get_change_end($node_sibling, $node);
          }
        }else{
          $update_order = $this->order->get_change_assign($node_sibling, $node, $node_target, $direct);
        }
        //echo "последовательность узлов к изменению : <pre>";
        //print_r($update_order);
        //echo "</pre><br />";
        if(is_array($update_order)){
          $this->query_update_order($update_order);
          // здесь возможно нужно будет очистить кэш,
          // т.к. обновленная запись может исказить результаты кэширования
          $this->_del_all_cache();
        }
      }

    }
    // перемещение узла в пределах одного родителя
    // params: $node = узел или массив узлов для перемещения(одного родителя)
    //         $node_target - узел направления
    //         $direct - направление смещения, after(default) - после узла направления
    //                                         before - перед узлом направления
    function move_node_to_order($node,$node_target,$direct){
      if(!is_array($node)) {
        $node = array($node);
      }
      $node_one = $node[0];

      $node_sibling = $this->get_sibling($node_one);
      if(is_object($this->order)){
        if ($node_target == 0){
          if($direct == 'before'){
            $update_order = $this->order->get_change_top($node_sibling, $node);
          }else{
            $update_order = $this->order->get_change_end($node_sibling, $node);
          }
        }else{
          $update_order = $this->order->get_change_assign($node_sibling, $node, $node_target, $direct);
        }
        if(is_array($update_order)){
          $this->query_update_order($update_order);
        }
      }

    }
    // выполнение запроса на INSERT
    function query_insert_node($name_node, $parent_node){
      if(isset($name_node) && isset($parent_node)){
        $this->db->set($this->name_field, $name_node);
        $this->db->set($this->parent_field, $parent_node);
        $this->_add_set($this->table);
        if($this->db->insert($this->table)){
          return true;
        }else{
          return false;
        }
      }

    }
    // выполнение запроса на UPDATE
    function query_update_node($node, $parent_node){
      if(isset($node) && isset($parent_node)){
        $this->db->set($this->parent_field, $parent_node);
        $this->db->where_in($this->id_field,$node);
        //$this->_add_where($this->table);
        if($this->db->update($this->table)){
          return true;
        }else{
          return false;
        }
      }

    }
    // выполнение запроса на обновление сортировки узлов
    function query_update_order($update_array){
      foreach($update_array as $key=>$items){
        if(isset($items[$this->order_field]) && isset($items[$this->id_field])){
          $data = array(
                        $this->order_field => $items[$this->order_field],
                        );
          $this->db->where($this->id_field, $items[$this->id_field]);
          $this->_add_where($this->table);
          if($this->db->update($this->table, $data));
        }
      }
    }
/*--------- конец работы с приложением ----------*/

/*///////////////////////////////////////
| Методы для сортировки
|
*///////////////////////////////////////
   //  сортировка массива в случае заданного метода сортировки
    function get_array_order($array){
      if(isset($this->order_field)){
        //if(isset($array[0][$this->order_field]) && isset($array[0][$this->id_field])) {
          if(is_object($this->order)){
            $this->order->set_field_order($this->order_field);
            $this->order->set_field_id($this->id_field);
            //print_r($this->order->get_array($array));
            return $this->order->get_array($array);
          }
        //}
      }
      return $array;
    }

    // узлы с кол-вом детей
    function _get_double_node(){
      $this->db->select('count(*),'.$this->table.'.'.$this->parent_field.','.$this->table.'.'.$this->id_field, FALSE);
      $this->db->group_by($this->table.'.'.$this->parent_field);
      $this->db->from($this->table);
      //$this->db->where($this->table.'.'.$this->parent_field.' !=','0');
      $this->_add_condition($this->table);
      $query = $this->db->get();


      return $query->num_rows();
    }
    /*
     * возвращает дерево начиная от указанного уровня
     * $level - уровень (id) с которого считывать дерево
     * по умолчанию $level  0, т.е. корень дерева
    */

    // единственный путь
    function _get_single_path(){
      //SELECT t1.name AS lev1, t2.name as lev2, t3.name as lev3, t4.name as lev4
      //FROM category AS t1
      //LEFT JOIN category AS t2 ON t2.parent = t1.category_id
      //LEFT JOIN category AS t3 ON t3.parent = t2.category_id
      //LEFT JOIN category AS t4 ON t4.parent = t3.category_id
      //WHERE t1.name = 'ELECTRONICS' AND t4.name = 'FLASH';


    }




/*///////////////////////////////////////
| Логирование
|
*///////////////////////////////////////
    /**********************
    * запись ошибок
    * уровни:
    * 1 - предупреждения
    * 2 - рекомендуемые
    * 3 - критические
    * 4 - фатальные
    ***********************/

    function error($name,$msg,$level = 1){
      $this->setting[$this->table][$this->key_cache]['error'][$level][$name] = $msg;
    }
/*--------- конец работы логирования ----------*/

}





