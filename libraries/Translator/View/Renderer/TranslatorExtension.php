<?php
/**
 * @package		Translator
 * @copyright	Copyright Open Source Matters, Inc. All rights reserved.
 * @license     GNU General Public License version 2 or later; see LICENSE.txt
 * @author		Carles Serrats <carless@cesigrup.com> - http://www.cesigrup.com
 */

namespace Translator\View\Renderer;

// use g11n\g11n;

use Joomla\DI\Container;
use Joomla\Language\Text;

/**
 * Twig extension class
 *
 * @since  1.0
 */
class TranslatorExtension extends \Twig_Extension
{
	/**
	 * @var    Container
	 * @since  1.0
	 */
	private $container = null;

	/**
	 * Constructor.
	 *
	 * @param   Container  $container  The DI container.
	 *
	 * @since   1.0
	 */
	public function __construct(Container $container)
	{
		$this->container = $container;
	}

	/**
	 * Returns the name of the extension.
	 *
	 * @return  string  The extension name.
	 *
	 * @since   1.0
	 */
	public function getName()
	{
		return 'translator';
	}

	/**
	 * Returns a list of global variables to add to the existing list.
	 *
	 * @return  array  An array of global variables.
	 *
	 * @since   1.0
	 */
	public function getGlobals()
	{
        $lang = $this->container->get('app')->getLanguage();
		return array(
			'uri'    => $this->container->get('app')->get('uri'),
			'jdebug' => JDEBUG,
			'lang'   => $lang
		);
	}

	/**
	 * Returns a list of functions to add to the existing list.
	 *
	 * @return  array  An array of functions.
	 *
	 * @since   1.0
	 */
	public function getFunctions()
	{
		$functions = array(
			new \Twig_SimpleFunction('translate', 'traducir'),
//			new \Twig_SimpleFunction('g11n4t', 'g11n4t'),
			new \Twig_SimpleFunction('sprintf', 'sprintf'),
			new \Twig_SimpleFunction('stripJRoot', array($this, 'stripJRoot')),
			new \Twig_SimpleFunction('avatar', array($this, 'fetchAvatar')),
//			new \Twig_SimpleFunction('prioClass', array($this, 'getPrioClass')),
//			new \Twig_SimpleFunction('statuses', array($this, 'getStatus')),
//			new \Twig_SimpleFunction('issueLink', array($this, 'issueLink')),
//			new \Twig_SimpleFunction('getRelTypes', array($this, 'getRelTypes')),
		);

		if (!JDEBUG)
		{
			array_push($functions, new \Twig_SimpleFunction('dump', array($this, 'dump')));
		}

		return $functions;
	}

	/**
	 * Returns a list of filters to add to the existing list.
	 *
	 * @return  array  An array of filters
	 *
	 * @since   1.0
	 */
	public function getFilters()
	{
		return array(
			new \Twig_SimpleFilter('basename', 'basename'),
			new \Twig_SimpleFilter('get_class', 'get_class'),
			new \Twig_SimpleFilter('json_decode', 'json_decode'),
			new \Twig_SimpleFilter('stripJRoot', array($this, 'stripJRoot')),
//			new \Twig_SimpleFilter('contrastColor', array($this, 'getContrastColor')),
//			new \Twig_SimpleFilter('labels', array($this, 'renderLabels')),
			new \Twig_SimpleFilter('yesno', array($this, 'yesNo')),
			new \Twig_SimpleFilter('_', array($this, 'traducir'))
		);
	}

	/**
	 * Replaces the Joomla! root path defined by the constant "JPATH_ROOT" with the string "JROOT".
	 *
	 * @param   string  $string  The string to process.
	 *
	 * @return  mixed
	 *
	 * @since   1.0
	 */
	public function stripJRoot($string)
	{
		return str_replace(JPATH_ROOT, 'JROOT', $string);
	}

	/**
	 * Fetch an avatar.
	 *
	 * @param   string   $userName  The user name.
	 * @param   integer  $width     The with in pixel.
	 * @param   string   $class     The class.
	 *
	 * @return  string
	 *
	 * @since   1.0
	 */
	public function fetchAvatar($userName = '', $width = 0, $class = '')
	{
		$base = $this->container->get('app')->get('uri.base.path');

		$avatar = $userName ? $userName . '.png' : 'user-default.png';

		$width = $width ? ' style="width: ' . $width . 'px"' : '';
		$class = $class ? ' class="' . $class . '"' : '';

		return '<img'
		. $class
		. ' alt="avatar ' . $userName . '"'
		. ' src="' . $base . 'images/avatars/' . $avatar . '"'
		. $width
		. ' />';
	}

    /**
     * Fetch an translate text.
     *
     * @param   string   $text  text to translate.
     *
     * @return  string
     *
     * @since   1.0
     */
    public function traducir($text)
    {
        $lang = $this->container->get('app')->getLanguage();
        return $lang->_($text);
//        return Text::_($text);
    }

	/**
	 * Dummy function to prevent throwing exception on dump function in the non-debug mode.
	 *
	 * @return  void
	 *
	 * @since   1.0
	 */
	public function dump()
	{
		return;
	}

    /**
     * Generate a localized yes/no message.
     *
     * @param   integer  $value  A value that evaluates to TRUE or FALSE.
     *
     * @return string
     *
     * @since   1.0
     */
    public function yesNo($value)
    {
        return $value ? $this->traducir('JYES') : $this->traducir('No');
    }
}
