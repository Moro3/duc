<?php (defined('BASEPATH')) OR exit('No direct script access allowed');


class Replaced_cfg extends CI_Driver
{
    private $prefix = 'cfg';          // ������� ������
    private $params = array();          // ����� ����������
    private $cfg_params = array();          // ��������� ��� ������ �� ���������������� ������
    private $f_start = '{';             // ��������� ���� ���������
  	private $f_end = '}';               // �������� ���� ���������

    public $CIC;

    function __construct()
	{
    	$this->load_data();

        $this->CIC = & get_instance();
        $this->CIC->config->load('site', TRUE);
        //print_r();
    	$site = $this->CIC->config->item('site');
        foreach($site as $key=>$items){        	$this->add($key, $items);
        }

    }

    /**
    * �������� ���������� �� ���������
    *
    */
    function load_data(){
    	$this->params = array('name', 'arg', 'style', 'css', 'ajax', 'span', 'div', 'tag', 'class', 'id', 'title');

    }

    /**
    * ���������� ���������
    *
    */
    function add($name, $value){    	if(is_array($name)){    		foreach($name as $key=>$items){
    			$this->add($key, $items);
    		}
    	}
    	$this->cfg_params[$name] = $value;
    }
    /**
    * ��������� ���������
    *
    */
    function get($name){
    	if(isset($this->cfg_params[$name])){
    		return $this->cfg_params[$name];
    	}
    	return NULL;
    }
    /**
    * �������� ���������
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
        //�������� �������� ��� �����
		$attr = 'style="';
        if($key_style_tpl = array_keys($params['params'],'style')){
        	$attr .= rtrim($params['value'][$key_style_tpl[0]],';').';';
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

        //�������� ������ ��������� � ����������� ��������
        if($key_style_tpl = array_keys($params['params'],'tag')){
        	$result = '<'.$params['value'][$key_style_tpl[0]].' '.$attr.'>'.$result.'</'.$params['value'][$key_style_tpl[0]].'>';
        }elseif($key_style_tpl = array_keys($params['params'],'span')){
            $result = '<span '.$attr.'>'.$result.'</span>';
        }elseif($key_style_tpl = array_keys($params['params'],'div')){
        	$result = '<div '.$attr.'>'.$result.'</div>';
        }elseif($key_style_tpl = array_keys($params['params'],'style')){
        	$result = '<div '.$attr.'>'.$result.'</div>';
        }else{        	$result = $result;
        }

        $this->load_data();
        return $result;
    }

	/**
	*	���������� ������ ��� ������ ���������� �������
	*
	*/
    function get_pattern_split_fragment(){    	return '|'.$this->f_start.''.$this->prefix.' ([^'.$this->f_end.$this->f_start.']*)'.$this->f_end.'|Uis';
    }

	/**
	*	���������� ������ ��� ������ ���������� ����������
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