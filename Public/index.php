<?php
try {
    require '../_Common/router.php';
}
catch (Exception $Exception)
{
    echo json_encode($Exception->getMessage());
}

