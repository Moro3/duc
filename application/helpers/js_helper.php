<?php  if ( ! defined('BASEPATH')) exit('No direct script access allowed');

//���������� ��������� js_helper
// ------------------------------------------------------------------------

// ------------------------------------------------------------------------

/**
 *  ������ ������ ������� � ������������ HTML ��� jscript
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
     * �������������� ����� ��� JavaScript
     *
     * ����� ���������� ������, ������� ������ ���� ��������� � JavaScript.
     * ��� �������� ����� ���������� �� ������� "\n".
     *
     * <code>
     * <?$str = "Hello\nworld"?>
     * <script>
     *     alert('<?=js($str)?>');
     * </script>
     * <a href="javascript:;" onclick="<?=js($str, true)?>">Click me!</a>
     * </code>
     *
     * ���� ������ �������������� ��� ������� � JavaScript ������ ���� (��������,
     * � ������ onclick) �� ���������� �������� ���� $isInline. � ���� ������ � ������
     * ����� �������� ���������, ����� �������� ����������� HTML �� �� �����������.
     *
     * @param  string  $str       �������� ������
     * @param  bool    $isInline  ������������ �� ��� ���������� ������.
     * @return string             ���������� ������������ ������
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