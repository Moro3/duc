<?php
/*
This script is written for use with PHPMailer-ML when
using the SPAW Editor v.2. It is designed to add a menu option for
variable substitution within an email message body. To use it
you will require the file "inc.campaigns.php" for PHPMailer-ML
version 1.8.1a.
Author: Andy Prevost
License: GPL (see docs/license.txt)
*/
if(isset($this)){
    $site = $this->config->item('site');
    if(isset($site)){
      $array_list = array(
          "{cfg name='num_tel'}" => 'Номер телефона: '.$site['num_tel'],
          "{cfg name='num_tel2'}" => 'Номер телефона 2: '.$site['num_tel2'],
          "{cfg name='num_fax'}" => 'Номер факса: '.$site['num_fax'],
          "{cfg name='address'}" => 'Адрес: '.$site['address'],
          "{cfg name='postal_code'}" => 'Почтовый индекс: '.$site['postal_code'],
          "{cfg name='city'}" => 'Город: '.$site['city'],
          "{cfg name='email'}" => 'E-mail: '.$site['email'],
          "{cfg name='email2'}" => 'E-mail2: '.$site['email2'],
          "{cfg name='www'}" => 'Адрес сайта: '.$site['www'],
          "{cfg name='name'}" => 'Название сайта: '.$site['name'],
          "{cfg name='short_name'}" => 'Краткое название сайта: '.$site['short_name'],          
           
        );        
    }else{
        $array_list = array();
    } 
}else{
    $array_list = array();
}

SpawConfig::setStaticConfigItem("dropdown_data_varinsert_varinsert", $array_list
  
);


?>
