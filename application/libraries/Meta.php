<?php if (!defined('BASEPATH')) exit('No direct script access allowed');

class Meta {

  private $meta = array();
  function __construct()
	{
    //parent::__construct();
    //$this->css = new $this;
	}

  function set_param($name, $value){

    switch($name){
      case 'title':
        $this->meta[$name] = htmlspecialchars($value);
        break;
      case 'description':
        $this->meta[$name] = htmlspecialchars($value);
        break;
      case 'keywords':
        $this->meta[$name] = htmlspecialchars($value);
        break;
      case 'h1':
        $this->meta[$name] = htmlspecialchars($value);
        break;
    }
  }

  function get_param($name){
    switch($name){
      case 'title':
        if(isset($this->meta[$name])) return $this->meta[$name];
        break;
      case 'description':
        if(isset($this->meta[$name])) return $this->meta[$name];
        break;
      case 'keywords':
        if(isset($this->meta[$name])) return $this->meta[$name];
        break;
      case 'h1':
        if(isset($this->meta[$name])) return $this->meta[$name];
        break;
    }
  }

}

