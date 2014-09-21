<?php (defined('BASEPATH')) OR exit('No direct script access allowed');


class CI_Assets {

	private $config = array();
	private $is_loaded = array();
	private $_config_paths = array();
	private $_type = array();
	private $_link_site = false; //��� ������ �� ���� false -(��� http://��� �����/)
                           //                   true - (c http:/��� �����/)

  // local - �������� (����� ������������� ������ �� ����, ���� ����� ������� � ����� ����������)
  // head  - � ���� �������� (���� ����� �������� � ���� ������ � ��������� �������� (������ ��� ����� - script, style), ������ ������ ����� ������� � ����� ����������)
  // remote - ������ �� ���� ����� ��������� �� ��������� ������ (���� ��� ����� ������� � ������ url)
  private $_place_script = 'local'; // ����� ���������� �������
  private $_place_style = 'local';  // ����� ���������� ����� ������
  private $_place_img = 'local';    // ����� ���������� �����������
  private $_place_doc = 'local';    // ����� ���������� ���������

  /**
  *  ������ ���������� �� ���������. ����� ������� �� ������� ����� ��������� ������,
  *  �� ��� ������ ���� ��������� ����� ������������� � ������� �������.
  *  ������ ���� ����� ������� ��������, �.�. ��������� ������ ����� ������� ���������� � ������� ����������� ������ �� ��������� �������
  *  �� ������ ������ � ����� ������������������ (�.�. ���������� ������� ���������� ���������� ���� � ���� ��������)
  *  �������� ��� ����� ����� ����������������� ��� �������� ��������
  *  �������� ��������������� ����� ������ ��� ������� ���� ����� � ������ ������
  *  ��� �� �������������� ����� ���������� � ������ set_domain_target($domain, $module),
  *  ������ ����� ����� ������������� ������ ��� �������� ������
  */
  private $_domain_script = '';
  private $_domain_style = '';
  private $_domain_img = '';
  private $_domain_doc = '';

  // ������ � ���������� ���� ������ ��� ������� ������ � ���� �����
  private $domain_target;

  // ������ � ���������� �������� �� ����������� ����� � ����� ���������� ��� ������� ������ � ���� �����
  private $timeout_target;
  private $timeout_default = 0;
  
  /**
  *   ��� ��������������� ���� ������������ �� ���� �������
  *   �.�. ���� ������ ���������� �� ������ /tpl/default, ���� � ������������ �����: /tpl/default/dir_img
  *   ��������� ���������� ����� ������������� � ����� ����: /tpl/default/dir_modules/name_module/dir_img (name_module - ����� �������� �������������, ���� �� �� ������)
  */
  // ����� ����������
  private $_dir_assets = 'assets'; // ����� ���������� ��������� ���������� ������

  // ����� �������� ������
  //var $_dir_target_img     = 'img';  // ����� ���������� �����������
  //var $_dir_target_script  = 'js';   // ����� ���������� ��������
  //var $_dir_target_style   = 'css';   // ����� ���������� ������ ������
  private $_dir_target_modules = 'modules';  // ����� ��� ���������� ���������� ������
  private $_dir_target_public = 'public';  // ����� ��� ���������� ���������� ������
  
  /**
  *  ��������� ���������� ������
  */
  private $_domain_donor = '';  // ��� ���������� ������ (http://site.ru)
  private $_donor_access = false; // ���������� �� ������ � ���������� ������ (false - ������� ���)
  
  /**
  *  ����� ��������� ��� �����������
  */
  private $_time_local_script = 0;
  private $_time_local_style = 0;
  private $_time_local_img = 0;
  private $_time_local_doc = 0;
  
  // ��������� �������� ���� ��� ������ ���������� � ���������� (������ ���������)
  private $path_root_target;
  private $path_root_source;
  
  // ������ �� ���������� ������� ��������� �������� �� �������� (���������) ���� � ������ ����������
  // ����������� ��� ������ ����� ������ script � style
  private $path_for_replace = array('{assets}/',
                                    '{assets}'
                                    );
  
	/**
	 * Constructor
	 *
	 * Sets the $config data from the primary config.php file as a class variable
	 *
	 * @access   public
	 * @param   string	the config file name
	 * @param   boolean  if configuration values should be loaded into their own section
	 * @param   boolean  true if errors should just return false, false if an error message should be displayed
	 * @return  boolean  if the file was successfully loaded or not
	 */
	function __construct()
	{
            $this->config =& get_config();
            $this->_type = array(
                         'style' => array('css'),
                         'script'  => array('js'),
                         'images'  => array('jpg','jpeg','gif','png','tiff','bmp','ico'),
                         'img'  => array('jpg','jpeg','gif','png'),
                         'text' => array('doc','docx','xls','xlsx','txt','rtf','pdf','ppt'),
                         'arj'  => array('zip','rar','jar','bz2','gz','tar','rpm'),
                         'audio'  => array('mp3','wav','ogg','flac','midi','rm','aac','wma','mka','ape'),
                         'video'  => array('mpeg','mp4','ram','ra','avi','mpg','mov','divx','asf','qt','mwv','rv','vob','asx','ogm','',''),
                         'mixed' => array('js','css','jpg','gif','ico','xml','txt','csv'),
                        );
            $this->_dir = array(
                         'style' => 'css',
                         'script'  => 'js',
                         'images'  => 'img',
                         'img'  => 'img',
                         'text' => 'doc',
                         'arj'  => 'arj',
                         'audio' => 'audio',
                         'video' => 'video',
                         'mixed' => 'packed',
                        );

            $this->path_root_target = rtrim($_SERVER['DOCUMENT_ROOT'],DIRECTORY_SEPARATOR).DIRECTORY_SEPARATOR;
            $this->path_root_source = rtrim($_SERVER['DOCUMENT_ROOT'],DIRECTORY_SEPARATOR).DIRECTORY_SEPARATOR;
            log_message('debug', "Assets Class Initialized");
		
	}
        


        /**
         *  �������� ����� ������
         * @param type $file
         * @param type $module
         * @return type 
         */
        public function load_style($file = 'style', $_module = '', $place = '') {                
                if($_module !== false){
                    $_module OR $_module = CI::$APP->router->current_module();
                }            
                
                if($this->load($file, $_module, 'style', $place)){
                    return true;
                }
                return false;
        }
        /**
         *  �������� ����� java �������
         * @param type $file
         * @param type $module
         * @return type 
         */
         public function load_script($file , $_module = '', $place = '') {
		if($_module !== false){
                    $_module OR $_module = CI::$APP->router->current_module();
                }
                if($this->load($file, $_module, 'script', $place)){
                    return true;
                }		
                return false;
	}
        /**
         *  �������� ����� ����������� �� ������� ������
         * @param string $file
         * @param string $module
         * @return boolean 
         */
         public function load_img($file , $_module = '') {		
		if($_module !== false){
                    $_module OR $_module = CI::$APP->router->current_module();
                }
                if($this->load($file, $_module, 'img')){
                    return true;
                }		
                return false;
	}
        
        /**
         *  �������� ������ ���� ������ �� ������� ������
         * 
         * @param string $dir  - ��� ����������, ������� ����� �����������
         * @param string $file_out - ��� ����� (��� ������ �� ������), ������� ������ ���� ������������ �� �������
         * @param string $module - ��� ������
         * @param string $type - ��� �����������
         * @return boolean 
         */
         public function load_packed($dir , $type = 'mixed', $file_out, $_module = '' ) {		
		if($_module !== false){
                    $_module OR $_module = CI::$APP->router->current_module();
                }
                if($this->load($file, $_module, $type)){
                    return true;
                }		
                return false;
	}
        
        /**
         * �������� ����� ������� ������
         *
         * @param string $file  name file without extention
         * @param string $module  name module
         * @param string $type    type (style, js, img)
         */
        private function load($file, $module = '', $type = 'style', $place = ''){
            // ��� ������
            if(!empty($module)){
                $data['module'] = trim($module, '/');
            }else{
                $data['module'] = '';
            }

            $type_content = $this->get_type_content($file, $type);
            // ���� ��� ���������� �� ��������� ��� ������ �� �����, ���������� ���������
            if($type_content == false){
                $this->set_error(__CLASS__, __METHOD__, 'debug', '��� ���������� '.htmlspecialchars($type).' ������ �� ����� ��� ��� ����� '.htmlspecialchars($file).' �� ������������� ������������');
                return false;
            }
            
            $type_dir = $this->get_dir_type($type);
            if($type_content == 'file' || $type_content == 'url'){
                $file = trim(str_replace('\\', '/', trim($file)),'/');

                // ������ � ������������ ���� ����� (��������� ������ �������� �� ���� ������ ��� host � �.�.)
                $data['url_arr'] = parse_url($file);
                if(isset($data['url_arr']['path'])){
                    $segments = explode('/', $data['url_arr']['path']);
                }else{
                    return false;
                }
                //$segments = explode('/', $file);

                $file_name = array_pop($segments);
		            $file_name = str_replace(EXT, '', $file_name);
                // ������ ���������� ����
                $data['path_int'] = ltrim(implode('/', $segments).'/', '/');
                // ���������� �����
                $data['ext_file'] = $this->get_ext_file($file_name, $type);
                $file_name_without_ext = $this->get_file_name_without_ext($file_name, $type);
                if(!isset($file_name_without_ext)) return false;
                // �������� ��� ����� � �����������
                $data['file_name'] = $file_name_without_ext.'.'.$data['ext_file'];
                $place = $this->get_place_target($place, $type);
            }else{
                $place = 'head';
            }


            if($this->_donor_access == true){
                if(!empty($this->_domain_donor)){
                    $domain_donor = $this->_domain_donor;
                }else{
                    $domain_donor = false;
                }
            }else{
                $domain_donor = false;
            }

            $data['file_source'] = $file;           // ��� ����� ���������� ��� ��������
            $data['place_target'] = $place;         // ����� ��������� ����� ����������
            $data['type'] = $type;                  // ��� �����
            $data['dir_type'] = $type_dir;          // ����� ��� ����������� ���������� ������ ������� ����

            $data['domain_donor'] = $domain_donor;  // ����� �����
            $data['type_content'] = $type_content;  // ��� ����������� (file, text, url)

            /*
            if(is_file($path.$path_file.$file_name)){
                $this->set_item($file, $path.$path_file, $type);
                return $path.$path_file;
            }
            */
            if($this->set_item($data)) return true;

            return false;            
        }
        
        /**
        *  ��������� ��� �����������
        *  ��������� ��������: file - ������ �������� ������,
        *                      text - ������ �������� �������,
        *                      url  - ������ �������� �������
        *
        */
        function get_type_content($string, $type){
            if($this->check_path_file($string))  return 'file';
            if($this->check_url($string)) return 'url';
            if($this->check_is_jquery($string)){
                if($type == 'script')  return 'jQuery';
            }
            if($this->check_is_javascript($string)){
                if($type == 'script')  return 'javascript';
            }
            if($this->check_is_style($string)){
                if($type == 'style') return 'style';
            }
            return false;
        }
        
        /**
        *  ���������� ����� ���������� �����
        *  @param string
        *  @param string
        */
        function get_place_target($place, $type){
            if($place == 'head'){
                if($type == ('script' or 'style')){
                    return 'head';
                }
            }
            return 'local';
        }
        
        /**
        *  ���������� ��� ����� ��� ���������� �� ������ ��� ����
        *  @param type string
        *  @param type string
        *
        *  return string
        */
        function get_file_name_without_ext($file, $type){
            if(!isset($file)) return false;
            if(strpos($file, '.')){
                $seg = explode('.',$file);
                $ext = array_pop($seg);
                foreach($this->_type as $type_file=>$arr_exts){
                    if(in_array($ext, $arr_exts)){
                        if($type == $type_file){
                            return implode('.',$seg);
                        }
                    }
                }
            }
            return $file;
        }
        
        /**
         *  ���������� ��������� ���������� �����
         * @param string $type
         * @return string
         */
        function get_ext_file($file, $type = ''){
            if(strstr($file,'.')){
                $arr_file = explode('.',$file);
                $seg = array_pop($arr_file);
                if(isset($this->_type[$type])){
                  if(in_array($seg, $this->_type[$type])){
                      $ext = $seg;
                  }else{
                      $ext = reset($this->_type[$type]);
                  }
                }
            }else{
                if(isset($this->_type[$type])){
                    $ext = reset($this->_type[$type]);
                }
            }
            if(!isset($ext)) return false;
            return $ext;
        }
        
        /**
         *  ��������� ���������
         * @param type $file
         * @param type $path
         * @param type $type 
         */
        function set_item($data){
            $data['place_source'] = $this->get_place_source(&$data);
            $data['path_local_target'] = $this->get_path_local_target(&$data);
            if($this->search(&$data)){
                return true;
            }
            if($data['place_source'] == 'remote'){
                if($data['type_content'] == 'url'){
                    if($this->get_timeout($data['module'], $data['type']) < (time() - $this->time_create_file(&$data))){
                        if( ! $this->is_identical(&$data)){
                            $this->copy_remote_files(&$data);
                        }
                    }
                }
            }elseif($data['place_source'] == 'local'){
                if($data['type_content'] == 'file'){
                    if($this->get_timeout($data['module'], $data['type']) < (time() - $this->time_create_file(&$data))){
                        if( ! $this->is_identical(&$data)){
                            $this->copy_local_files(&$data);
                        }
                    }
                }
            }elseif($data['place_source'] == 'donor'){
                    if($this->get_timeout($data['module'], $data['type']) < (time() - $this->time_create_file(&$data))){
                        if( ! $this->is_identical(&$data)){
                            $this->copy_donor_files(&$data);
                        }
                    }
            }

            $this->_config_paths[$data['module']][$data['type']][$data['path_local_target']][] = $data;
            //echo "<pre>";
            //print_r($data);
            //echo "</pre>";

        }
        /**
        *  ����� ���������� ������
        *
        */
        function search($data){
            if(isset($this->_config_paths[$data['module']][$data['type']][$data['path_local_target']])){
                foreach($this->_config_paths[$data['module']][$data['type']][$data['path_local_target']] as $items){
                    if($items['place_source'] == $data['place_source']){
                        if($items['type_content'] == $data['type_content']){
                            if($items['type_content'] == 'file' || $items['type_content'] == 'url' || $items['type_content'] == 'donor'){
                                if($items['file_name'] == $data['file_name']){
                                    return true;
                                }
                            }else{
                                if($items['file_source'] == $data['file_source']){
                                    return true;
                                }
                            }
                        }
                    }
                }
            }
        }
        
        /**
        *  ��������� ����� �������� �����
        *  ��� ������� ����� ���� ���������� �� �������� ������
        *
        */
        function time_create_file($data){
            if($data['type_content'] == 'file' || $data['type_content'] == 'url'){
                if(is_file($this->path_root_target.$data['path_local_target'].$data['file_name'])){
                    return filectime($this->path_root_target.$data['path_local_target'].$data['file_name']);
                }
            }else{
                return time() + 1000; // !!!��� ���������� ������� �� ���. ������� � ������� �� ����� ���������� ������� , � ������ ���������� ��������
            }
            return false;
        }
        
        /**
        *  ���������� ����� ���������� ����� ���������
        *
        */
        function get_place_source($data){
            if($data['type_content'] == 'url'){
                return 'remote';
            }else{
                if($this->_donor_access == true){
                    if($this->get_domain_donor()){
                        return 'donor';
                    }
                }
            }
            return 'local';
        }
        
        /**
        *  ���������� ��� ������ ������
        */
        function get_domain_donor(){
            if($this->check_domain($this->_donor_domain)){
                return $this->_donor_domain;
            }
            return false;
        }
        

        /**
        *   �������� �� ������������ ������
        *
        *
        */
        function is_identical($data){
            if($data['type'] == 'video' || $data['type'] == 'audio' || $data['type'] == 'arj' || $data['type'] == 'img' || $data['type'] == 'images' || $data['type'] == 'text'){

                if($data['type_content'] == 'url' || $data['type_content'] == 'file'){
                    if($data['place_source'] == 'local'){
                        $hash_source = $this->hash_local_source(&$data);
                    }elseif($data['place_source'] == 'remote'){
                        $hash_source = $this->hash_remote_source(&$data);
                    }elseif($data['place_source'] == 'donor'){
                        $hash_source = $this->hash_donor_source(&$data);
                    }
                    $hash_target = $this->hash_local_target(&$data);
                    if($hash_source == $hash_target && $hash_source !== false){
                      return true;
                    }else{
                      return false;
                    }
                }
            }elseif($data['type'] == 'script' || $data['type'] == 'style'){
                if($data['type_content'] == 'url' || $data['type_content'] == 'file'){
                    $content_source = $this->get_content_source_file(&$data);
                    if(isset($content)){
                        //������ ����� �� �����������
                        $content_source = $this->replace_path($content);
                    }
                    $content_target = $this->content_local_target(&$data);
                    if($content_source == $content_target && $content_source !== false){
                      return true;
                    }else{
                      return false;
                    }
                }

            }
            return false;
        }
        /**
        *  ������ ����� ��� ���� ����� �� ���� �� �����������
        */
        function replace_path($content){
          if(isset($this->path_for_replace) && is_array($this->path_for_replace)){
              $path = $this->get_assets_path();
              foreach($this->path_for_replace as $item){
                  $content = str_replace($item, $path, $content);
              }
          }
          return $content;
        }
        /**
        *  ���������� ���������� ����� ���������
        */
        function get_content_source_file($data){
            if($data['place_source'] == 'local'){
                $content_source = $this->content_local_source(&$data);
            }elseif($data['place_source'] == 'remote'){
                $content_source = $this->content_remote_source(&$data);
            }elseif($data['place_source'] == 'donor'){
                $content_source = $this->content_donor_source(&$data);
            }
            return $content_source;
        }
        /**
        *  ���������� md5 ��� ���������� ����� ���������
        */
        function hash_local_source(&$data){
            $path_source = $this->get_path_local_source_full(&$data);
            return md5($path_source.$data['file_name']);
        }
        /**
        *  ���������� md5 ��� ���������� ����� ���������
        */
        function hash_remote_source(&$data){
            $path_source = $this->get_path_remote_source_full(&$data);
            return md5($path_source.$data['file_name']);
        }
        /**
        *  ���������� md5 ��� ���������� ����� ���������
        */
        function hash_donor_source(&$data){
            //������ �� ������ ������
            return false;
        }
        /**
        *  ���������� md5 ��� ���������� ����� ����������
        */
        function hash_local_target(&$data){
            $path_target = $this->path_root_target.$data['path_local_target'];
            return md5($path_target.$data['file_name']);
        }
        /**
        *  ���������� ���������� ���������� ����� ���������
        */
        function content_local_source(&$data){
            $path_source = $this->get_path_local_source_full(&$data);
            if(is_file($path_source.$data['file_name'])){
                return file_get_contents($path_source.$data['file_name']);
            }
            return false;
        }
        /**
        *  ���������� ���������� ���������� ����� ���������
        */
        function content_remote_source(&$data){
            $path_source = $this->get_path_remote_source_full(&$data);
            return file_get_contents($path_source.$data['file_name']);
        }
        /**
        *  ���������� ���������� ���������� ����� ���������
        */
        function content_donor_source(&$data){
            //������ �� ������ ������
            return false;
        }
        /**
        *  ���������� ���������� ���������� ����� ����������
        */
        function content_local_target(&$data){
            $path_target = $this->path_root_target.$data['path_local_target'];
            if(is_file($path_target.$data['file_name'])){
                return file_get_contents($path_target.$data['file_name']);
            }
            return false;
        }
        /**
        *  ����������� ���������� ����� � ����� ����������
        *
        */
        function copy_remote_files($data){
            if( ! is_dir($this->path_root_target.$data['path_local_target'])){
                if( ! $this->mk_dir($data['path_local_target'])) return false;
            }
            if($this->content_remote_source(&$data)){
                return true;
            }
            return false;
        }
        
        /**
        *  ����������� ���������� ����� � ����� ����������
        *
        */
        function copy_local_files($data){
            if( ! is_dir($this->path_root_target.$data['path_local_target'])){
                if( ! $this->mk_dir($data['path_local_target'])) return false;
            }
            echo "����������: ".$data['file_name'];
            if(copy($this->get_path_local_source_full(&$data).$data['file_name'], $this->path_root_target.$data['path_local_target'].$data['file_name'])){
                return true;
            }else{
                return false;
            }
        }
        /**
        *  ����������� ���������� ����� � ����� ����������
        *
        */
        function copy_donor_files($data){

            //echo "<b>���� �c������� <i>������</i>:</b> ".$path_source.''.$data['file_name']." ���������� �<br />";
            //echo "���� ����������: ".$path_target.''.$data['file_name']."<br />";

            //copy($path_source.'/'.$data['file_name'], $path_target.'/'.$data['file_name']);
        }

        /**
        *  ������� ���������� ����������, ���� ������� �����������
        *  ��������� ����� ����� ���������
        */
        function mk_dir($local_path){
            if(!empty($local_path)){
                $local_path = str_replace('\\','/',$local_path);
                $local_path = trim($local_path, '/');
                if(strstr($local_path, '/')){
                    $arr_path = explode('/', $local_path);
                }else{
                    $arr_path = array($local_path);
                }
            }
            if(isset($arr_path) && is_array($arr_path)){
                if(is_dir($this->path_root_target)){
                    $dir = '';
                    print_r($arr_path);

                    foreach($arr_path as $item){
                        if(!empty($item)){
                            $dir .= $item;
                            if (! is_dir($this->path_root_target.$dir)){
                                if( ! mkdir($this->path_root_target.$dir, 0644)) return false;
                            }
                            $dir .= DIRECTORY_SEPARATOR;
                        }
                    }
                }
                return true;
            }
            return false;
        }

        /** �������� �� ������������� ����� ����������
        *  arg $path - ���� � ����� ���������� !!!
        *      $file_name - ��� �����
        *  return boolean
        */
        function is_file_target($data){
            $path_target = $this->path_root_target.$data['path_local_target'];
            if(is_file($path_target.$data['file_name'])){
              return true;
            }
            return false;
        }
        
        /** �������� �� ������������� ���������� ����� ���������
        *
        *  return boolean
        */
        function is_file_source_remote($data){
            // ����, ������� �� ���������
            $url = $this->get_path_remote_source_full($data).$data['file_name'];
            $headers = @get_headers($url);
            // ��������� �� ����� �� ������� � ����� 200 - ��
            //if(preg_match("|200|", $Headers[0])) { // - ������� ������ :)
            if(strpos('200', $headers[0])) {
                return true;
            }else{
                return false;
            }
        }
        /** �������� �� ������������� ���������� ����� ���������
        *
        *  return boolean
        */
        function is_file_source_local($data){
            $path_full = $this->get_path_local_source_full(&$data);
            if(is_file($path_full.$data['file_name'])){
              return true;
            }
            return false;
        }
        /** �������� �� ������������� ���������� ����� ���������
        *
        *  return boolean
        */
        function is_file_source_donor($data){
            //����� ������ ���� ����������� ������ � ����� ������
            return false;
        }

        /*
        *  ���������� �������� ���� � ������ ����������
        */
        function get_assets_path(){
            $path = trim($this->config['tpl_path'],'\/').'/'.trim($this->config['tpl_name'],'\/').'/';
            return $path;
        }
        /*
        *  ���������� ���������� ���� � ����� � �����������
        */
        function get_path_application(){
            $path_app = rtrim(APPPATH, '\/').'/';
            $path_root_source = $this->path_root_source;
            if(strstr($path_app, $path_root_source)){
              $path = str_replace($path_root_source, '', $path_root_source);
            }else{
              $path = $path_app;
            }
            return $path;
        }
        /**
        *  ���������� ��������� ���� � ����� ��������� (��� ����� �����)
        */
        function get_path_local_source_full($data){
            return $this->path_root_source.$this->get_path_local_source(&$data);
        }
        /**
        *  ���������� ��������� ���� (������������ ����� �����) � ����� ��������� (��� ����� �����)
        */
        function get_path_local_source($data){
            if($data['dir_type']){
                $path_file = $this->_dir_assets.'/'.$data['dir_type'].'/'.$data['path_int'];
            }else{
                $path_file = $this->_dir_assets.'/'.$data['path_int'];
            }
            $path_app = $this->get_path_application();

            if(!empty($data['module'])){
                $path = $path_app.'modules'.'/'.$data['module'].'/';
            }else{
                $path = $path_app;
            }
            return $path.$path_file;
        }
        /**
        *  ���������� ��������� ���� � ����� ��������� (��� ����� �����)
        */
        function get_path_remote_source_full($data){
            if($data['type_content'] == 'url'){
                $str = @$data['url_arr']['scheme'].'://';
                $str .= @$data['url_arr']['host'].'/';
                $str .= $this->get_path_remote_source(&$data);
                return $str;
            }
            return false;
        }
        /**
        *  ���������� ��������� ���� (������������ ����� ���������) � ����� ��������� (��� ����� �����)
        */
        function get_path_remote_source($data){
            if($data['type_content'] == 'url'){
                $str = $data['path_int'];
                return $str;
            }
            return false;
        }

        /**
        *  ���������� ��������� ������ ���� (������� ���������� ���� � �����) � ����� ����������
        *
        */
        function get_path_local_target_full($data){
            $path = $this->path_root_target;
            $path .= $this->get_path_local_target(&$data);
            return $path;
        }
        /**
        *  ���������� ��������� ���� (������������� ���� � �����) � ����� ����������
        *  http://www.site.com/{path_local_target/}file.ext
        *  ������ ���� ������������ ��� �������� � ���� ������ ��� ���� �� ������
        */
        function get_path_local_target($data){
            $path = $this->get_assets_path();
            if(!empty($data['module'])){
              $path .= trim($this->_dir_target_modules,'\/').'/'.$data['module'].'/';
            }else{
              $path .= trim($this->_dir_target_public,'\/').'/';
            }
            $path .= $data['dir_type'].'/';
            if($data['type_content'] == 'url'){
                $str = @$data['url_arr']['scheme'];
                $str .= @$data['url_arr']['host'];
                $str .= $data['path_int'];
                $str .= $data['file_name'];
                $str .= $data['module'];
                $str .= $data['dir_type'];
                $path .= md5($str).'/';
            }elseif($data['type_content'] == 'file'){
                if(!empty($data['path_int'])){
                  $path .= $data['path_int'];
                }
            }else{
                if(!empty($data['path_int'])){
                  $path .= $data['path_int'];
                }
                if(!empty($data['type_content'])){
                    $path .= $data['type_content'].'/';
                }
                //$path .= '/';
            }

            return $path;
        }

        /**
         *
         * @param string $type
         * @return boolean 
         */
        function get_dir_type($type){
            if(!empty($this->_dir[$type])){
                $dir = $this->_dir[$type];
                return $dir;
            }
            
            return false;
        }
        
        /**
        *   ��������������� ������ ���������� ��� �������� ��� ������� ������
        *
        */
        function set_domain_script($domain, $module = ''){
            if($this->check_domain($domain)){
                $_module OR $_module = CI::$APP->router->fetch_module();
                $this->set_domain_target($domain, $_module, 'script');
            }
            return false;
        }
        
        /**
        *   ��������������� ������ ���������� ��� ������ ��� ������� ������
        *
        */
        function set_domain_style($domain, $module = ''){
            if($this->check_domain($domain)){
                $_module OR $_module = CI::$APP->router->fetch_module();
                $this->set_domain_target($domain, $_module, 'style');
            }
            return false;
        }
        
        /**
        *   ��������������� ������ ���������� ��� ����������� ��� ������� ������
        *
        */
        function set_domain_img($domain, $module = ''){
            if($this->check_domain($domain)){
                $_module OR $_module = CI::$APP->router->fetch_module();
                $this->set_domain_target($domain, $_module, 'img');
            }
            return false;
        }
        
        /**
        *   ��������������� ������ ���������� ��� ����������� ��� ������� ������
        *
        */
        function set_domain_doc($domain, $module = ''){
            if($this->check_domain($domain)){
                $_module OR $_module = CI::$APP->router->fetch_module();
                $this->set_domain_target($domain, $_module, 'text');
            }
            return false;
        }
        
        /**
        *   ��������������� ������ ���������� ��� �������� ������ ��� ������� ������
        *
        */
        function set_domain_arj($domain, $module = ''){
            if($this->check_domain($domain)){
                $_module OR $_module = CI::$APP->router->fetch_module();
                $this->set_domain_target($domain, $_module, 'arg');
            }
            return false;
        }
        
        /**
        *   ��������������� ������ ���������� ��� ����������� ������ ��� ������� ������
        *
        */
        function set_domain_audio($domain, $module = ''){
            if($this->check_domain($domain)){
                $_module OR $_module = CI::$APP->router->fetch_module();
                $this->set_domain_target($domain, $_module, 'audio');
            }
            return false;
        }
        
        /**
        *   ��������������� ������ ���������� ��� ����� ������ ��� ������� ������
        *
        */
        function set_domain_video($domain, $module = ''){
            if($this->check_domain($domain)){
                $_module OR $_module = CI::$APP->router->fetch_module();
                $this->set_domain_target($domain, $_module, 'video');
            }
            return false;
        }
        
        /**
        *  ��������������� ������ ���������� ��� ������� ������
        *
        */
        private function set_domain_target($domain, $module = 0, $type = 0){
            if(!empty($domain)){
                if(!strpos('http://', $domain)){
                    $domain = 'http://'.$domain;
                }
                if(strpos('.',$domain)){
                    if(!empty($module)){
                        $this->domain_target[$module][$type] = $domain;
                    }
                }
            }
            $this->domain_target[$module][$type] = false;
        }
        
        /**
        *  ��������� ����� ������ ����������
        *
        */
        function get_domain_target($module = 0, $type = 0){
            if(!empty($this->domain_target[$module][$type])){
                return $this->domain_target[$module][$type];
            }
            return false;
        }
        /**
        *  ��������� �������� ��� ����� ������������ ������ � ����
        *
        */
        function get_timeout($module = 0, $type = 0){
            if(!empty($this->timeout_target[$module][$type])){
                return $this->timeout_target[$module][$type];
            }else{
                if(!empty($this->timeout_default)) return $this->timeout_default;
            }
            return false;
        }
        
        /**
         *
         * @param type $dir
         * @param type $return
         * @return string 
         */
        function out_style($module = '', $return = false){
            if($module != '' && isset($this->_config_paths[$module])){
                $assets[] = $this->_config_paths[$module];
            }else{
                $assets = $this->_config_paths;
            }

            $fp = '';
            foreach($assets as $modules=>$types){
                if(isset($types['style']) && is_array($types['style'])){
                    foreach($types['style'] as $items){
                        if(is_array($items)){
                            foreach($items as $item){
                                if($item['type_content'] == 'file' || $item['type_content'] == 'url' || $item['type_content'] == 'donor'){
                                    if($item['place_source'] == 'local'){
                                        $fp .= "<link rel=\"stylesheet\" href=\"";
                                        if(isset($item['domain_target'])){
                                            $fp .= "http://".$item['domain_target'].$item['path_local_target'].$item['file_name'];
                                        }else{
                                            $fp .= '/'.$item['path_local_target'].$item['file_name'];
                                        }
                                        $fp .= "\" type=\"text/css\" />\r\n";
                                    }elseif($item['place_source'] == 'head'){
                                        $fp .= "<style type=\"text/css\">\r\n";
                                        $fp .= file_get_contents($this->path_root_target.$item['path_local_target'].$item['file_name']);
                                        $fp .= "\r\n</style>\r\n";
                                    }
                                }elseif($item['type_content'] == 'style'){
                                    $fp .= "<style type=\"text/css\">\r\n";
                                    $fp .= $item['file_source'];
                                    $fp .= "\r\n</style>\r\n";
                                }
                            }
                        }
                    }
                }
            }

            if($return === true){
                return $fp;
            }else{
                echo $fp;
            }
            
        }
        
        /**
         *
         * @param type $dir
         * @param type $return
         * @return string 
         */
        function out_script($module = '', $return = false){
            if($module != '' && isset($this->_config_paths[$module])){
                $assets[] = $this->_config_paths[$module];
            }else{
                $assets = $this->_config_paths;
            }

            $fp = '';
            foreach($assets as $modules=>$types){
                if(isset($types['script']) && is_array($types['script'])){
                    foreach($types['script'] as $items){
                        if(is_array($items)){
                            foreach($items as $item){
                                if($item['type_content'] == 'file' || $item['type_content'] == 'url' || $item['type_content'] == 'donor'){
                                    if($item['place_source'] == 'local'){
                                        $fp .= "<script src=\"";
                                        if(isset($item['domain_target'])){
                                            $fp .= "http://".$item['domain_target'].$item['path_local_target'].$item['file_name'];
                                        }else{
                                            $fp .= '/'.$item['path_local_target'].$item['file_name'];
                                        }
                                        $fp .= "\" type=\"text/javascript\"></script>\r\n";
                                    }elseif($item['place_source'] == 'head'){
                                        $fp .= "<script type=\"text/javascript\">\r\n";
                                        $fp .= file_get_contents($this->path_root_target.$item['path_local_target'].$item['file_name']);
                                        $fp .= "\r\n</script>\r\n";
                                    }
                                }elseif($item['type_content'] == 'javascript' || $item['type_content'] == 'jQuery'){
                                    $fp .= "<script type=\"text/javascript\">\r\n";
                                    $fp .= $item['file_source'];
                                    $fp .= "\r\n</script>\r\n";
                                }
                            }
                        }
                    }
                }
            }
            if($return === true){
                return $fp;
            }else{
                echo $fp;
            }
            
        }
        
        /**
         *
         * @param type $dir
         * @param type $return
         * @return string 
         */
        function out_img($dir = '', $return = false){
            
        }
        
        /**
        *  �������� ������������ ����� ������
        */
        function check_domain($string){
            $pattern = '/^(http|https|ftp)://([0-9a-z]([0-9a-z\-])*[0-9a-z]\.)+[a-z]{2,4}$/i';
            if (strlen($string < 64) && preg_match($pattern, $string)){
              return true;
            }
            return false;
        }
        /**
        *  �������� ������������ ����� url
        */
        function check_url($string){
            $pattern = "%^(http|https|ftp)://([A-Z0-9][A-Z0-9_-]*(?:.[A-Z0-9][A-Z0-9_-]*)+):?(d+)?/?%i";
            if(preg_match($pattern, $string)){
                return true;
            }
            return false;
        }
        /**
        *  �������� ������������ ����� ����� � �����
        */
        function check_path_file($string){
            $pattern = "%^[/]?[A-z0-9]+([A-z_0-9-/\.]*)$%";
            if(preg_match($pattern, $string)){
                return true;
            }
            return false;
        }
        /**
        *  �������� ������������ ����� �����
        */
        function check_file($string){
            $pattern = "%^[A-z0-9]+([A-z_0-9-\.]*)$%";
            if(preg_match($pattern, $string)){
                return true;
            }
            return false;
        }
        /**
        *  �������� ������� ���� jQuery
        */
        function check_is_jquery($string){
            $pattern = "%[\$][A-z0-9_]?\([^)]*\)+%is";
            if(preg_match($pattern, $string)){
                return true;
            }
            return false;
        }
        /**
        *  �������� ������� ���� javascript
        */
        function check_is_javascript($string){
            $pattern = "%(function[A-z0-9_ ]?\([^)]\))+%is";
            if(preg_match($pattern, $string)){
                return true;
            }
            return false;
        }
        /**
        *  �������� ������� ����� ������� ������
        */
        function check_is_style($string){
            $pattern = "|^([^\{]+\{[^\}]+\})+|is";
            if(preg_match($pattern, $string)){
                return true;
            }
            return false;
        }
        
        /**
    *  ����� ��������� ������
    *  @param string $class - �����
    *  @param string $method - �����
    *  @param string $level  - ������� ������
    *  @param string $message - ���������
    */
        function set_error($class, $method, $type, $message ){
            
        }
        
        	/**
	*  ��������� ����� �������
	*  @param string $message
	*/
        function set_log($message){
            $this->logs[] = $message;
        }
}







