<?php

namespace WorkerBundle\Command;

use Symfony\Bundle\FrameworkBundle\Command\ContainerAwareCommand;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Doctrine\Common\Util\Inflector;
use WorkerBundle\Entity\WorkerJob;

class ProcessJobsCommand extends ContainerAwareCommand {
    /**
     * @var \Doctrine\Common\Persistence\ObjectManager
     */
    protected $em;
    
    /* @var OutputInterface */
    protected $output;
    
    /**
     * @param WorkerJob $job
     *
     * @SuppressWarnings(PHPMD.StaticAccess)
     */
    private function processJob(WorkerJob $job) {
        $t1 = time();
        $job->setStatus(WorkerJob::STATUS_WORKING);
        $this->em->flush();

        $jobStatus = WorkerJob::STATUS_SUCCESS;

        try {
            $processor = $this->getContainer()->get('worker_job_processor_'.Inflector::tableize($job->getType()));
            /* @var $processor \WorkerBundle\JobProcessor */
            $processor->setOutput($this->output);
            $processor->setContainer($this->getContainer());
            $processor->processJob($job);
        } catch (\Exception $e) {
            $jobStatus = WorkerJob::STATUS_FAILURE;
            $job->setErrorMessage("$e");

            if ($this->getContainer()->has('worker_notification')) {
                $this->getContainer()
                    ->get('worker_notification')
                    ->send($job->getType().' Error', "Error: $e");
            }
            $this->writeln("Error: $e");
        }

        $job->setElapsedTime(time() - $t1);
        $job->setStatus($jobStatus);
        $this->em->flush();
        $this->writeln($job->getType().' done.');
    }
    
    /**
     * @param InputInterface $input
     * @param OutputInterface $output
     *
     * @SuppressWarnings(PHPMD.UnusedFormalParameter)
     * @SuppressWarnings(PHPMD.ElseExpression)
     */
    protected function execute(InputInterface $input, OutputInterface $output) {
        $this->output = $output;
        //$output->writeln("Starting Job processor");
        
        $this->em = $this->getContainer()->get('doctrine.orm.entity_manager');
        
        $workerRepository = $this->em->getRepository('WorkerBundle:WorkerJob');
        /* @var $workerRepository \Doctrine\ORM\EntityRepository */
        
        $jobs = $workerRepository->findBy([ 'status' => WorkerJob::STATUS_CREATED ]);
        
        foreach ($jobs as $job) {
            /* @var $job WorkerJob */
            $this->processJob($job);
        }
        
        //$output->writeln("Job processor done.");
    }
    
    protected function writeln(string $message) {
        $this->output->writeln($message);
    }
    
    protected function configure() {
        $this->setName('worker:process');
    }
    
}