<?php

namespace Sections\IpCheck;

//TODO добавить родительский класс или интерфейс
class IpCheckController
{
    protected $actionDef='showPage';

    public function ipCheck()
    {
        $url=isset($_REQUEST['url'])?(string)$_REQUEST['url']:null;
        if (!$url) throw new \Exception("Не был передан IP хоста");
        //TODO вынести в отдельный файл настроек некоторые парамтеры
        $checkCount=10;
        $responseTimeMin=null;
        $responseTimeMax=null;
        ini_set('max_execution_time',0);
        for($n=0, $timeTemp=0;$n<$checkCount;$n++)
        {
            if (!$curl=curl_init($url)) throw new \Exception('',1);
            //TODO вынести в отдельный файл настроек некоторые парамтеры соединения
            $res=curl_setopt_array($curl,
                [CURLOPT_NOBODY=>true,
                CURLOPT_RETURNTRANSFER=>true,
                CURLOPT_VERBOSE=>false,
                CURLOPT_FRESH_CONNECT=>true,
                CURLOPT_COOKIESESSION=>true,
                CURLOPT_CONNECTTIMEOUT=>0,
                CURLOPT_TIMEOUT=>0,
                CURLOPT_FOLLOWLOCATION=>true,

                //отключил проверку сертификатов, т.к. я не уверен, что вы не будете проверять хосты без доверенных сертификатов по протоколу https
                CURLOPT_SSL_VERIFYPEER=>false,
                CURLOPT_SSL_VERIFYHOST=>false
                //
                ]);
            if (!$res) throw new \Exception('',2);

            curl_exec($curl);
            $errorMsg=curl_error($curl);
            if ($errorMsg) throw new \Exception($errorMsg,3);

            //curl_getinfo($curl, CURLINFO_TOTAL_TIME );
            $responseTime=curl_getinfo($curl, CURLINFO_STARTTRANSFER_TIME );
            $timeTemp+=$responseTime;
            if (0==$n or $responseTime<$responseTimeMin) $responseTimeMin=$responseTime;
            if (0==$n or $responseTime>$responseTimeMax) $responseTimeMax=$responseTime;

            curl_close($curl);
        }
        $responseTimeAvg=$timeTemp/$checkCount;

        $result=['min'=>$responseTimeMin,'max'=>$responseTimeMax,'avg'=>$responseTimeAvg];
        echo json_encode($result);
    }

    public function getActionDef()
    {
       return $this->actionDef;
    }

    public function showPage()
    {
        $html=require "ip-check-view.php";
        echo $html;
    }
}