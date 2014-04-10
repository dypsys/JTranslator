<?php
/**
 * @package		Translator
 * @copyright	Copyright Open Source Matters, Inc. All rights reserved.
 * @license     GNU General Public License version 2 or later; see LICENSE.txt
 * @author		Carles Serrats <carless@cesigrup.com> - http://www.cesigrup.com
 */

namespace App\Groups\View\Groups;

use App\Groups\Model\GroupsModel;

use Translator\View\AbstractTranslatorHtmlView;

/**
 * The groups list view
 *
 * @since  1.0
 */
class GroupsHtmlView extends AbstractTranslatorHtmlView
{
    /**
     * Method to render the view.
     *
     * @return  string  The rendered view.
     *
     * @since   1.0
     * @throws  \RuntimeException
     */
    public function render()
    {
        // Set the vars to the template.
        $this->renderer->set('items', $this->model->getItems());

        return parent::render();
    }
}