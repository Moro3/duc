<?php if (!defined('BASEPATH')) exit('No direct script access allowed');

/**
 * ����� Duc - �������� ����� ��� ������ � �������������
 *
 *
 */

class Pages extends MY_Controller {

    //��������� ������
    protected $setting;

    function __construct(){
        parent::__construct();
        $this->load->helper(array('form', 'url', 'assets'));
        $this->load->library('form_validation');
        $this->load->library('control_uri');
        $this->load->library('pagination');


        $this->lang->load('pages');
        $this->load->helper('language');
        $this->load->helper('text');

        //$this->config->load('index_request', TRUE);
        //$this->index_request = $this->config->item('index_request', 'index_request');

        //$this->config->load('menu', TRUE);
        //$this->get = $this->config->item('menu', 'menu');
        $this->config->load('setting', TRUE);
        $this->setting = $this->config->item('setting');

        //$this->load->library('datetimepicker');
        $this->load_config();
        $this->load_models();

        $this->table = 'duc_groups';

    }

    function load_config(){
		$this->config->load('setting', true);
		$this->setting = $this->config->item('setting');
        //print_r($this->setting);
        //exit;
	}

	function load_models(){
    	$this->load->model('pages_contents_model');
    	$this->load->model('pages_headers_model');
    	$this->load->model('pages_seo_model');
    }

    function index(){

    }

    // ������������� ������
    function admin_index(){
        $buf = '';

        //$this->assets->load_style('pages.css', 'pages');
        //$this->assets->load_script('jquery/jquery-ui/plugin/jquery-ui-timepicker-addon.js', false);
        //echo "<pre>";
        //print_r ($this->router_modules->tree_menu('admin'));
        //echo "</pre>";
        $this->admin_menu_view();
//        echo "!!";
        //$this->grid_render();
//        echo "<script>";
//        echo $this->grid->loader->render('grid_news');
//        echo "</script>";

        // ��������� ������� ��� ������ � ������ ������ admin (file: route), ������ duc, ������� ��������������� ���������
        $buf .= $this->router_modules->run('admin', 'pages', true);

        echo $buf;
        //exit();
        //return $buf;
    }

    /*
     * ������ ���� ������ �� ���������
     */
    function admin_menu_view(){
        //$data['tree'] = $this->router_modules->tree_menu('admin');
        $data['tree'] = $this->control_uri->route->tree_menu('admin');
        $data['uri']['point'] = $this->load->module('pages/pages')->uri_point('admin');
        //$data['uri']['point'] = '/';

		if(is_array($data['tree'])){
			//echo "<pre>";
			//print_r($data['tree']);
			//echo "</pre>";
			echo "<div class=\"menu_module\">";
			$this->print_tree($data['tree'], 0, 0, array('uri' => $data['uri']));
			echo "</div>";
		}

        //$this->load->view('admin/menu_default_2', $data);
    }


	function print_tree($tree, $current_key = 0, $level = 0, $options = false){
        if(isset($tree[$current_key])){
        	$arr = array('data' => $tree[$current_key], 'current' => $current_key, 'level' =>  $level);
        	$data = array_merge($arr, $options);
        	$this->load->view('admin/menu_default_row', $data);

	        foreach($tree[$current_key] as $key=>$value){

				 //if($key ){

						if(isset($tree[$key]) && $value['path_link'] == 1 || $value['active_link'] == 1){
	                        $l = $level + 1;
							$this->print_tree($tree, $key, $l, $options);
						}
				 //}

			}
        }
	}

    /*
     *  ����� ������ ������� uri
     */
    function uri_point($name = ''){
        switch ($name){
            case 'admin':
                $uri = $this->control_uri->get_full_segment('admin','mod');
                $uri .= 'pages/';
            break;
            case 'user':
                $uri = $this->control_uri->get_full_segment('pages');
                $uri = 'pages/';
            break;
            case 'user_main':
                $uri = '/pages/';
            break;
            case 'admin_ajax':
                $uri = '/ajax/pages/admin/';
            break;
            case 'admin_ajax_pages_delete_foto':
                 // Uri ��� �������� ���� ��������: admin
                 // (user_list_object_main)
                 $data['uri']['object'] = $this->router_modules->generate_link('pages_foto_delete', 'ajax', 'pages');
             break;
            default:
                $uri = $this->control_uri->get_full_segment('pages');
                $uri .= '';
            break;
        }

        return $uri;
    }

	function grid_admin_object(){
        $this->grid_render();
	}

	function grid(){
        $this->grid_params();
        // header("Content-type: text/json;charset=utf-8");

        if(isset($_POST['id']) && isset($_POST['oper'])){
            if(is_numeric($this->input->post('id')) && $this->input->post('oper') == 'edit'){
            	$this->grid->loader->oper($this->table, 'edit');
            }elseif(is_numeric($this->input->post('id')) && $this->input->post('oper') == 'del'){
            	$this->grid->loader->oper($this->table, 'del');
            }else{
            	$this->grid->loader->oper($this->table, 'add');
            }
        }else{
        	$this->grid->loader->output($this->table);
        }
    }

    function grid_render(){

        $this->grid_params();

//-------------- ���������� ������ ���������� �������� (WYSIWYG)
		echo '<script type="text/javascript" src="/wysiwyg/fckeditor/fckeditor.js"></script>';
		//echo '<script type="text/javascript" src="/wysiwyg/tiny_mce/tiny_mce.js"></script>';
        //echo '<script type="text/javascript" src="/wysiwyg/ckeditor/ckeditor.js"></script>';



//-------------- �������� ������� jQGrid
   		echo "\r\n<script>";
        	echo $this->grid->loader->render($this->table);
        echo "</script>\r\n";



//------------- ����������� ������� ��� ����� � �������������� FCKeditor -----------

		//��� ������ ����� � �������������� FCKeditor
		echo '<script>';
		echo '$grid.bind(\'jqGridAddEditAfterShowForm\', function(event, $form)
			{

                //var oFCKeditor = new FCKeditor( "description" ) ;
				//oFCKeditor.BasePath = "/wysiwyg/fckeditor/" ;
                //oFCKeditor.Height = 300 ;
                //oFCKeditor.ToolbarSet = "BasicA";
				//oFCKeditor.ReplaceTextarea() ;

				gridEditWysiwyg("description");
			});
		';

        //echo '</script>';
        //echo "<script>";
		// ��� ������ ��������� � �������������� FCKeditor
        echo '$grid.bind(\'jqGridAddEditClickSubmit\', function(event, $form)
			{
				oEditor = FCKeditorAPI.GetInstance("description"); //�������� ������ �� ������ "��������"
				//description   = oEditor.GetXHTML("html");

				//text = oEditor.GetXHTML("html");
				return {
			     description: oEditor.GetHTML() //�������� ����� � �������
			    };
			});
        ';
        echo 'function gridEditWysiwyg(field)
			{
                var oFCKeditor = new FCKeditor( field ) ;
				oFCKeditor.BasePath = "/wysiwyg/fckeditor/" ;
                oFCKeditor.Height = 300 ;
                oFCKeditor.ToolbarSet = "BasicA";
				oFCKeditor.ReplaceTextarea() ;
			}';

        echo '</script>';



    }

    function grid_params(){
        $get = '';
        if(isset($_GET['id']) && is_numeric($_GET['id'])){        	$get = 'id='.$_GET['id'];
        }
        $path_to_tables = Modules::current_path().'models/grid/';
        $this->grid->loader->set("grid_path", $path_to_tables);
        //$this->grid->loader->set("images_path", assets_uploads().'images/lands/objects/');
        //$this->grid->loader->set("path_root", FCPATH);
        $this->grid->loader->set("config", $this->setting);
        $this->grid->loader->set('debug_output', true);
    }

    /**
	*  ��������� ����� ����� ��� ���� ��������
	*
	*  @param numeric - id �������
	*
	*  return string - ������ md5
	*/
    function generate_file_name_object($file_name){
    	//$file_name = $file_name.mktime();
        //echo $file_name;

        //echo $file;
        //echo '<br>'.$ext;
        //exit;
        $file_name = iconv('utf-8','cp1251', $file_name);

        $file_name = Modules::run('pages/pages_headers/rus2lat', $file_name, 'low');

        $info = pathinfo($file_name);
		$ext = ".".$info['extension']; // ������� ����������
		$p = strrpos($file_name, '.');
    	if ($p > 0) $file = substr($file_name, 0, $p);
    	else $file = $file_name;

        //$file_name = $this->rus2lat($file_name, 'low');
        $name =  'page_'.$file;
        $dir_source = $this->setting['path']['root'].$this->setting['path']['icons'].$this->setting['image_config']['dir'].'/';
        $i = 2;
        $name_tmp = $name;
        while(is_file($dir_source.$name_tmp.$ext)){        	$name_tmp = $name.'_'.$i;
        	$i++;
        }
    	//echo $name_tmp;
    	//exit;
    	return $name_tmp.$ext;
    }

    /**
    *	�������� ����������� c �����
    *   @param string - ��� �����
    */
    function delete_image_object($file_name){
    	if(!empty($file_name)){
	    	$file_source = $this->setting['path']['root'].$this->setting['path']['icons'].$this->setting['image_config']['dir'].'/'.$file_name;
	    	if(is_file($file_source)){
	    		 if(unlink($file_source)) return true;
	    	}
	    	return true;
	  	}
	  	return false;
    }

    //�������� �����������
    function upload_file($name){
        if(empty($name)) return false;
			$config['upload_path'] = $this->setting['path']['root'].$this->setting['path']['icons'].$this->setting['image_config']['dir'];
			$config['allowed_types'] = $this->setting['image_config']['allowed_types'];
			$config['max_size']	= $this->setting['image_config']['max_size'];
			$config['max_width'] = $this->setting['image_config']['max_width'];
			$config['max_height'] = $this->setting['image_config']['max_height'];
           	$config['change_name'] = Modules::run('pages/pages/generate_file_name_object', $name);
            //$this->CI->output->set_header("X-Requested-With: XMLHttpRequest");
            //$this->CI->output->set_header("ci-cms: rules");
           	//header('X-Requested-With: XMLHttpRequest');
			//echo $config['change_name'];
			$this->load->library('upload', $config);
            //print_r($config);
			if ( ! $this->upload->do_upload('foto_upload')){
				//$error = array('error' => $this->upload->display_errors());
                throw new jqGrid_Exception($this->upload->display_errors());
			}else{
				$files_data = $this->upload->data();
				//Modules::run('duc/duc_teacher/images_resize_object', $files_data['file_name']);
				//Modules::run('lands/lands_photos/delete_images_original_object', $files_data['file_name']);

			}
            //$data['icon'] = $files_data['file_name'];

            return $files_data;
	}

	/**
	* �������� ����������� POST �������� �� ID ��������
	*
	*/
	function delete_foto(){

		if($this->input->post('id')){
			$id = $this->input->post('id');
			$row = Modules::run('pages/pages_headers/MY_data_row',
			                    //select
			                    array('id', 'icon'),
			                    //where
			                    array('id' => $id)

			);

			if($row->id == $id){
				if(!empty($row->icon)){
					// ���� ������ � ����� �� ��������� ���� ����� �������� id
					$row_foto = Modules::run('pages/pages_headers/MY_data_row',
			                    //select
			                    array('id', 'icon'),
			                    //where
			                    array('icon' => $row->icon,
			                    		'id !=' => $row->id
			                    )
					);
					//���� ����� � ����� �� ������������ �� �������, ������� ����
				    if(empty($row_foto)) $this->delete_image_object($row->icon);
					//������� ������ ���� �� ����
							if(Modules::run('pages/pages_headers/MY_update',
									//set
									array('icon' => ''),
									//where
									array('id' => $row->id)
								)){
								echo '����������� �������';
								//return true;
							}


				}else{
					echo '����������� ���';
				}
			}else{
				echo 'ID �� ������';
			}
		}
		//return false;
	}

	/**
	*  ������ ���� ��� ��������
	*  @param string - ��� �����
	*
	*/
    function images_resize_object($file_name){
    	if(!empty($file_name)){
	    	$file_source = $this->setting['path']['root'].$this->setting['path']['icons'].$this->setting['image_config']['dir'].'/'.$file_name;
	    	if(is_file($file_source)){
		    	//echo '���� ���������: '.$file_source.'<br />';
		    	$this->load->library('image_lib');
		    	$config['image_library'] = 'imagemagick';
		    	//$config['quality'] = '90%';
		    	//$config['library_path'] = 'Z:\usr\local\php5\ext';
		    	//$config['library_path'] = 'Z:\usr\local\ImageMagick-6.8.0-Q16';
		    	//$config['library_path'] = 'Z:\usr\local\ImageMagick-6.5.1-Q16';

		    	//---64-bit
		    	$config['library_path'] = 'Z:\usr\local\ImageMagick-6.8.4-Q16';
		    	//--- ������� SWEB
		    	//$config['library_path'] = '/usr/bin/convert';
				$config['source_image'] = $file_source;
				//$config['new_image'] = $this->setting['path']['root'].$this->setting['path']['images'].$value['dir'].'/'.$file_name;
					//echo '���� �����: '.$config['new_image'].'<br />';
                $size = getimagesize($file_source);

                if($size[0] > $this->setting['image_config']['x'] || $size[1] > $this->setting['image_config']['y']){
					$config['width'] = $this->setting['image_config']['x'];
					$config['height'] = $this->setting['image_config']['y'];
				}else{
					$config['width'] = $size[0];
					$config['height'] = $size[1];
				}
					// ��������� �������� �� ���������� � ����������
					$config['maintain_ratio'] = TRUE;
					//$config['width'] = $this->setting['image_config']['x'];
					//$config['height'] = $this->setting['image_config']['y'];
                    $this->image_lib->initialize($config);
					if ( ! $this->image_lib->resize())
					{
					    throw new jqGrid_Exception($this->image_lib->display_errors());
					    echo $this->image_lib->display_errors();
					    exit;
					}
					$this->image_lib->clear();
					$size = getimagesize($file_source);


				//$config['image_library'] = 'imagemagick';
				//$config['library_path'] = '/usr/bin/convert';
				//$config['library_path'] = 'Z:\usr\local\ImageMagick-6.8.0-Q16';
				//$config['source_image'] = $file_source;

		        // ������ ������ ���������
		    	foreach($this->setting['images'] as $key=>$value){
					$config['new_image'] = $this->setting['path']['root'].$this->setting['path']['icons'].$value['dir'].'/'.$file_name;
					if($size[0] > $value['x'] || $size[1] > $value['y']){
						//echo '���� �����: '.$config['new_image'].'<br />';
						$config['maintain_ratio'] = TRUE;
						$config['width'] = $value['x'];
						$config['height'] = $value['y'];
	                    $this->image_lib->initialize($config);
						if ( ! $this->image_lib->resize())
						{
						    throw new jqGrid_Exception($this->image_lib->display_errors());
						    echo $this->image_lib->display_errors();
						    exit;
						}
						$this->image_lib->clear();
					}else{
						copy($config['source_image'],$config['new_image']);
					}
		    	}
	    	}else{
            	throw new jqGrid_Exception('�� ������ �������� �����: '.$file_name);
	    	}
    	}else{
    		throw new jqGrid_Exception('��� ����� ������: '.$file_name);
    	}
    }

	/**
	* ������ �� ������ ��������� ������ ��� ������
	* ����� ����������� �� ����������� ����������
	*
	*/
	function array_icon(){
		$path_icon = $this->setting['path']['icons'].$this->setting['image_config']['dir'].'/';
		$dh  = opendir($path_icon);
		//$files[0] = '�� �������';
		$files = array();
		$i = 1;
		while (false !== ($filename = readdir($dh))) {
            if(is_file($path_icon.$filename)){
		    	$files[$i] = $filename;
		    	$i++;
            }
		}
        return $files;
	}

	/**
	* ������ �� ������ ��������� ������ ��� ���� � �����
	* ����� ����������� �� ����������� ����������
	*
	*/
	function array_img_fon(){
		//$path_icon = $this->setting['path']['img_fon'];
		$path_img = $this->assets->get_assets_path_source(false, 'img').'fon_header/';
		$dh  = opendir($path_img);
		//$files[0] = '�� �������';
		$files = array();
		$i = 1;
		while (false !== ($filename = readdir($dh))) {
            if(is_file($path_img.$filename)){
		    	$files[$i] = $filename;
		    	$i++;
            }
		}
        return $files;
	}

	/**
	*  ���������� ������ ������ ������� HTML
	*  ��� ������������� �� ����� ������ jquery combobox
	*  ������ ��������� ������ ����������� � ������
	*
	*/
	function image_combobox(){
		$path_icon = $this->setting['path']['images_teachers'].$this->setting['image_config']['dir'].'/';
		$files = $this->array_icon();
		//$res = '<div id="select_img">';
		$res .= '<select class="select_img" style="width:400px" name="webmenu" id="webmenu">';
		$res .= '<option value="0" data-image="">�� ������</option>';
		foreach($files as $key=>$value){
			$res .= '<option value="'.$key.'" data-image="/'.$path_icon.$value.'">'.$value.'</option>';
		}
		$res .= '</select>';
		//$res .= '</div>';
        return $res;
	}

	/**
	*  ���������� ������ ������ ������� HTML
	*  ��� ������������� �� ����� ������ jquery selectmenu
	*  ������ ��������� ������ ����������� � ������
	*
	*/
	function image_selectmenu(){
		$path_icon = $this->setting['path']['images_teachers'].$this->setting['image_config']['dir'].'/';
		$files = $this->array_icon();
		$res = '<div id="select_img"><fieldset>';
		$res .= '<label for="peopleA">Select a Person:</label>';
		$res .= '<select name="peopleA" id="peopleA">';
		foreach($files as $key=>$value){
			$res .= '<option value="'.$key.'" class="avatar" title="/'.$path_icon.$value.'">'.$value.'</option>';
		}
		$res .= '</select></fieldset</div>';

        $res = '
		<fieldset>
			<label for="peopleA">Select a Person:</label>
			<select name="peopleA" id="peopleA">
				<option value="1" class="avatar" title="http://www.gravatar.com/avatar/b3e04a46e85ad3e165d66f5d927eb609?d=monsterid&r=g&s=16">John Resig</option>
				<option value="2" class="avatar" title="http://www.gravatar.com/avatar/e42b1e5c7cfd2be0933e696e292a4d5f?d=monsterid&r=g&s=16">Tauren Mills</option>
				<option value="3" class="avatar" title="http://www.gravatar.com/avatar/bdeaec11dd663f26fa58ced0eb7facc8?d=monsterid&r=g&s=16">Jane Doe</option>
			</select>
		</fieldset>
		';
        return $res;
	}

	// Disclaimer: ������ �� ��������� �������! ��������� ������������� ����������� � ������.
	// ��� ������� � �������������� ���������� �������������� ���������� �������� �������.
	// �������������� ������-����������� ������� UpLow($s)
    /**
    *  @param string - ������ ��� �����������
    *  @param string - low || up - ������� � ������ ��� � ������� �������
    */
	protected function UpLow($string,$registr='low'){
		  $upper = '�����Ũ��������������������������ABCDEFGHIJKLMNOPQRSTUVWXYZ';
		  //$upper = '�����Ũ��������������������������';
		  $lower = '��������������������������������abcdefghijklmnopqrstuvwxyz';
		  //$lower = '��������������������������������';
		  if($registr == 'up') $string = strtr($string,$lower,$upper);
		  if($registr == 'low') $string = strtr($string,$upper,$lower);
		  return $string;
	} //function UpLow(&$string,$registr='up')

	public function rus2lat($s, $registr = 'low') { // ������� ��������� ������������� ��������� � ��������.
		  // ������� �� ��������� � ������� �������, ������ �� � ������� ������� strtoupper
		  $s = $this->UpLow($s, $registr);
		  //� ����� ������ ��������� � ������

		  $s=str_replace("��","YHA",$s);
		  $s=str_replace("��","YHO",$s);
		  $s=str_replace("��","YHU",$s);
		  $s=str_replace("�","YO",$s);
		  $s=str_replace("�","ZH",$s);
		  $rus = "����������������������";
		  $lat = "ABVGDEZIJKLMNOPRSTUFXC";
		  $s = strtr($s, $rus, $lat);
		  $s=str_replace("�","CH",$s);
		  $s=str_replace("�","SH",$s);
		  $s=str_replace("�","SHH",$s);
		  $s=str_replace("�","QH",$s);
		  $s=str_replace("�","Y",$s);
		  $s=str_replace("�","Q",$s);
		  $s=str_replace("�","EH",$s);
		  $s=str_replace("�","YU",$s);
		  $s=str_replace("�","YA",$s);

		  $s=str_replace("��","yha",$s);
		  $s=str_replace("��","yho",$s);
		  $s=str_replace("��","yhu",$s);
		  $s=str_replace("�","yo",$s);
		  $s=str_replace("�","zh",$s);
		  $rus = "����������������������";
		  $lat = "abvgdezijklmnoprstufxc";
		  $s = strtr($s, $rus, $lat);
		  $s=str_replace("�","ch",$s);
		  $s=str_replace("�","sh",$s);
		  $s=str_replace("�","shh",$s);
		  $s=str_replace("�","qh",$s);
		  $s=str_replace("�","y",$s);
		  $s=str_replace("�","q",$s);
		  $s=str_replace("�","eh",$s);
		  $s=str_replace("�","yu",$s);
		  $s=str_replace("�","ya",$s);

		  $s=str_replace(" ","_",$s); // ��������� ������ �� �������� � %20
		  $s=str_replace(",",".h",$s); // ��������� �������
		  //$s=str_replace('"',"&quot;",$s); // ��������� �������
		  //$s=rawurlencode($s); // ����������� ������� URL - ��������� �����, �����, ����� � �������������
		  $pattern = '%[^A-z0-9_.-]+%is';
		  $s = preg_replace($pattern, '', $s);

		  return $s;
	} // function rus2lat($s)


	/**
	* 	���������� ������ �������
	*
	*/
	public function get_pages($arr){
	}

	public function get_all_id(){

	}

    public function pages_select(){    	$res = Modules::run('pages/pages_headers/MY_data',
    		//select
    		array('id',
    			  'related' => array('content' => array('id','name')),
    			  'uri'
    		)
    	);
    	foreach($res as $items){    		$result[$items->id] = '<b>'.$items->{content}[0]->name. '</b> : '. $items->uri . ' : <i style=\'grey\'>id=' . $items->id . '</i>';
    		//$result[$items->id] = $items->{content}[0]->name;
    	}

		if($result) return $result;
		return array();
    }

    /**
    * ���������� id �������� �� uri
    *	@param string $uri - ������ uri
    *
    *	@return number - ����� id ��� false, ���� �������� �� �������
    */
    public function id_is_uri($uri){    	//cho 'Uri: '.$uri.'<br/>';
    	//exit;
    	$res = Modules::run('pages/pages_headers/MY_data_row',
    		//select
    		array('id'),
    		//where
    		array('uri' => $uri)
    	);
    	if(isset($res->id)) return $res->id;
    	return false;
    }

    /**
    * ���������� �������� uri, �.�. uri ��� ������ "/"
    * ���� ����� ������� uri �� ������� "first/too/free", �� ����� ��������� ������������ uri
    * �������� ���� ���� "page1/page2/filters"
    * ���� ���������� �������� page1 � page2, �� �� ���������� filters, �� ������ id page2
    * ���� ��� ����� ������ uri ������������, �� ������ false
    * ������ ������ ������ ��� ������������ ���������� �������� � ������ ���������� ������� uri � ��������
    *
    */
    public function id_is_valid_uri($uri){        $uri = $this->get_uri_valid($uri);
        if($uri === false) return false;
    	if($uri == '/') return $this->id_is_uri($uri);
    	$arr_uri = explode('/', $uri);
    	$end_uri = end($arr_uri);
    	$res = $this->id_is_uri($end_uri);
    	if(isset($res)) return $res;
    	return false;
    }

	/**
	* ���������� �������� uri � ������ ������� uri
	*	���� �� ���� ���������� �������������� uri, �������� �������������
	*	������ uri "page1/page2/page3/page4"
	*	page1, page2, page4 - ����������  page3 - �� ����������
	*	����� ���������� "page1/page2"
	*	������ ����� �� ��������� ������������ �������, � ������ ��������� ������� ������� uri
	*	���� ��������� ��������� ������������ ������� �������������� ����, ���������� �� ����������� uri ���������� ����������
	*
	*/
	public function get_uri_valid($uri){        //�������� ������ ������� ��������
        if($uri == '/'){
    		if($this->id_is_uri($uri)) return $uri;
    	}
    	$uri = trim($uri, '/');
    	$arr_uri = explode('/', $uri);
    	foreach($arr_uri as $item){
    		if($uri_check = $this->id_is_uri($item)){
    			$path_uri[] = $item;
    		}else{
    			break;
    		}
    	}
    	if(isset($path_uri)){    		//return $path_uri;
    		$res = implode('/', $path_uri);
    	}
    	if(isset($res)) return $res;
    	return false;
	}

	/**
	* ���������� �������� ������ id ������� � ������ ������� uri
	*	���� �� ���� ���������� �������������� uri, �������� �������������
	*	������ uri "page1/page2/page3/page4"
	*	page1, page2, page4 - ����������  page3 - �� ����������
	*	����� ���������� array(0=>{id page1}, 1=>{id_page2})
	*	������ ����� �� ��������� ������������ �������, � ������ ��������� ������� ������� uri
	*	���� ��������� ��������� ������������ ������� �������������� ����, ���������� �� ����������� uri ���������� ����������
	*
	*/
	public function get_ids_valid($uri){
        //�������� ������ ������� ��������
        if($uri == '/'){
    		if($ids = $this->id_is_uri($uri)) return array($ids);
    	}
    	$uri = trim($uri, '/');
    	$arr_uri = explode('/', $uri);
    	foreach($arr_uri as $item){
    		if($id_check = $this->id_is_uri($item)){
    			$ids[] = $id_check;
    		}else{
    			break;
    		}
    	}
    	if(isset($ids)){
    		return $ids;
    	}

    	return false;
	}

    public function pages_select_prefix(){    	//$prefix = '[page]';
    	$res = $this->pages_select();
    	//return $res;
    	foreach($res as $key=>$item){    		$res_pr[$key] = '<a class=\'iframe\' href=/ajaxs/?resource=pages/admin/pages~&id='.$key.'>'.$item.'</a>';
    		//$res_pr[$key] = '<a id=\'iframe cboxElement\' href=/ajaxs/?resource=pages/admin/pages~&id='.$key.'>'.$item.'</a>';

    	}
    	if($res_pr) return $res_pr;
		return array();
    }

    public function get_pages_menus(){    	return Modules::run('menus/menus_trees/get_nodes');
    }

    /**
    * 	���������� ���������� � ��������
    *
    */
    public function get_data_page($id){   		if( ! is_numeric($id)) return false;
   		$res = Modules::run('pages/pages_headers/MY_data_array_row',   			//select
   			array('main.id','main.active', 'main.uri', 'main.icon', 'main.text1', 'main.text2', 'main.img_fon',
   				 //'seo.title', 'seo.description', 'seo.keywords', 'seo.h1',
   				 //'content.id', 'content.name', 'content.active', 'content.description',
   					'related' => array('content' => array(
   														'main.id', 'main.active', 'main.name', 'main.description',
   														'seo.id', 'seo.title', 'seo.description', 'seo.keywords',
   														'related' => array('seo' => array('id', 'title', 'description', 'keywords', 'h1'))
   										),

   					),
   			),
   			//where
   			array('main.id' => $id,
   				//'content.id_page_header' => $id,
   				//'seo.id_page_content' => array('encode' => 'content.id')

   			)

   		);
   		return $res;
    }

    /**
    * 	���������� ���������� � ���������
    *
    */
    public function get_data_pages($ids){
        if( ! is_array($ids)) $ids = array($ids);
   		$res = Modules::run('pages/pages_headers/MY_data',
   			//select
   			array('main.id','main.active', 'main.uri', 'main.icon', 'main.text1', 'main.text2', 'main.img_fon',
   				 //'seo.title', 'seo.description', 'seo.keywords', 'seo.h1',
   				 //'content.id', 'content.name', 'content.active', 'content.description',
   					'related' => array('content' => array(
   														'main.id', 'main.active', 'main.name', 'main.description',
   														'seo.id', 'seo.title', 'seo.description', 'seo.keywords',
   														'related' => array('seo' => array('id', 'title', 'description', 'keywords', 'h1'))
   										),

   					),
   			),
   			//where
   			array('main.id' => $ids,
   				//'content.id_page_header' => $id,
   				//'seo.id_page_content' => array('encode' => 'content.id')

   			)

   		);
   		return $res;
    }

    public function getFieldUri($uri){    	if($uri == '/'){    		return '/';
    	}
    	if(is_string($uri)){    		return '/'.trim($uri, '\/').'/';
    	}
    	return '/';
    }

}