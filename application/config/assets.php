<?php  if ( ! defined('BASEPATH')) exit('No direct script access allowed');

/*
|--------------------------------------------------------------------------
| Assets Variables
|--------------------------------------------------------------------------
|
| Если вы используете в представлении функции библиотеке assets
| или используете удаленный сервер в качестве донора по содержимому в контенте
| настройте необходимые переменные для нормальной работы приложений
|
| 'assets_access_domain'		= использовать или нет удаленный домен
| 'assets_domain'		        = имя удаленного домена, если не указан, то текущий
| 'assets_domain_start_dir' = имя стартовой папки на удаленном домене (например: если 'template/default', имя файла будет читаться с http://site.com/template/default/)
| 'assets_access_donor'		  = использовать или нет удаленный сайт донор
| 'assets_donor'		        = имя удаленного сайта донора (domain name or IP) (site.com, 120.130.140.150)
| 'assets_timeout_domain'		= время таймаута в сек. между запросоми на удаленный домен, 0 - таймаут отключен(если данные обновляются редко, рекомендуется ставить максимальное значение)
| 'assets_timeout_donor'		= время таймаута в сек. между запросоми на удаленный сайт донор, 0 - таймаут отключен(если данные обновляются редко, рекомендуется ставить максимальное значение)
| 'assets_link_site'		    = вид ссылки на сайт (true - с использованием домена (http://site.com/link), false - без домена(/link))
| 'assets_place_script'		  = место размещения скриптов (local - локально в виде файла, head - в заголовке страницы, remote - в виде ссылки на удаленный домен)
| 'assets_place_style'		  = место размещения стилей (local - локально в виде файла, head - в заголовке страницы, remote - в виде ссылки на удаленный домен)
| 'assets_place_img'		    = место размещения изображений (local - локально в виде файла, remote - в виде ссылки на удаленный домен)
| 'assets_place_doc'		    = место размещения документов (local - локально в виде файла, remote - в виде ссылки на удаленный домен)
|
*/

$config['type'] = array('assets_script', 'assets_style', 'assets_img', 'assets_text', 'assets_audio', 'assets_video', 'assets_mixed');

/*
$config['allow_ext'] = array(
                         'style' => array('css'),
                         'script'  => array('js'),
                         'images'  => array('jpg','jpeg','gif','png','tiff','bmp','ico'),
                         'img'  => array('jpg','jpeg','gif','png'),
                         'text' => array('doc','docx','xls','xlsx','txt','rtf','pdf','ppt'),
                         'arj'  => array('zip','rar','jar','bz2','gz','tar','rpm'),
                         'audio'  => array('mp3','wav','ogg','flac','midi','rm','aac','wma','mka','ape'),
                         'video'  => array('mpeg','mp4','ram','ra','avi','mpg','mov','divx','asf','qt','mwv','rv','vob','asx','ogm'),
                         'mixed' => array('js','css','jpg','gif','ico','xml','txt','csv'),
                        );
*/

$config['setting'] = array(
            'style' => array(
                        'ext' => array('css','jpg','jpeg','gif','png'),
                        'dir' => 'css',
                        'domain' => false,
                        'timeout' => 0,
                        'place' => 'local',
                        'donor' => 'http://www.donor.ru',
            ),
            'script' => array(
                        'ext' => array('js','css','jpeg','gif','png'),
                        'dir' => 'js',
                        'domain' => false,
                        'timeout' => 0,
                        'place' => 'local',
                        'donor' => '',
            ),
            'img' => array(
                        'ext' => array('jpg','jpeg','gif','png','tiff','bmp','ico'),
                        'dir' => 'img',
                        'domain' => false,
                        'timeout' => 0,
                        'place' => 'local',
                        'donor' => '',
            ),
            'text' => array(
                        'ext' => array('doc','docx','xls','xlsx','txt','rtf','pdf','ppt'),
                        'dir' => 'doc',
                        'domain' => false,
                        'timeout' => 0,
                        'place' => 'local',
                        'donor' => '',
            ),
            'arj' => array(
                        'ext' => array('zip','rar','jar','bz2','gz','tar','rpm'),
                        'dir' => 'arj',
                        'domain' => false,
                        'timeout' => 0,
                        'place' => 'local',
                        'donor' => '',
            ),
            'audio' => array(
                        'ext' => array('mp3','wav','ogg','flac','midi','rm','aac','wma','mka','ape'),
                        'dir' => 'audio',
                        'domain' => false,
                        'timeout' => 0,
                        'place' => 'local',
                        'donor' => '',
            ),
            'video' => array(
                        'ext' => array('mpeg','mp4','ram','ra','avi','mpg','mov','divx','asf','qt','mwv','rv','vob','asx','ogm'),
                        'dir' => 'video',
                        'domain' => false,
                        'timeout' => 0,
                        'place' => 'local',
                        'donor' => '',
            ),
            'mixed' => array(
                        'ext' => array('js','css','jpg','gif','png','ico','xml','txt','csv', 'swf'),
                        'dir' => 'mixed',
                        'domain' => false,
                        'timeout' => 0,
                        'place' => 'local',
                        'donor' => '',
            ),
);
//
$config['tpl_path'] = 'assets';
$config['tpl_name'] = 'default';

$config['path_assets'] = '/assets/default/';

$config['path_uploads'] = '/uploads/';

$config['path_wysiwyg'] = '/wysiwyg/';

// Папки источников
$config['dir_source_assets'] = 'assets'; // папка размещения локального источника приложений модуля

// Папки для конечных данных
$config['dir_target_modules'] = 'modules';  // папка для размещения приложений модуля
$config['dir_target_public'] = 'public';  // папка для размещения общих приложений

// папки для размещения локальных файлов для каждого типа файла
/*
$config['dir_assets'] = array(
                         'style' => 'css',
                         'script'  => 'js',
                         'images'  => 'img',
                         'img'  => 'img',
                         'text' => 'doc',
                         'arj'  => 'arj',
                         'audio' => 'audio',
                         'video' => 'video',
                         'mixed' => 'packed',
                        );
*/
/********************************************
 *  Переопределяющие в модуле
 * ******************************************
 */

/* использовать ссылку с именем домена
 * false - относительно корня сайта(/)
 * true - ссылка с именем домена (http://имя сайта/)
 */
$config['link_site'] = false;

/*
 * Место расположения скриптов и стилей в html документе
 * local - локально в виде ссылки на файл
 * head - в заголовке документа
 */
$config['place_script'] = 'local';
$config['place_style'] = 'local';

/*
 * Домены назначения куда будут ссылаться ссылки на файл
 * Используется в случае использования файлов приложения на другом сервере
 * false или пустая строка - текущий сервер html документа
 *
 */
/*
$config['domain'] = array('script' => false,
                          'style' => false,
                          'img' => false,
                          'text' => false,
                          'arj' => false,
                          'audio' => false,
                          'video' => false,
                          'mixed' => false,
                         );
*/
/*
 * Таймаут (в сек.) для локального копирования файлов
 * 0 (таймаут не используется)
 *
 * Если не требуется длительного обновления файлов,
 * рекомендуется ставить время как можно больше для экономии времени обработки скрипта
 *
 * Внимание! Если изменений в файле обнаружено не будет, копирования также не произойдет
 * При установленом таймауте, просто не будет происходить сравнение и дальнейшего копирования файла
 *
 */
/*
$config['timeout'] = array('script' => 0,
                          'style' => 0,
                          'img' => 0,
                          'text' => 0,
                          'arj' => 0,
                          'audio' => 0,
                          'video' => 0,
                          'mixed' => 0,
                         );
*/

/**
 *  Параметры удаленного домена (донор)
 *  Донором является домен где все файлы источника берутся не из локальной папки модуля а через другой домен
 *  т.е. место расположения локальных файлов источника будет черпаться через запросы к домену донору
 *  Важно! Файлы источника могут не находится на домене доноре, они будут только отдаваться через него при запросах
 */
$config['donor_access'] = false; // разрешение на доступ к удаленному домену (false - доступа нет)
$config['donor_domain'] = '';  // имя удаленного домена (http://site.ru)

/* массив со значениями которые требуется заменять на основной (начальный) путь к файлам приложения
 * применяется для файлов типов файлов script и style
 * В ваших законченых файлах скриптов и стилей вы можете указывать данный синтаксис начала пути к файлам,
 * при перемещении их в папку приложений для общего доступа эти пути будут заменены на реальные
 *
 * Вы также можете добавить нужный вам синтаксис, если текущие вас не устроют!
 *
 */
$config['path_for_replace'] = array(
                                    '{assets}/',
                                    '{assets}'
                                    );



/**
* Разбивать скрипты в заголовке страницы отдельными тегами
* 	true - каждый скрипт будет окружен тегами скрипта <script></script>
*	false - все скрипты будут заключены в одном теге
*/
$config['split_head'] = true;
/* End of file assets.php */
/* Location: ./application/config/assets.php */