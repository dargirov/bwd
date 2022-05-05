<?php

define('APP_VERSION', 1012);
define('ENABLE_CSS_JS_MINIFICATION', true);
define('RECAPTCHA_SITE_KEY', '*');
define('RECAPTCHA_SECRET_KEY', '*');

define('IS_DEV', stristr(PHP_OS, 'WIN') !== false);
define('IS_PROD', !IS_DEV);

define('SITES_PER_PAGE', 10);