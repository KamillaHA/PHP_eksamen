<?php

function _($text) {
    echo htmlspecialchars($text ?? '', ENT_QUOTES, 'UTF-8');
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
    if (!isset($_POST['comment_text'])) {
        throw new Exception("Comment text is required", 400);
    }
    
    $commentText = trim($_POST['comment_text']);
    
    if (empty($commentText)) {
        throw new Exception("Comment text cannot be empty", 400);
    }

    // Fjern potentielt farlig HTML
    $commentText = strip_tags($commentText);
    
    $len = strlen($commentText);

    if ($len < commentMinLength || $len > commentMaxLength) {
        throw new Exception("Comment must be between " . commentMinLength . " and " . commentMaxLength . " characters", 400);
    }
    return $commentText;
}

define("postMinLength", 1);
define("postMaxLength", 300);
function _validatePost() {
    if (!isset($_POST['post_message'])) {
        throw new Exception("Message is required", 400);
    }
    
    $postMessage = trim($_POST['post_message']);
    
    if (empty($postMessage)) {
        throw new Exception("Message cannot be empty", 400);
    }
    
    // Fjern HTML (konsistent med validateCommentText)
    $postMessage = strip_tags($postMessage);
    
    $len = strlen($postMessage);

    if ($len < postMinLength || $len > postMaxLength) {
        throw new Exception("Message must be between " . postMinLength . " and " . postMaxLength . " characters", 400);
    }
    return $postMessage;
}

define("pkMinLength", 1);
define("pkMaxLength", 50);
function _validatePk($fieldName) {
    if (!isset($_POST[$fieldName])) {
        throw new Exception("Primary key field '$fieldName' is required", 400);
    }
    
    $pk = trim($_POST[$fieldName]);
    
    if (empty($pk)) {
        throw new Exception("Primary key cannot be empty", 400);
    }
    
    $len = strlen($pk);
    if ($len < pkMinLength) {
        throw new Exception("Primary key must be at least " . pkMinLength . " characters", 400);
    } else if ($len > pkMaxLength) {
        throw new Exception("Primary key must be at most " . pkMaxLength . " characters", 400);
    }
    
    // Tjek at det kun er alfanumerisk
    if (!ctype_alnum($pk)) {
        throw new Exception("Primary key must contain only letters and numbers", 400);
    }
    
    return $pk;
}

define("usernameMinLength", 3);
define("usernameMaxLength", 20);
function _validateUsername() {
    if (!isset($_POST['user_username'])) {
        throw new Exception("Username is required", 400);
    }
    
    $username = trim($_POST['user_username']);
    
    if (empty($username)) {
        throw new Exception("Username cannot be empty", 400);
    }
    
    $len = strlen($username);
    if ($len < usernameMinLength || $len > usernameMaxLength) {
        throw new Exception("Username must be between " . usernameMinLength . " and " . usernameMaxLength . " characters", 400);
    }
    
    // Kun tilladt tegn
    if (!preg_match('/^[a-zA-Z0-9_]+$/', $username)) {
        throw new Exception("Username can only contain letters, numbers and underscores", 400);
    }
    
    // Starter med bogstav
    if (!ctype_alpha($username[0])) {
        throw new Exception("Username must start with a letter", 400);
    }
    
    return $username;
}

define("emailMinLength", 6);
define("emailMaxLength", 100);
function _validateEmail() {
    if (!isset($_POST['user_email'])) {
        throw new Exception("Email is required", 400);
    }
    
    $email = trim($_POST['user_email']);
    
    if (empty($email)) {
        throw new Exception("Email cannot be empty", 400);
    }
    
    // Tjek længde
    $len = strlen($email);
    if ($len < emailMinLength) {
        throw new Exception("Email must be at least " . emailMinLength . " characters", 400);
    } else if ($len > emailMaxLength) {
        throw new Exception("Email must be at most " . emailMaxLength . " characters", 400);
    }
    
    // Valider email format
    if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        throw new Exception("Email must be a valid email address", 400);
    }
    
    // Normaliser email til lowercase
    $email = strtolower($email);
    
    return $email;
}

define("passwordMinLength", 6);
define("passwordMaxLength", 50);
function _validatePassword() {
    if (!isset($_POST['user_password'])) {
        throw new Exception("Password is required", 400);
    }
    
    $password = $_POST['user_password'];
    
    if (empty($password)) {
        throw new Exception("Password cannot be empty", 400);
    }
    
    $len = strlen($password);
    if ($len < passwordMinLength) {
        throw new Exception("Password must be at least " . passwordMinLength . " characters", 400);
    } else if ($len > passwordMaxLength) {
        throw new Exception("Password must be at most " . passwordMaxLength . " characters", 400);
    }
    
    return $password;
}

define("fullNameMinLength", 3);
define("fullNameMaxLength", 20);
function _validateFullName() {
    if (!isset($_POST['user_full_name'])) {
        throw new Exception("Full name is required", 400);
    }
    
    $full_name = trim($_POST['user_full_name']);
    
    if (empty($full_name)) {
        throw new Exception("Full name cannot be empty", 400);
    }
    
    $len = strlen($full_name);
    if ($len < fullNameMinLength) {
        throw new Exception("Full name must be at least " . fullNameMinLength . " characters", 400);
    } else if ($len > fullNameMaxLength) {
        throw new Exception("Full name must be at most " . fullNameMaxLength . " characters", 400);
    }
    
    // Kun tilladte tegn (bogstaver, mellemrum og bindestreger)
    if (!preg_match('/^[a-zA-ZæøåÆØÅ\s\-]+$/', $full_name)) {
        throw new Exception("Full name can only contain letters, spaces and hyphens", 400);
    }
    
    // Tjek for dobbelte mellemrum
    if (preg_match('/\s{2,}/', $full_name)) {
        throw new Exception("Full name cannot contain multiple consecutive spaces", 400);
    }
    
    // Normaliser mellemrum
    $full_name = preg_replace('/\s+/', ' ', $full_name);
    
    return $full_name;
}