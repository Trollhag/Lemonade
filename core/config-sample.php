<?php

define('ABSPATH', realpath(dirname(__FILE__) . '/..'));
define('ABSDIST', ABSPATH . "/dist");
define('DEBUGLOGFILE', ABSPATH . '/debug.log');

define('DEBUG', true);
define('DEBUG_LOG', true);

/* INSTALL BEGIN
define('LEMONADE_I', true);
define('SESSION_ID', "%s");

define('DB_HOST', '%s');
define('DB_PORT', '%s');
define('DB_USER', '%s');
define('DB_PASS', '%s');
define('DB_NAME', '%s');
define('DB_PREFIX', '%s');
INSTALL END */
