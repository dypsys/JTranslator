<?php
/**
 * @package		Translator
 * @copyright	Copyright Open Source Matters, Inc. All rights reserved.
 * @license     GNU General Public License version 2 or later; see LICENSE.txt
 * @author		Carles Serrats <carless@cesigrup.com> - http://www.cesigrup.com
 */

$parts = explode(DIRECTORY_SEPARATOR, JPATH_BASE);

//Defines.
define('JPATH_ROOT',            implode(DIRECTORY_SEPARATOR, $parts));
define('JPATH_SITE',            JPATH_ROOT);
define('JPATH_THEMES',          JPATH_BASE . '/themes');
define('JPATH_TEMPLATES',       JPATH_ROOT . '/templates');
define('JPATH_LIBS',            JPATH_ROOT . '/libraries');
define('JPATH_CACHE',           JPATH_BASE . '/cache');

define('JPATH_TRANSLATOR',      JPATH_LIBS . '/Translator');
define('JPATH_CONFIGURATION',   JPATH_TRANSLATOR . '/Config');
define('JPATH_SETUP',           JPATH_TRANSLATOR . '/Setup');

// get the auto loader
require_once JPATH_LIBS . '/autoload.php';