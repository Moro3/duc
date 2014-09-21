<?php (defined('BASEPATH')) OR exit('No direct script access allowed');


class Assets_place
{
        // Основной объект
        private $assets;
        /**
         *  Конструктор принимает основной объект для взаимодействий с ним
         * @param type $obj
         */
        function __construct($obj) {
            if(is_object($obj)){
                $this->assets = $obj;
            }else{
                exit('Основной объект библиотеки "ASSETS" не передан в класс "source"');
            }
        }

        /**
        *  Возвращает корректное место назначения файла
        *  @param string
        *
        */
        function get_place(){
            $data = $this->assets->data_collect->get();
            if(isset($data['place_content'])){
                  if($data['place_content'] == 'head'){
                      if($data['type'] == 'script' || $data['type'] == 'style'){
                          return 'head';
                      }
                  }
                  if(isset($data['type_string']) && $data['type_string'] == 'content'){
                      return 'head';
                  }
            }
            return 'local';
        }
}
