<?php  if ( ! defined('BASEPATH')) exit('No direct script access allowed');

//Расширение помощника js_helper
// ------------------------------------------------------------------------

// ------------------------------------------------------------------------

/**
 *  Замена знаков скрипта и комментарием HTML для jscript
 */ 
if ( ! function_exists('js_string'))
{
	function js_string($string) {
        return strtr(json_encode( (string) $string), array(
            '</'  => '<\\/',
            '-->' => '-\\->',
            ']]>' => ']\\]>',
        ));
    }
}

// ------------------------------------------------------------------------
/**
     * Обезопасивание строк для JavaScript
     *
     * Метод экранирует данные, которые должны быть вставлены в JavaScript.
     * Все переносы строк заменяются на литерал "\n".
     *
     * <code>
     * <?$str = "Hello\nworld"?>
     * <script>
     *     alert('<?=js($str)?>');
     * </script>
     * <a href="javascript:;" onclick="<?=js($str, true)?>">Click me!</a>
     * </code>
     *
     * Если строка обрабатывается для вставки в JavaScript внутрь тега (например,
     * в методе onclick) то необходимо включить флаг $isInline. В этом случае в строке
     * после основной обработки, будут заменены спецсимволы HTML на их эквиваленты.
     *
     * @param  string  $str       Исходная строка
     * @param  bool    $isInline  Обрабатывать ли как встроенную строку.
     * @return string             Возвращает обработанную строку
     * @access public
     */
if ( ! function_exists('js_escape'))
{
    function js_escape( $str, $isInline = false ) {
 
        if ( $isInline ) {
            $str = htmlspecialchars($str);
        }
 
        return preg_replace('/\r?\n/m', '\\n', str_replace('/', '\/', addslashes($str)));
 
    }
}


if ( ! function_exists('array_php_in_js'))
{
	function array_php_in_js($arr_php, $script = false){		
		$js_obj = json_encode($arr_php);
		if($script){
			return "<script language='javascript'>var obj=$js_obj;</script>";
		}else{
			return "var obj=$js_obj;";
		}		
	}	
}