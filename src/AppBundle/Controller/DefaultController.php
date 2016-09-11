<?php

namespace AppBundle\Controller;

use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;

use AppBundle\Entity\Article;

class DefaultController extends Controller {
    /**
     * @Route("/", name="homepage")
     * @Template
     */
    public function indexAction() {
        $em = $this->getDoctrine();
        $postsRepository = $em->getRepository('AppBundle:Article');
        /* @var $postsRepository \AppBundle\Entity\ArticleRepository */
        
        $siteRepository = $em->getRepository('AppBundle:Site');
        /* @var $postsRepository \Doctrine\ORM\EntityRepository */
        
        $workerRepository = $em->getRepository('WorkerBundle:WorkerJob');
        /* @var $workerRepository \Doctrine\ORM\EntityRepository */
        
        $stateCounts = $postsRepository->getCountsByState();

        return array(
            'stateCounts' => $stateCounts,
            'sites' => $siteRepository->findAll(),
            'workerLogs' => $workerRepository->findBy(array(), [ 'createdAt' => 'desc' ], 10),
        );
    }
}
