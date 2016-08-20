<?php

namespace AppBundle\Twig\Extension;

use \Twig_Extension;
use Twig_SimpleFilter;
use \Twig_SimpleFunction;

class TimeFormatExtension extends Twig_Extension {

    protected $helper;

    public function __construct($helper) {
        $this->helper = $helper;
    }

    /**
     * Returns a list of global functions to add to the existing list.
     *
     * @return array An array of global functions
     */
    public function getFunctions() {
        return array(
            new Twig_SimpleFunction(
                'time_format',
                array($this, 'format'),
                array('is_safe' => array('html'))
            ),
        );
    }

    public function getFilters() {
        return array(
            new Twig_SimpleFilter(
                'time_format',
                array($this, 'format'),
                array('is_safe' => array('html'))
            ),
        );
    }

    public function format($since = null, $to = null) {
        return $this->helper->format($since, $to);
    }

    /**
     * Returns the name of the extension.
     *
     * @return string The extension name
     */
    public function getName() {
        return 'time';
    }

}
