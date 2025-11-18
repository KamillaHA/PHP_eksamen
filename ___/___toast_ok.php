<?php

$msg = isset($message) ? htmlspecialchars($message, ENT_QUOTES, 'UTF-8') : 'OK';
return "<div class='toast-ok' mix-ttl='3000'>{$msg}</div>";