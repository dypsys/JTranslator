<?php
/**
 * @package		Translator
 * @copyright	Copyright Open Source Matters, Inc. All rights reserved.
 * @license     GNU General Public License version 2 or later; see LICENSE.txt
 * @author		Carles Serrats <carless@cesigrup.com> - http://www.cesigrup.com
 */

namespace App\Users\Controller;

use Translator\Controller\AbstractTranslatorController;

/**
 * Controller class to manage a user group.
 *
 * @package  Translator\Components\Translator
 * @since    1.0
 */
class User extends AbstractTranslatorController
{
    protected $defaultLayout = 'edit';

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

        $this->model->setUserId($this->container->get('app')->input->getInt('id'));

        return $this;
    }
}