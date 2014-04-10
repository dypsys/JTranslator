<?php
/**
 * @package		Translator
 * @copyright	Copyright Open Source Matters, Inc. All rights reserved.
 * @license     GNU General Public License version 2 or later; see LICENSE.txt
 * @author		Carles Serrats <carless@cesigrup.com> - http://www.cesigrup.com
 */

namespace App\System\Controller;

use Translator\Controller\AbstractTranslatorController;

/**
 * Controller class to display the server phpinfo() output
 *
 * @since  1.0
 */
class Phpinfo extends AbstractTranslatorController
{
    /**
     * Execute the controller.
     *
     * @return  void
     *
     * @since   1.0
     */
    public function execute()
    {
        // $this->container->get('app')->getUser()->authorize('admin');

        return parent::execute();
    }
}
