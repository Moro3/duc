<?php  if ( ! defined('BASEPATH')) exit('No direct script access allowed');

//Расширение помощника url_helper
// ------------------------------------------------------------------------

/**
 * Uri replace
 * замена неизвестных значений в строке uri при построении запроса

 * @access	public
 * @param: $uri    - строка запроса
 *         $replace - заменяемое значение
 *                если значений несколько, то массив
 *                порядок следования в массиве должен соответствовать порядку следования индексов в конфигурационном файле
 *         $name    - имя нужного запроса, если не указано, то текущее.
 *                !!! рекомендовано всегда указывать имя запроса,
 *                    т.к. текущий запрос может менятся и несоответствовать правильному
 * @return	string
 */
if ( ! function_exists('uri_replace'))
{
	function uri_replace($uri, $replace, $name = '')
	{
            $CI =& get_instance();
            //return $CI->load->library('control_uri')->guri($name)->uri_replace($uri, $replace, $name);
            return $CI->load->library('uri_generation')->uri_replace($uri, $replace, $name);
	}
}
