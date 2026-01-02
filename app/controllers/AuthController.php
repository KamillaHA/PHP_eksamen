<?php
require_once __DIR__ . "/../models/UserModel.php";

class AuthController
{
    public static function signup(): void
    {
        session_start();
        require_once __DIR__ . "/../../private/x.php";

        try {
            // Først valider input
            $fullname = _validateFullName();
            $username = _validateUsername();
            $email = _validateEmail();
            $password = _validatePassword();

            // Tjek om email allerede findes i databasen (præ-validering)
            $existingUser = UserModel::findByEmail($email);
            if ($existingUser !== null) {
                throw new Exception("Email er allerede i brug");
            }

            $user = [
                ':pk'       => bin2hex(random_bytes(25)),
                ':fullname' => $fullname,
                ':username' => $username,
                ':email'    => $email,
                ':password' => password_hash($password, PASSWORD_DEFAULT),
            ];

            UserModel::create($user);

            echo '<mixhtml mix-redirect="/?login=1"></mixhtml>';
            exit;

        } catch (Exception $e) {
            echo '<mixhtml mix-redirect="/?message=' . urlencode($e->getMessage()) . '"></mixhtml>';
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

            // Altid samme fejlbesked uanset om email findes eller password er forkert
            // Dette forhindrer "user enumeration" (at afsløre hvilke emails der findes)
            if (!$user || !password_verify($password, $user["user_password"])) {
                throw new Exception("Forkert email eller password", 401);
            }

            unset($user["user_password"]);
            $_SESSION["user"] = $user;

            echo '<mixhtml mix-redirect="/home"></mixhtml>';
            exit;

        } catch (Exception $e) {
            // Log fejlen til server log (anbefales til debugging)
            error_log("Login fejl: " . $e->getMessage());
            
            // Send en generisk fejlbesked til brugeren
            $genericMessage = "Forkert email eller password";
            echo '<mixhtml mix-redirect="/login?message=' . urlencode($genericMessage) . '"></mixhtml>';
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

        session_destroy();
        header("Location: /");
        exit;
    }
}