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
use Translator\Controller\AbstractTranslatorController;

/**
 * Controller class to save a group.
 *
 * @since  1.0
 */
class Save extends AbstractTranslatorController
{
    /**
     * The default view for the app.
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
     * @return  string  The rendered view.
     *
     * @since   1.0
     */
    public function execute()
    {
        $group = $this->container->get('app')->input->get('group', array(), 'array');

        $table = new GroupsTable($this->container->get('db'));

        $table->save($group);

        return parent::execute();
    }
}
