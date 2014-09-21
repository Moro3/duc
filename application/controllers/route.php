<?php if (!defined('BASEPATH')) exit('No direct script access allowed');

class Route extends MX_Controller {

	function __construct()
	{
            parent::__construct();
            //$this->output->enable_profiler(TRUE);
            //$this->load->helper('file');
            $this->config->load('site', 'TRUE');
            //$this->load->library('control_uri');
            //$this->load->library('meta');
            $this->load->driver('assets');
            //$this->load->library('assets');
            //$this->assets->load_style('framework');
	}



	function index()
	{
            //$this->db->cache_on(1);
            //$this->output->cache(1);

            //phpinfo();
            //echo FCPATH."<br>";
            //echo SELF."<br>";

            /*
             *  выясняем текущую страницу сайта
             */
            $data['id_page'] = $this->_pages();
            //echo "ID page: ";
            //var_dump($data['id_page']);
            //echo "<br />";

			//если есть в строке запроса старая страница, делаем перенаправление на новую
			if(!empty($this->bufer->pages['id_old'])){
                	//echo 'найдена перемещаемая страница';
                	$data_page_old = Modules::run('pages/pages/get_data_page', $this->bufer->pages['id_old']);
                	if(!empty($data_page_old['uri']) && @$data_page_old['active'] == 1){
                		$uri_page_old = Modules::run('pages/pages/getFieldUri', $data_page_old['uri']);
                	}
                	if(!empty($uri_page_old)){
                        // echo $uri_page_old;
                		header("HTTP/1.1 301 Moved Permanently");
						header("Location: http://".$_SERVER['SERVER_NAME'].$uri_page_old);
						//redirect($uri_page_old, 'location', 301);
						show_error($uri_page_old , 301, 'Страница была перемещена' );
						//echo $_error->show_error($heading, $message, 'error_general', $status_code);
						exit();
                	}
      		}

            if($data['id_page']){
              // выясняем текущую страницу сайта
              //$id_content = $this->_content($data['id_page']);
              //echo "ID page: ".$id_page."<br />";
              //echo "ID content: ".$id_content."<br />";
              $page_info = $this->_content($data['id_page']);
              //echo "ID page: <pre>";
              //print_r($page_info);
              //echo "</pre><br />";
              $data['page'] = $page_info;
              if($page_info['active'] == 1){
                  if(isset($page_info['content'][0])){


                            if(isset($page_info['content'][0]->description)){
                              //$data['content']['content'] = Modules::run('pages/pagesmod/get_content', $data['content']['content']);

                                /*
                                $this->load->driver('cache', array('adapter' => 'apc', 'backup' => 'file'));
                                if ( ! $ccontent = $this->cache->get('ccontent')){
                                	$ccontent = $this->load->module('gate')->replace_mod_content($page_info['content'][0]->description);
                                	// Save into the cache for 5 minutes
     								$this->cache->save('ccontent', $ccontent, 300);
                                }
                                $data['content']['content'] = $ccontent;
                                */
                                $data['content']['content'] = $this->load->module('gate')->replace_mod_content($page_info['content'][0]->description);;
                                Modules::run('pages/pages/breadcrumbs', $this->bufer->pages['id'], 'side_left');
                                //echo $this->bufer->menus['node_valid'];
                                //print_r($this->bufer->menus['nodes']);
                                //Modules::run('pages/pages/breadcrumbs_node', $this->bufer->menus['node']);
                                $cache = true;
                            }
                            if(is_array($page_info['content'][0]->seo)){
                              $seo = $page_info['content'][0]->seo[0];
                              $this->meta->set_param('title',$seo->title);
                              $this->meta->set_param('description',$seo->description);
                              $this->meta->set_param('keywords',$seo->keywords);
                            }

                  }
              }else{
                  show_404($this->bufer->pages['request_uri']);
                  $data['page']['content'][0]->description = Modules::run('pages/pages/page_404');
                  $this->meta->set_param('title','Страница не найдена');
                  $this->meta->set_param('description','');
                  $this->meta->set_param('keywords','');
              }
              //$data['page'] = Modules::run('pages/pages/get_pages',$id_page);
              //$data['page'] = $this->load->module('gate')->get_pages($id_page);
            }else{
                /*
                if($this->bufer->pages['uri'] == '/order/'){                	$data['content']['content'] = 'Запущен модуль заказ';
                	//$data['content']['content'] = Modules::run('pages/pages/page_404');
                }else{

	                //show_404($this->bufer->pages['request_uri']);
	                $data['page']['content'][0]->description = Modules::run('pages/pages/page_404');
	                $this->meta->set_param('title','Страница не найдена');
	                $this->meta->set_param('description','');
	                $this->meta->set_param('keywords','');
                }
                */
                //echo 'Сегмент:<br>';

                //если нашелся адрес по старому uri, делаем перенаправление


                $error_404['page'] = true;

            }

            $nn = $this->control_uri->num_segment('pages');
                if($segment = $this->control_uri->segment($nn)){

	                //var_dump($segment);
	                switch($segment){
	                	case 'duc':
	                		//echo 'order';
	                		$data['modules']['content'] = Modules::run('duc/duc/MY_route', 'user');
	                		$cache = false;
	                		//exit;
	                	break;
	                	case 'wysiwyg':
	                		echo 'wysiwyg';
	                		//exit;
	                	break;
	                	default:
	                		//если никакой модуль не найден
	                		//делаем доп. проверку на нестандартные случаи запроса

	                		//пример: для настройки fckeditor filemanager для защиты от неавторизованных
                            $pos = strpos($segment,'Command=');
	                		if($pos === false){
	                			//если вариантов на маршрут не осталось, выводим страницу 404
	                			$error_404['module'] = true;

	                		}else{
                                /**
                                *
                                * здесь мы пропускаем команды FCKeditor Filemanager
                                * т.е. вывод отображения кешируется а затем отбрасывается в конфигурации fckeditor
                                * wysiwyg\fckeditor\editor\filemanager\connectors\php\connector.php
                                * Данный код попадает туда путем подключения framework для проверки авторизации
                                *
                                */
	                		}

	                }
                }

				//echo $data['modules']['content'];
				//exit;
                if(isset($error_404['page']) && isset($error_404['module'])){                	show_404($this->bufer->pages['request_uri']);
                }

                //if($cache === true) $this->output->cache(20);

            /*
             * загружаем подключенные модули страницы
             */
            if(isset($data['id_page'])){
              $data['modules']['cache'] = $this->load->module('gate')->get_modules_content($data['id_page']);
            }


            $data['meta']['title'] = $this->meta->get_param('title');
            $data['meta']['description'] = $this->meta->get_param('description');
            $data['meta']['keywords'] = $this->meta->get_param('keywords');


            /*
             * Генерируем шаблон по основным параметрам
             */
            $this->_template($data);

            //echo "<pre>";
            //print_r($this->control_uri->get_uri_use());
            //echo "</pre>";
	}

    function _language(){
        $this->control_uri->set_segment('lang');
        if($this->control_uri->get_segment('lang')){
          $segment = $this->control_uri->get_segment('lang');
          $lang = $this->_get_lang($segment);
        }else{
          $lang = $this->_get_lang();
        }

        return $lang;
    }

    /*
     * Возвращает текущий id страницы
     */
    function _pages(){
        //$this->bufer->pages['url'] = $this->control_uri->get_uri();
        $this->bufer->pages['request_uri'] = $this->control_uri->get_uri('request_uri');
        $this->bufer->pages['query_string'] = $this->control_uri->get_uri('query_string');
        $this->bufer->pages['url'] = $this->control_uri->get_uri('url');
        $this->bufer->pages['query_string'] = $this->control_uri->get_uri('query_string');

        //$this->control_uri->set_segment('pages_num');
        $this->control_uri->set_segment('pages');
        //$this->control_uri->set_segment('pages_num');
        //$this->control_uri->set_segment('pages_comments');
        $segment = $this->control_uri->get_segments_next('pages');
        //echo $segment;
        if($segment){
          $page = $this->_get_page($segment);
        }else{
          $page = $this->_get_page();
        }

        return $page;
    }

    /*
     * Возвращает текущий id содержимого страницы
     */
    function _content($id_page){
        // узнаем значение страницы
        //$id_page = $this->_pages();
        //echo "$id_page";
        if($id_page){
          if($this->control_uri->get_segment('pages')){
            //узнаем порядковый номер сегмента страницы
            $ns_pages = $this->control_uri->num_segment('pages');
          //если нет контрольного сегмента pages ищем сегмент lang
          }elseif($this->control_uri->get_segment('lang')){
            $ns_pages = $this->control_uri->num_segment('lang');
          //по умолчанию присваиваем 0
          }else{
            $ns_pages = 0;
          }

          //берем следующий сегмент contenta после страницы
            $seg_content = $this->control_uri->segment($ns_pages+1);

            //если сегмента нет даем ему номер подстраницы 1
            if($seg_content === false){

                $id_content = $this->_get_content($id_page,1);
            }else{

                //если сегмент имеет значение вычисляем id contenta
                if($this->control_uri->get_segment('content')){

                    $segment = $this->control_uri->get_segment('content');

                  $id_content = $this->_get_content($id_page, $segment);
                }else{
                    $id_content = $this->_get_content($id_page,$seg_content);

                }
              //устанавливаем контроль над сегментом только в том случае
              //если имеется он в строке uri и соответствует id contenta
              if(isset($id_content)) $this->control_uri->set_segment('content');
            }

        }
        if(isset($id_content)) return $id_content;
        return false;
    }

    /*
     * Возвращает текущий id языка
     */
    function _get_lang($lang = ''){

        return 1;
    }

    /*
     * Возвращает id страницы по uri
     * и записывает в буфер информацию о текущих данных страницы
     */
    function _get_page($uri = '/'){
        //$id_page = $this->load->module('pages/pages')->id_is_uri($uri);
        //$id_page = $this->load->module('gate')->id_is_uri($uri);
        // получаем id страницы с учетом цепочки пути
        $id_page = Modules::run('pages/pages/id_is_valid_uri', $uri);

        //получаем id страницы, uri которой был по старому адресу
        $id_page_old = Modules::run('pages/pages/id_is_uri_old', $this->bufer->pages['request_uri']);

        //если старый адрес страницы существует
        //сравниваем с новым, если не равны записываем найденный id старой uri в буфер
        if(!empty($id_page_old)){        	if($id_page_old != $id_page){        		$this->bufer->pages['id_old'] = $id_page_old;
        	}
        }

        $this->bufer->pages['id'] = $id_page;
        $this->bufer->pages['uri'] = $uri;
        //получаем цепочку валидных uri
        $uri_valid = Modules::run('pages/pages/get_uri_valid', $uri);
        $this->bufer->pages['uri_valid'] = $uri_valid;

		//получаем массив валидных id
        $ids_valid = Modules::run('pages/pages/get_ids_valid', $uri);
        $this->bufer->pages['ids_valid'] = $ids_valid;

        //получаем узлы данной id страницы
        $nodes = Modules::run('menus/menus/get_id_nodes', $id_page, 'page');
        $this->bufer->menus['nodes'] = $nodes;
        /*
        echo 'Валидные uri: '.$uri_valid."<br>";
        echo 'Найденные узлы: ';
        var_dump($nodes);
        echo "<br>";
        echo 'Найденные id страниц в цепочке uri: ';
        print_r($ids_valid);
        echo "<br>";
        */
        //проверяем на наличие данного пути в найденных узлах
        if(is_array($nodes)){
	        $this->bufer->menus['node'] = current($nodes);
	        if(count($nodes) == 1){	        	//получаем полный путь до узла
		        $uri_node = Modules::run('menus/menus/get_path', $this->bufer->menus['node']);
		        $this->bufer->menus['uri_path'] = $uri_node;
		        $this->bufer->menus['node_valid'] = $this->bufer->menus['node'];
	        }else{

		        foreach($nodes as $node){		        	//получаем полный путь до узла
		        	$uri_node = Modules::run('menus/menus/get_path', $node);
		        	$this->bufer->menus['uri_path'] = $uri_node;
		            $arr_node = array();
					//создаем массив пути из имен
		        	if(is_array($uri_node)){
		        		foreach($uri_node as $items){		        			$arr_node[] = $items['name'];
		        		}
		        	}

		        	//сравниваем массивы пути имен и массив валидных id сраниц
		        	if($ids_valid === $arr_node){			        		$this->bufer->menus['node_valid'] = $node;
			        		/*
			        		echo 'Путь верный, узел: '.$node.'<br />';
			        		print_r($arr_node);
			        		echo '<br />';
	                        */
		        	}else{
			        		/*
			        		echo 'Ошибка пути, узел: '.$node.'<br />';
			        		print_r($arr_node);
			        		echo '<br />';
			        		*/
			        }
		        }
	        }
        }
        return $id_page;
    }

    /*
     * Возвращает id содержимого страницы по id page и номеру подстраницы
     */
    function _get_content($id_page, $pg = ''){
        $lang = $this->_get_lang();
        $config = array('id_language' => $lang,
                        );
        //$id_content = $this->load->module('pages/pages')->id_content($id_page, $pg, $config);
        //$id_content = $this->load->module('gate')->get_id_content($id_page,$pg,$config);

		$id_content = Modules::run('pages/pages/get_data_page', $id_page);


        return $id_content;
    }

    /**
     *  Шаблон вывода
     * @param type $vars
     */
    function _template($vars){
        $items['contents'] = (isset($vars['content'])) ? $vars['content'] : '';
        $items['modules'] =  (isset($vars['modules'])) ? $vars['modules'] : '';

        // Подгрузка необходимым стилей до вызова модулей
        $this->load->view('template_1/before_style', '');
        // Подгрузка необходимым скриптов до вызова модулей
        $this->load->view('template_1/before_script', '');

        $data['header'] = $this->load->view('template_1/header', '', true);
        $data['navigation'] = $this->load->view('template_1/navigation', '', true);
        $data['modal_popup'] = Modules::run('adverts/adverts/tpl_user_modal');

        if($this->uri->segment(1)){
            $data['content'] = $this->load->view('template_1/content_inside', $items , true);
        }else{
            $data['content'] = $this->load->view('template_1/content', $items, true);
        }
        $data['footer'] = $this->load->module('gate')->replace_mod_content($this->load->view('template_1/footer', '', true));
        //$this->load->module('gate')->replace_mod_content();

        $this->load->view('template_1/index',$data);
    }

    function _template_login(){
        if ($this->ion_auth->logged_in()) {
	    	//redirect them to the login page
		redirect($this->config->item('page_in', 'ion_auth'), 'refresh');
    	}
    	else {
	        //set the flash data error message if there is one
	        $this->data['message'] = (validation_errors()) ? validation_errors() : $this->session->flashdata('message');

    		//list the users
    		$this->data['users'] = $this->ion_auth->get_users_array();

            echo Modules::run('auth/auth/login');
    	}
    }
}

/* End of file welcome.php */
/* Location: ./system/application/controllers/welcome.php */








