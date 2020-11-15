<?php
$route=isset($_REQUEST['route'])?(string)$_REQUEST['route']:null;
switch ($route)
{
    case 'ipcheck-page':
    default:
        require '../Sections/IpCheck/IpCheckController.php';
        $IpCheckController=new Sections\IpCheck\IpCheckController();
        $IpCheckController->showPage();
}
