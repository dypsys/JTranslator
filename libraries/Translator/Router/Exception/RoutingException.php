<?php
/**
 * @package		Translator
 * @copyright	Copyright Open Source Matters, Inc. All rights reserved.
 * @license     GNU General Public License version 2 or later; see LICENSE.txt
 * @author		Carles Serrats <carless@cesigrup.com> - http://www.cesigrup.com
 */

namespace Translator\Router\Exception;

/**
 * RoutingException
 *
 * @since  1.0
 */
class RoutingException extends \Exception
{
	/**
	 * The raw route.
	 *
	 * @var    string
	 * @since  1.0
	 */
	protected $rawRoute = '';

	/**
	 * Constructor.
	 *
	 * @param   string  $rawRoute  The raw route.
	 *
	 * @since   1.0
	 */
	public function __construct($rawRoute)
	{
		$this->rawRoute = $rawRoute;

		parent::__construct('Bad Route', 404);
	}

	/**
	 * Get the raw route.
	 *
	 * @return  string
	 *
	 * @since   1.0
	 */
	public function getRawRoute()
	{
		return $this->rawRoute;
	}
}
