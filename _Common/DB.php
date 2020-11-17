<?php

namespace _Common;

use PDO;
use PDOStatement;

class DB
{
    /**
     * Имя драйвера, который PHP должен использовать для соединения с базой данных.
     * Например для PostgresSQL, это будет "pgsql"
     *
     * @var null|string
     */
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

    static protected function _setConnectParams()
    {
        static::$dbType=Config::$DB_DRIVER;
        static::$host=Config::$DB_HOST;
        static::$port=Config::$DB_PORT;
        static::$dbName=Config::$DB_NAME;
        static::$userLogin=Config::$DB_USER;
        static::$userPass=Config::$DB_USER_PASSWORD;
        static::$charset=Config::$DB_CHARSET;
    }

    static protected function connect()
    {
        static::_setConnectParams();
        $connectStr= static::$dbType .":host=".static::$host .";port=".static::$port.";dbname=" .static::$dbName;
        if ("pgsql"==static::$dbType) $connectStr .=";options='--client_encoding=" .static::$charset ."'";
        static::$PDO = new PDO($connectStr, static::$userLogin, static::$userPass, [PDO::ATTR_ERRMODE=>PDO::ERRMODE_EXCEPTION]);
        static::$PDO->query("SET NAMES '" .static::$charset ."'" );
    }

    /**
     * Выбирает только первую строку после выполнения SQL запроса и возвращает его в виде ассоциативного массива
     *
     * @param string $sql
     * @param null|string[]|int[]|float[] $params
     * @return mixed
     */
    static public function selectRowFirst($sql, $params=null)
    {
        if (!static::$PDO) static::connect();
        if (static::$PDOStatement) static::$PDOStatement->closeCursor();
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
