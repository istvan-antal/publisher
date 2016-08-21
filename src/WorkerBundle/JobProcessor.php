<?php

namespace WorkerBundle;

use WorkerBundle\Entity\WorkerJob;

interface JobProcessor {
    public function processJob(WorkerJob $job);
}