<?php

define('APP_VERSION', 1012);
define('RECAPTCHA_SITE_KEY', '6LetxrIeAAAAAJrGGuHfKw26AU9dfnzxY5TWbZQ8');
define('RECAPTCHA_SECRET_KEY', '6LetxrIeAAAAAH4dn7fwtOhKfksVsbWscineNISu');

define('IS_DEV', stristr(PHP_OS, 'WIN') !== false);
define('IS_PROD', !IS_DEV);