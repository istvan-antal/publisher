<?php

namespace AppBundle\Controller;

use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;

use AppBundle\Entity\Post;

class DefaultController extends Controller {
    /**
     * @Route("/", name="homepage")
     * @Template
     */
    public function indexAction() {
        $em = $this->getDoctrine();
        $postsRepository = $em->getRepository('AppBundle:Post');
        /* @var $postsRepository \AppBundle\Entity\PostRepository */
        
        $workerRepository = $em->getRepository('WorkerBundle:WorkerJob');
        /* @var $workerRepository \Doctrine\ORM\EntityRepository */
        
        $stateCounts = $postsRepository->getCountsByState();

        return array(
            'stateCounts' => $stateCounts,
            'workerLogs' => $workerRepository->findBy(array(), [ 'createdAt' => 'desc' ], 10),
        );
    }
}
