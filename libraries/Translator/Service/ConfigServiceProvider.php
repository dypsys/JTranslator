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
class ConfigServiceProvider implements ServiceProviderInterface
{
	/**
	 * Configuration object
	 *
	 * @var    Registry
	 * @since  1.0
	 */
	protected $config;

	/**
	 * Class constructor.
	 *
	 * @param   string  $path  Path to the config file.
	 *
	 * @since   1.0
	 * @throws  \RuntimeException
	 */
	public function __construct($path)
	{
		// Verify the configuration exists and is readable.
		if (!is_readable($path))
		{
			throw new \RuntimeException(sprintf('Configuration file %s does not exist or is unreadable.',$path));
		}

		// Load the configuration file into an object.
		$configObject = json_decode(file_get_contents($path));

		if ($configObject === null)
		{
			throw new \RuntimeException(sprintf('Unable to parse the configuration file %s.', $path));
		}

		$config = new Registry;

		$config->loadObject($configObject);

		$this->config = $config;
	}

	/**
	 * {@inheritdoc}
	 */
	public function register(Container $container)
	{
		$config = $this->config;

		$container->share(
			'config',
			function () use ($config)
			{
				return $config;
			}
		);
	}
}
