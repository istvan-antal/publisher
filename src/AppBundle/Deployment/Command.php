<?php

namespace AppBundle\Deployment;

use AppBundle\Entity\Site;
use AppBundle\Entity\Post;

class Command {
    
    /**
     * @SuppressWarnings(PHPMD.UnusedFormalParameter)
     */
    public function execute(Site $site, string $distDir) {
        system($site->getDeploySettings()['command']);
    }
    
}