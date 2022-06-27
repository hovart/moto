<?php
/**
 * install.conf.php file defines all needed constants and variables used in installation of module
 *
 * @author    Business Tech SARL <http://www.businesstech.fr/en/contact-us>
 * @copyright 2003-2015 Business Tech SARL
 */

require_once(dirname(__FILE__) . '/common.conf.php');

/* defines install library path */
define('_GSR_PATH_LIB_INSTALL', _GSR_PATH_LIB . 'install/');

/* defines installation sql file */
define('_GSR_INSTALL_SQL_FILE', 'install.sql'); /* comment if not use SQL */

/* defines uninstallation sql file */
define('_GSR_UNINSTALL_SQL_FILE', 'uninstall.sql'); /* comment if not use SQL */

/* defines constant for plug SQL install/uninstall debug */
define('_GSR_LOG_JAM_SQL', false); /* comment if not use SQL */

/* defines constant for plug CONFIG install/uninstall debug */
define('_GSR_LOG_JAM_CONFIG', false);