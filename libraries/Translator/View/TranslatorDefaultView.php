<?php
/**
 * @package		Translator
 * @copyright	Copyright Open Source Matters, Inc. All rights reserved.
 * @license     GNU General Public License version 2 or later; see LICENSE.txt
 * @author		Carles Serrats <carless@cesigrup.com> - http://www.cesigrup.com
 */

namespace Translator\View;

use Joomla\Model\ModelInterface;

use Translator\Model\TranslatorDefaultModel;
use Translator\View\Renderer\RendererInterface;
use Translator\View\Renderer\Twig;

/**
 * Default view class for the Tracker application
 *
 * @since  1.0
 */
class TranslatorDefaultView  extends AbstractTranslatorHtmlView
{
	/**
	 * Method to instantiate the view.
	 *
	 * @param   ModelInterface     $model           The model object.
	 * @param   RendererInterface  $renderer        The renderer interface.
	 * @param   string|array       $templatesPaths  The templates paths.
	 *
	 * @since   1.0
	 */
	public function __construct(ModelInterface $model = null, RendererInterface $renderer = null, $templatesPaths = '')
	{
		$model = $model ? : new TranslatorDefaultModel;

		if (is_null($renderer))
		{
			$renderer = new Twig(
				array(
					'templates_base_dir' => JPATH_TEMPLATES,
					'environment' => array('debug' => true)
				)
			);
		}

		parent::__construct($model, $renderer, $templatesPaths);
	}
}
