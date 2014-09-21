<?php
function get_setting_feedback(){
    $menu_setting['path']['dir_root'] = rtrim($_SERVER['DOCUMENT_ROOT'],"\/");
    $menu_setting['path']['img'] = "tpl/default/img/feedback";
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
                                                          'uri_mode' => 'dot',
                                                          ),
                                     'index2' => array('name' => 'act',
                                                           'form_value' => 'действие',
                                                          'uri_mode' => 'dot',
                                                          ),
                                     'index3' => array('name' => 'go',
                                                           'form_value' => 'ID',
                                                           'uri_mode' => 'dot',
                                                          ),
                                     'index4' => array('name' => 'page',
                                                           'form_value' => 'страница',
                                                           'uri_mode' => 'get',
                                                          ),
                                     'index5' => array('name' => 'order',
                                                           'form_value' => 'сортировка',
                                                           'uri_mode' => 'get',
                                                          ),
                                     'index6' => array('name' => 'direction',
                                                           'form_value' => 'направление сортировки',
                                                           'uri_mode' => 'get',
                                                          ),
                                    );

    $menu_setting['get']['menu']['message'] = array('link' => 'message', 'name' => 'сообщения');
    $menu_setting['get']['menu']['theme'] = array('link' => 'theme', 'name' => 'темы');
    $menu_setting['get']['sub_menu']['message']['list'] = array('link' => 'list', 'name' => 'все сообщения');
    $menu_setting['get']['sub_menu']['theme']['theme'] = array('link' => 'theme', 'name' => 'все темы');
    $menu_setting['get']['sub_menu']['theme']['add_theme'] = array('link' => 'add_theme', 'name' => 'добавить тему');

    return $menu_setting;
}









