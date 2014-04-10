<?php
/**
 * @package		Translator
 * @copyright	Copyright Open Source Matters, Inc. All rights reserved.
 * @license     GNU General Public License version 2 or later; see LICENSE.txt
 * @author		Carles Serrats <carless@cesigrup.com> - http://www.cesigrup.com
 */

namespace Translator\Authentication;

use Joomla\Database\DatabaseDriver;
use Joomla\Date\Date;
use Translator\Authentication\Database\TableUsers;

/**
 * Abstract class containing the application user object
 *
 * @since  1.0
 */
abstract class User implements \Serializable
{
    /**
     * Id.
     *
     * @var    integer
     * @since  1.0
     */
    public $id = 0;

    /**
     * User name.
     * @var    string
     * @since  1.0
     */
    public $username = '';

    /**
     * Name.
     *
     * @var    string
     * @since  1.0
     */
    public $name = '';

    /**
     * E-mail.
     *
     * @var    string
     * @since  1.0
     */
    public $email = '';

    /**
     * Register date.
     *
     * @var    string
     * @since  1.0
     */
    public $registerDate = '';

    /**
     * A list of groups a user has access to.
     *
     * @var    array
     * @since  1.0
     */
    protected $accessGroups = array();

    /**
     * @var    DatabaseDriver
     * @since  1.0
     */
    protected $database = null;

    /**
     * Constructor.
     *
     * @param   DatabaseDriver  $database    The database connector.
     * @param   integer         $identifier  The primary key of the user to load..
     *
     * @since   1.0
     */
    public function __construct(DatabaseDriver $database, $identifier = 0)
    {
        $this->setDatabase($database);

        // Load the user if it exists
        if ($identifier)
        {
            $this->load($identifier);
        }
    }

    /**
     * Method to set the database connector.
     *
     * @param   DatabaseDriver  $database  The Database connector.
     *
     * @return  void
     *
     * @since 1.0
     */
    public function setDatabase(DatabaseDriver $database)
    {
        $this->database = $database;
    }

    /**
     * Load data by a given user name.
     *
     * @param   string  $userName  The user name
     *
     * @return  TableUsers
     *
     * @since   1.0
     */
    public function loadByUserName($userName)
    {
        $db = $this->database;

        $table = new TableUsers($db);

        $table->loadByUserName($userName);

        if (!$table->id)
        {
            // Register a new user
            $date               = new Date;
            $this->registerDate = $date->format($db->getDateFormat());

            $table->save($this);
        }

        $this->id = $table->id;

        $this->loadAccessGroups();

        return $this;
    }

    /**
     * Get available access groups.
     *
     * @return  array
     *
     * @since   1.0
     */
    public function getAccessGroups()
    {
        return $this->accessGroups;
    }

    /**
     * Method to load a User object by user id number.
     *
     * @param   mixed  $identifier  The user id of the user to load.
     *
     * @return  $this  Method allows chaining
     *
     * @since   1.0
     * @throws  \RuntimeException
     */
    protected function load($identifier)
    {
        // $db = $this->database;

        $table = new TableUsers($this->database);

        if (!$table->load($identifier))
        {
            throw new \RuntimeException('Unable to load the user with id: ' . $identifier);
        }

        foreach ($table->getFields() as $key => $value)
        {
            if (isset($this->$key))
            {
                $this->$key = $table->$key;
            }
        }

        $this->loadAccessGroups();

        return $this;
    }

    /**
     * Load the access groups.
     *
     * @return  $this  Method allows chaining
     *
     * @since   1.0
     */
    protected function loadAccessGroups()
    {
        $db = $this->database;

        $this->accessGroups = $db->setQuery(
            $db->getQuery(true)
                ->from($db->quoteName('#__user_accessgroup_map'))
                ->select($db->quoteName('group_id'))
                ->where($db->quoteName('user_id') . '=' . (int) $this->id)
        )->loadColumn();

        return $this;
    }

    /**
     * Serialize the object
     *
     * @return  string  The string representation of the object or null
     *
     * @since   1.0
     */
    public function serialize()
    {
        $props = array();

        foreach (get_object_vars($this) as $key => $value)
        {
            if (in_array($key, array('authModel', 'cleared', 'authId', 'database')))
            {
                continue;
            }

            $props[$key] = $value;
        }

        return serialize($props);
    }

    /**
     * Unserialize the object
     *
     * @param   string  $serialized  The serialized string
     *
     * @return  void
     *
     * @since   1.0
     */
    public function unserialize($serialized)
    {
        $data = unserialize($serialized);

        foreach ($data as $key => $value)
        {
            $this->$key = $value;
        }
    }
}