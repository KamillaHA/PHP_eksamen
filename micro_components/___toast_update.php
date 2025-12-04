<?php
$message = isset($message) ? htmlspecialchars($message, ENT_QUOTES, 'UTF-8') : 'Updated!';
return "<div class='toast-update' mix-ttl='3000'>{$message}</div>";
?>