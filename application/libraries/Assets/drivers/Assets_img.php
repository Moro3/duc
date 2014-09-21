<?php (defined('BASEPATH')) OR exit('No direct script access allowed');


class Assets_img extends CI_Driver
{
   	// параметры для вывода файлов
    private $tags_ext_file = array(
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
             if($this->_load_file($file, $_module, 'img', $config)){
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
            $config['tags_open'] = $this->tags_ext_file['img']['tag_open'];
            $config['tags_close'] = $this->tags_ext_file['img']['tag_close'];
            $config['tags_file'] = $this->tags_ext_file['img']['tag_file'];
            $config['ext_file_out'] = $this->tags_ext_file['img']['ext_file'];

            $arr = $this->_out($file, $module, 'img', $config, $return);

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
            $result = $this->_file($file, $module, 'img', $config);

            if($result){
                if($return === true){
                    return $result;
                }else{
                    echo $result;
                }
            }
            return false;
        }
}
