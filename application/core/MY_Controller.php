<?php (defined('BASEPATH')) OR exit('No direct script access allowed');

/* load the MX_Loader class */
require APPPATH."third_party/MX/Controller.php";

class MY_Controller extends MX_Controller {

	protected $prefix_model = '';
    protected $postfix_model = '_model';

    protected $MY_errors;
    protected $MY_logs;

    protected $MY_table;
    protected $MY_module;

	function __construct(){		parent::__construct();
	}

	/**
    *  ���������� ������ � ���� ��� ��� ����
    *  @param array - ����, �� ��������� ���
    *  @param array - �������
    *  @param string - "20,10" 20 - ���-��, 10 - ��������
    *
    *  return array
    */
	public function MY_data($select = '*', $where = false, $limit = false, $order_by = false, $group_by = false, $having = false){
		$ob = $this->MY_get_class_model();
    	if(class_exists($ob)){
			return $this->$ob->MY_data($select, $where, $limit, $order_by, $group_by, $having);
		}
		return false;
	}

	/**
    *  ���������� ������ � ���� ��� ��� ����
    *  @param array - ����, �� ��������� ���
    *  @param array - �������
    *  @param string - "20,10" 20 - ���-��, 10 - ��������
    *
    *  return array
    */
	public function MY_data_array($select = '*', $where = false, $limit = false, $order_by = false, $group_by = false, $having = false){
		$ob = $this->MY_get_class_model();
    	if(class_exists($ob)){
			return $this->$ob->MY_data_array($select, $where, $limit, $order_by, $group_by, $having);
		}
		return false;
	}

	/**
    *  ���������� ������ ����� ������ � ���� �������
    *  @param array - ����, �� ��������� ���
    *  @param array - �������
    *  @param string - "20,10" 20 - ���-��, 10 - ��������
    *
    *  return object
    */
	public function MY_data_row($select = '*', $where = false, $limit = false, $order_by = false){
    	$ob = $this->MY_get_class_model();
    	if(class_exists($ob)){
			return $this->$ob->MY_data_row($select, $where, $limit, $order_by);
		}
		return false;
 	}

	/**
    *  ���������� ������ ����� ������ � ������� �������
    *  @param array - ����, �� ��������� ���
    *  @param array - �������
    *  @param string - "20,10" 20 - ���-��, 10 - ��������
    *
    *  return array
    */
	public function MY_data_array_row($select = '*', $where = false, $limit = false, $order_by = false){
    	$ob = $this->MY_get_class_model();
    	if(class_exists($ob)){
			return $this->$ob->MY_data_array_row($select, $where, $limit, $order_by);
		}
		return false;
 	}

 	/**
	* 	���������� ������ � �������
	*  ��������� �� ������ ���������� ������� ��� ���������� ������� ����
	*   @param string - ���� ����� ($key) ������� (�� ��������� id)
	*  @param string - ���� �������� ($value) ������� (�� ��������� name)
	*  @param array - ������ � ��������� ������� (�� ��������� false)
	*  @param array || string - ������� ��������� ����������
	*
	*   return array($key=>$value) $key='id', $value='name'
	*/
	public function MY_data_array_one($key_field = 'id', $value_field = 'name', $where = false, $order_by = false, $separator = ' '){
        $ob = $this->MY_get_class_model();
        if(class_exists($ob)){
			return $this->$ob->MY_data_array_one($key_field, $value_field, $where, $order_by, $separator);
		}
		return false;
	}

	/**
    *  ���������� �������� ������
    *	@param string - ��� ������
    *  @param array - ����, �� ��������� ���
    *  @param array - �������
    *  @param string - "20,10" 20 - ���-��, 10 - ��������
    *
    *  return array
    */
	public function MY_related_has_many($model_name, $value, $select = '*'){
		$ob = $this->MY_get_class_model();
    	if(class_exists($ob)){
			return $this->$ob->MY_related_has_many($model_name, $value, $select);
		}
		return false;
	}

    /**
    *	���������� ������ ������������� � ������ �������� (belongs to)
    *   @param string - ��� ������
    *	@param string - �������� ���������� ���������
    *
    */
    public function MY_related_belongs_to($model, $value, $select = '*'){
    	$ob = $this->MY_get_class_model();
    	if(class_exists($ob)){
			return $this->$ob->MY_related_belongs_to($model, $value, $select);
		}
		return false;
    }

	protected function MY_get_class_model(){
		return strtolower(get_class($this).$this->postfix_model);
	}

	/**
	* ���������� ������� ������� ������ ��� ������ ������� �����������
	*
	*  @param array - ������ ���������� ������� 'set'
	*
	*/
    public function MY_insert($set){
    	$ob = $this->MY_get_class_model();
    	if(class_exists($ob)){
    		return $this->$ob->MY_insert($set);
    	}
    	return false;
    }

    /**
	* ���������� ������� ���������� ������ ��� ������ ������� �����������
	*
	*  @param array - ������ ���������� ������� 'set'
	*  @param array - ������ ���������� ������� 'where'
	*/
    public function MY_update($set, $where){
    	$ob = $this->MY_get_class_model();
    	if(class_exists($ob)){
    		return $this->$ob->MY_update($set, $where);
    	}
    	return false;
    }

    /**
	* ���������� ������� �������� ������ ��� ������ ������� �����������
	*
	*  @param array - ������ ���������� ������� 'where'
	*
	*/
    public function MY_delete($where){
    	$ob = $this->MY_get_class_model();
    	if(class_exists($ob)){
    		return $this->$ob->MY_delete($where);
    	}
    	return false;
    }

/******************************************************************************
***************** ��������� ��������� ��������� *******************************
*******************************************************************************
*/
    /**
    * ������ ��������� ���������
    * @param string - ��� �������
    * @param string - ���������
    * @param array - ������������ ��������� ���������, ������ key - ��� �����, value - �������� �����
    *
    */
	function MY_set_status($name, $level, $message, $options = false){
         if(is_array($options)){
         	foreach($options as $key=>$item){
         		$opt[$key] = $item;
         	}
         }else{
         	$opt = false;
         }
			$this->status[$name][$level][] = array('message' => $message,
			                              'options' => $opt,
            );
	}
    /**
    * ��������� ��������� ���������
    * @param string - ��� �������
    * @param array - ������������ ��������� ���������,
    *				���� �������, ����� ���������� ������ �������������� ����������
    *
    */
	function MY_get_status($name, $level, $options = false){

	    	if(isset($this->status[$name][$level])){
	    		return $this->status[$name][$level];
	    	}else{
	    		return false;
	    	}

	}
    /**
    * ������� ��������� ���������
    * @param string - ��� �������
    * @param array - ������������ ��������� ���������,
    *				���� �������, ����� ������� ������ �������������� ����������
    *
    */
	function MY_clear_status($name = false, $level = false, $options = false){
        if(!empty($name)){
	        if(!empty($level)){
		        if(isset($this->status[$name][$level])){
		    		unset($this->status[$name][$level]);
		    	}
	    	}else{
	    		unset($this->status[$name]);
	    	}
    	}else{
    		$this->status = array();
    	}

	}

/******************************************************************************
***************** ��������� ��������������� ������ *******************************
*******************************************************************************
*/
	/**
    * ���������� ����� �� id ���������
    *	���������� �������� (�������� �� �����������)
    *	���������� ������, ��� ���� - ��� id, � �������� - ��� �������� ���������
    *	@param string || ������ $ids - ����� ��������� ��� ������ (������������������ id ����������� �������)
    *								   ��� ������ �� id
    *   @param string $fieldSorter - ��� ���� ��� ���������� (�� ��������� sorter)
    */
    public function sorterId($ids, $fieldSorter = 'sorter', $module = false, $table = false){
        $incNum = 10; //��������� ����������
    	if( ! is_array($ids)){
    		$ids = explode(',', $ids);
    	}
    	foreach($ids as $item){
    		if(is_numeric($item)){
    			$arr_id[] =	$item;
    		}else{
    			return false;
    		}
    	}
    	$module = (!empty($module)) ? $module : $this->MY_module;
    	$table = (!empty($table)) ? $table : $this->MY_table;
    	if(empty($module) && empty($table)) return false;
    	//echo '@';
    	$res = Modules::run($module.'/'.$table.'/MY_data_array_row',
    	                    //select
    	                    array('min('.$fieldSorter.') AS minsort'),
    	                    //where
    	                    array('id' => $ids)
    	);

    	if(isset($res['minsort'])){
    		$n = (is_numeric($res['minsort'])) ? $res['minsort'] : 10;
    		foreach($arr_id as $item){
            	$new[$item] = $n;
            	$n = $n + $incNum;
    		}
    	}
    	if(isset($new)) return $new;
    	return false;
    }

/***************** ����� ��������� ��������� ��������� ************************/



	/**
        *  ����� ��������� ������
        *  @param string $class - �����
        *  @param string $method - �����
        *  @param string $level  - ������� ������
        *  @param string $message - ���������
        */
        function MY_error($class, $method, $level, $message ){
            $this->MY_errors[] = array('class' => $class,
                                    'method' => $method,
                                    'level' => $level,
                                    'message' => $message,
                              );
            log_message($level, $message);


        }

        	/**
	*  ��������� ����� �������
	*  @param string $message
	*/
        function MY_log($message){
            $this->MY_logs[] = $message;
        }


/******************************************************************************
***************** ���������� ��������� ������ *******************************
*******************************************************************************
*/
    /**
	* ������� ������ ��� ������������
	*
	*/
	function MY_route($group = 'default'){

		$buf = '';
		$buf .= $this->router_modules->run($group, $this->MY_module, true);

        echo $buf;
	}
}