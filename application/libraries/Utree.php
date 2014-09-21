<?php if (!defined('BASEPATH')) exit('No direct script access allowed');

class Utree {
  private $multi_tree;
  private $set_arr = array();
	function __construct()
	{
    // все параметры используемые в работе
    $this->set_arr = array('table',
                           'id_field',
                           'parent_field',
                           'name_field',
                           'order_field',
                           'select_field',
                           'condition_field',
                           'method',
                           'method_order',
                           'lft_name_field', // для метода Nested Sets (вложенные множества)
                           'rgt_name_field', // для метода Nested Sets (вложенные множества)
                           'level_field', // для метода Nested Sets (вложенные множества)
                           );
	}

  // запускает команду на выполнение
  // перед запуском команды идет обработка установленных параметров
  // на случай изменения на иные параметры в других контроллерах
  function run_command($cmd,$arg){
    if (true == method_exists($this, $cmd)) {
      return call_user_func_array(array($this, $cmd), array($arg));
    }
  }
  // загрузка установленных параметров
  function set_param($param){
    if(is_array($param)){
      $this->clear_setting();
      $rnd_str = '';
      $CI =& get_instance(); // to access CI resources, use $CI instead of $this

      if(isset($param['method'])){
        if($this->method != $param['method']){
           //$CI =& get_instance(); // to access CI resources, use $CI instead of $this
              switch($param['method'])
              {
                case 'arjacency':
                  $CI->load->model('adjacency_model');
                  $this->multi_tree =& $CI->adjacency_model;
                  $this->method = $param['method'];
                  $rnd_str .= $param['method'];
                  break;
                case 'nested':
                  $CI->load->library('nested_set');
                  $this->multi_tree =& $CI->nested_set;
                  $this->method = $param['method'];
                  $rnd_str .= $param['method'];
                  break;
                default:
                  $CI->load->model('adjacency_model');
                  $this->multi_tree =& $CI->adjacency_model;
                  $this->method = $param['method'];
                  $rnd_str .= $param['method'];
              }
           //print_r ($this->multi_tree);
        }
      }else{
        echo "метод работы не определен";
        exit();
      }

      if(isset($param['order_field'])){
        if(isset($param['method_order'])){
          if ($param['method_order'] != $this->method_order){
            //$CI =& get_instance(); // to access CI resources, use $CI instead of $this
            switch($param['method_order'])
            {
              case 'arj':
                $CI->load->library('order_arj');
                //$this->order =& $CI->order_arj;
                $jj = & $CI->order_arj;
                $this->order = $jj;
                $this->method_order = $param['method_order'];
                $this->order_field = $param['order_field'];
                $rnd_str .= $param['method_order'].$param['order_field'];
                //$this->multi_tree->order = & $CI->order_arj;
                break;
              case 'num':
                $CI->load->library('order_num');
                $this->order =& $CI->order_num;
                $this->method_order = $param['method_order'];
                $this->order_field = $param['order_field'];
                $rnd_str .= $param['method_order'].$param['order_field'];
                break;
              default:
                break;
            }
            //print_r ($this->order);
          }
        }
      }
      //unset($this->table);

      //echo $this->table;
      foreach($param as $key=>$value){
        switch ($key){
          case 'table':
            $this->$key = $value;
            $rnd_str .= $value;
            break;
          case 'id_field':
            $this->$key = $value;
            $rnd_str .= $value;
            break;
          case 'parent_field':
            $this->$key = $value;
            $rnd_str .= $value;
            break;
          case 'name_field':
            $this->$key = $value;
            $rnd_str .= $value;
            break;
          case 'select_field':
          	$this->$key = $value;
            if(is_array($value)){            	foreach($value as $key_cond=>$value_cond){
                	$rnd_str .= $key_cond.$value_cond;
              	}
            }else{
            	$rnd_str .= $value;
            }
          	break;
          case 'condition_field':
            //if ($this->$key != $value){
              foreach($value as $key_cond=>$value_cond){
                $rnd_str .= $key_cond.$value_cond;
              }
              //echo "<br><b>".$rnd_str."</b><br>";
              //$random_key = md5($rnd_str);
              //echo md5($rnd_str);

            //}
            $this->$key = $value;
            break;
        }
      }
      $this->multi_tree->key_cache = md5($rnd_str);
      unset($rnd_str);

    }else{
      echo "не установлены необходимые параметры для выборки данных дерева";
      exit();
    }
  }

  function clear_setting(){
    foreach ($this->set_arr as $key=>$value){
      if($this->$value){
        $this->$value = null;
      }
    }
  }

  // отладочная информация
  // возвращает кеш
	function get_cache() {
	  return $this->multi_tree->_get_all_cache();
	}
/////////////////////////////////////////////
//  информация о узлах
/////////////////////////////////////////////

  // возвращает детей узла
  // array() массив в ключевом порядке, если нет то пустой массив
	function get_child($node) {
	  return $this->multi_tree->get_child($node);
	}
  // возвращает родителя узла
  // number - id родителя, если это корень то 0, если не удалось то false
  function get_parent($node) {
	  return $this->multi_tree->_get_parent($node);
	}
	// возвращает братьев узла
  // array() - массив в ключевом порядке (включая сам узел), если нет то пустой массив
  function get_sibling($node) {
    return $this->multi_tree->get_sibling($node);
  }
  // возвращает полный путь до узла
  // array() - массив в ключевом порядке до исходная узла (включая сам узел)
  function get_path($node) {

    return $this->multi_tree->get_path($node);
  }
	// возвращает кол-во уровней
  // number
  function get_all_level() {
	  return $this->multi_tree->_get_max_level();
	}
	// возвращает уровень узла
  // number, false - если уровень не определен
  function get_level($node) {
    return $this->multi_tree->_get_level($node);
	}
	// возвращает имя узла
  // поле с именем, false - если не удалось определить
  function get_name($node) {
	  return $this->multi_tree->get_name_node($node);
	}
	//возвращает id узла по его имени
  //возвращается первое найденное
  function get_id_node($name){
    return $this->multi_tree->get_id_node($name);
  }

  /**возвращает id узла по его имени
  *	возвращается все найденные
  *	@param $name - имя узла
  *	@return array - массив с найденными id узлов
  */
  function get_id_nodes($name){
    return $this->multi_tree->get_id_nodes($name);
  }
	// возвращает информацию об узле
  // return: массив с информацией об узле или узлах
  function get_node($node) {
	  return $this->multi_tree->get_node($node);
	}

  // является ли узел листом дерева
  // @return boolean
  function check_is_leaf($node) {
     return $this->multi_tree->check_is_leaf($node);
	}
	// имеет ли узел детей
  // @return boolean
  function check_is_children($node) {
    return $this->multi_tree->check_is_children($node);
	}
	// сколько детей имеет узел
  // @return number
  function get_num_children($node) {
    return $this->multi_tree->get_num_children($node);
	}
  // возвращает полное дерево от узла $node
	// Params: $node - узел от которого стрить дерево (0 - корень)
	function get_tree_level($node = 0){
	  return $this->multi_tree->get_tree_level($node);
	}
  // возвращает узлы с детьми
  function get_node_with_child(){
    return $this->multi_tree->get_node_with_child();
  }
  // возвращает массив в виде дерева
  function get_tree_array($array, $parent = 0, $array_node_parent = '?'){
    return $this->multi_tree->build_tree($array, $parent, $array_node_parent);
  }
  // возвращает массив в виде array[parent_id][id] = value
  function get_tree_parent(){
    return $this->multi_tree->tree_parent();
  }

  // возвращает массив в виде дерева где узлы могут быть разорваны
  function get_tree_array_torn($array){
    return $this->multi_tree->build_tree_torn($array);
  }
  // возвращает всех родителей дерева
  function get_all_parent(){
    return $this->multi_tree->get_all_parent();
  }
  // возвращает кол-во всех узлов дерева
  function get_num_all_nodes(){
    return $this->multi_tree->_get_num_nodes_all();
  }
  // возвращает все корневые узлы дерева
  function get_root_nodes(){
    return $this->multi_tree->get_root();
  }
  // возвращает кол-во циклов
  function get_num_cycle(){
    return $this->multi_tree->_get_num_cycle();
  }
  // возвращает кол-во цикличнфых узлов
  function get_num_nodes_cycle(){
    return $this->multi_tree->_get_num_nodes_cycle();
  }
  // возвращает цикличные узлы
  function get_nodes_cycle(){
    return $this->multi_tree->_get_nodes_cycle();
  }
  // возвращает кол-во разорванных узлов
  function get_num_nodes_torn(){
    return $this->multi_tree->_get_num_nodes_torn();
  }
  // возвращает разорванные узлы
  function get_nodes_torn(){
    return $this->multi_tree->_get_nodes_torn();
  }

/////////////////////////////////////////////
//  манипуляции с узлами
/////////////////////////////////////////////
/*
| Добавление нового узла
*/
	// добавить новый узел в корень в конец списка
  // добавляет в базу новый узел с родителем = 0
  function new_root_end($node_name) {
    return $this->multi_tree->new_node($node_name, 0, 0, 'after');
	}
	// добавить новый узел в корень в начало списка
  // добавляет в базу новый узел с родителем = 0
  function new_root_top($node_name) {
    return $this->multi_tree->new_node($node_name, 0, 0, 'before');
	}
  // добавляет новый узел с указанным родителем в вершину
  function new_parent_top($node_name, $parent_node) {
    return $this->multi_tree->new_node($node_name, $parent_node, 0, 'before');
	}
	// добавляет новый узел с указанным родителем  вниз
  function new_parent_end($node_name, $parent_node) {
    return $this->multi_tree->new_node($node_name, $parent_node, 0, 'after');
	}
  // добавить новый узел к братьем указанного узла в конец списка
  // добавляет в базу новый узел
  function new_sibling_end($node_name, $node_sibling) {
    return $this->multi_tree->new_node($node_name, '?', $node_sibling, 'after');
	}
	// добавить новый узел к братьем указанного узла в начало списка
  // добавляет в базу новый узел
  function new_sibling_top($node_name, $node_sibling) {
    return $this->multi_tree->new_node($node_name, '?', $node_sibling, 'before');
	}
	// добавить новый узел после указанного узла
  // добавляет в базу новый узел после указанного
  function new_sibling_down($node_name, $node_target) {
    return $this->multi_tree->new_node($node_name, '?', $node_target, 'after');
	}
	// добавить новый узел перед узлом
  // добавляет в базу новый узел перед указанным
  function new_sibling_up($node_name, $node_target) {
    return $this->multi_tree->new_node($node_name, '?', $node_target, 'before');
	}

/*
| Перемещение узла в пределах данного родителя
*/
  // переместить узел к вершине списка
  // перемещает узел в верхнюю позицию на данном уровне своего родителя
  function move_top($node) {
    $this->multi_tree->update_node($node,'?',0,'before');
	}
	// переместить узел в низ списка
  // перемещает узел в нижнюю позицию на данном уровне сврего родителя
  function move_end($node) {
    $this->multi_tree->update_node($node,'?',0,'after');
	}
	// переместить узел после узла
  // перемещает узел в место после указанного узла
  function move_down($node) {
    $this->multi_tree->update_node($node,'?','?','after');
	}
	// переместить узел перед узлом
  // перемещает узел в место перед указанным узлом
  function move_up($node) {
    $this->multi_tree->update_node($node,'?','?','before');
	}
/*
| Перемещение узла за пределы родителя
*/
	// сделать узел ребенком узла
  // перемещает узел к родителю узла к вершине
  function move_parent_top($node, $node_parent) {
    $this->multi_tree->update_node($node,$node_parent,0,'before');
	}
	// сделать узел ребенком узла
  // перемещает узел к родителю узла вниз
  function move_parent_end($node, $node_parent) {
    $this->multi_tree->update_node($node,$node_parent,0,'after');
	}
	// сделать узел братом узла
  // перемещает узел к брату узла после него
  function move_sibling_down($node, $node_sibling) {
    $this->multi_tree->update_node($node,'?',$node_sibling,'after');
	}
	// сделать узел братом узла
  // перемещает узел к брату узла перед ним
  function move_sibling_up($node, $node_sibling) {
    $this->multi_tree->update_node($node,'?',$node_sibling,'before');
	}
	// сделать узел корнем
  // перемещает узел в корень в конец
  function move_root_end($node) {
    $this->multi_tree->update_node($node,'0','0','after');
	}
	// сделать узел корнем
  // перемещает узел в корень в начало
  function move_root_top($node) {
    $this->multi_tree->update_node($node,'0','0','before');
	}
	// сделать узел корнем
  // перемещает узел в корень после узла
  function move_root_down($node, $node_target) {
    $this->multi_tree->update_node($node,'0',$node_target,'after');
	}
	// сделать узел корнем
  // перемещает узел в корень перед узлом
  function move_root_up($node, $node_target) {
    $this->multi_tree->update_node($node,'0',$node_target,'before');
	}

  function __get($name) {
	  if (isset($this->multi_tree->$name)) return $this->multi_tree->$name ;

    return false;
	}

	function __set($name,$value) {
    $this->multi_tree->$name = $value;
	}

	function del_cache($name) {
	  $this->multi_tree->_del_cache($name);
	}
}


























