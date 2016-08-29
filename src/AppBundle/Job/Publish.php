<?php

namespace AppBundle\Job;

use WorkerBundle\JobProcessor;
use WorkerBundle\Entity\WorkerJob;
use Doctrine\ORM\EntityManager;
use Symfony\Component\Filesystem\Filesystem;
use AppBundle\Entity\Site;
use AppBundle\Entity\Post;
use AppBundle\ContentTransformer;
use AppBundle\Templating;
use AppBundle\AssetCompiler;

class Publish extends JobProcessor {
    
    private $em;
    private $projectRootDir;
    
    public function __construct(EntityManager $entityManager, string $kernelRootDir) {
        $this->em = $entityManager;
        $this->projectRootDir = $kernelRootDir.'/..';
    }
    
    public function processJob(WorkerJob $job) {
        $transformer = new ContentTransformer();
        $templating = new Templating();
        $compiler = new AssetCompiler();
        
        $postRepository = $this->em->getRepository('AppBundle:Post');
        /* @var $postRepository \Doctrine\ORM\EntityRepository */
        
        $siteRepository = $this->em->getRepository('AppBundle:Site');
        /* @var $siteRepository \Doctrine\ORM\EntityRepository */
        
        foreach ($siteRepository->findAll() as $site) {
            /* @var $site \AppBundle\Entity\Site */
            $publishedContentDir = "$this->projectRootDir/var/publishedContent";

            $fs = new Filesystem();

            if (!$fs->exists($publishedContentDir)) {
                $fs->mkdir($publishedContentDir);
            }

            //$version = time();
            $version = 'latest';

            $publishDir = "$publishedContentDir/$version";
            $fs->mkdir($publishDir);

            $compiler->compile('src/AppBundle/Resources/views/ContentTheme/main.js', $publishDir);

            $posts = $postRepository->findBy([ 'site' => $site, 'state' => Post::STATE_PUBLISHED ], ['created' => 'desc']);

            foreach ($posts as $post) {
                /* @var $post Post */
                $post->setTransformedContent($transformer->parse($post->getContent()));

                $fileName = "$publishDir/".$post->getUrl().'.html';

                file_put_contents($fileName, $templating->render('ContentTheme/view.html.twig', [
                    'site' => $site,
                    'defaultTitle' => $site->getSiteTitle(),
                    'post' => $post
                ]));
                $this->writeln("Written $fileName");
            }

            $this->generateList($site, $posts, $publishDir, $templating);
            
            $deploy = $this->getContainer()->get('publisher_deyploy_'.$site->getDeployType());
            $deploy->execute($site, $publishDir);
        }
    }
    
    const pageSize = 10;
    
    private function generateList(Site $site, $posts, $publishDir, $templating) {
        $startIndex = 0;
        $pageNumber = 1;
        $previousPagePath = null;
        $size = count($posts);
        $currentPagePath = 'index.html';
        
        while ($startIndex < $size) {
            $isLastPage = ($startIndex + self::pageSize) > $size;
            
            if ($startIndex === 0) {
                $fileName = "$publishDir/$currentPagePath";
            } else {
                $fileName = "$publishDir/$currentPagePath";
            }
            
            $postsToRender = array_slice($posts, $startIndex, self::pageSize);
            
            file_put_contents($fileName, $templating->render('ContentTheme/index.html.twig', [
                'site' => $site,
                'defaultTitle' => $site->getSiteTitle(),
                'posts' => $postsToRender,
                'isLastPage' => $isLastPage,
                'pageNumber' => $pageNumber,
                'nextPagePath' => $isLastPage ? null : 'page-'.($pageNumber + 1).'.html',
                'previousPagePath' => $previousPagePath,
            ]));

            $this->writeln("Written $fileName");
            
            $startIndex += self::pageSize;
            $previousPagePath = $currentPagePath;
            $pageNumber++;
            $currentPagePath = "page-$pageNumber.html";
        }
    }

}