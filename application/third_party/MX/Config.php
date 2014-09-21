<?php (defined('BASEPATH')) OR exit('No direct script access allowed');

/**
 * Modular Extensions - HMVC
 *
 * Adapted from the CodeIgniter Core Classes
 * @link	http://codeigniter.com
 *
 * Description:
 * This library extends the CodeIgniter CI_Config class
 * and adds features allowing use of modules and the HMVC design pattern.
 *
 * Install this file as application/third_party/MX/Config.php
 *
 * @copyright	Copyright (c) 2011 Wiredesignz
 * @version 	5.4
 *
 * Permission is hereby granted, free of charge, to any person obtaining a copy
 * of this software and associated documentation files (the "Software"), to deal
 * in the Software without restriction, including without limitation the rights
 * to use, copy, modify, merge, publish, distribute, sublicense, and/or sell
 * copies of the Software, and to permit persons to whom the Software is
 * furnished to do so, subject to the following conditions:
 *
 * The above copyright notice and this permission notice shall be included in
 * all copies or substantial portions of the Software.
 *
 * THE SOFTWARE IS PROVIDED "AS IS", WITHOUT WARRANTY OF ANY KIND, EXPRESS OR
 * IMPLIED, INCLUDING BUT NOT LIMITED TO THE WARRANTIES OF MERCHANTABILITY,
 * FITNESS FOR A PARTICULAR PURPOSE AND NONINFRINGEMENT. IN NO EVENT SHALL THE
 * AUTHORS OR COPYRIGHT HOLDERS BE LIABLE FOR ANY CLAIM, DAMAGES OR OTHER
 * LIABILITY, WHETHER IN AN ACTION OF CONTRACT, TORT OR OTHERWISE, ARISING FROM,
 * OUT OF OR IN CONNECTION WITH THE SOFTWARE OR THE USE OR OTHER DEALINGS IN
 * THE SOFTWARE.
 **/
class MX_Config extends CI_Config
{
	public function load($file = 'config', $use_sections = FALSE, $fail_gracefully = FALSE, $_module = '') {
		//if (in_array($file, $this->is_loaded, TRUE)) return $this->item($file);
                //echo "Модуль до определения = $_module<br>";
		if($_module === false){
                    $_module = '';
                }else{
                    $_module OR $_module = CI::$APP->router->fetch_module();
                }
                //echo "Модуль после определения = $_module<br>";
		list($path, $file) = Modules::find($file, $_module, 'config/');

    if ($path === FALSE) {
			parent::load($file, $use_sections, $fail_gracefully);
			return $this->item($file);
		}
     //echo "Путь - $path, файл-$file<br>";
    // добавлено для корректной работы уже загруженных файлов модулей
    $file_path = $path.$file.EXT;
    //echo "Данные $file_path:<br>";
    //print_r($this->item($file_path));
    //if (in_array($file_path, $this->is_loaded, TRUE)) return $this->item($file);
    // конец добавления

    if ($config = Modules::load_file($file, $path, 'config')) {

			/* reference to the config array */
			$current_config =& $this->config;

			if ($use_sections === TRUE)	{

				if (isset($current_config[$file])) {
					//$current_config[$file] = array_merge($current_config[$file], $config);
                                        $current_config[$file] = $this->array_merge_recursive_distinct($current_config[$file], $config);
				} else {
					$current_config[$file] = $config;
				}

			} else {
				$current_config = array_merge($current_config, $config);
			}
                      //$this->is_loaded[] = $file;
                      // исправлено на:
                      $this->is_loaded[] = $file_path;

			unset($config);
			return $this->item($file);
		}
	}


        /**
     *  Функция принимает несколько массивов и объеденяет их по принципу array_merge_recursive,
     *  но с заменой старых значений
     * @return array
     */
    function array_merge_recursive_distinct(){
        $arrays = func_get_args();
        $base = array_shift($arrays);

        if (!is_array($base)) $base = empty($base) ? array() : array($base);

        foreach ($arrays as $append) {
            if (!is_array($append)){
              $base = $append;
              //$append = array($append);
            }else{
              foreach ($append as $key => $value) {
                  if (!array_key_exists($key, $base) and !is_numeric($key)) {
                      $base[$key] = $append[$key];
                      continue;
                  }
                  if (is_array($value) or is_array($base[$key])) {
                      if($this->array_numeric($value)){
                        $base[$key] = $value;
                      }else{
                        $base[$key] = $this->array_merge_recursive_distinct($base[$key], $append[$key]);
                      }
                  } else {
                      $base[$key] = $value;
                  }
              }
            }
        }
        return $base;
    }

    /**
    *  Проверяет являются ли все ключи массива числами
    *
    *
    */
	function array_numeric($array){
		if(is_array($array)){
			foreach($array as $key=>$value){
				if( ! is_numeric($key)) return false;
			}
			return true;
		}
		return false;
	}
}
