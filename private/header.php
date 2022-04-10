<!DOCTYPE html>
<html dir="ltr" lang="bg">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <?php
        if (IS_PROD)
        {
            echo '<base href="http://bgwebdir.eu/">';
        }
        ?>

        <title><?php echo isset($page_title) && mb_strlen($page_title) ? htmlspecialchars($page_title) : ''; ?></title>
        <?php
        if (isset($page_description) && mb_strlen($page_description) > 0)
        {
            echo '<meta name="description" content="'. htmlspecialchars($page_description) . '">';
        }
        ?>

        <link rel="stylesheet" href="/css/main.css?v=<?php echo APP_VERSION; ?>">
        <script src="https://www.google.com/recaptcha/api.js?hl=bg" async defer></script>
    </head>
    <body>
        <?php
        /*
        https://colorhunt.co/palette/c6d57ed57e7ea2cdcdffe1af
        */
        ?>
        <header>
            <div id="header-desktop">
                <div id="logo-container"><a href="/"><img src="/images/logo.png"></a></div>
                <?php
                if (!array_key_exists('loggedin', $_SESSION) || $_SESSION['loggedin'] !== 1)
                {
                ?>
                    <div>
                        <a href="/add" class="btn btn-site-register"><img src="/images/add.svg" alt="Добави сайт"> Добави сайт безплатно</a>
                        <a href="#" class="btn btn-log-reg login-popup"><img src="/images/user.svg"> Вход</a>
                        <a href="#" class="btn btn-log-reg register-popup"><img src="/images/user.svg"> Регистрация</a>
                    </div>
                <?php
                }
                else
                {
                ?>
                    <div>
                        <a href="/add" class="btn btn-site-register"><img src="/images/add.svg" alt="Добави сайт"> Добави сайт безплатно</a>
                        <a href="/profile" class="btn btn-log-reg"><img src="/images/user.svg"> Профил</a>
                        <a href="/auth?l=1" class="btn btn-log-reg"><img src="/images/exit.svg">Изход</a>
                    </div>
                <?php
                }
                ?>
            </div>
            <div id="header-mobile">
                <nav>
                    <ul id="header-nav-mobile">
                        <li><a href="/" class="<?php echo isset($class_active_home) && $class_active_home ? 'active' : ''; ?>">Начало</a></li>
                        <li><a href="/add" class="<?php echo isset($class_active_add) && $class_active_add ? 'active' : ''; ?>">Добави сайт безплатно</a></li>
                        <li><a href="/contacts" class="<?php echo isset($class_active_contacts) && $class_active_contacts ? 'active' : ''; ?>">Контакти</a></li>
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
                        <li><a href="/profile">Профил</a></li>
                        <li><a href="/auth?l=1">Изход</a></li>
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
        <nav>
            <div>
                <ul id="header-nav">
                    <li><a href="/" class="<?php echo isset($class_active_home) && $class_active_home ? 'active' : ''; ?>">Начало</a></li>
                    <li><a href="/contacts" class="<?php echo isset($class_active_contacts) && $class_active_contacts ? 'active' : ''; ?>">Контакти</a></li>
                </ul>
                <div id="search">
                    <form method="get" action="/search">
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
                        <a href="">
                            <svg width="14px" height="14px" x="0px" y="0px" viewBox="0 0 487.95 487.95" style="enable-background:new 0 0 487.95 487.95;">
                                <g>
                                    <path d="M481.8,453l-140-140.1c27.6-33.1,44.2-75.4,44.2-121.6C386,85.9,299.5,0.2,193.1,0.2S0,86,0,191.4s86.5,191.1,192.9,191.1
                                        c45.2,0,86.8-15.5,119.8-41.4l140.5,140.5c8.2,8.2,20.4,8.2,28.6,0C490,473.4,490,461.2,481.8,453z M41,191.4
                                        c0-82.8,68.2-150.1,151.9-150.1s151.9,67.3,151.9,150.1s-68.2,150.1-151.9,150.1S41,274.1,41,191.4z"/>
                                </g>
                            </svg>
                            Търси
                        </a>
                    </form>
                </div>
            </div>
        </nav>
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