<?php
/**
 * @package		Translator
 * @copyright	Copyright Open Source Matters, Inc. All rights reserved.
 * @license     GNU General Public License version 2 or later; see LICENSE.txt
 * @author		Carles Serrats <carless@cesigrup.com> - http://www.cesigrup.com
 */

namespace App\Groups\Controller\Group;

use Translator\Controller\AbstractTranslatorController;

/**
 * Controller class to add a group.
 *
 * @since  1.0
 */
class Add extends AbstractTranslatorController
{
    /**
     * The default view for the app.
     *
     * @var    string
     * @since  1.0
     */
    protected $defaultView = 'group';

    /**
     * The default layout for the app.
     *
     * @var    string
     * @since  1.0
     */
    protected $defaultLayout = 'edit';

    /**
     * @var  GroupHtmlView
     */
    protected $view;

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
        parent::initialize();

        // $this->container->get('app')->getUser()->authorize('manage');

        return $this;
    }
}
