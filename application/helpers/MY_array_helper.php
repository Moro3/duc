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