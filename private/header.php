<!DOCTYPE html>
<html dir="ltr" lang="bg">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <title><?php echo isset($page_title) ? $page_title : ''; ?></title>
        <link rel="stylesheet" href="css/main.css?v=<?php echo APP_VERSION; ?>">
        <script src="https://www.google.com/recaptcha/api.js" async defer></script>
    </head>
    <body>
        <header>
            <div id="header-desktop">
                <div>
                    <nav>
                        <ul id="header-nav">
                            <li><a href="/" class="<?php echo isset($class_active_home) && $class_active_home ? 'active' : ''; ?>">Начало</a></li>
                            <li><a href="/add.php" class="<?php echo isset($class_active_add) && $class_active_add ? 'active' : ''; ?>">Добави сайт безплатно</a></li>
                            <li><a href="/contacts.php" class="<?php echo isset($class_active_contacts) && $class_active_contacts ? 'active' : ''; ?>">Контакти</a></li>
                        </ul>
                    </nav>
                </div>
                <div id="logo-container"><!--<img src="/images/logo.svg">--></div>
                <?php
                if (!array_key_exists('loggedin', $_SESSION) || $_SESSION['loggedin'] !== 1)
                {
                ?>
                    <div id="login-register-desktop-container"><img src="/images/user.svg"> <a href="#" class="login-popup">Вход</a> / <a href="#" class="register-popup">Регистрация</a></div>
                <?php
                }
                else
                {
                ?>
                    <div id="login-register-desktop-container"><img src="/images/user.svg"> <a href="/profile.php">Профил</a> / <a href="/auth.php?l=1">Изход</a></div>
                <?php
                }
                ?>
            </div>
            <div id="header-mobile">
                <nav>
                    <ul id="header-nav-mobile">
                        <li><a href="/" class="<?php echo isset($class_active_home) && $class_active_home ? 'active' : ''; ?>">Начало</a></li>
                        <li><a href="/add.php" class="<?php echo isset($class_active_add) && $class_active_add ? 'active' : ''; ?>">Добави сайт безплатно</a></li>
                        <li><a href="/contacts.php" class="<?php echo isset($class_active_contacts) && $class_active_contacts ? 'active' : ''; ?>">Контакти</a></li>
                        <?php
                        if (!array_key_exists('loggedin', $_SESSION) || $_SESSION['loggedin'] !== 1)
                        {
                        ?>
                        <li><a href="#" class="login-popup">Вход</a></li>
                        <li><a href="#" class="register-popup">Регистрация</a></li>
                        <?php
                        }
                        else
                        {
                        ?>
                        <li><a href="/profile.php">Профил</a></li>
                        <li><a href="/auth.php?l=1">Изход</a></li>
                        <?php
                        }
                        ?>
                    </ul>
                </nav>
            </div>
            <div id="nav-icon1">
                <span></span>
                <span></span>
                <span></span>
            </div>
        </header>
        <section id="search">
            <div>
                <form method="get" action="/search.php">
                    <input type="text" name="n" placeholder="Търси по име, продукт или услуга">
                    <?php
                    $header_categories = $pdo->query("SELECT * FROM categories ORDER BY Name ASC");
                    ?>
                    <select name="c">
                        <option value="0">Всички категории</option>
                        <?php
                        foreach($header_categories as $c)
                        {
                            echo '<option value="' . $c['Id'] . '">' . $c['Name'] . '</option>';
                        }
                        ?>
                    </select>
                    <a href=""><img src="images/search.svg"> Търси</a>
                </form>
            </div>
        </section>
        <?php
        if (array_key_exists('auth', $_SESSION) && $_SESSION['auth']['has_error'])
        {
        ?>
            <div id="index-error-container">
                <div>
                    <b>Грешка при <?php echo $_SESSION['auth']['type'] == 'login' ? 'вход' : 'регистрация'; ?>:</b><br>
                    <?php
                    foreach ($_SESSION['auth']['messages'] as $e)
                    {
                        echo $e . '<br>';
                    }
                    ?>
                </div>
            </div>
        <?php
        }
        else if (array_key_exists('auth', $_SESSION) && ($_SESSION['auth']['has_success']))
        {
        ?>
            <div id="index-success-container">
                <div>
                    <b><?php echo $_SESSION['auth']['type'] == 'login' ? 'Успешен вход' : 'Регистрацията е успешна'; ?></b>
                </div>
            </div>
        <?php
        }

        unset($_SESSION['auth']);
        ?>