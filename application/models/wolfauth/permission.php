<?php
  
class Permission extends DataMapper {
    
    public $has_one = array('role');
    public $has_many = array('group', 'user');
    
    public function __construct($id = NULL)
    {
        parent::__construct($id);
    }
    
}
