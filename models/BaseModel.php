<?php
require_once $_SERVER['DOCUMENT_ROOT'].'/core/app/library/Mysql.php';

class BaseModel extends Mysql2 {
      
    public function __construct()
    {
        parent::__construct();
        $this->setAdapter("task_manager");
    }
    
    public function doSelect($table, $arrFields, $arrWhere = array(), $arrOrderBy = array())
    {
        return $this->_select($table, $arrFields, $arrWhere, $arrOrderBy);
    }
}