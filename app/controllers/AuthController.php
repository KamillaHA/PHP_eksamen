<?php

// Loader UserModel-klassen (så vi kan bruge findByEmail() og create())
require_once __DIR__ . "/../models/UserModel.php";

class AuthController
{
    public static function signup(): void
    {
        // Starter en session så vi kan bruge $_SESSION (også selvom signup her ikke gemmer noget i sessionen)
        session_start();
        
        // Indlæser fælles hjælpefunktioner (fx _validateEmail(), _noCache(), _() osv.)
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

            // 5) Redirect efter succes (PRG-pattern: Post/Redirect/Get)
            // login=1 kan bruges til at vise en "Du er oprettet" besked på forsiden
            header("Location: /?login=1");
            exit;

        } catch (Exception $e) {
            header("Location: /?popup=signup&message=" . urlencode($e->getMessage()));
            exit;
        }
    }

    public static function login(): void
    {
        // Starter session, fordi vi skal gemme user i $_SESSION
        session_start();

        // Indlæser validerings- og hjælpefunktioner
        require_once __DIR__ . "/../../private/x.php";

        try {

            // 1) Valider input fra login-formen
            $email    = _validateEmail();
            $password = _validatePassword();

            // 2) Find bruger via email
            $user = UserModel::findByEmail($email);

            if (!$user || !password_verify($password, $user['user_password'])) {
                throw new Exception("Wrong email or password");
            }

            unset($user['user_password']);
            $_SESSION['user'] = $user;

            // 6) Redirect til beskyttet side /home efter login
            header("Location: /home");
            exit;

        } catch (Exception $e) {
            header("Location: /?popup=login&message=" . urlencode("Wrong email or password"));
            exit;
        }
    }


    public static function logout(): void
    {
        // Starter session så vi kan rydde den korrekt
        session_start();

        // Indlæser hjælpefunktioner
        require_once __DIR__ . "/../../private/x.php";

        // Hvis _noCache findes, så send no-cache headers (mindsker risiko for at browser viser cached "logged in" sider)
        if (function_exists('_noCache')) {
            _noCache();
        }

        // 1) Ryd alle session-data i PHP (tømmer $_SESSION arrayet)
        $_SESSION = [];

        // 2) Hvis PHP bruger cookies til sessions, så slet session-cookien i browseren
        // Dette gør vi ved at sætte cookie med udløbstid i fortiden
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

        // 3) Ødelæg sessionen på serveren (invalidér session-id)
        session_destroy();

        // 4) Redirect til forsiden efter logout
        header("Location: /");
        exit;
    }
}