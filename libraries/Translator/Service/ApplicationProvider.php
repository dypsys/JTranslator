<?php
/**
 * @package		Translator
 * @copyright	Copyright Open Source Matters, Inc. All rights reserved.
 * @license     GNU General Public License version 2 or later; see LICENSE.txt
 * @author		Carles Serrats <carless@cesigrup.com> - http://www.cesigrup.com
 */

namespace Translator\Service;

use Joomla\DI\Container;
use Joomla\DI\ServiceProviderInterface;

use Translator\Application;

// no direct access
defined( '_JEXEC' ) or die( 'Restricted access' );

/**
 * Application service provider
 *
 * @since  1.0
 */
class ApplicationProvider implements ServiceProviderInterface
{
    /**
     * Application instance
     *
     * @var    Application
     * @since  1.0
     */
    private $app;

    /**
     * Constructor
     *
     * @param   Application  $app  Application instance
     *
     * @since   1.0
     */
    public function __construct(Application $app)
    {
        $this->app = $app;
    }

    /**
     * Registers the service provider with a DI container.
     *
     * @param   Container  $container  The DI container.
     *
     * @return  Container  Returns itself to support chaining.
     *
     * @since   1.0
     * @throws  \RuntimeException
     */
    public function register(Container $container)
    {
        $app = $this->app;

        $container->set('Translator\\Application',
            function () use ($app)
            {
                return $app;
            }, true, true
        );

        // Alias the application
        $container->alias('app', 'Translator\\Application');
    }
}