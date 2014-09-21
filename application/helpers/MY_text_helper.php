<?php  if ( ! defined('BASEPATH')) exit('No direct script access allowed');

//Расширение помощника text_helper
// ------------------------------------------------------------------------

// преобразования первого символа строки в верхний регистр на русском в кодировке UTF-8
if (!function_exists('mb_ucfirst') && function_exists('mb_substr')) {
     function mb_ucfirst($string) {
          $string = mb_ereg_replace("^[\ ]+","", $string);
          $string = mb_strtoupper(mb_substr($string, 0, 1, "UTF-8"), "UTF-8").mb_substr($string, 1, mb_strlen($string), "UTF-8" );
          return $string;
     }
}