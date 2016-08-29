<?php

namespace AppBundle\Transformer;

use Symfony\Component\Form\DataTransformerInterface;

class ArrayToJSONStringTransformer implements DataTransformerInterface {

    /**
     * Transform an array to a JSON string
     */
    public function transform($array) {
        return json_encode($array, JSON_PRETTY_PRINT);
    }

    /**
     * Transform a JSON string to an array
     */
    public function reverseTransform($string) {
        return json_decode($string, true);
    }

}
