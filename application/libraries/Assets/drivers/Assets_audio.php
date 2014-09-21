<?php (defined('BASEPATH')) OR exit('No direct script access allowed');


class Assets_audio extends CI_Driver
{
    private $tags_open = "";

    private $tags_close = "";

    private $tags_file = "";


        /**
         *  Загрузка звукового файла
         * @param type $file
         * @param type $module
         * @return type
         */
         public function load($file , $_module = '', $config = array()) {
             if(empty($file)) return false;
             if($this->_load_file($file, $_module, 'audio', $config)){
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
            $config['tags_open'] = $this->tags_open;
            $config['tags_close'] = $this->tags_close;
            $config['tags_file'] = $this->tags_file;

            $arr = $this->_out($file, $module, 'audio', $config, $return);

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
            $result = $this->_file($file, $module, 'audio', $config);

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
