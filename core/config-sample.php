<?php

define('ABSPATH', realpath(dirname(__FILE__) . '/..'));
define('ABSDIST', ABSPATH . "/dist");
define('DEBUGLOGFILE', ABSPATH . '/debug.log');

define('DEBUG', true);
define('DEBUG_LOG', true);

/* INSTALL BEGIN
define('LEMONADE_I', 'true');
define('SESSION_ID', "%1s");

define('DB_HOST', '%2s');
define('DB_PORT', '%3s');
define('DB_USER', '%4s');
define('DB_PASS', '%5s');
define('DB_NAME', '%6s');
define('DB_PREFIX', '%7s');
INSTALL END */
