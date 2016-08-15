<?php

namespace WorkerBundle;

use Symfony\Bundle\FrameworkBundle\Command\ContainerAwareCommand;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use WorkerBundle\Entity\WorkerLog;

abstract class Process extends ContainerAwareCommand {
    
    protected $processName;
    /**
     * @var \Doctrine\Common\Persistence\ObjectManager
     */
    protected $em;
    
    /* @var OutputInterface */
    protected $output;

    protected function process() {
        
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
        $output->writeln("Starting $this->processName");
        $t1 = time();
        $this->em = $this->getContainer()->get('doctrine.orm.entity_manager');
        
        $logEntry = new WorkerLog();
        $logEntry->setType($this->processName);
        $this->em->persist($logEntry);
        $this->em->flush();
        
        $crawlStatus = WorkerLog::STATUS_SUCCESS;
        
        try {
            $this->process();
        } catch (\Exception $e) {
            $crawlStatus = WorkerLog::STATUS_FAILURE;
            $logEntry->setErrorMessage("$e");
            
            if ($this->getContainer()->has('worker_notification')) {
                $this->getContainer()
                    ->get('worker_notification')
                    ->send("$this->processName Error", "Error: $e");
            }
            $output->writeln("Error: $e");
        }
        
        $logEntry->setElapsedTime(time() - $t1);
        $logEntry->setStatus($crawlStatus);
        $this->em->flush();
        $output->writeln("$this->processName Done.");
    }
    
    protected function writeln(string $message) {
        $this->output->writeln($message);
    }
    
}