<?php
/**
 * @package		Translator
 * @copyright	Copyright Open Source Matters, Inc. All rights reserved.
 * @license     GNU General Public License version 2 or later; see LICENSE.txt
 * @author		Carles Serrats <carless@cesigrup.com> - http://www.cesigrup.com
 */

namespace App\Groups\View\Group;

use App\Groups\Model\GroupModel;
use Joomla\Utilities\ArrayHelper;
use Translator\View\AbstractTranslatorHtmlView;

/**
 * The group edit view
 *
 * @since  1.0
 */
class GroupHtmlView extends AbstractTranslatorHtmlView
{
    /**
     * Redefine the model so the correct type hinting is available.
     *
     * @var     GroupModel
     * @since   1.0
     */
    protected $model;

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
        $this->renderer->set('group', ArrayHelper::fromObject($this->model->getItem()));

        return parent::render();
    }
}
