<?php

function quark_dump($var, $return = FALSE, $use_xdebug = TRUE)
{
	$use_xdebug = $use_xdebug && function_exists('xdebug_var_dump');
	if ($return)
	{
		if ($use_xdebug && function_exists('ob_start'))
		{
			ob_start();
			xdebug_var_dump($var);
			$out = ob_get_contents();
			ob_end_clean();
		}
		else
			$out = "<pre class='quark-dump'>" . htmlentities(var_export($var, $return), ENT_QUOTES, "utf-8") . "</pre>";
		
		return $out;
	}
	else
	{
		if ($use_xdebug)
			xdebug_var_dump($var);
		else 
			echo "<pre class='quark-dump'>" . htmlentities(var_export($var, TRUE), ENT_QUOTES, "utf-8") . "</pre>";
	}
}

function get_caller_info() {
    $c = '';
    $file = '';
    $func = '';
    $class = '';
    $line = '';
    $trace = debug_backtrace();
    if (isset($trace[2])) {
        $file = $trace[1]['file'];
        $line = $trace[1]['line'];
        $func = $trace[2]['function'];
        if ((substr($func, 0, 7) == 'include') || (substr($func, 0, 7) == 'require')) {
            $func = '';
        }
    } else if (isset($trace[1])) {
        $file = $trace[1]['file'];
        $line = $trace[1]['line'];
        $func = '';
    }
    if (isset($trace[3]['class'])) {
        $class = $trace[3]['class'];
        $func = $trace[3]['function'];
        $file = $trace[2]['file'];
        $line = $trace[2]['line'];
    } else if (isset($trace[2]['class'])) {
        $class = $trace[2]['class'];
        $func = $trace[2]['function'];
        $file = $trace[1]['file'];
        $line = $trace[1]['line'];
    }
    //if ($file != '') $file = basename($file);
    $c = array(
    	'file' => $file,
    	'line' => $line,
    	'class' => $class,
    	'func' => $func
    );
    return($c);
}


/**
*	функция выводит переданные данные через print_r в удобочитаемом виде:
*	- данные оформлены в блоке pre
*	- перед выводом отображается имя файла строка откуда была вызвана функция и её класс и/или метод (если такие имеются)
*	- !!! если функция вызвана в функции или методе, то после вывода данные прекращается работа скрипта через exit (чтобы можно было увидеть если функция вызвана ы методе который что-то возвращает)
*
*/
function dd($str, $print = false) {
    
	$data = get_caller_info();
	$c = "<b>file:</b> ".$data['file'] . "; ";
    $c .= "<b>line:</b> " . $data['line'] . "; ";
    $c .= ($data['class'] != '') ? ":" . $data['class'] . "->" : "";
    $c .= ($data['func'] != '') ? $data['func'] . "(): " : "";

    if($print) debug_print_backtrace();
    echo $c . "<br><pre>";
    if(isset($str)) print_r($str);
    echo "</pre>\n";
    if($data['func'] != '') exit;
}

/**
*   функция выводит переданные данные через var_dump в удобочитаемом виде:
*   - данные оформлены в блоке pre
*   - перед выводом отображается имя файла строка откуда была вызвана функция и её класс и/или метод (если такие имеются)
*   - !!! если функция вызвана в функции или методе, то после вывода данные прекращается работа скрипта через exit (чтобы можно было увидеть если функция вызвана ы методе который что-то возвращает)
*
*/
function ddv($str, $print = false) {
    
    $data = get_caller_info();
    $c = "<b>file:</b> ".$data['file'] . "; ";
    $c .= "<b>line:</b> " . $data['line'] . "; ";
    $c .= ($data['class'] != '') ? ":" . $data['class'] . "->" : "";
    $c .= ($data['func'] != '') ? $data['func'] . "(): " : "";

    if($print) debug_print_backtrace();
    echo $c . "<br><pre>";
    if(isset($str)) var_dump($str);
    echo "</pre>\n";
    if($data['func'] != '') exit;
}