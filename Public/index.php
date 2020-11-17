<?php
use _Common\Config;
use _Common\Router;

require_once '../_Common/ErrorHandler.php';

require_once '../_Common/Config.php';
Config::load();

require_once '../_Common/router.php';
Router::execute();
