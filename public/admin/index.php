<?php
session_start();

$email = filter_input(INPUT_POST, 'email');
$password = filter_input(INPUT_POST, 'password');

if ($email == 'argirov@outlook.com' && $password == 'Argirov4444')
{
    $_SESSION['admin_loggedin'] = 1;
    header('Location: /admin/home.php');
}

?>
<!DOCTYPE html>
<html>
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <title>Администрация</title>
    </head>
    <body>
        <form method="post" action="/admin/">
            <input type="email" name="email" placeholder="Email">
            <input type="password" name="password" placeholder="Парола">
            <input type="submit" value="Вход">
        </form>
    </body>
</html>