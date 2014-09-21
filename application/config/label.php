<?php  if ( ! defined('BASEPATH')) exit('No direct script access allowed');
/*
| -------------------------------------------------------------------------
| LABEL ROUTING
| -------------------------------------------------------------------------
| Этот файл задает метки отчета сегментов для путей в модулях.
| каждая одна такая метка определяет соответствующий сегмент
| если сегмент удовлетворяет условиям, то устанавливается метка по имени ключа в массиве
| если сегмент не найден или он не удовлетворяет условию метка не ставится и соответственно игнорируются все следующие за ней
|
|  номер сегмента соответствует последовательности меткам, т.е
|   например сформироан массив меток $label['lang'], $label['filial'], $label['page']
|   мы обращаемся по url www.site.com/en/moscow/contact
|   сегмент en будет соответствовать метке 'lang'
|   сегмент moscow будет соответствовать метке 'filial'
|   сегмент contact будет соответствовать метке 'page'
|
|   если вдруг сегмент en не будет соответствовать условию правил или не удовлетворяет условию в самом модуле
|   то метка 'lang' и все последующие не ставятся
|   если же не удовлетворяет условиям метка 'filials', то будет установлена только метка 'lang'
|
|   Параметр 'path_default' вычисляет путь меток
|   Введен для того чтобы формировать начальный uri в тех случаях когда метки не установлены
|   Если парметр 'path_default' не установлен или его значение будет false метка и все последующие исключается из пути
|   Например по умолчанию метки возвращают значения 'lang' = ru, 'filial' = vladivostok, 'page' = product
|   при обращении к url www.site.com начальный uri будет равен /ru/vladivostok/product
|   Если например параметр будет отсутствовать в метке 'filial', то начальный uri будет равен /ru
|
|
|
|
|
|
|
|
|
|
|
|
 */

$label['lang'] = array(
                    'module' => 'language',
                    'controller' => 'language',
                    'method' => 'get_id_on_abbr',
                    'rules' => array(
                                    'exact_length' => 2,
                                    'alpha',
                    ),
                    'path_default' => 'language/language/get_current',
);
$label['filial'] = array(
                    'module' => 'filials',
                    'controller' => 'filials',
                    'method' => 'check_filial',
                    'rules' => array(
                                    'min_size' => 4,
                                    'max_size' => 15,
                                    'alpha',
                    ),
                    'path_default' => 'filials/filials/get_current',
);
$label['page'] = array(
                    'module' => 'pages',
                    'controller' => 'pages',
                    'method' => 'id_is_uri',
                    'rules' => array(
                                    'min_size' => 4,
                                    'alpha_dash',
                    ),
                    'path_default' => 'pages/pages/get_current',
);

/* End of file label.php */
/* Location: ./application/config/label.php */