<?php
/**
 * @package		Translator
 * @copyright	Copyright Open Source Matters, Inc. All rights reserved.
 * @license     GNU General Public License version 2 or later; see LICENSE.txt
 * @author		Carles Serrats <carless@cesigrup.com> - http://www.cesigrup.com
 */

namespace Translator\Controller;

use Joomla\DI\Container;
use Joomla\DI\ContainerAwareInterface;
use Joomla\Input\Input;

use Translator\View\AbstractTranslatorHtmlView;
use Translator\View\Renderer\TranslatorExtension;
use Translator\View\Renderer\RendererInterface;

/**
 * Abstract Controller class for the Translator Application
 *
 * @since  1.0
 */
abstract class AbstractTranslatorController implements ContainerAwareInterface
{
    /**
     * The default view for the app
     *
     * @var    string
     * @since  1.0
     */
    protected $defaultView = '';

    /**
     * The default layout for the app
     *
     * @var    string
     * @since  1.0
     */
    protected $defaultLayout = 'index';

    /**
     * The app being executed.
     *
     * @var    string
     * @since  1.0
     */
    protected $app;

    /**
     * @var  Container
     * @since  1.0
     */
    protected $container;

    /**
     * @var  \Joomla\View\AbstractHtmlView
     */
    protected $view;

    /**
     * @var  \Joomla\Model\AbstractModel
     */
    protected $model;

    /**
     * Constructor.
     *
     * @since   1.0
     */
    public function __construct()
    {
        // Detect the App name
        if (empty($this->app))
        {
            // Get the fully qualified class name for the current object
            $fqcn = (get_class($this));

            // Strip the base app namespace off
            $className = str_replace('App\\', '', $fqcn);

            // Explode the remaining name into an array
            $classArray = explode('\\', $className);

            // Set the app as the first object in this array
            $this->app = $classArray[0];
        }
    }

    /**
     * Initialize the controller.
     *
     * This will set up default model and view classes.
     *
     * @return  $this
     *
     * @since   1.0
     * @throws  \RuntimeException
     */
    public function initialize()
    {
        // Get the input
        /* @type Input $input */
        $input = $this->container->get('app')->input;

        // Get some data from the request
        $viewName   = $input->getWord('view', $this->defaultView);
        $viewFormat = $input->getWord('format', 'html');
        $layoutName = $input->getCmd('layout', $this->defaultLayout);

        if (!$viewName)
        {
            $parts = explode('\\', get_class($this));
            $viewName = strtolower($parts[count($parts) - 1]);
        }

        $base = '\\App\\' . $this->app;

        $viewClass  = $base . '\\View\\' . ucfirst($viewName) . '\\' . ucfirst($viewName) . ucfirst($viewFormat) . 'View';
        $modelClass = $base . '\\Model\\' . ucfirst($viewName) . 'Model';

        // If a model doesn't exist for our view, revert to the default model
        if (!class_exists($modelClass))
        {
            $modelClass = $base . '\\Model\\DefaultModel';

            // If there still isn't a class, panic.
            if (!class_exists($modelClass))
            {
                throw new \RuntimeException(
                    sprintf(
                        'No model found for view %s or a default model for %s', $viewName, $this->app
                    )
                );
            }
        }

        // Make sure the view class exists, otherwise revert to the default
        if (!class_exists($viewClass))
        {
            $viewClass = '\\Translator\\View\\TranslatorDefaultView';
        }

        // Register the templates paths for the view
        $paths = array();

        $sub = ('php' == $this->container->get('app')->get('renderer.type')) ? '/php' : '';

        $path = JPATH_TEMPLATES . $sub . '/' . strtolower($this->app);

        if (is_dir($path))
        {
            $paths[] = $path;
        }

        // @$this->model = $this->container->buildObject($modelClass);
        $this->container->get('app')->mark('AbstractTranslatorController initialize()');
        $this->container->get('app')->mark('db');
        $this->model = new $modelClass($this->container->get('db'), $this->container->get('app')->input);

        // Create the view
        /* @type AbstractTranslatorHtmlView $view */
        $this->view = new $viewClass(
            $this->model,
            $this->fetchRenderer($paths)
        );

        $this->view->setLayout($viewName . '.' . $layoutName);

        return $this;
    }

    /**
     * Execute the controller.
     *
     * This is a generic method to execute and render a view and is not suitable for tasks.
     *
     * @return  string
     *
     * @since   1.0
     */
    public function execute()
    {
        try
        {
            // Render our view.
            $contents = $this->view->render();
        }
        catch (\Exception $e)
        {
            $contents = $this->container->get('app')->getDebugger()->renderException($e);
        }

        return $contents;
    }

    /**
     * Returns the current app
     *
     * @return  string  The app being executed.
     *
     * @since   1.0
     */
    public function getApp()
    {
        return $this->app;
    }

    /**
     * Get a renderer object.
     *
     * @param   string|array  $templatesPaths  A path or an array of paths where to look for templates.
     *
     * @return  RendererInterface
     *
     * @throws  \RuntimeException
     * @since   1.0
     */
    protected function fetchRenderer($templatesPaths)
    {
        /* @type \Translator\Application $application */
        $application = $this->container->get('app');

        // $rendererName = $application->get('renderer.type');
        $rendererName = $this->container->get('config')->get('renderer')->type;

        $className = 'Translator\\View\\Renderer\\' . ucfirst($rendererName);

        // Check if the specified renderer exists in the application
        if (false == class_exists($className))
        {
            throw new \RuntimeException(sprintf('Invalid renderer: %s', $rendererName));
        }

        $config = array();

        switch ($rendererName)
        {
            case 'twig':
                $config['templates_base_dir'] = JPATH_TEMPLATES;
                $config['environment']['debug'] = JDEBUG ? true : false;

                break;

            case 'mustache':
                $config['templates_base_dir'] = JPATH_TEMPLATES;

                // . '/partials';
                $config['partials_base_dir'] = JPATH_TEMPLATES;

                $config['environment']['debug'] = JDEBUG ? true : false;

                break;

            case 'php':
                $config['templates_base_dir'] = JPATH_TEMPLATES . '/php';
                $config['debug'] = JDEBUG ? true : false;

                break;

            default:
                throw new \RuntimeException('Unsupported renderer: ' . $rendererName);
                break;
        }

        // Load the renderer.
        /* @type RendererInterface $renderer */
        $renderer = new $className($config);

        // Register tracker's extension.
        $renderer->addExtension(new TranslatorExtension($this->container));

        // Register additional paths.
        if (!empty($templatesPaths))
        {
            $renderer->setTemplatesPaths($templatesPaths, true);
        }

        // TODO: $renderer->set('user', $application->getUser());

        // Retrieve and clear the message queue
        $renderer->set('flashBag', $application->getMessageQueue());
        $application->clearMessageQueue();

        // Add build commit if available
        if (file_exists(JPATH_ROOT . '/current_SHA'))
        {
            $data = trim(file_get_contents(JPATH_ROOT . '/current_SHA'));
            $renderer->set('buildSHA', $data);
        }
        else
        {
            $renderer->set('buildSHA', '');
        }

        return $renderer;
    }

    /**
     * Get the DI container.
     *
     * @return  Container
     *
     * @since   1.0
     *
     * @throws  \UnexpectedValueException May be thrown if the container has not been set.
     */
    public function getContainer()
    {
        return $this->container;
    }

    /**
     * Set the DI container.
     *
     * @param   Container  $container  The DI container.
     *
     * @return  mixed
     *
     * @since   1.0
     */
    public function setContainer(Container $container)
    {
        $this->container = $container;
    }
}