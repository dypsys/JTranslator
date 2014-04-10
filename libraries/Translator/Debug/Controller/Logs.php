<?php
/**
 * @package		Translator
 * @copyright	Copyright Open Source Matters, Inc. All rights reserved.
 * @license     GNU General Public License version 2 or later; see LICENSE.txt
 * @author		Carles Serrats <carless@cesigrup.com> - http://www.cesigrup.com
 */

namespace Translator\Debug\Controller;

use Translator\Debug\TranslatorDebugger;
use Translator\Debug\View\Logs\LogsHtmlView;

use Translator\Controller\AbstractTranslatorController;

/**
 * Controller class to display the application configuration
 *
 * @since  1.0
 */
class Logs extends AbstractTranslatorController
{
	/**
	 * @var  LogsHtmlView
	 */
	protected $view = null;

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

		$this->container->get('app')->getUser()->authorize('admin');

		$this->view->setLogType($this->container->get('app')->input->get('log_type'));
		$this->view->setDebugger(new TranslatorDebugger($this->container));

		return $this;
	}
}
