<?php
namespace _Common;

//преобразование всех ошибок в исключения
function projectErrorHandler($errno, $errstr, $errfile, $errline)
{
    if (!error_reporting()) return true;
    throw new \ErrorException($errstr, $errno, "Error", $errfile, $errline);
}
set_error_handler("_Common\projectErrorHandler");

//установка собственного обратчика исключений на проект
/**
 * @param \Exception $Exception
 * @return bool
 */
function projectExceptionHandler($Exception)
{
    if (!error_reporting()) return true;
    $errorMsg="";
    if ($Exception instanceof \ErrorException)
    {
        $errorMsg=$Exception->getFile() .":" .$Exception->getLine() .". ";
    }
    $errorMsg .= $Exception->getMessage();

    //для AJAX возвращаем ошибку в виде JSON
    if (isset($_REQUEST['isAjax']) and $_REQUEST['isAjax'])
    {
        $errorData = json_encode(['status' => 'error', 'message' => $errorMsg]);
        //если информацию об ошибке не удалось преобразовать в JSON, то просто выводим сообщение о какой-то ошибке
        if (!$errorData) $errorData = json_encode(['status' => 'error']);
        echo $errorData;
    }

    //в остальных случаях возвращаем просто текст
    echo $errorMsg;
}
set_exception_handler('_Common\projectExceptionHandler');
