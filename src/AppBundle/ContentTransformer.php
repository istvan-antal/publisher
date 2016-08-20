<?php

namespace AppBundle;

use ParsedownExtra;
use GeSHi;

class ContentTransformer extends ParsedownExtra {

    protected $colorizer;

    public function __construct() {
        $this->colorizer = new GeSHi();
        // $this->colorizer->enable_classes();
        $this->colorizer->set_overall_class('geshi');
        $this->colorizer->set_header_type(GESHI_HEADER_NONE);
        //$this->colorizer->enable_line_numbers(GESHI_NORMAL_LINE_NUMBERS);
    }

    protected function blockFencedCodeComplete($block) {
        if (!$this->colorizer) {
            return parent::completeFencedCode($block);
        }
        $language = null;
        if (isset($block['element']['text']['attributes']['class'])) {
            $language = str_ireplace('language-', '', $block['element']['text']['attributes']['class']);
        }
        if (!empty($language)) {
            $colorized = $this->colorize($block['element']['text']['text'], $language);
            $elt = array(
                'name' => 'pre',
                'text' => "<code>$colorized</code>",
            );
            $block['element'] = $elt;
        }
        return $block;
    }

    public function getSupportedLanguages() {
        if (empty($this->supportedLanguages)) {
            $this->supportedLanguages = $this->colorizer->get_supported_languages();
            sort($this->supportedLanguages);
        }
        return $this->supportedLanguages;
    }

    public function colorize($text, $language) {
        $this->colorizer->set_source($text);
        $this->colorizer->set_language($language);
        return $this->colorizer->parse_code();
    }
}
