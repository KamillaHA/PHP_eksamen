<?php

require_once __DIR__ . '/../controllers/AuthController.php';
AuthController::logout();

require_once __DIR__ . '/../../../private/x.php';
if (function_exists('_noCache')) {
    _noCache();
}

?>