<?php (defined('BASEPATH')) OR exit('No direct script access allowed');


class Assets_style extends CI_Driver
{

    private $tags_ext_file = array(
    					'css' => array(
    								'tag_open' => "<style type=\"text/css\">\r\n",
    								'tag_close' => "\r\n</style>\r\n",
    								'tag_file' => "<link href=\"{file}\" type=text/css rel=stylesheet />\r\n",
    								'ext_file' => array('css'),
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
         *  Загрузка файла стилей
         * @param type $file
         * @param type $module
         * @return type
         */
         public function load($file , $_module = '', $config = array()) {
             if(empty($file)) return false;
             $config['out_file'] = true;

             if($this->_load_file($file, $_module, 'style', $config)){
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
			$res = $this->out_style($file, $module, $config, true);

           	if($return === true){
                return $res;
            }else{
                echo $res;
            }

        }
        /**
         * Вывод стилей
         * @param string - $file - имя файла
         * @param string - $module - имя модуля
         * @param string - $config - параметры для вывода
         * @param boolean - $return - возвращать результат или выводить в браузер
         * @return string
         */
        function out_style($file = false, $module = '', $config = array(), $return = false){
			$config['tags_open'] = $this->tags_ext_file['css']['tag_open'];
            $config['tags_close'] = $this->tags_ext_file['css']['tag_close'];
            $config['tags_file'] = $this->tags_ext_file['css']['tag_file'];
            $config['ext_file_out'] = $this->tags_ext_file['css']['ext_file'];

            $arr = $this->_out($file, $module, 'style', $config, $return);

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
            if($this->_package($dir, $file_out, $module, 'style', $config)){
            	return true;
            }
            return false;
            //$files = $this->get_dir_files($dir, $module, 'script', $config);
        }
}
