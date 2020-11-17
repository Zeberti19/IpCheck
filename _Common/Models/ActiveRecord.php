<?php
namespace _Common\Models;

abstract class ActiveRecord
{
    protected $_isLoaded=false;
    protected $_properties=[];

    static public $tableName=null;
    static public $schemaName='public';
    static public $primaryColumn='id';
    static public $uniqueColumns=[];

    public function __set($propName,$propVal)
    {
        $this->_properties[$propName]=$propVal;
    }

    public function __get($propName)
    {
        if ($this->_properties and isset($this->_properties[$propName])) return $this->_properties[$propName];
        return null;
    }

    abstract protected function _insert();

    abstract protected function _update();

    abstract function loadFromDb($refresh=true);

    public function save()
    {
        if (!$this->_properties) return false;
        require_once '../_Common/DB.php';

        if ($this->_isLoaded or $this->loadFromDb(false)) return $this->_update();
        return $this->_insert();
    }

    static public function selectAll()
    {
        //
    }
}
