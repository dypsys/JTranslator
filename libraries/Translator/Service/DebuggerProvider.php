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
use Translator\Debug\TranslatorDebugger;

/**
 * Debug service provider
 *
 * @since  1.0
 */
class DebuggerProvider implements ServiceProviderInterface
{
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
        $container->set('Translator\\Debug\\TranslatorDebugger',
            function () use ($container)
            {
                return new TranslatorDebugger($container);
            }, true, true
        );

        // Alias the object
        $container->alias('debugger', 'Translator\\Debug\\TranslatorDebugger');
    }
}