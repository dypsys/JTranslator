<?php
/**
 * @package		Translator
 * @copyright	Copyright Open Source Matters, Inc. All rights reserved.
 * @license     GNU General Public License version 2 or later; see LICENSE.txt
 * @author		Carles Serrats <carless@cesigrup.com> - http://www.cesigrup.com
 */

namespace App\Groups\Controller;

use Translator\Controller\AbstractTranslatorController;

/**
 * Default Controller class
 *
 * @since  1.0
 */
class Groups extends AbstractTranslatorController
{
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

//        $this->model->setProject($this->container->get('app')->getProject());
//        $this->view->setProject($this->container->get('app')->getProject());

        return $this;
    }
}