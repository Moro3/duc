<?php (defined('BASEPATH')) OR exit('No direct script access allowed');


class Help_request_order extends CI_Driver
{
    //���������� �������� ��� ����������� ����������
    private $direct = array('asc','desc','random');
    /**
    *  ���������� ���������� ���� ����������
    *	@param string - ��� ���� ���������� (!!! ��� ��������� ���� ������� ������ �� ������ ���������)
    *	@param array - ������ ���������� ����� ����������
    *					array('alias' => 'field') - alias: ��������� ����� (����� ���� ������, ����� ��� ����� �������� �� �������� 'field')
    *												field: ��� ���������� � �������
    *   @param string - ��� ���������� �� ���������. ���� ������� �������� �������� current ��� ��� �� ��������� � allow/
    *					!!! ���� �� ������ ��������� (allow) ������ ���������, �� ����� ����������� ����� �� ����������
    *					���� �� ������ ��������, ������� ������ �������� �� ������� allow
    *
    *	@return string - ���������� ���� ��� ����������
    */	public function by($current, $allow, $default = false){
		if(!is_array($allow) || count($allow) == 0) return false;
		if(is_numeric($current)) return $this->get_first_value_array($allow);
		if(isset($allow[$current]) && $current !== false){
			return $allow[$current];
		}elseif(in_array($current, $allow)){
            $key = array_search($current, $allow);
        	if(is_numeric($key)){        		return $current;
        	}else{        		if($default === false){
					return $this->get_first_value_array($allow);
				}else{
					return $this->by($default, $allow);
				}
        	}
		}else{
			if($default === false){				return $this->get_first_value_array($allow);
			}else{
				return $this->by($default, $allow);
			}
		}
	}

	/**
    *  ���������� ���������� ��� ����������� ����������
    *	@param string - ��� ���� ���������� (!!! ��� ��������� ���� ������� ������ �� ������ ���������)
    *	@param array - ������ ���������� ���������� ����������� ����������
    *					���������� �������� ��������� 'asc', 'desc', 'random'
    *					���� ���������� ������� ���������� ��� ���, ������� ����������� � �������,
    *							 ��� ���� ����� ������� �����������,
    *							 � �������� ���� �� ���������� ����������    *
    *   @param string - ��� ����������� ���������� �� ���������. ���� ������� �������� �������� current ��� ��� �� ��������� � allow/
    *					����� ���� ������ ��� alias, ��� � �������� field.
    *					���� �� ������ ��������, ������� ������ �������� �� ������� allow
    *
    *	@return string - ���������� ���� ��� ����������
    */
	public function direct($current, $allow = false, $default = false){
		if($allow !== false && is_array($allow)){			foreach($this->direct as $key=>$items){				if(in_array($items, $allow)){					$keys = array_search($items, $allow);
					$new_allow[$keys] = $items;
				}else{					$new_allow[$key] = $items;
				}
			}
		}else{			$new_allow = $this->direct;
		}

		if(is_numeric($current)) return $this->get_first_value_array($new_allow);
		if(isset($new_allow[$current])  && $current !== false){
			return $new_allow[$current];
		}elseif(in_array($current, $new_allow)){
            $key = array_search($current, $new_allow);
        	if(is_numeric($key)){
        		return $current;
        	}else{
        		if($default === false){
					return $this->get_first_value_array($new_allow);
				}else{
					return $this->direct($default, $new_allow);
				}
        	}
		}else{
			if($default === false){
				return $this->get_first_value_array($new_allow);
			}else{
				return $this->direct($default, $new_allow);
			}
		}
	}

	/**
	* 	���������� �������� ������� �������� ������� ��� ��� ���� ���� �� �� ��������
	*   @param array - ������ ���������� ����������
	*	@return string - ������ ������� �������� ������� ��� ��� ���� ���� �� �� ��������
	*/
	private function get_first_array($array){		if(is_array($array)){
			reset($array);
            $first = each($array);
			if(isset($first[0])){				if(is_numeric($first[0])){					return $first[1];
				}else{					return $first[0];
				}
			}
		}
		return false;
	}
	/**
	* 	���������� �������� ������� �������� �������
	*   @param array - ������ ���������� ����������
	*	@return string - ������ ������� �������� �������
	*/
	private function get_first_value_array($array){		if(is_array($array)){
			reset($array);
            $first = each($array);
			if(isset($first[1])){
				return $first[1];
			}
		}
		return false;
	}

	/**
	* 	���������� ���� ������� �������� �������
	*   @param array - ������ ���������� ����������
	*	@return string - ������ ������� �������� �������
	*/
	private function get_first_key_array($array){
		if(is_array($array)){
			reset($array);
            $first = each($array);
			if(isset($first[0])){
				return $first[0];
			}
		}
		return false;
	}

}
