<?php
require_once __DIR__ . "/../models/UserModel.php";

class AuthController
{
    public static function signup(): void
    {
        session_start();
        require_once __DIR__ . "/../../private/x.php";

        try {
            _validateFullName();
            _validateUsername();
            _validateEmail();
            _validatePassword();

            UserModel::create([
                ':pk'       => bin2hex(random_bytes(25)),
                ':fullname' => $_POST['user_full_name'],
                ':username' => $_POST['user_username'],
                ':email'    => $_POST['user_email'],
                ':password' => password_hash($_POST['user_password'], PASSWORD_DEFAULT),
            ]);

            header("Location: /?login=1");
            exit;

        } catch (Exception $e) {
            header("Location: /?popup=signup&message=" . urlencode($e->getMessage()));
            exit;
        }
    }

    public static function login(): void
    {
        session_start();
        require_once __DIR__ . "/../../private/x.php";

        try {
            $email    = _validateEmail();
            $password = _validatePassword();

            $user = UserModel::findByEmail($email);

            if (!$user || !password_verify($password, $user['user_password'])) {
                throw new Exception("Wrong email or password");
            }

            unset($user['user_password']);
            $_SESSION['user'] = $user;

            header("Location: /home");
            exit;

        } catch (Exception $e) {
            header("Location: /?popup=login&message=" . urlencode("Wrong email or password"));
            exit;
        }
    }


    public static function logout(): void
    {
        session_start();
        require_once __DIR__ . "/../../private/x.php";
        if (function_exists('_noCache')) {
            _noCache();
        }

        $_SESSION = [];

        if (ini_get("session.use_cookies")) {
            $params = session_get_cookie_params();
            setcookie(
                session_name(),
                '',
                time() - 42000,
                $params["path"],
                $params["domain"],
                $params["secure"],
                $params["httponly"]
            );
        }

        session_destroy();
        header("Location: /");
        exit;
    }
}