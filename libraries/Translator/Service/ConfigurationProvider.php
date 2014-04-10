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
use Joomla\Registry\Registry;

// no direct access
defined( '_JEXEC' ) or die( 'Restricted access' );

/**
 * Configuration service provider
 *
 * @since  1.0
 */
class ConfigurationProvider implements ServiceProviderInterface
{
    /**
     * Configuration instance
     *
     * @var    Registry
     * @since  1.0
     */
    private $config;

    /**
     * Constructor.
     *
     * @param   Registry  $config  The config object.
     *
     * @since   1.0
     * @throws  \RuntimeException
     */
    public function __construct(Registry $config)
    {
        // Check for a custom configuration.
        $type = trim(getenv('TRANSLATOR_ENVIRONMENT'));

        $name = ($type) ? 'config.' . $type : 'config';

        // Set the configuration file path for the application.
        $file = JPATH_CONFIGURATION . '/' . $name . '.json';

        // Verify the configuration exists and is readable.
        if (!is_readable($file))
        {
            throw new \RuntimeException('Configuration file does not exist or is unreadable.');
        }

        // Load the configuration file into an object.
        $configObject = json_decode(file_get_contents($file));

        if ($configObject === null)
        {
            throw new \RuntimeException(sprintf('Unable to parse the configuration file %s.', $file));
        }

        $config->loadObject($configObject);

        defined('JDEBUG') || define('JDEBUG', ($config->get('debug.system') || $config->get('debug.database')));

        $this->config = $config;
    }

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
        $config = $this->config;

        $container->set('config',
            function () use ($config)
            {
                return $config;
            }, true, true
        );
    }
}