<?php
// ini_set('display_errors', 1);
// ini_set('display_startup_errors', 1);
// error_reporting(E_ALL);
error_reporting(0);
ini_set('display_errors', 0);

ini_set('session.gc_maxlifetime', 3600);
session_set_cookie_params(3600);

session_start();

define('PATH_TO_PRIVATE', '../private/');

include_once PATH_TO_PRIVATE . 'config.php';

$page = filter_input(INPUT_GET, 'page');
$acronym = null;

$page_components = explode('/', $page);
if (count($page_components) === 2)
{
    $page = $page_components[1];
}
else if (count($page_components) > 2)
{
    $page = $page_components[1];
    $acronym = $page_components[2];
}

$pdo = new PDO('sqlite:' . PATH_TO_PRIVATE . '/db/bgwebdir.db');
$add_header_footer = true;

switch ($page)
{
    case 'site':
        $page_name = 'website.php';
        if (mb_strlen($acronym) < 3 || mb_strlen($acronym) > 500)
        {
            $page_title = '404 страницата не е намерена';
            header('HTTP/1.0 404 Not Found');
            $page_name = '404.php';
        }
        else
        {
            $website_q = $pdo->prepare("SELECT * FROM Websites WHERE Acronym = :acronym");
            $website_q->execute([':acronym' => $acronym]);
            $website = $website_q->fetch();
            if ($website === false || !is_array($website))
            {
                $page_title = '404 страницата не е намерена';
                header('HTTP/1.0 404 Not Found');
                $page_name = '404.php';
            }
            else
            {
                $page_title = htmlspecialchars($website['Title']);
            }
        }

        break;
    case 'add':
        $page_title = 'Добави безплатно сайт и данни за контакт';
        $page_description = 'Безплатно добавяне на сайт, кратка информация и данни за контакт';
        $page_name = 'add.php';
        if (!array_key_exists('loggedin', $_SESSION) || $_SESSION['loggedin'] !== 1)
        {
            $page_name = 'add_login.php';
        }

        break;
    case 'category':
        $page_name = 'category.php';
        if (mb_strlen($acronym) < 3 || mb_strlen($acronym) > 500)
        {
            $page_title = '404 страницата не е намерена';
            header('HTTP/1.0 404 Not Found');
            $page_name = '404.php';
        }
        else
        {
            $category = $pdo->prepare("SELECT * FROM Categories WHERE Acronym = :acronym");
            $category->execute([':acronym' => $acronym]);
            $category_data = $category->fetch();
            if ($category_data === false)
            {
                $page_title = '404 страницата не е намерена';
                header('HTTP/1.0 404 Not Found');
                $page_name = '404.php';
            }
            else
            {
                $page_title = htmlspecialchars($category_data['Name']);
            }
        }

        break;
    case 'auth':
        $page_name = 'auth.php';
        $add_header_footer = false;
        break;
    case 'search':
        $page_title = 'Търсене';
        $page_name = 'search.php';
        break;
    case 'contacts':
        $page_title = 'За контакти';
        $page_name = 'contacts.php';
        $class_active_contacts = 'contacts.php';
        break;
    case 'profile':
        $page_title = 'Профил';
        $page_name = 'profile.php';

        if (!array_key_exists('loggedin', $_SESSION) || $_SESSION['loggedin'] !== 1)
        {
            $page_title = '404 страницата не е намерена';
            header('HTTP/1.0 404 Not Found');
            $page_name = '404.php';
        }
        else
        {
            $page_title = 'Профил - ' . $_SESSION['loggedin_email'];
        }

        break;
    case 'sitemap.xml':
        $page_name = 'sitemap.php';
        $add_header_footer = false;
        break;
    case 'robots.txt':
        $page_name = 'robots.php';
        $add_header_footer = false;
        break;
    default:
        $page_title = 'Каталог с web сайтове в България';
        $page_description = 'Списък с български сайтове и кратка информация. Безплатно добавяне.';
        $page_name = 'home.php';
        $class_active_home = true;
        if ($page !== null)
        {
            $page_title = '404 страницата не е намерена';
            header('HTTP/1.0 404 Not Found');
            $page_name = '404.php';
        }

        break;
}

if ($add_header_footer)
{
    include_once PATH_TO_PRIVATE . 'header.php';
}

include_once PATH_TO_PRIVATE . $page_name;

if ($add_header_footer)
{
    include_once PATH_TO_PRIVATE . 'footer.php';
}