<?php
/**
 * @package		Translator
 * @copyright	Copyright Open Source Matters, Inc. All rights reserved.
 * @license     GNU General Public License version 2 or later; see LICENSE.txt
 * @author		Carles Serrats <carless@cesigrup.com> - http://www.cesigrup.com
 */

if (version_compare(PHP_VERSION, '5.3.10', '<')) {
    die('Your host needs to use PHP 5.3.10 or higher to run this version of Joomla! Translator');
}

define('_JEXEC', 1);

error_reporting(E_ALL);
ini_set('display_errors', 1);

define('JPATH_BASE', __DIR__);

require_once JPATH_BASE . '/includes/defines.php';

$app = new Translator\Application();
$app->execute();