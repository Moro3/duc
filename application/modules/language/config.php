<?php
function get_setting_language(){
    $menu_setting['path']['dir_root'] = rtrim($_SERVER['DOCUMENT_ROOT'],"\/");
    $menu_setting['path']['img'] = "tpl/default/img/language";
    $menu_setting['path']['img_original'] = "tpl/default/img/language/original";
    $menu_setting['path']['thumb']['small'] = "tpl/default/img/language/small";
    $menu_setting['path']['thumb']['mini'] = "tpl/default/img/language/mini";
    $menu_setting['path']['thumb']['micro'] = "tpl/default/img/language/micro";

    $menu_setting['img']['allow'] = "jpg|png|gif";
    $menu_setting['img']['max_size'] = 5000000;
    $menu_setting['img']['size']['img_original'] = '320x240';
    $menu_setting['img']['size']['small'] = '120x100';
    $menu_setting['img']['size']['mini'] = '60x40';
    $menu_setting['img']['size']['micro'] = '20x13';

    $menu_setting['index_request'] = array(
                                     'index1' => array('name' => 'm',
                                                           'form_value' => 'меню',
                                                          'uri_mode' => 'get',
                                                          ),
                                     'action' => array('name' => 'act',
                                                           'form_value' => 'действие',
                                                          'uri_mode' => 'get',
                                                          ),
                                     'id' => array('name' => 'go',
                                                           'form_value' => 'ID',
                                                           'uri_mode' => 'get',
                                                          ),

                                    );

    $menu_setting['get']['menu']['lang'] = array('link' => 'lang', 'name' => 'Языки');
    $menu_setting['get']['menu']['setting'] = array('link' => 'setting', 'name' => 'Настройки');
    $menu_setting['get']['sub_menu']['lang']['lang'] = array('link' => 'lang', 'name' => 'все языки');
    $menu_setting['get']['sub_menu']['lang']['add_lang'] = array('link' => 'add_lang', 'name' => 'добавить язык');
    $menu_setting['get']['sub_menu']['setting']['user'] = array('link' => 'user', 'name' => 'пользовательские');
    $menu_setting['get']['sub_menu']['setting']['admin'] = array('link' => 'admin', 'name' => 'административные');

    return $menu_setting;
}





