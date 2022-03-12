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
        <title>Администрация - вход</title>
        <link href="/admin/css/bootstrap.min.css" rel="stylesheet">
        <script src="/admin/js/bootstrap.bundle.min.js"></script>
        <style>
        html,
        body {
            height: 100%;
        }

        body {
            display: flex;
            align-items: center;
            padding-top: 40px;
            padding-bottom: 40px;
            background-color: #f5f5f5;
        }

        .form-signin {
            width: 100%;
            max-width: 330px;
            padding: 15px;
            margin: auto;
            text-align: center;
        }

        .form-signin .checkbox {
            font-weight: 400;
        }

        .form-signin .form-floating:focus-within {
            z-index: 2;
        }

        .form-signin input[type="email"] {
            margin-bottom: -1px;
            border-bottom-right-radius: 0;
            border-bottom-left-radius: 0;
        }

        .form-signin input[type="password"] {
            margin-bottom: 10px;
            border-top-left-radius: 0;
            border-top-right-radius: 0;
        }
        </style>
    </head>
    <body>
        <main class="form-signin">
            <form method="post" action="/admin/">
                <img class="mb-4" src="/images/logo.png" alt="logo">
                <h1 class="h3 mb-3 fw-normal">Вход</h1>
                <div class="form-floating">
                    <input type="email" name="email" class="form-control" id="floatingInput" placeholder="name@example.com">
                    <label for="floatingInput">Email адрес</label>
                </div>
                <div class="form-floating">
                    <input type="password" name="password" class="form-control" id="floatingPassword" placeholder="Password">
                    <label for="floatingPassword">Парола</label>
                </div>
                <input type="submit" class="w-100 btn btn-lg btn-primary" value="Вход">
            </form>
        </main>
    </body>
</html>