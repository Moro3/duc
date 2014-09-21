<?php  if ( ! defined('BASEPATH')) exit('No direct script access allowed');

$scheme_gp_modules['-mod-'] = Array('name'        => 'modules',
                                    'alias'       => 'mod',
                                    'modules'     => Array( //'-menu-',
                                                            '-menus-',
                                                            //'-site-',
                                                            '-pages-',
                                                            //'-language-',
                                                            //'-katalog-',
                                                            //'-auth-',
                                                            //'-feedback-',
                                                            //'-news-',
                                                            '-duc-',
                                                            '-adverts-',
                                                            '-mods-',
                                                            '-snippets-',
                                                           ),
                                    'short_desctription'=> 'Модули',
                                    'desctription'=> 'Административные модули',
                                    'img'         => '',
                                    );
$scheme_gp_modules['-system-'] = Array('name'        => 'Конфигурация',
                                    'modules'     => Array(
                                                           ),
                                    'desctription'=> 'Конфигурация системы',
                                    'img'         => '',
                                    );
$scheme_gp_modules['-setting-'] = Array('name'        => 'Настройка сайта',
                                    'modules'     => Array(
                                                           ),
                                    'desctription'=> 'Настройка параметров сайта',
                                    'img'         => '',
                                    );





