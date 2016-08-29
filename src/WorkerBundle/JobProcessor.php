<?php

namespace WorkerBundle;

use WorkerBundle\Entity\WorkerJob;
use Symfony\Component\DependencyInjection\ContainerInterface;

abstract class JobProcessor {
    abstract public function processJob(WorkerJob $job);
    
    private $output;
    
    public function setOutput($output) {
        $this->output = $output;
    }
    
    private $container;
    
    public function setContainer(ContainerInterface $container = null) {
        $this->container = $container;
    }
    
    public function getContainer() : ContainerInterface {
        return $this->container;
    }

    protected function writeln(string $message) {
        $this->output->writeln($message);
    }
}