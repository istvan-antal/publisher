<?php

namespace WorkerBundle;

use WorkerBundle\Entity\WorkerJob;

abstract class JobProcessor {
    abstract public function processJob(WorkerJob $job);
    
    private $output;
    
    public function setOutput($output) {
        $this->output = $output;
    }

    protected function writeln(string $message) {
        $this->output->writeln($message);
    }
}