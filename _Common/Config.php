<?php

namespace _Common;

class Config
{
    /**
     * Имя драйвера, который PHP должен использовать для соединения с базой данных.
     * Например для PostgresSQL, это будет "pgsql"
     *
     * @var null|string
     */
    static public $DB_DRIVER = null;
    static public $DB_HOST = null;
    static public $DB_PORT = null;
    static public $DB_NAME = null;
    static public $DB_USER = null;
    static public $DB_USER_PASSWORD = null;
    static public $DB_CHARSET = null;
    /**
     * Количество проверок, чтобы определить время отклика
     *
     * @var null|string
     */
    static public $CURL_CHECK_COUNT = null;
    /**
     * Количество цифр после запятой во времени отклика
     *
     * @var null|string
     */
    static public $CURL_TIME_PRECISION = null;

    static public function load( $configPath="../config" )
    {
        $configFile=file_get_contents($configPath);
        $configLines=explode("\n", $configFile);
        foreach ($configLines as &$configLine)
        {
            if (!$configLine) continue;
            $paramVal=explode("=", $configLine );
            $param=trim($paramVal[0]);
            $val=trim($paramVal[1]);
            if ($param and $val) static::$$param=$val;
        }
    }
}
