<?php
/**
 * @package		Translator
 * @copyright	Copyright Open Source Matters, Inc. All rights reserved.
 * @license     GNU General Public License version 2 or later; see LICENSE.txt
 * @author		Carles Serrats <carless@cesigrup.com> - http://www.cesigrup.com
 */

namespace Translator\View;

use Joomla\Model\ModelInterface;
use Joomla\View\AbstractView;

use Translator\View\Renderer\RendererInterface;

/**
 * Abstract HTML view class for the Translator application
 *
 * @since  1.0
 */
abstract class AbstractTranslatorHtmlView extends AbstractView
{
    /**
     * The view layout.
     *
     * @var    string
     * @since  1.0
     */
    protected $layout = 'index';

    /**
     * The view template engine.
     *
     * @var    RendererInterface
     * @since  1.0
     */
    protected $renderer = null;

    /**
     * Method to instantiate the view.
     *
     * @param   ModelInterface     $model     The model object.
     * @param   RendererInterface  $renderer  The renderer object.
     *
     * @throws \RuntimeException
     * @since   1.0
     */
    public function __construct(ModelInterface $model, RendererInterface $renderer)
    {
        parent::__construct($model);

        $this->renderer = $renderer;
    }

    /**
     * Magic toString method that is a proxy for the render method.
     *
     * @return  string
     *
     * @since   1.0
     */
    public function __toString()
    {
        return $this->render();
    }

    /**
     * Method to escape output.
     *
     * @param   string  $output  The output to escape.
     *
     * @return  string  The escaped output.
     *
     * @see     ViewInterface::escape()
     * @since   1.0
     */
    public function escape($output)
    {
        // Escape the output.
        return htmlspecialchars($output, ENT_COMPAT, 'UTF-8');
    }

    /**
     * Method to get the view layout.
     *
     * @return  string  The layout name.
     *
     * @since   1.0
     */
    public function getLayout()
    {
        return $this->layout;
    }

    /**
     * Method to get the renderer object.
     *
     * @return  RendererInterface  The renderer object.
     *
     * @since   1.0
     */
    public function getRenderer()
    {
        return $this->renderer;
    }

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
        return $this->renderer->render($this->layout);
    }

    /**
     * Method to set the view layout.
     *
     * @param   string  $layout  The layout name.
     *
     * @return  $this  Method supports chaining
     *
     * @since   1.0
     */
    public function setLayout($layout)
    {
        $this->layout = $layout;

        return $this;
    }
}