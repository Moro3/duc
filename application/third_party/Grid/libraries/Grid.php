<?php


class Grid{
  /**
  *  Объект GRID
  */
  public $loader;

  private $path_grid;

  /**
   *  Параметры базы данных
   * @var type
   */
  private $db;

  function __construct($name = 'jqGrid'){

      $this->path_grid = rtrim(dirname(__FILE__),"\/").DIRECTORY_SEPARATOR;
      //$this->path_grid = 'test.helpa.ru/www/application/third_party/Grid/libraries/jqGridPHP/';
      $name = strtolower($name);
      switch($name){
          case 'jqgrid':
              $grid_loader = 'jqGridLoader';
              $grid_dir = 'jqGridPHP/';
          break;
      	  default:
      		$grid_loader = 'jqGridLoader';
            $grid_dir = 'jqGridPHP/';
      }

      //echo $this->path_grid.$grid_dir.$grid_loader.EXT;
      //echo '<br>'.APPPATH;
      //echo '<br>'.__FILE__;
      //echo '<br>';
      if(is_file($this->path_grid.$grid_dir.$grid_loader.EXT)){
          require $this->path_grid.$grid_dir.$grid_loader.EXT;

          $this->loader = new $grid_loader;
      }else{
          die('Не обнаружен файл загрузчик для GRID');
      }

      $this->load_db('default');
      //var_dump($this->loader);

      //return $this->loader;
  }

  function load_db($params = ''){

      // Is the config file in the environment folder?
        if ( ! defined('ENVIRONMENT') OR ! file_exists($file_path = APPPATH.'config/'.ENVIRONMENT.'/database.php'))
        {
                if ( ! file_exists($file_path = APPPATH.'config/database.php'))
                {
                        show_error('The configuration file database.php does not exist.');
                }
        }

        include($file_path);

        if ( ! isset($db) OR count($db) == 0)
        {
                show_error('No database connection settings were found in the database config file.');
        }
        if ($params != '')
        {
                $active_group = $params;
        }

        if ( ! isset($active_group) OR ! isset($db[$active_group]))
        {
                show_error('You have specified an invalid database connection group.');
        }

        $this->db = $db[$active_group];
        $this->set_param();

  }

  function set_param(){
      // путь к папкам таблиц
      //$path_to_tables = Modules::current_path().'models/';
      $module = CI::$APP->router->current_module();
      if(empty($module)){
          $path_to_tables = APPPATH.'models/';
      }else{
          $path_to_tables = APPPATH.'modules/'.$module.'/models/';
      }

      #Set grid directory
      $this->loader->set("grid_path", $path_to_tables);

      #Use PDO for database connection
      $this->loader->set("db_driver", "Pdo");
      if(!empty($this->db['dbdriver'])){
        $dsn = $this->db['dbdriver'].':dbname='.$this->db['database'].';host='.$this->db['hostname'];
      }else{
          die('Не обнаружен драйвер базы данных для GRID');
      }

      #Set PDO-specific settings
      $this->loader->set("pdo_dsn"  , $dsn);
      $this->loader->set("pdo_user" , $this->db['username']);
      $this->loader->set("pdo_pass" , $this->db['password']);

      $this->loader->addInitQuery('SET NAMES utf8');

  }

  function set_param_mysql(){
      // путь к папкам таблиц
      //$path_to_tables = Modules::current_path().'models/';
      $module = CI::$APP->router->current_module();
      if(empty($module)){
          $path_to_tables = APPPATH.'models/';
      }else{
          $path_to_tables = APPPATH.'modules/'.$module.'/models/';
      }

      #Set grid directory
      $this->loader->set("grid_path", $path_to_tables);

      #Use PDO for database connection

      if(!empty($this->db['dbdriver'])){
          $this->loader->set("db_driver", $this->db['dbdriver']);
      }else{
          die('Не обнаружен драйвер базы данных для GRID');
      }

      #Set PDO-specific settings
      $this->loader->set("db_host" , $this->db['hostname']);
      $this->loader->set("db_name" , $this->db['database']);
      $this->loader->set("db_user" , $this->db['username']);
      $this->loader->set("db_pass" , $this->db['password']);


  }

}
