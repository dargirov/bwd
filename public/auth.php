<?php
session_start();

include_once '../private/config.php';

$pdo = new PDO('sqlite:../private/bgwebdir.db');

$error_messages = [];
$has_error_register = false;
$has_error_login = false;
$has_success_register = false;
$has_success_login = false;

$action = filter_input(INPUT_POST, 'action');
if ($action === 'login')
{
    $email = filter_input(INPUT_POST, 'email', FILTER_VALIDATE_EMAIL);
    $password = filter_input(INPUT_POST, 'password');

    if ($email !== false && mb_strlen($email) < 5)
    {
        $error_messages[] = 'Въведеният email адрес трябва да съдържа поне 5 символа';
        $has_error_login = true;
    }

    if ($email !== false && mb_strlen($email) > 50)
    {
        $error_messages[] = 'Въведеният email адрес не трябва да съдържа повече от 50 символа';
        $has_error_login = true;
    }

    if ($email === false)
    {
        $error_messages[] = 'Въведеният email адрес е невалиден';
        $has_error_login = true;
    }

    if (mb_strlen($password) < 5)
    {
        $error_messages[] = 'Въведената парола трябва да съдържа поне 5 символа';
        $has_error_login = true;
    }

    if (mb_strlen($password) > 100)
    {
        $error_messages[] = 'Въведената парола не трябва да съдържа повече от 100 символа';
        $has_error_login = true;
    }

    if (!$has_error_login)
    {
        $user_query = $pdo->prepare("SELECT * FROM Users WHERE Email = :email");
        $user_query->execute([':email' => $email]);
        $user = $user_query->fetch();
        if ($user === false || !is_array($user))
        {
            $error_messages[] = 'Въведеният email адрес не съществува в системата';
            $has_error_login = true;
        }
    }

    if (!$has_error_login)
    {
        $verify = password_verify($password, $user['Password']);
        if (!$verify)
        {
            $error_messages[] = 'Въведената парола е грешна';
            $has_error_login = true;
        }
        else
        {
            $_SESSION['loggedin'] = 1;
            $_SESSION['loggedin_email'] = $email;
            $_SESSION['loggedin_user_id'] = $user['Id'];
            $has_success_login = true;
        }
    }

    $_SESSION['auth']['messages'] = $error_messages;
    $_SESSION['auth']['has_error'] = $has_error_login;
    $_SESSION['auth']['has_success'] = $has_success_login;
    $_SESSION['auth']['type'] = 'login';
    header('Location: /');
    exit;
}
else if ($action === 'register')
{
    $email = filter_input(INPUT_POST, 'email', FILTER_VALIDATE_EMAIL);
    $password = filter_input(INPUT_POST, 'password');
    $password2 = filter_input(INPUT_POST, 'password2');
    $recaptcha = filter_input(INPUT_POST, 'g-recaptcha-response');

    if ($email !== false && mb_strlen($email) < 5)
    {
        $error_messages[] = 'Въведеният email адрес трябва да съдържа поне 5 символа';
        $has_error_register = true;
    }

    if ($email !== false && mb_strlen($email) > 50)
    {
        $error_messages[] = 'Въведеният email адрес не трябва да съдържа повече от 50 символа';
        $has_error_register = true;
    }

    if ($email === false)
    {
        $error_messages[] = 'Въведеният email адрес е невалиден';
        $has_error_register = true;
    }

    if (mb_strlen($password) < 5)
    {
        $error_messages[] = 'Въведената парола трябва да съдържа поне 5 символа';
        $has_error_register = true;
    }

    if (mb_strlen($password) > 100)
    {
        $error_messages[] = 'Въведената парола не трябва да съдържа повече от 100 символа';
        $has_error_register = true;
    }

    if ($password != $password2)
    {
        $error_messages[] = 'Въведените пароли не съвпадат';
        $has_error_register = true;
    }

    if (!$has_error_register)
    {
        $postdata = http_build_query([
            'secret' => RECAPTCHA_SECRET_KEY,
            'response' => $recaptcha]);
    
        $opts = ['http' => [
            'method' => 'POST',
            'content' => $postdata]];
    
        $context = stream_context_create($opts);
        $recaptcha_result = file_get_contents('https://www.google.com/recaptcha/api/siteverify', false, $context);
        $recaptcha_result_data = json_decode($recaptcha_result, true);
        if ($recaptcha_result_data['success'] !== true)
        {
            $error_messages[] = 'Грешна каптча';
            $has_error_register = true;
        }
    }

    if (!$has_error_register)
    {
        $userExists = $pdo->prepare("SELECT COUNT(*) AS n FROM Users WHERE Email = :email");
        $userExists->execute([':email' => $email]);
        if ($userExists->fetch()['n'] != 0)
        {
            $error_messages[] = 'Въведеният email адрес е вече регистриран';
            $has_error_register = true;
        }
    }

    if (!$has_error_register)
    {
        $hashed_password = password_hash($password, PASSWORD_ARGON2ID);
        $insert = $pdo->prepare("INSERT INTO Users
                (Email, Password, DateCreated)
                VALUES (:email, :password, datetime('now'))");
        $insert->bindParam(':email', $email);
        $insert->bindParam(':password', $hashed_password);
        $has_success_register = $insert->execute();
        if (!$has_success_register)
        {
            $error_messages[] = 'Възникна грешка при регистрация. Моля, опитайте пак по-късно.';
            $has_error_register = true;
        }
    }

    $_SESSION['auth']['messages'] = $error_messages;
    $_SESSION['auth']['has_error'] = $has_error_register;
    $_SESSION['auth']['has_success'] = $has_success_register;
    $_SESSION['auth']['type'] = 'register';
    header('Location: /');
    exit;
}

$l = filter_input(INPUT_GET, 'l', FILTER_VALIDATE_INT);
if ($l === 1 && array_key_exists('loggedin', $_SESSION))
{
    unset($_SESSION['loggedin']);
    unset($_SESSION['loggedin_email']);
    unset($_SESSION['loggedin_user_id']);
    header('Location: /');
    exit;
}