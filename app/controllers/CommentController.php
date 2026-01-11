<?php

// Loader CommentModel, som håndterer database-logik for kommentarer
require_once __DIR__ . "/../models/CommentModel.php";
require_once __DIR__ . "/../models/PostModel.php";
require_once __DIR__ . "/../../private/x.php";

class CommentController
{
    public static function create(): void
    {
        // Sørger for at sessionen er startet
        if (session_status() === PHP_SESSION_NONE) {
            session_start();
        }

        // Kun loggede brugere må kommentere
        if (!isset($_SESSION['user'])) {
            http_response_code(401);
            exit;
        }
        
    // NYT EFTER AFLEVERING: vis errormessage (__post.php)
    try {

        // Indlæser fælles valideringsfunktioner
        require_once __DIR__ . "/../../private/x.php";

        // Valider input
        $postPk = _validatePk("post_pk");
        $text   = validateCommentText();

        // Opret kommentaren (MODEL)
        CommentModel::create([
            ":pk"   => bin2hex(random_bytes(25)),
            ":text" => $text,
            ":post" => $postPk,
            ":user" => $_SESSION["user"]["user_pk"]
        ]);

        // Find postens ejer (MODEL)
        $username = PostModel::getOwnerUsername($postPk);

        // Redirect
        header("Location: " . ($username ? "/$username/status/$postPk" : "/home"));
        exit;

        // NYT EFTER AFLEVERING: Vis error message (__post.php)
        } catch (Exception $e) {
            // Gem fejlbesked i session (flash)
            $_SESSION['error'] = $e->getMessage();

            // Bevar kontekst (bliv på samme post)
            $postPk = $_POST['post_pk'] ?? null;

            if ($postPk) {
                header("Location: /home?post=" . $postPk);
            } else {
                header("Location: /home");
            }
            exit;
        }
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

    require_once __DIR__ . "/../../private/x.php";

    // Valider comment_pk
    $commentPk = _validatePk("comment_pk");

    // Valider tekst
    $text = validateCommentText();

    // Find post_pk
    $postPk = CommentModel::getPostPkByComment($commentPk);

    // Opdater kommentar
    CommentModel::update($commentPk, $text);

    // Find username (KAN returnere null)
    $username = PostModel::getOwnerUsername($postPk);

    // Redirect
    if ($username) {
        header("Location: /{$username}/status/{$postPk}");
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

        require_once __DIR__ . "/../../private/x.php";

    // Valider comment_pk
    $commentPk = _validatePk("comment_pk");

    // Find post_pk (bruges til redirect)
    $postPk = CommentModel::getPostPkByComment($commentPk);

    // Soft delete kommentaren
    CommentModel::softDelete($commentPk);

    // Find postens ejer
    $username = PostModel::getOwnerUsername($postPk);

    // Redirect (if / else – tydeligt og sikkert)
    if ($username) {
        header("Location: /" . $username . "/status/" . $postPk);
    } else {
        header("Location: /home");
    }

    exit;
    }
}