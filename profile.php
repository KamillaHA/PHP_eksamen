<?php
session_start();
require_once __DIR__."/_/_header.php";
$user = $_SESSION["user"];
?>

My profile here

<form action="api/api-update-profile" method="POST">
    <input type="text" value="A">
    <button>
        Update profile
    </button>
</form>

<form action="api/api-delete-profile" method="POST">
    <button>
        Delete profile
    </button>
</form>