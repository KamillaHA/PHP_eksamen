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
            // 1) Valider input fra POST (typisk fra signup-formen)
            // _validate* funktionerne bør trimme input, tjekke længde/format og kaste Exception ved fejl
            $fullname = _validateFullName();
            $username = _validateUsername();
            $email = _validateEmail();
            $password = _validatePassword();

            // 2) Præ-validering: tjek om email allerede findes i databasen
            // (hurtigere og mere brugervenligt end først at forsøge INSERT og fejle)
            $existingUser = UserModel::findByEmail($email);
            if ($existingUser !== null) {

                // Kaster fejl, som fanges i catch og sendes tilbage som message i URL
                throw new Exception("Email er allerede i brug");
            }

            // 3) Forbered data til INSERT
            // Vi bruger placeholders (':...') så UserModel kan bruge prepared statements (beskytter mod SQL injection)
            $user = [

                // Primær nøgle / id genereres som random bytes -> hex string
                ':pk'       => bin2hex(random_bytes(25)),

                // Navn, username og email kommer fra valideret input
                ':fullname' => $fullname,
                ':username' => $username,
                ':email'    => $email,

                // Password gemmes ALDRIG i klartekst:
                // password_hash() laver en sikker hash, så databasen ikke kan lække rå passwords
                ':password' => password_hash($password, PASSWORD_DEFAULT),
            ];

            // 4) Opret brugeren i databasen
            UserModel::create($user);

            // 5) Redirect efter succes (PRG-pattern: Post/Redirect/Get)
            // login=1 kan bruges til at vise en "Du er oprettet" besked på forsiden
            header("Location: /?login=1");
            exit;

        } catch (Exception $e) {
            // Ved fejl redirecter vi tilbage med en message i querystring
            // urlencode sikrer at beskeden ikke ødelægger URL'en
            header("Location: /?message=" . urlencode($e->getMessage()));
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

            // 3) Sikkerhed: samme fejlbesked uanset om email findes eller ej
            // Det forhindrer "user enumeration" (at angribere kan gætte hvilke emails der findes)
            if (!$user || !password_verify($password, $user["user_password"])) {

                // 401 er en fornuftig statuskode til auth-fejl (selvom vi redirecter)
                throw new Exception("Forkert email eller password", 401);
            }

            // 4) Fjern password-hash fra arrayet før vi gemmer brugeren i sessionen
            // (god praksis: minimér sensitiv data i session)
            unset($user["user_password"]);

            // 5) Gem bruger i session, så resten af appen kan kende den loggede ind bruger
            $_SESSION["user"] = $user;

            // 6) Redirect til beskyttet side /home efter login
            header("Location: /home");
            exit;

        } catch (Exception $e) {
            // Logger fejlen på serveren (kun til udvikler – ikke til brugeren)
            error_log("Login fejl: " . $e->getMessage());
            
            // Brugeren får en generisk fejl (igen for at undgå enumeration)
            $genericMessage = "Forkert email eller password";

            // Redirect tilbage til login-side med besked
            header("Location: /login?message=" . urlencode($genericMessage));
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