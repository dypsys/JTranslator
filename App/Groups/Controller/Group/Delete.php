<?php
/**
 * @package		Translator
 * @copyright	Copyright Open Source Matters, Inc. All rights reserved.
 * @license     GNU General Public License version 2 or later; see LICENSE.txt
 * @author		Carles Serrats <carless@cesigrup.com> - http://www.cesigrup.com
 */

namespace App\Groups\Controller\Group;

use App\Groups\Model\GroupModel;
use App\Groups\Table\GroupsTable;
use App\Groups\View\Group\GroupHtmlView;
use Joomla\Language\Text;
use Translator\Controller\AbstractTranslatorController;

/**
 * Controller class to delete a group.
 *
 * @since  1.0
 */
class Delete extends AbstractTranslatorController
{
    /**
     * The default view for the component
     *
     * @var    string
     * @since  1.0
     */
    protected $defaultView = 'groups';

    /**
     * @var  GroupModel
     */
    protected $model;

    /**
     * @var  GroupHtmlView
     */
    protected $view;

    /**
     * Initialize the controller.
     *
     * @return  $this
     *
     * @since   1.0
     * @throws  \RuntimeException
     */
    public function initialize()
    {
        parent::initialize();

        // $this->container->get('app')->getUser()->authorize('manage');

        return $this;
    }

    /**
     * Execute the controller.
     *
     * @return  string
     *
     * @since   1.0
     */
    public function execute()
    {
        $table = new GroupsTable($this->container->get('db'));

        $table->load($this->container->get('app')->input->getInt('group_id'))
            ->delete();

        $this->container->get('app')->enqueueMessage( Text::_('The group has been deleted.'), 'success');

        return parent::execute();
    }
}
