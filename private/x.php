<?php

function _($text) {
    echo htmlspecialchars($text);
}

function _noCache() {
    header("Cache-Control: no-store, no-cache, must-revalidate, max-age=0");
    header("Pragma: no-cache");
    header("Expires: 0");
    header('Clear-Site-Data: "cache", "cookies", "storage", "executionContexts"');
}

define("commentMinLength", 1);
define("commentMaxLength", 255);
function validateCommentText() {
    $commentText = trim($_POST['comment_text']);
    $len = strlen($commentText);

    if ($len < commentMinLength || $len > commentMaxLength) {
        throw new Exception("Comment must be between " . commentMinLength . " and " . commentMaxLength . " characters");
    }
    return $commentText;
}

define("postMinLength", 1);
define("postMaxLength", 300);
function _validatePost() {
    $postMessage = trim($_POST['post_message']);
    $len = strlen($postMessage);

    if ($len < postMinLength || $len > postMaxLength) {
        throw new Exception("Message must be between " . postMinLength . " and " . postMaxLength . " characters");
    }
    return $postMessage;
}


define("pkMinLength", 1);
define("pkMaxLength", 50);
function _validatePk($fieldName) {
    $pk = trim($_POST[$fieldName]);
    $len = strlen($pk);
    if ($len < pkMinLength) {
        throw new Exception("Primary key must be at least " . pkMinLength . " characters");
    } else if ($len > pkMaxLength) {
        throw new Exception("Primary key must be at most " . pkMaxLength . " characters");
    }
    return $pk;
}

define("usernameMinLength", 3);
define("usernameMaxLength", 20);
function _validateUsername() {
    $username = trim($_POST['user_username']);
    $len = strlen($username);
    if ($len < usernameMinLength || $len > usernameMaxLength) {
        throw new Exception("Username must be between " . usernameMinLength . " and " . usernameMaxLength . " characters");
    }
    return $username;
}

define("emailMinLength", 6);
define("emailMaxLength", 100);
function _validateEmail() {
    $email = trim($_POST['user_email']);
    $len = strlen($email);
    if ($len < emailMinLength) {
        throw new Exception("Email must be at least " . emailMinLength . " characters");
    } else if ($len > emailMaxLength) {
        throw new Exception("Email must be at most " . emailMaxLength . " characters");
    }
    return $email;
}

define("passwordMinLength", 6);
define("passwordMaxLength", 50);
function _validatePassword() {
    $password = $_POST['user_password'];
    $len = strlen($password);
    if ($len < passwordMinLength) {
        throw new Exception("Password must be at least " . passwordMinLength . " characters");
    } else if ($len > passwordMaxLength) {
        throw new Exception("Password must be at most " . passwordMaxLength . " characters");
    }
    return $password;
}

define("fullNameMinLength", 3);
define("fullNameMaxLength", 20);
function _validateFullName() {
    $full_name = trim($_POST['user_full_name']);
    $len = strlen($full_name);
    if ($len < fullNameMinLength) {
        throw new Exception("Full name must be at least " . fullNameMinLength . " characters");
    } else if ($len > fullNameMaxLength) {
        throw new Exception("Full name must be at most " . fullNameMaxLength . " characters");
    }
    return $full_name;
}
