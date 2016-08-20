<?php

namespace AppBundle;

class AssetCompiler {
    
    public function compile(string $main, string $outputDirectory) {
        $commandOutput = null;
        $result = null;
        exec("./bin/compile.js --main=$main --out=$outputDirectory", $commandOutput, $result);
        return $result;
    }
    
}