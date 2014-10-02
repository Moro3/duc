<?php  if ( ! defined('BASEPATH')) exit('No direct script access allowed');

/**
 * CodeIgniter
 *
 * helper для дополнительной обработки массивов
 *
 */

/**
     *  Возвращает массив с параметрами из массива 1 которые присутствуют в массиве 2
     *  Если параметр является числовым массивом, возвращается весь список
     * @param array $arr1
     * @param array $arr2
     * @return array
     */
if ( ! function_exists('array_intersect_keys_recursive'))
{
    function array_intersect_keys_recursive($arr1, $arr2) {
        if(is_array($arr2)){
            if(is_array($arr1)){
                if(is_array_numeric($arr1) && is_array_numeric($arr2)){
                    $arr = $arr2;
                }else{
                    foreach($arr1 as $key=>$value){
                          if(isset($arr2[$key])){
                              $sub_arr = array_intersect_keys_recursive($value, $arr2[$key]);
                              //if($sub_arr !== false){
                                    $arr[$key] = $sub_arr;
                              //}
                          }
                    }
                }
            }else{
              $arr = $arr2;
            }
        }else{
            if(isset($arr1)){
                $arr = $arr2;
            }
        }
        if(isset($arr)) return $arr;
        return false;
    }
}

/**
     *  Возвращает массив с параметрами из массива 1 которые присутствуют в массиве 2
     *  значение лючей имеет значение и также сравнивается
     * @param array $arr1
     * @param array $arr2
     * @return array
     */
if ( ! function_exists('array_intersect_assoc_recursive'))
{
	function array_intersect_assoc_recursive(&$arr1, &$arr2) {
        if (!is_array($arr1) || !is_array($arr2)) {
            return $arr1 == $arr2; // or === for strict type
        }
        $commonkeys = array_intersect(array_keys($arr1), array_keys($arr2));
        $ret = array();
        foreach ($commonkeys as $key) {
            $ret[$key] =& array_intersect_assoc_recursive($arr1[$key], $arr2[$key]);
        }
        return $ret;
    }
 }

 /**
    *  Проверяет являются ли все ключи массива числами
    *  @param array - массив
    *
    */
if ( ! function_exists('is_array_numeric'))
{
	function is_array_numeric($array){
		if(is_array($array)){
			foreach($array as $key=>$value){
				if( ! is_numeric($key)) return false;
			}
			return true;
		}
		return false;
	}
}

/**
     *  Функция принимает несколько массивов и объеденяет их по принципу array_merge_recursive,
     *  но с заменой старых значений,
     *  если ключами массива является числа, то значение будет добавлено к имеющимся
     *  если одинаковые числовые ключи нужно заменять новыми используйте array_merge_assoc_recursive_distinct
     * @return array
     */
if ( ! function_exists('array_merge_recursive_distinct'))
{
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
                      $base[$key] = array_merge_recursive_distinct($base[$key], $append[$key]);
                  } else if (is_numeric($key)) {
                      if (!in_array($value, $base)) $base[] = $value;
                      //$base[$key] = $value;
                  } else {
                      $base[$key] = $value;
                  }
              }
            }
        }
        return $base;
    }
}

/**
     *  Функция принимает несколько массивов и объеденяет их по принципу array_merge_recursive,
     *  но с заменой старых значений,
     *  в том числе заменяются значения ключей массива являющиеся числами,
     *  что бы имеь возможность числовые значения прибавлять к старым используйте array_merge_recursive_distinct
     * @return array
     */
if ( ! function_exists('array_merge_assoc_recursive_distinct'))
{
    function array_merge_assoc_recursive_distinct(){
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
                      $base[$key] = array_merge_assoc_recursive_distinct($base[$key], $append[$key]);
                  } else if (is_numeric($key)) {
                      if (!in_array($value, $base)) //$base[] = $value;
                      $base[$key] = $value;
                  } else {
                      $base[$key] = $value;
                  }
              }
            }
        }
        return $base;
    }
}

if ( ! function_exists('array_diff_assoc_recursive'))
{
	function array_diff_assoc_recursive($array1, $array2)
    {
        foreach($array1 as $key => $value)
        {
            if(is_array($value))
            {
                  if(!isset($array2[$key]))
                  {
                      $difference[$key] = $value;
                  }
                  elseif(!is_array($array2[$key]))
                  {
                      $difference[$key] = $value;
                  }
                  else
                  {
                      $new_diff = $this->array_diff_assoc_recursive($value, $array2[$key]);
                      if($new_diff != FALSE)
                      {
                            $difference[$key] = $new_diff;
                      }
                  }
              }
              elseif(!isset($array2[$key]) || $array2[$key] != $value)
              {
                  $difference[$key] = $value;
              }
        }
        return !isset($difference) ? 0 : $difference;
    }
}

/*****************************************
*  Работа с многомерными массивами
******************************************
*/

/**
* Функция проверяет наличие данного значения в многомерном массиве
*
* @param array $array - многомерный массив (вложенные массивы могут представлять объекты)
* @param string $needle - значение ищущего параметра
* @param string $index - ключ по которому будет искаться значение (если не задан, то ищется по всем ключам)
* @param numeric $level - уровень поиска значения в массиве (если массив более 2-ух уровней, то есть возможность указать уровень на котором искать данное значение)
*                         по умолчанию значение ищется в двумерном массиве = значению 1, нумерация начинается с 0
* @param array $level_path - путь для уровней (если вы ищете значение в более чем 2 уровня, то вам может понадобиться уточнить более точный путь для поиска на предыдущих уровнях)
*                            т.е. если вам нужно найти значение id из 3 уровня в массиве $array[]['pages']['id']
*                            параметром level_path будет array( 2 => 'pages')
* @result boolean - true если значение найдено, false - если не найдено
*/
if ( ! function_exists('in_array_multi'))
{
    function in_array_multi($array, $needle, $index = false, $level = 1, $level_path = false) {
   		$array_iterator = new RecursiveIteratorIterator(new RecursiveArrayIterator($array), RecursiveIteratorIterator::SELF_FIRST);
		foreach($array_iterator as $key=>$value){
		    $current_level = $array_iterator->getDepth();
		    if($current_level > $level) continue;
		    if(isset($level_path[$current_level])){
		    	if($level_path[$current_level] != $key) continue;
		    }
		    if($current_level == ($level-1)){
		    	if(is_array($value)){
		    		if($index !== false){		    			if(isset($value[$index]) && $value[$index] == $needle){		    				return true;
		    			}
		    		}else{		    			if(in_array($needle, $value)) return true;
		    		}
		    	}elseif(is_object($value)){
		    		if($index !== false){
		    			if(isset($value->$index) && $value->$index == $needle){
		    				return true;
		    			}
		    		}else{
		    			if(in_array($needle, (array) $value)) return true;
		    		}
		    	}
		    }
        }
        return false;
    }
}

/**
* Функция ищет значение параметра и возвращает часть массива из многомерного массива
*
* @param array $array - многомерный массив (вложенные массивы могут представлять объекты)
* @param string $needle - значение ищущего параметра
* @param string $index - ключ по которому будет искаться значение
* @param numeric $level - уровень поиска значения в массиве (если массив более 2-ух уровней, то есть возможность указать уровень на котором искать данное значение)
*                         по умолчанию значение ищется в двумерном массиве = значению 1, нумерация начинается с 0
* @param array $level_path - путь для уровней (если вы ищете значение в более чем 2 уровня, то вам может понадобиться уточнить более точный путь для поиска на предыдущих уровнях)
*                            т.е. если вам нужно найти значение id из 3 уровня в массиве $array[]['pages']['id']
*                            параметром level_path будет array( 2 => 'pages')
* @return array - массив с параметрами соответсвующих поиску
*/
if ( ! function_exists('array_multi_params'))
{
    function array_multi_params($array, $needle, $index, $level = 1, $level_path = false) {
   		//if( ! is_numeric($level)) return false;
   		//$level--;
        //echo 'Уровень: '.$level."<br>";
   		$array_iterator = new RecursiveIteratorIterator(new RecursiveArrayIterator($array), RecursiveIteratorIterator::SELF_FIRST);
		foreach($array_iterator as $key=>$value){
		    $current_level = $array_iterator->getDepth();
		    if($current_level > $level) continue;
		    if(isset($level_path[$current_level])){
		    	if($level_path[$current_level] != $key) continue;
		    }
		    if($current_level == ($level-1)){
		    	if(is_array($value) && isset($value[$index]) && $value[$index] == $needle){
		    		return $value;
		    	}elseif(is_object($value) && isset($value->$index) && $value->$index == $needle){		    		return $value;
		    	}
		    }
		    //echo $key.' — '.$value."\r\n<br />";
		    //echo $array_iterator->getDepth().'<br />';
        }
    }
}

/**
* Функция возвращает индексный массив из многомерного массива
*
*
* @param array $array - многомерный массив (вложенные массивы могут представлять объекты)
* @param string $index - ключ по которому будет возвращаться значение
* @param numeric $level - уровень поиска значения в массиве (если массив более 2-ух уровней, то есть возможность указать уровень на котором искать данное значение)
*                         по умолчанию значение ищется в двумерном массиве = значению 1, нумерация начинается с 0
* @param array $level_path - путь для уровней (если вы ищете значение в более чем 2 уровня, то вам может понадобиться уточнить более точный путь для поиска на предыдущих уровнях)
*                            т.е. если вам нужно найти значение id из 3 уровня в массиве $array[]['pages']['id']
*                            параметром level_path будет array( 2 => 'pages')
* @return array - индексный массив со значениями соответсвующих поиску
*/
if ( ! function_exists('array_multi_values'))
{
    function array_multi_values($array, $index, $level = 1, $level_path = false) {
        $array_iterator = new RecursiveIteratorIterator(new RecursiveArrayIterator($array), RecursiveIteratorIterator::SELF_FIRST);
		foreach($array_iterator as $key=>$value){
		    $current_level = $array_iterator->getDepth();
		    if($current_level > $level) continue;
		    if(isset($level_path[$current_level])){
		    	if($level_path[$current_level] != $key) continue;
		    }
		    if($current_level == ($level-1)){
		    	if(is_array($value) && isset($value[$index])){
		    		$arr[] = $value[$index];
		    	}elseif(is_object($value) && isset($value->$index)){
		    		$arr[] = $value->$index;
		    	}
		    }
        }
        if(isset($arr)) return $arr;
        return false;
    }
}
