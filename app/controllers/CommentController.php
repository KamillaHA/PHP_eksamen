<?php

// Loader CommentModel, som håndterer database-logik for kommentarer
require_once __DIR__ . "/../models/CommentModel.php";

class CommentController
{
    public static function create(): void
    {
        // Sørger for at sessionen er startet (undgår fejl hvis den allerede kører)
        if (session_status() === PHP_SESSION_NONE) {
            session_start();
        }

        // Tjek om brugeren er logget ind
        // Hvis ikke: returner 401 (Unauthorized) og stop eksekvering
        if (!isset($_SESSION['user'])) {
            http_response_code(401);
            echo 'You have to be logged in to create a comment';
            exit;
        }

        // Indlæser fælles validerings- og hjælpefunktioner
        require_once __DIR__ . "/../../private/x.php";

        // Validerer post_pk fra POST (sikrer korrekt format og forhindrer manipulation)
        $postPk = _validatePk("post_pk");
        
        // Opretter kommentaren i databasen
        // Data sendes som placeholders for at sikre prepared statements (SQL injection-beskyttelse)
        CommentModel::create([

            // Unik primær nøgle til kommentaren
            ":pk"   => bin2hex(random_bytes(25)),

            // Kommentarens tekst (valideret og trimmet)
            ":text" => validateCommentText(),

            // Reference til posten kommentaren hører til
            ":post" => $postPk,

            // Reference til den bruger der har oprettet kommentaren
            ":user" => $_SESSION["user"]["user_pk"]
        ]);

        // Efter oprettelse skal vi redirecte tilbage til den post kommentaren tilhører
        // For at bygge korrekt URL henter vi postens ejer (brugernavn)
        require __DIR__ . '/../../private/db.php';
        $stmt = $_db->prepare("
            SELECT users.user_username 
            FROM posts 
            JOIN users ON posts.post_user_fk = users.user_pk 
            WHERE posts.post_pk = ?
        ");
        $stmt->execute([$postPk]);
        $post = $stmt->fetch(PDO::FETCH_ASSOC);
        
        // Hvis posten findes, redirect til postens side
        if ($post) {
            header("Location: /" . $post['user_username'] . "/status/" . $postPk);
        } else {
            // Fallback hvis noget går galt
            header("Location: /home");
        }
        exit;
    }

    public static function update(): void
    {
        // Sørger for aktiv session
        if (session_status() === PHP_SESSION_NONE) {
            session_start();
        }

        // Brugeren skal være logget ind for at redigere en kommentar
        if (!isset($_SESSION['user'])) {
            http_response_code(401);
            echo 'You have to be logged in to update a comment';
            exit;
        }

        // Indlæser hjælpefunktioner
        require_once __DIR__ . "/../../private/x.php";

        // Validerer kommentarens PK og den nye tekst
        $commentPk = _validatePk("comment_pk");
        $text      = validateCommentText();

        // Finder hvilken post kommentaren hører til (bruges til redirect)
        $postPk = CommentModel::getPostPkByComment($commentPk);

        // Opdaterer kommentaren i databasen
        CommentModel::update($commentPk, $text);

        // Henter igen postens ejer for korrekt redirect
        require __DIR__ . '/../../private/db.php';
        $stmt = $_db->prepare("
            SELECT users.user_username 
            FROM posts 
            JOIN users ON posts.post_user_fk = users.user_pk 
            WHERE posts.post_pk = ?
        ");
        $stmt->execute([$postPk]);
        $post = $stmt->fetch(PDO::FETCH_ASSOC);
        
        // Redirect tilbage til posten
        if ($post) {
            header("Location: /" . $post['user_username'] . "/status/" . $postPk);
        } else {
            header("Location: /home");
        }
        exit;
    }

    public static function delete(): void
    {
        // Sørger for aktiv session
        if (session_status() === PHP_SESSION_NONE) {
            session_start();
        }

        // Brugeren skal være logget ind for at slette en kommentar
        if (!isset($_SESSION['user'])) {
            http_response_code(401);
            echo 'You have to be logged in to delete a comment';
            exit;
        }

        // Indlæser hjælpefunktioner
        require_once __DIR__ . "/../../private/x.php";

        // Validerer både kommentarens og postens PK
        $commentPk = _validatePk("comment_pk");
        $postPk    = _validatePk("post_pk");

        // Soft delete: kommentaren markeres som slettet i databasen
        // (bevares typisk af hensyn til historik, relationer eller moderation)
        CommentModel::softDelete($commentPk);

        // Henter postens ejer for redirect
        require __DIR__ . '/../../private/db.php';
        $stmt = $_db->prepare("
            SELECT users.user_username 
            FROM posts 
            JOIN users ON posts.post_user_fk = users.user_pk 
            WHERE posts.post_pk = ?
        ");
        $stmt->execute([$postPk]);
        $post = $stmt->fetch(PDO::FETCH_ASSOC);
        
        // Redirect tilbage til posten eller fallback til home
        if ($post) {
            header("Location: /" . $post['user_username'] . "/status/" . $postPk);
        } else {
            header("Location: /home");
        }
        exit;
    }
}