<?php
namespace Sections\IpCheck;

use _Common\DB;
use _Common\Models\ActiveRecord;
use PDO;

require_once "../_Common/Models/ActiveRecord.php";

class IpCheckModel extends ActiveRecord
{
    protected $_isLoaded=false;
    protected $_properties=[];

    static public $tableName='t_data';
    static public $schemaName='sch_ip_check';
    static public $primaryColumn='id';
    static public $uniqueColumns=['url'];

    public function __set($propName,$propVal)
    {
        $this->_properties[$propName]=$propVal;
    }

    public function __get($propName)
    {
        if ($this->_properties and isset($this->_properties[$propName])) return $this->_properties[$propName];
        return null;
    }

    public function loadFromDb($refresh=true)
    {
        //массив полей по которому будем искать нужное поле в базе
        $fieldsFind=array_merge( [static::$primaryColumn], static::$uniqueColumns );

        //если таковых нет, то не найти нужную запись в БД
        if (!$fieldsFind) return false;

        //смотрим какие заполненные поля для поиска у нас есть и по ним формируем запрос
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
        //если ни одного поля для поиска нет, то нужную запись не найти
        if (!$valsFind) return false;

        $schemaEncoded=DB::prepareName(static::$schemaName);
        $tableNameEncoded=DB::prepareName(static::$tableName);
        $sql=
            "select 
                   id,
                   to_char(datetime,'DD.MM.YYYY HH24:MI:SS') as datetime,
                   url,
                   response_time_avg,
                   response_time_min,
                   response_time_max 
             from {$schemaEncoded}.{$tableNameEncoded} 
             where {$whereClause}";

        $row=DB::selectRowFirst($sql,$valsFind);
        if ($row)
        {
            foreach ($row as $fieldName=>&$fieldVal)
            {
                if ($refresh or !$this->{$fieldName}) $this->{$fieldName}=$fieldVal;
            }
            $this->_isLoaded=true;
            return true;
        }
        return false;
    }

    protected function _insert()
    {
        $schemaEncoded=DB::prepareName(static::$schemaName);
        $tableNameEncoded=DB::prepareName(static::$tableName);
        $sql="insert into {$schemaEncoded}.{$tableNameEncoded}";

        $vals=[];
        $fieldsEncoded=[];
        foreach ($this->_properties as $propName => &$propVal)
        {
            $fieldsEncoded[]=DB::prepareName($propName);
            if ("datetime" == $this->$propName)
                $vals[] = "to_timestamp(?, 'DD.MM.YYYY HH24:MI:SS')";
            else
                $vals[] ="?";
        }
        $sql.="(" .implode(",", $fieldsEncoded) .")";
        $sql.=" values(" .implode( ",", $vals ) .")";

        if (!DB::exec($sql,array_values($this->_properties))) throw new \Exception(__CLASS__ .". Не удалось вставить данные объекта в БД, хотя SQL запрос выполнен без ошибок");
        return true;
    }

    protected function _update()
    {
        $schemaEncoded=DB::prepareName(static::$schemaName);
        $tableNameEncoded=DB::prepareName(static::$tableName);
        $primaryColumnEncoded=DB::prepareName(static::$primaryColumn);
        $sql="update {$schemaEncoded}.{$tableNameEncoded} set ";

        $fieldsEncoded=[];
        $vals=[];
        foreach ($this->_properties as $propName => &$propVal)
        {
            //ИД нет смысла обновлять
            if (static::$primaryColumn == $propName) continue;
            //с датой работаем по особому
            if ('datetime' == $propName)
                $fieldsEncoded[]=DB::prepareName($propName) . " = to_timestamp(?, 'DD.MM.YYYY HH24:MI:SS')";
            else
                $fieldsEncoded[]=DB::prepareName($propName) . " = ?";
            $vals[]=$propVal;
        }
        $sql.="" .implode(", ", $fieldsEncoded);

        $vals[]=$this->{static::$primaryColumn};
        $sql.=" where {$primaryColumnEncoded} = ?";

        if (!DB::exec($sql,array_values($this->_properties))) throw new \Exception(__CLASS__ .". Не удалось обновить данные объекта в БД, хотя SQL запрос выполнен без ошибок");
        return true;
    }

    public function save()
    {
        if (!$this->_properties) return false;
        require_once '../_Common/DB.php';

        if ($this->_isLoaded or $this->loadFromDb(false)) return $this->_update();
        return $this->_insert();
    }

    static public function selectAll()
    {
        require_once '../_Common/DB.php';
        $schemaEncoded=DB::prepareName(static::$schemaName);
        $tableNameEncoded=DB::prepareName(static::$tableName);
        $sql=
            "select 
                   id,
                   to_char(datetime,'DD.MM.YYYY HH24:MI:SS') as datetime,
                   url,
                   response_time_avg,
                   response_time_min,
                   response_time_max 
             from {$schemaEncoded}.{$tableNameEncoded}
             order by datetime desc
             ";

        $DBCursor=DB::selectCursor($sql);
        $ipcCheckMas=[];
        if ($DBCursor)
        {
            $DBCursor->setFetchMode(PDO::FETCH_CLASS,get_class());
            while($IpCheckModel=$DBCursor->fetch())
            {
                $ipcCheckMas[]=$IpCheckModel;
            }
            return $ipcCheckMas;
        }
        return false;
    }
}
