<?php

namespace AppBundle;

use Twig_Environment;
use Twig_Loader_Filesystem;

class Templating {
    
    private $twig;
    
    public function __construct() {
        $loader = new Twig_Loader_Filesystem(dirname(__FILE__).'/Resources/views');
        $this->twig = new Twig_Environment($loader);
    }
    
    public function render($name, $context) {
        return $this->twig->render($name, $context);
    }
    
}