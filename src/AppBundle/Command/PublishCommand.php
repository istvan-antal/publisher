<?php

namespace AppBundle\Command;

use Symfony\Component\Filesystem\Filesystem;

use WorkerBundle\Process;
use AppBundle\Entity\Post;
use AppBundle\ContentTransformer;
use AppBundle\Templating;
use AppBundle\AssetCompiler;

class PublishCommand extends Process {
    
    protected function process() {
        $transformer = new ContentTransformer();
        $templating = new Templating();
        $compiler = new AssetCompiler();
        
        $postRepository = $this->em->getRepository('AppBundle:Post');
        /* @var $postRepository \Doctrine\ORM\EntityRepository */
        
        $projectRootDir = $this->getContainer()->get('kernel')->getRootDir().'/..';
        $publishedContentDir = "$projectRootDir/var/publishedContent";
        
        $fs = new Filesystem();
        
        if (!$fs->exists($publishedContentDir)) {
            $fs->mkdir($publishedContentDir);
        }
        
        //$version = time();
        $version = 'latest';
        
        $publishDir = "$publishedContentDir/$version";
        $fs->mkdir($publishDir);
        
        $compiler->compile('src/AppBundle/Resources/views/ContentTheme/main.js', $publishDir);
        
        $posts = $postRepository->findBy(array( 'state' => Post::STATE_PUBLISHED ), array('created' => 'desc'));
        
        foreach ($posts as $post) {
            /* @var $post Post */
            $post->setTransformedContent($transformer->parse($post->getContent()));
            
            $fileName = "$publishDir/".$post->getUrl().'.html';
            
            file_put_contents($fileName, $templating->render('ContentTheme/view.html.twig', array(
                'post' => $post
            )));
            $this->writeln("Written $fileName");
        }
        
        $fileName = "$publishDir/index.html";
            
        file_put_contents($fileName, $templating->render('ContentTheme/index.html.twig', array(
            'posts' => $posts
        )));
        $this->writeln("Written $fileName");
    }

    protected function configure() {
        $this->setName('worker:publish');
        $this->processName = 'Publish';
    }

}
