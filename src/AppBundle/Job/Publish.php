<?php

namespace AppBundle\Job;

use WorkerBundle\JobProcessor;
use WorkerBundle\Entity\WorkerJob;
use Doctrine\ORM\EntityManager;
use Symfony\Component\Filesystem\Filesystem;
use AppBundle\Entity\Site;
use AppBundle\Entity\Article;
use AppBundle\ContentTransformer;
use AppBundle\Templating;
use AppBundle\AssetCompiler;

class Publish extends JobProcessor {

    private $em;
    private $projectRootDir;
    private $transformer;
    private $templating;

    public function __construct(EntityManager $entityManager, string $kernelRootDir) {
        $this->em = $entityManager;
        $this->projectRootDir = $kernelRootDir.'/..';
        $this->transformer = new ContentTransformer();
        $this->templating = new Templating();
    }

    public function processJob(WorkerJob $job) {
        
        $compiler = new AssetCompiler();

        $articleRepository = $this->em->getRepository('AppBundle:Article');
        /* @var $articleRepository \AppBundle\Entity\ArticleRepository */

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

            $posts = $articleRepository->findPublishedPostsForSite($site);

            foreach ($posts as $article) {
                $this->generateArticle($publishDir, $site, $article);
            }

            $this->generateList($site, $posts, $publishDir);
            
            foreach ($articleRepository->findPublishedPagesForSite($site) as $article) {
                $this->generateArticle($publishDir, $site, $article);
            }

            $deploy = $this->getContainer()->get('publisher_deyploy_'.$site->getDeployType());
            $deploy->execute($site, $publishDir);
        }
    }
    
    private function generateArticle(string $publishDir, Site $site, Article $article) {
        $article->setTransformedContent($this->transformer->parse($article->getContent()));

        $fileName = "$publishDir/".$article->getUrl().'.html';

        file_put_contents($fileName, $this->templating->render('ContentTheme/view.html.twig', [
            'site' => $site,
            'defaultTitle' => $site->getSiteTitle(),
            'post' => $article
        ]));
        $this->writeln("Written $fileName");
    }

    const PAGE_SIZE = 10;

    private function generateList(Site $site, $posts, $publishDir) {
        $startIndex = 0;
        $pageNumber = 1;
        $previousPagePath = null;
        $size = count($posts);
        $currentPagePath = 'index.html';

        while ($startIndex < $size) {
            $isLastPage = ($startIndex + self::PAGE_SIZE) > $size;

            $fileName = "$publishDir/$currentPagePath";

            $postsToRender = array_slice($posts, $startIndex, self::PAGE_SIZE);

            file_put_contents($fileName, $this->templating->render('ContentTheme/index.html.twig', [
                'site' => $site,
                'defaultTitle' => $site->getSiteTitle(),
                'posts' => $postsToRender,
                'isLastPage' => $isLastPage,
                'pageNumber' => $pageNumber,
                'nextPagePath' => $isLastPage ? null : 'page-'.($pageNumber + 1).'.html',
                'previousPagePath' => $previousPagePath,
            ]));

            $this->writeln("Written $fileName");

            $startIndex += self::PAGE_SIZE;
            $previousPagePath = $currentPagePath;
            $pageNumber++;
            $currentPagePath = "page-$pageNumber.html";
        }
    }

}