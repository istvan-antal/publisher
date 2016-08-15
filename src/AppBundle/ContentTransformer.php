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
        $this->colorizer->enable_line_numbers(GESHI_NORMAL_LINE_NUMBERS);
    }

    protected function blockFencedCodeComplete($Block) {
        if (!$this->colorizer) {
            return parent::completeFencedCode($Block);
        }
        $language = null;
        if (isset($Block['element']['text']['attributes']['class'])) {
            $language = str_ireplace('language-', '', $Block['element']['text']['attributes']['class']);
        }
        if (!empty($language)) {
            $colorized = $this->colorize($Block['element']['text']['text'], $language);
            if (preg_match('#<(\w+)\s?(?:class="([^"\'<>]+)")?[^<>]*>(.*)</\w+>#s', $colorized, $matches)) {
                $elt = array(
                    'name' => $matches[1],
                    'text' => $matches[3],
                );
                if (!empty($matches[2])) {
                    $elt['attributes'] = array(
                        'class' => $matches[2],
                    );
                }
                $Block['element'] = $elt;
            }
        }
        return $Block;
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
        return preg_replace(
                '#<pre([^<>]*)>(.*)</pre>#s', '<pre><code$1>$2</code></pre>', @$this->colorizer->parse_code()
        );
    }

    /* public function __construct() {
      $this->setCodeBlockAdapter(function ($element) {
      $markup = '';
      $text = htmlspecialchars($element['text'], ENT_NOQUOTES, 'UTF-8');

      if (isset($element['language'])) {
      $aliases = array('js' => 'javascript');
      $lang = $element['language'];

      if (isset($aliases[$lang])) {
      $lang = $aliases[$lang];
      }

      $geshi = new GeSHi($element['text'], $lang);
      $geshi->set_header_type(GESHI_HEADER_NONE);

      if ($lang === 'nginx') {
      $geshi->set_numbers_style('');
      }

      $markup .= '<code>' . $geshi->parse_code() . '</code>';
      } else {
      $markup .= '<pre><code>' . $text . '</code></pre>';
      }
      $markup .= "\n";

      return $markup;
      });
      } */
}
