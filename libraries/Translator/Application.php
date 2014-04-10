<?php
/**
 * @package		Translator
 * @copyright	Copyright Open Source Matters, Inc. All rights reserved.
 * @license     GNU General Public License version 2 or later; see LICENSE.txt
 * @author		Carles Serrats <carless@cesigrup.com> - http://www.cesigrup.com
 */

namespace Translator;

use Joomla\Application\AbstractWebApplication;
use Joomla\DI\Container;
use Joomla\Language\Language;
use Joomla\Registry\Registry;

use Symfony\Component\HttpFoundation\Session\Session;

use Translator\Authentication\Exception\AuthenticationException;
use Translator\Authentication\TranslatorUser;
use Translator\Authentication\User;
use Translator\Controller\AbstractTranslatorController;
use Translator\Debug\TranslatorDebugger;
use Translator\Router\Exception\RoutingException;
use Translator\Router\TranslatorRouter;

// no direct access
defined( '_JEXEC' ) or die( 'Restricted access' );

class Application extends AbstractWebApplication {

    /**
     * DI Container
     *
     * @var    Container
     * @since  1.0
     */
    protected $container;

    /**
     * The default theme.
     *
     * @var    string
     * @since  1.0
     */
    protected $theme = null;

    /**
     * A session object.
     *
     * @var    Session
     * @since  1.0
     * @note   This has been created to avoid a conflict with the $session member var from the parent class.
     */
    private $newSession = null;

    /**
     * A language object.
     *
     * @var    Language
     * @since  1.0
     */
    private $language = null;

    /**
     * A user object.
     *
     * @var    User
     * @since  1.0
     */
    private $user= null;

    /**
     * Class constructor.
     *
     * @since   1.0
     */
    public function __construct()
    {
        // Run the parent constructor
        parent::__construct();

        $this->container = new \Joomla\DI\Container;

        $this->container
            ->registerServiceProvider(new \Translator\Service\ApplicationProvider($this))
            ->registerServiceProvider(new \Translator\Service\ConfigurationProvider($this->config))
            ->registerServiceProvider(new \Translator\Service\DatabaseProvider)
            ->registerServiceProvider(new \Translator\Service\DebuggerProvider)
        ;
        // ->registerServiceProvider(new \Translator\Service\ConfigServiceProvider(JPATH_CONFIGURATION . '/config.json'))

        $this->getLanguage();
        $this->mark('Application started');
    }

    /**
     * Get a debugger object.
     *
     * @return  TranslatorDebugger
     *
     * @since   1.0
     */
    public function getDebugger()
    {
        return $this->container->get('debugger');
    }

    /**
     * Get the DI container.
     *
     * @return  Container
     *
     * @since   1.0
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
     * @return  $this  Method allows chaining
     *
     * @since   1.0
     */
    public function setContainer(Container $container)
    {
        $this->container = $container;

        return $this;
    }

    /**
     * Get a session object.
     *
     * @return  Session
     *
     * @since   1.0
     */
    public function getSession()
    {
        if (is_null($this->newSession))
        {
            $this->newSession = new Session;

            $this->newSession->start();

            $registry = $this->newSession->get('registry');

            if (is_null($registry))
            {
                $this->newSession->set('registry', new Registry('session'));
            }
        }

        return $this->newSession;
    }

    /**
     * Add a profiler mark.
     *
     * @param   string  $text  The message for the mark.
     *
     * @return  $this  Method allows chaining
     *
     * @since   1.0
     */
    public function mark($text)
    {
//        echo "debug.system:".$this->get('debug.system')."<br/>\n";
//        echo "texto:".$text."<br/>\n";
        if ($this->get('debug.system'))
        {
            $this->getDebugger()->mark($text);
        }

        return $this;
    }

    /**
     * Enqueue a system message.
     *
     * @param   string  $msg   The message to enqueue.
     * @param   string  $type  The message type. Default is message.
     *
     * @return  $this  Method allows chaining
     *
     * @since   1.0
     */
    public function enqueueMessage($msg, $type = 'message')
    {
        $this->getSession()->getFlashBag()->add($type, $msg);

        return $this;
    }

    /**
     * Clear the system message queue.
     *
     * @return  void
     *
     * @since   1.0
     */
    public function clearMessageQueue()
    {
        $this->getSession()->getFlashBag()->clear();
    }

    /**
     * Get the system message queue.
     *
     * @return  array  The system message queue.
     *
     * @since   1.0
     */
    public function getMessageQueue()
    {
        return $this->getSession()->getFlashBag()->peekAll();
    }

    /**
     * Set the system message queue for a given type.
     *
     * @param   string  $type     The type of message to set
     * @param   mixed   $message  Either a single message or an array of messages
     *
     * @return  void
     *
     * @since   1.0
     */
    public function setMessageQueue($type, $message = '')
    {
        $this->getSession()->getFlashBag()->set($type, $message);
    }

    /**
     * Get the language Object.
     *
     * @return  Language  The language object.
     *
     * @since   1.0
     */
    public function getLanguage()
    {
        if (!$this->language)
        {
            $this->language = $this->loadLanguage();
        }

        return $this->language;
    }

    /**
     * Get a language object.
     *
     * @since  1.0
     * @return $this
     */
    protected function loadLanguage()
    {
        // Get the language tag from user input.
        $lang = $this->input->get('lang');

        if ($lang)
        {
            if (false == in_array($lang, $this->get('languages')))
            {
                // Unknown default language - Fall back to British.
                $lang = 'es-ES';
            }

            // Store the language tag to the session.
            $this->getSession()->set('lang', $lang);
        }
        else
        {
            // Get the language tag from the session - Default to British.
            $lang = $this->getSession()->get('lang', 'es-ES');
        }

        $debug = $this->get('debug.language');
        $language = Language::getInstance($lang, $debug);

        // Load Library language
        $language->load('lib_joomla');

        return $language;
    }

    /**
     * Method to run the application routines.  Most likely you will want to instantiate a controller
     * and execute it, or perform some sort of task directly.
     *
     * @return  void
     *
     * @since   1.0
     */
    protected function doExecute()
    {
        try
        {
            // Instantiate the router
            $router = new TranslatorRouter($this->container, $this->input);
            $maps = json_decode(file_get_contents(JPATH_CONFIGURATION . '/routes.json'));

            if (!$maps)
            {
                throw new \RuntimeException('Invalid router file.', 500);
            }

            $router->addMaps($maps, true);
            $router->setControllerPrefix('\\App');
            $router->setDefaultController('\\Dashboard\\Controller\\Dashboard');

            // Fetch the controller
            /* @type AbstractTranslatorController $controller */
            $controller = $router->getController($this->get('uri.route'));

            $this->mark('Controller->initialize()');

            $controller->initialize();
            // Execute the App

            // Define the app path
            define('JPATH_APP', JPATH_ROOT . '/App/' . ucfirst($controller->getApp()));

            // Load the App language file
            // g11n::loadLanguage($controller->getApp(), 'App');

            $this->mark('Controller->execute()');

            $contents = $controller->execute();
// echo "application controller execute:".htmlentities($contents)."<hr/>\n";
            $this->mark('Application terminated OK');

            $contents = str_replace('%%%DEBUG%%%', $this->getDebugger()->getOutput(), $contents);

            $this->setBody($contents);
        }
        catch (AuthenticationException $exception)
        {
            header('HTTP/1.1 403 Forbidden', true, 403);

            $this->mark('Application terminated with an AUTH EXCEPTION');

            $context = array();
            $context['message'] = 'Authentication failure';

            if (JDEBUG)
            {
                // The exceptions contains the User object and the action.
                if ($exception->getUser()->username)
                {
                    $context['user'] = $exception->getUser()->username;
                    $context['id'] = $exception->getUser()->id;
                }

                $context['action'] = $exception->getAction();
            }

            $this->setBody($this->getDebugger()->renderException($exception, $context));
        }
        catch (RoutingException $exception)
        {
            header('HTTP/1.1 404 Not Found', true, 404);

            $this->mark('Application terminated with a ROUTING EXCEPTION');

            $context = array('message' => $exception->getRawRoute());

            $this->setBody($this->getDebugger()->renderException($exception, $context));
        }
        catch (\Exception $exception)
        {
            header('HTTP/1.1 500 Internal Server Error', true, 500);

            $this->mark('Application terminated with an EXCEPTION');

            $this->setBody($this->getDebugger()->renderException($exception));
        }
    }

    /**
     * Get a user object.
     *
     * @param   integer $id The user id or the current user.
     *
     * @return  TranslatorUser
     *
     * @since   1.0
     */
    public function getUser($id = 0)
    {
        if ($id) {
            return new TranslatorUser($this->container->get('db'), $id);
        }

        if (is_null($this->user)) {
            if ($this->user = $this->getSession()->get('translator_user'))
            {
                // @todo Ref #275
                $this->user->setDatabase($this->container->get('db'));
            }
            else
            {
                $this->user = new TranslatorUser($this->container->get('db'));
            }
        }

        return $this->user;
    }

    /**
     * Login or logout a user.
     *
     * @param   User  $user  The user object.
     *
     * @throws  \UnexpectedValueException
     * @return  $this  Method allows chaining
     *
     * @since   1.0
     */
    public function setUser(User $user = null)
    {
        if (is_null($user))
        {
            // Logout
            $this->user = new TranslatorUser($this->container->get('db'));

            $this->getSession()->set('translator_user', $this->user);

            // @todo cleanup more ?
        }
        elseif($user instanceof User)
        {
            // Login
            // $user->isAdmin = in_array($user->username, $this->get('acl.admin_users'));

            $this->user = $user;

            $this->getSession()->set('translator_user', $user);
        }
        else
        {
            throw new \UnexpectedValueException('Wrong parameter when instantiating a new user object.');
        }

        return $this;
    }

    /**
     * Gets a user state.
     *
     * @param   string  $key      The path of the state.
     * @param   mixed   $default  Optional default value, returned if the internal value is null.
     *
     * @return  mixed  The user state or null.
     *
     * @since   1.0
     */
    public function getUserState($key, $default = null)
    {
        /* @type Registry $registry */
        $registry = $this->getSession()->get('registry');

        if (!is_null($registry))
        {
            return $registry->get($key, $default);
        }

        return $default;
    }

    /**
     * Gets the value of a user state variable.
     *
     * @param   string  $key      The key of the user state variable.
     * @param   string  $request  The name of the variable passed in a request.
     * @param   string  $default  The default value for the variable if not found. Optional.
     * @param   string  $type     Filter for the variable, for valid values see {@link JFilterInput::clean()}. Optional.
     *
     * @return  mixed The request user state.
     *
     * @since   1.0
     */
    public function getUserStateFromRequest($key, $request, $default = null, $type = 'none')
    {
        $cur_state = $this->getUserState($key, $default);
        $new_state = $this->input->get($request, null, $type);

        // Save the new value only if it was set in this request.
        if ($new_state !== null)
        {
            $this->setUserState($key, $new_state);
        }
        else
        {
            $new_state = $cur_state;
        }

        return $new_state;
    }

    /**
     * Sets the value of a user state variable.
     *
     * @param   string  $key    The path of the state.
     * @param   string  $value  The value of the variable.
     *
     * @return  mixed  The previous state, if one existed.
     *
     * @since   1.0
     */
    public function setUserState($key, $value)
    {
        /* @type Registry $registry */
        $registry = $this->getSession()->get('registry');

        if (!is_null($registry))
        {
            return $registry->set($key, $value);
        }

        return null;
    }
}