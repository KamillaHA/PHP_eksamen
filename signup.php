<?php
require_once __DIR__. "/components/_header.php"
?>



<form action="api/api-create-profile" method="POST">

    <h1>Signup</h1>

    <input type="text" name="user_username" value="test" placeholder="Username">
    <input type="text" name="user_full_name" value="test testing" placeholder="Full name">
    <input type="email" name="user_email" placeholder="Email">
    <input type="password" name="user_password" value="password" placeholder="Password">

    <button>
        Signup
    </button>

</form>


<?php
require_once __DIR__. "/components/_footer.php"
?>