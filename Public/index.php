<?php
//TODO переделать ошибки в исключения
//TODO использовать set_exception_handler
try
{
    require_once '../_Common/router.php';
}
catch (Exception $Exception)
{
    $errorData = json_encode(['status' => 'error', 'message' => $Exception->getMessage()]);
    //если информацию об ошибке не удалось преобразовать в JSON, то просто выводим сообщение о какой-то ошибке
    if (!$errorData) $errorData = json_encode(['status' => 'error']);
    echo $errorData;
}
