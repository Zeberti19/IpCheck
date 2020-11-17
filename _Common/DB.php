<?php

namespace _Common;

use PDO;
use PDOStatement;

class DB
{
    static protected $dbType = null;
    static protected $host = null;
    static protected $port = null;
    static protected $dbName = null;
    static protected $userLogin = null;
    static protected $userPass = null;
    static protected $charset = null;

    /**
     * @var PDO
     */
    static protected $PDO = null;

    /**
     * @var PDOStatement
     */
    static protected $PDOStatement = null;


    //TODO вынести параметры подключения в конфиг
    static public function setConnectParams()
    {
        static::$dbType="pgsql";
        //static::$dbType="mysql";
        static::$host="localhost";
        static::$port=5432;
        //static::$port=3306;
        static::$dbName="db_ip_check";
        static::$userLogin="postgres";
        static::$userPass="password";
        static::$charset="UTF8";
    }

    static protected function connect()
    {
        static::setConnectParams();
        $connectStr= static::$dbType .":host=".static::$host .";port=".static::$port.";dbname=" .static::$dbName;
        if ("pgsql"==static::$dbType) $connectStr .=";options='--client_encoding=" .static::$charset ."'";
        static::$PDO = new PDO($connectStr, static::$userLogin, static::$userPass, [PDO::ATTR_ERRMODE=>PDO::ERRMODE_EXCEPTION]);
        static::$PDO->query("SET NAMES '" .static::$charset ."'" );
    }

    /**
     * Выбирает только первую строку после выполнения SQL запроса и возвращает его в виде ассоциативного массива
     *
     * @param $sql
     * @param null $params
     * @return mixed
     */
    static public function selectRowFirst($sql, $params=null)
    {
        if (!static::$PDO) static::connect();
        if (static::$PDOStatement) static::$PDOStatement->closeCursor();
        //static::$PDOCursor = static::$PDO->query($sql, PDO::FETCH_CLASS, 'Sections\\IpCheck\\IpCheckModel');
        static::$PDOStatement=static::$PDO->prepare($sql);
        static::$PDOStatement->execute($params);
        return static::$PDOStatement->fetch(PDO::FETCH_ASSOC);
    }

    static public function selectCursor($sql, $params=null)
    {
        if (!static::$PDO) static::connect();
        if (static::$PDOStatement) static::$PDOStatement->closeCursor();
        static::$PDOStatement=static::$PDO->prepare($sql);
        static::$PDOStatement->execute($params);
        return static::$PDOStatement;
    }

    static public function exec($sql, $params=null)
    {
        if (!static::$PDO) static::connect();
        static::$PDOStatement=static::$PDO->prepare($sql);
        return static::$PDOStatement->execute($params);
    }

    static public function prepareName($str)
    {
        if (!static::$PDO) static::connect();
        if ("pgsql"==static::$dbType)
            $str="\"$str\"";
        return $str;
    }
}
