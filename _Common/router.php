<?php
namespace _Common\router;
use Sections\IpCheck\IpCheckController;

$route=isset($_REQUEST['route'])?(string)$_REQUEST['route']:null;
switch ($route)
{
    case 'ip-check':
    default:
        require '../Sections/IpCheck/IpCheckController.php';
        $IpCheckController=new IpCheckController();
        $action=isset($_REQUEST['action'])?(string)$_REQUEST['action']:null;
        if (!$action or !method_exists($IpCheckController,$action)) $action=$IpCheckController->actionDef;
        if (!method_exists($IpCheckController,$action)) throw new \Exception("Нет действия для указанного контроллера");
        $IpCheckController->$action();
}
