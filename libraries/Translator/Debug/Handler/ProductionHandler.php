<?php
/**
 * @package		Translator
 * @copyright	Copyright Open Source Matters, Inc. All rights reserved.
 * @license     GNU General Public License version 2 or later; see LICENSE.txt
 * @author		Carles Serrats <carless@cesigrup.com> - http://www.cesigrup.com
 */

namespace Translator\Debug\Handler;

use \Whoops\Handler\Handler;

/**
 * Catches the Whoops! and simply displays the message.
 *
 * @since  1.0
 */
class ProductionHandler extends Handler
{
	/**
	 * Handle the Whoops!
	 *
	 * @since  1.0
	 * @return integer
	 */
	public function handle()
	{
		echo $this->getException()->getMessage();

		return Handler::QUIT;
	}
}
