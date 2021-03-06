<?php
/**
 * @package		Translator
 * @copyright	Copyright Open Source Matters, Inc. All rights reserved.
 * @license     GNU General Public License version 2 or later; see LICENSE.txt
 * @author		Carles Serrats <carless@cesigrup.com> - http://www.cesigrup.com
 */

namespace Translator\Service;

use Joomla\Database\DatabaseDriver;
use Joomla\DI\Container;
use Joomla\DI\ServiceProviderInterface;

// no direct access
defined( '_JEXEC' ) or die( 'Restricted access' );

/**
 * Database service provider
 *
 * @since  1.0
 */
class DatabaseProvider implements ServiceProviderInterface
{
    /**
     * Registers the service provider with a DI container.
     *
     * @param   Container  $container  The DI container.
     *
     * @return  Container  Returns itself to support chaining.
     *
     * @since   1.0
     */
    public function register(Container $container)
    {
        $container->set('Joomla\\Database\\DatabaseDriver',
            function () use ($container)
            {
                $app = $container->get('app');

                $options = array(
                    'driver' => $app->get('database.driver'),
                    'host' => $app->get('database.host'),
                    'user' => $app->get('database.user'),
                    'password' => $app->get('database.password'),
                    'database' => $app->get('database.name'),
                    'prefix' => $app->get('database.prefix')
                );

                $db = DatabaseDriver::getInstance($options);
                $db->setDebug($app->get('debug.database', false));

                return $db;
            }, true, true
        );

        // Alias the database
        $container->alias('db', 'Joomla\\Database\\DatabaseDriver');
    }
}