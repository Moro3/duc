<?php  if ( ! defined('BASEPATH')) exit('No direct script access allowed');

// расширение класса
// 1. добавление параметра change_name
//    позволяет изменить оригинальное имя на ваше
// 2. Переводит кирилицу имен файлов в латинский алфавит
// 3. Изменение регистра символов 'up' - верхний 'low' - нижний '' - по умолчанию не изменный
class MY_Upload extends CI_Upload {

  var $change_name		= '';
  var $rus_convert    = TRUE;
  var $registr    = '';  // изменение регистра '' - не меняется 'up' - всё в верхний регистр 'low' - всё в нижний регистр
  var $overwrite_new_file  = FALSE; // перезапись файла с измененным именем

  function initialize($config = array())
	{
		$defaults = array(
							'max_size'			=> 0,
							'max_width'			=> 0,
							'max_height'		=> 0,
							'max_filename'		=> 0,
							'allowed_types'		=> "",
							'file_temp'			=> "",
							'file_name'			=> "",
							'orig_name'			=> "",
							//-- добавленный код---
							'change_name'			=> "",
							'rus_convert'    => TRUE,
							'registr'    => '',
							'overwrite_new_file'	=> FALSE,
              //-- конец доб. кода ---
							'file_type'			=> "",
							'file_size'			=> "",
							'file_ext'			=> "",
							'upload_path'		=> "",
							'overwrite'			=> FALSE,
							'encrypt_name'		=> FALSE,
							'is_image'			=> FALSE,
							'image_width'		=> '',
							'image_height'		=> '',
							'image_type'		=> '',
							'image_size_str'	=> '',
							'error_msg'			=> array(),
							'mimes'				=> array(),
							'remove_spaces'		=> TRUE,
							'xss_clean'			=> FALSE,
							'temp_prefix'		=> "temp_file_"
						);
    foreach ($defaults as $key => $val)
		{
			if (isset($config[$key]))
			{
				$method = 'set_'.$key;
				if (method_exists($this, $method))
				{
					$this->$method($config[$key]);
				}
				else
				{
					$this->$key = $config[$key];
				}
			}
			else
			{
				$this->$key = $val;
			}
		}
	}

  function set_filename($path, $filename)
	{
		if ($this->encrypt_name == TRUE)
		{
			mt_srand();
			$filename = md5(uniqid(mt_rand())).$this->file_ext;
		}

    //-------- добавленный код -----------
	  if ($this->change_name != '') {
      $filename = str_replace($this->file_ext, '', $filename);
      $filename = $this->change_name;
      if ($this->remove_spaces == TRUE)
		  {
			 $filename = preg_replace("/\s+/", "_", $filename);
		  }
		  $filename = $filename.$this->file_ext;
	  }
    $filename = str_replace($this->file_ext, '', $filename);
    if($this->registr == 'up'){

      $this->UpLow($filename,'up');
    }elseif($this->registr == 'low'){
      $this->UpLow($filename,'low');
    }
    $filename = $filename.$this->file_ext;
    if($this->rus_convert == TRUE){
      $filename = str_replace($this->file_ext, '', $filename);
      $filename = $this->rus2lat($filename);
      $filename = $filename.$this->file_ext;
    }

    //--------конец добавленного кода--------

    if($this->overwrite_new_file == TRUE){
      return $filename;
    }

    if ( ! file_exists($path.$filename))
		{
			return $filename;
		}

		$filename = str_replace($this->file_ext, '', $filename);

		$new_filename = '';
		for ($i = 1; $i < 100; $i++)
		{
			if ( ! file_exists($path.$filename.$i.$this->file_ext))
			{
				$new_filename = $filename.$i.$this->file_ext;
				break;
			}
		}

		if ($new_filename == '')
		{
			$this->set_error('upload_bad_filename');
			return FALSE;
		}
		else
		{
			return $new_filename;
		}
	}

	// Disclaimer: Скрипт принципиально не сохраняет регистр! Кириллица принудительно переводится в нижний, латиница - в верхний.
  // Это связано с необходимостью корректной транслитерации двуязычных названий страниц.
  // Если у кого есть идеи, как обойти эту проблему - welcome! Форма отправки месага на форум - внизу.
  // Регистрация не требуется.
  // Использованная локале-независимая функция UpLow($s)

  function UpLow(&$string,$registr='up'){
    $upper = 'АБВГДЕЁЖЗИЙКЛМНОПРСТУФХЦЧШЩЪЫЬЭЮЯABCDEFGHIJKLMNOPQRSTUVWXYZ';
    //$upper = 'АБВГДЕЁЖЗИЙКЛМНОПРСТУФХЦЧШЩЪЫЬЭЮЯ';
    $lower = 'абвгдеёжзийклмнопрстуфхцчшщъыьэюяabcdefghijklmnopqrstuvwxyz';
    //$lower = 'абвгдеёжзийклмнопрстуфхцчшщъыьэюя';
    if($registr == 'up') $string = strtr($string,$lower,$upper);
    if($registr == 'low') $string = strtr($string,$upper,$lower);

  } //function UpLow(&$string,$registr='up')
  function rus2lat($s) { // Функция обратимой перекодировки кириллицы в транслит.
    // Сначала всё переводим в верхний регистр, причём не с помощью глючной strtoupper
    //$this->UpLow($s);
    //а потом только кириллицу в нижний

    $s=str_replace("ЫА","YHA",$s);
    $s=str_replace("ЫО","YHO",$s);
    $s=str_replace("ЫУ","YHU",$s);
    $s=str_replace("Ё","YO",$s);
    $s=str_replace("Ж","ZH",$s);
    $rus = "АБВГДЕЗИЙКЛМНОПРСТУФХЦ";
    $lat = "ABVGDEZIJKLMNOPRSTUFXC";
    $s = strtr($s, $rus, $lat);
    $s=str_replace("Ч","CH",$s);
    $s=str_replace("Ш","SH",$s);
    $s=str_replace("Щ","SHH",$s);
    $s=str_replace("Ъ","QH",$s);
    $s=str_replace("Ы","Y",$s);
    $s=str_replace("Ь","Q",$s);
    $s=str_replace("Э","EH",$s);
    $s=str_replace("Ю","YU",$s);
    $s=str_replace("Я","YA",$s);

    $s=str_replace("ыа","yha",$s);
    $s=str_replace("ыо","yho",$s);
    $s=str_replace("ыу","yhu",$s);
    $s=str_replace("ё","yo",$s);
    $s=str_replace("ж","zh",$s);
    $rus = "абвгдезийклмнопрстуфхц";
    $lat = "abvgdezijklmnoprstufxc";
    $s = strtr($s, $rus, $lat);
    $s=str_replace("ч","ch",$s);
    $s=str_replace("ш","sh",$s);
    $s=str_replace("щ","shh",$s);
    $s=str_replace("ъ","qh",$s);
    $s=str_replace("ы","y",$s);
    $s=str_replace("ь","q",$s);
    $s=str_replace("э","eh",$s);
    $s=str_replace("ю","yu",$s);
    $s=str_replace("я","ya",$s);

    //$s=str_replace(" ","_",$s); // сохраняем пробел от перехода в %20
    $s=str_replace(",",".h",$s); // сохраняем запятую
    //$s=str_replace('"',"&quot;",$s); // сохраняем кавычки
    //$s=rawurlencode($s); // Разрешённые символы URL - латинские буквы, точка, минус и подчёркивание


    return $s;
  } // function rus2lat($s)


  function lat2rus($s) { // Функция обратной перекодировки транслита в кириллицу.
    //$s=rawurldecode($s);
    $s=str_replace(".h",",",$s);// возвращаем запятую
    //$s=str_replace("_"," ",$s);// возвращаем пробел
    $s=str_replace("yh","Ы",$s);
    $s=str_replace("yu","Ю",$s);
    $s=str_replace("ya","Я",$s);
    $s=str_replace("yo","Ё",$s);
    $s=str_replace("shh","Щ",$s);
    $s=str_replace("eh","Э",$s);
    $s=str_replace("sh","Ш",$s);
    $s=str_replace("ch","Ч",$s);
    $s=str_replace("qh","Ъ",$s);
    $s=str_replace("zh","Ж",$s);
    $lat = "abvgdezijklmnoprstufxcyq";
    $rus = "АБВГДЕЗИЙКЛМНОПРСТУФХЦЫЬ";
    $s = strtr($s, $lat, $rus);
    return $s;
  } // function lat2rus($s)


}

