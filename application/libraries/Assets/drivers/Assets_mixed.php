<?php (defined('BASEPATH')) OR exit('No direct script access allowed');


class Assets_mixed extends CI_Driver
{
    private $tags_ext_file = array(
    					'css' => array(
    								'tag_open' => "<style type=\"text/css\">\r\n",
    								'tag_close' => "\r\n</style>\r\n",
    								'tag_file' => "<link href=\"{file}\" type=text/css rel=stylesheet />\r\n",
    								'ext_file' => array('css'),
    					),
    					'js' => array(
    								'tag_open' => "<script type=\"text/javascript\">\r\n",
    								'tag_close' => "\r\n</script>\r\n",
    								'tag_file' => "<script src=\"{file}\" type=\"text/javascript\"></script>\r\n",
    								'ext_file' => array('js'),
    					),
    					'img' => array(
    								//открывающий тег
    								'tag_open' => '',
    								//закрывающий тег
    								'tag_close' => '',
    								//файл внутри тега
    								'tag_file' => '<img src="{file}" {attr} />\r\n',
    								//расширения файлов доступные для вывода с этими параметрами
    								'ext_file' => array('jpg', 'gif', 'jpeg', 'png'),
    					),
    );
        /**
         *  Загрузка файлов
         * @param type $file
         * @param type $module
         * @return type
         */
    public function load($file , $_module = '', $config = array()) {
        if(empty($file)) return false;
             $config['out_file'] = true;
             if($this->_load_file($file, $_module, 'mixed', $config)){
             	return true;
             }
             return false;
	}

        /**
         *
         * @param type $dir
         * @param type $return
         * @return string
         */
        function out($file = false, $module = '', $config = array(), $return = false){
            $result = '';
            $result .= $this->out_style($file, $module, $config, true);
            $result .= $this->out_script($file, $module, $config, true);

            if($return === true){
                return $result;
            }else{
                echo $result;
            }

        }

        /**
         *
         * @param type $dir
         * @param type $return
         * @return string
         */
        function out_script($file = false, $module = '', $config = array(), $return = false){

            $config['tags_open'] = $this->tags_ext_file['js']['tag_open'];
            $config['tags_close'] = $this->tags_ext_file['js']['tag_close'];
            $config['tags_file'] = $this->tags_ext_file['js']['tag_file'];
            $config['ext_file_out'] = $this->tags_ext_file['js']['ext_file'];

            $arr = $this->_out($file, $module, 'mixed', $config, $return);

            if($return === true){
                return $arr;
            }else{
                echo $arr;
            }

        }

        /**
         *
         * @param type $dir
         * @param type $return
         * @return string
         */
        function out_style($file = false, $module = '', $config = array(), $return = false){
			$config['tags_open'] = $this->tags_ext_file['css']['tag_open'];
            $config['tags_close'] = $this->tags_ext_file['css']['tag_close'];
            $config['tags_file'] = $this->tags_ext_file['css']['tag_file'];
            $config['ext_file_out'] = $this->tags_ext_file['css']['ext_file'];

            $arr = $this->_out($file, $module, 'mixed', $config, $return);

            if($return === true){
                return $arr;
            }else{
                echo $arr;
            }

        }

        /*
         *
         */
        function file($file = false, $module = '', $config = array(), $return = true){
            if($return) $out_file = false; else $out_file = true;
            $config['out_file'] = $out_file;
            $result = $this->_file($file, $module, 'style', $config, $return);
            if($result){
                if($return === true){
                    return $result;
                }else{
                    echo $result;
                }
            }
            return false;
        }

        /**
         * Пакетная обработка файлов
         * @param type $dir
         * @param type $file_out
         * @param type $module
         * @param type $config
         */
        function package($dir, $file_out = false, $module = '', $config = array()){
            if($this->_package($dir, $file_out, $module, 'mixed', $config)){
            	return true;
            }
            return false;
        }
}
