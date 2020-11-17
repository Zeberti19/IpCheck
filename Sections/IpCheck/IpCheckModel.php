<?php
namespace Sections\IpCheck;

use _Common\DB;

class IpCheckModel
{
    protected $_properties=[];

    public $tableName;
    public $schemaName='public';
    public $primaryColumn='id';
    public $uniqueColumns=['url'];

    public function __construct($tableName=null)
    {
        if (!$tableName)
        {
            $tableName=preg_replace('/^(.+)\\\\([^\\\\]+?)Model$/', '$2', get_class());
            if (!$tableName) throw new \Exception(__CLASS__ .". Не удалось автоматически определить имя таблицы в БД");
        }
    }

    public function __set($propName,$propVal)
    {
        $this->_properties[$propName]=$propVal;
    }

    public function __get($propName)
    {
        if ($this->_properties and isset($this->_properties[$propName])) return $this->_properties[$propName];
        return null;
    }

    /**
     * Проверяет находится ли указанный в базе
     *
     * @return bool
     * @throws \Exception
     */
    protected function _isRowExists()
    {
        if (!$this->uniqueColumns) return false;

        //массив полей по которому будем искать нужное поле в базе
        $fieldsFind=array_merge( [$this->primaryColumn], $this->uniqueColumns );

        $schemaEncoded=DB::prepareName($this->schemaName);
        $tableNameEncoded=DB::prepareName($this->tableName);
        $primaryColumnEncoded=DB::prepareName($this->primaryColumn);

        $whereClause=[];
        $valsFind=[];
        foreach ($fieldsFind as &$fieldName)
        {
            if ($this->{$fieldName})
            {
                $valsFind[]=$this->{$fieldName};
                $whereClause=DB::prepareName($fieldName) ." = ?";
                break;
            }
        }
        $sql="select {$primaryColumnEncoded} as p from {$schemaEncoded}.{$tableNameEncoded} where {$whereClause}";

        $row=DB::selectFirst($sql,$valsFind);
        if ($row)
        {
            $this->{$this->primaryColumn}=$row["p"];
            return true;
        }
        return false;
    }

    protected function _insert()
    {
        $schemaEncoded=DB::prepareName($this->schemaName);
        $tableNameEncoded=DB::prepareName($this->tableName);
        $sql="insert into {$schemaEncoded}.{$tableNameEncoded}";

        $paramsSql=array_fill(0, count($this->_properties), "?");
        $paramsSql=implode(",", $paramsSql );
        $fieldsEncoded=[];
        foreach ($this->_properties as $propName => &$propVal)
        {
            $fieldsEncoded[]=DB::prepareName($propName);
        }
        $sql.="(" .implode(",", $fieldsEncoded) .")";
        $sql.=" values({$paramsSql})";

        if (!DB::exec($sql,array_values($this->_properties))) throw new \Exception(__CLASS__ .". Не удалось вставить данные объекта в БД, хотя SQL запрос выполнен без ошибок");
        return true;
    }

    protected function _update()
    {
        $schemaEncoded=DB::prepareName($this->schemaName);
        $tableNameEncoded=DB::prepareName($this->tableName);
        $primaryColumnEncoded=DB::prepareName($this->primaryColumn);
        $sql="update {$schemaEncoded}.{$tableNameEncoded} set ";

        $fieldsEncoded=[];
        $vals=[];
        foreach ($this->_properties as $propName => &$propVal)
        {
            if ($this->primaryColumn == $propName) continue;
            $fieldsEncoded[]=DB::prepareName($propName) . " = ?";
            $vals[]=$propVal;
        }
        $sql.="" .implode(", ", $fieldsEncoded);

        $vals[]=$this->{$this->primaryColumn};
        $sql.=" where {$primaryColumnEncoded} = ?";

        if (!DB::exec($sql,array_values($this->_properties))) throw new \Exception(__CLASS__ .". Не удалось обновить данные объекта в БД, хотя SQL запрос выполнен без ошибок");
        return true;
    }

    public function save()
    {
        if (!$this->_properties) return false;
        require '..\_Common\DB.php';

        if ($this->_isRowExists()) return $this->_update();
        return $this->_insert();
    }
}
