<?php
require_once __DIR__ . "/../models/UserModel.php";

class AuthController
{
    public static function login(): void
    {
        session_start();
        require_once __DIR__ . "/../../private/x.php";

        try {
            $email    = _validateEmail();
            $password = _validatePassword();

            $user = UserModel::findByEmail($email);

            if (!$user || !password_verify($password, $user["user_password"])) {
                throw new Exception("Invalid credentials", 401);
            }

            unset($user["user_password"]);
            $_SESSION["user"] = $user;

            echo '<mixhtml mix-redirect="/home"></mixhtml>';
            exit;

        } catch (Exception $e) {
            echo '<mixhtml mix-redirect="/login?message=' . urlencode($e->getMessage()) . '"></mixhtml>';
            exit;
        }
    }

    public static function logout(): void
    {
        session_start();
        session_destroy();
        header("Location: /");
        exit;
    }
}
