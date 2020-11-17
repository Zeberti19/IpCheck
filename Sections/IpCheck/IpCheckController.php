<?php

namespace Sections\IpCheck;

use _Common\Config;
use _Common\Controllers\Controller;

require_once "../_Common/Controllers/Controller.php";

class IpCheckController extends Controller
{
    public $actionDef='showPage';

    public function ipCheck()
    {
        $url=isset($_REQUEST['url'])?trim((string)$_REQUEST['url']):null;
        if (!$url) throw new \Exception("Не был передан IP (URL) хоста");
        $checkCount=Config::$CURL_CHECK_COUNT;
        $responseTimeMin=null;
        $responseTimeMax=null;
        ini_set('max_execution_time',0);
        for($n=0, $timeTemp=0;$n<$checkCount;$n++)
        {
            if (!$curl=curl_init($url)) throw new \Exception('',1);
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

            $responseTime=curl_getinfo($curl, CURLINFO_STARTTRANSFER_TIME );
            $timeTemp+=$responseTime;
            if (0==$n or $responseTime<$responseTimeMin) $responseTimeMin=$responseTime;
            if (0==$n or $responseTime>$responseTimeMax) $responseTimeMax=$responseTime;

            curl_close($curl);
        }
        $responseTimeAvg=$timeTemp/$checkCount;

        $precision=Config::$CURL_TIME_PRECISION;
        $responseTimeAvg=number_format($responseTimeAvg,$precision);
        $responseTimeMin=number_format($responseTimeMin,$precision);
        $responseTimeMax=number_format($responseTimeMax,$precision);


        require_once 'IpCheckModel.php';
        $IpCheckModel = new IpCheckModel();
        $Date=new \DateTime();
        $IpCheckModel->datetime=$Date->format("d.m.Y H:i:s");
        $IpCheckModel->url=$url;
        $IpCheckModel->response_time_avg=$responseTimeAvg;
        $IpCheckModel->response_time_min=$responseTimeMin;
        $IpCheckModel->response_time_max=$responseTimeMax;
        $IpCheckModel->save();

        $result=[ 'status'=>'success', 'data'=>['id'=> $IpCheckModel->id,
            'datetime'=> $IpCheckModel->datetime,
            'min'=>$responseTimeMin,'max'=>$responseTimeMax,'avg'=>$responseTimeAvg] ];
        echo json_encode($result);
    }

    public function showPage()
    {
        require_once 'IpCheckModel.php';
        $ipCheckMas=IpCheckModel::selectAll();
        require_once '../_Common/Helpers/HtmlHelper.php';
        ob_start();
        require( "widgets/ip-check-data_table.php" );
        $ipCheckDataTableWidget=ob_get_clean();
        require "ip-check-view.php";;
    }
}
