<?php
require_once __DIR__ . "/../models/UserModel.php";

class ProfileController
{
    public static function update(): void
    {
        session_start();
        require_once __DIR__ . "/../../private/x.php";

        $user = $_SESSION["user"] ?? null;
        if (!$user) {
            header("Location: /login");
            exit;
        }

        UserModel::update($user["user_pk"], [
            "username" => _validateUsername(),
            "fullname" => _validateFullName(),
            "email"    => _validateEmail()
        ]);

        $_SESSION["user"] = UserModel::findByPk($user["user_pk"]);

        header("Location: /profile");
        exit;
    }

    public static function delete(): void
    {
        session_start();
        UserModel::delete($_SESSION["user"]["user_pk"]);
        session_destroy();
        header("Location: /login");
        exit;
    }
}
