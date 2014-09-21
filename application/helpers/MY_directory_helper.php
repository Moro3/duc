<?php  if ( ! defined('BASEPATH')) exit('No direct script access allowed');

// ------------------------------------------------------------------------

/**
 * Создает директории по полному пути
 *
 *
 * @access	public
 * @param	string || array	путь директории относительно корня сайта
 * @param	numeric	режим записи разрешений для директорий (числовые разрешения 4-х символьные) по умолчанию 0755
 * @return	boolean
 */
if ( ! function_exists('create_dir'))
{
	function create_dir($source_dir, $mode = DIR_READ_MODE)
	{
		if( ! is_array($source_dir)) $source_dir = array($source_dir);
		foreach($source_dir as $dir){
			$dir = str_replace('/',DIRECTORY_SEPARATOR,$dir);
			$dir = str_replace('\\',DIRECTORY_SEPARATOR,$dir);
			if(!empty($dir)){
				$dir_arr = explode(DIRECTORY_SEPARATOR, trim($dir, DIRECTORY_SEPARATOR));
			}else{
				return false;
			}

			if(is_array($dir_arr)){
				$path_root = rtrim($_SERVER['DOCUMENT_ROOT'],DIRECTORY_SEPARATOR).DIRECTORY_SEPARATOR;
	            $ddir = '';
				foreach($dir_arr as $item){
	            	$ddir .= $item.DIRECTORY_SEPARATOR;
	            	if( ! is_dir($path_root.$ddir)){
	                	if( ! mkdir($path_root.$ddir, $mode)) {
	                		log_message('error', 'Не удалось создать директорию '.$path_root.$ddir);
	                		return false;
	                	}
	            	}
				}
			}
		}

		return true;
	}
}


/* End of file directory_helper.php */
/* Location: ./system/helpers/directory_helper.php */