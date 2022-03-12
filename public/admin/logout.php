<?php

session_start();

if (!array_key_exists('admin_loggedin', $_SESSION) || $_SESSION['admin_loggedin'] !== 1)
{
    header('Location: /');
    exit;
}

unset($_SESSION['admin_loggedin']);
header('Location: /admin/');
exit;