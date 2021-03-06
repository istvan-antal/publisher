<?php

namespace AppBundle\Controller;

use Symfony\Component\HttpFoundation\Request;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use AppBundle\Entity\Site;
use AppBundle\Form\SiteType;
use WorkerBundle\Entity\WorkerJob;

/**
 * Site controller.
 *
 * @Route("/site")
 */
class SiteController extends Controller {

    /**
     * Lists all Site entities.
     *
     * @Route("/", name="site_index")
     * @Method("GET")
     */
    public function indexAction() {
        $em = $this->getDoctrine()->getManager();

        $sites = $em->getRepository('AppBundle:Site')->findAll();

        return $this->render('site/index.html.twig', array(
                    'sites' => $sites,
        ));
    }

    /**
     * Creates a new Site entity.
     *
     * @Route("/new", name="site_new")
     * @Method({"GET", "POST"})
     */
    public function newAction(Request $request) {
        $site = new Site();
        $form = $this->createForm('AppBundle\Form\SiteType', $site);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $em->persist($site);
            $em->flush();

            return $this->redirectToRoute('site_show', array('id' => $site->getId()));
        }

        return $this->render('site/new.html.twig', array(
                    'site' => $site,
                    'form' => $form->createView(),
        ));
    }

    /**
     * Finds and displays a Site entity.
     *
     * @Route("/{id}", name="site_show")
     * @Method("GET")
     */
    public function showAction(Site $site) {
        $deleteForm = $this->createDeleteForm($site);

        return $this->render('site/show.html.twig', array(
                    'site' => $site,
                    'delete_form' => $deleteForm->createView(),
        ));
    }

    /**
     * Displays a form to edit an existing Site entity.
     *
     * @Route("/{id}/edit", name="site_edit")
     * @Method({"GET", "POST"})
     */
    public function editAction(Request $request, Site $site) {
        $deleteForm = $this->createDeleteForm($site);
        $editForm = $this->createForm('AppBundle\Form\SiteType', $site);
        $editForm->handleRequest($request);

        if ($editForm->isSubmitted() && $editForm->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $em->persist($site);
            
            $job = new WorkerJob();
            $job->setType('Publish');
            $em->persist($job);
            
            $em->flush();

            return $this->redirectToRoute('site_edit', array('id' => $site->getId()));
        }

        return $this->render('site/edit.html.twig', array(
                    'site' => $site,
                    'edit_form' => $editForm->createView(),
                    'delete_form' => $deleteForm->createView(),
        ));
    }

    /**
     * Deletes a Site entity.
     *
     * @Route("/{id}", name="site_delete")
     * @Method("DELETE")
     */
    public function deleteAction(Request $request, Site $site) {
        $form = $this->createDeleteForm($site);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $em->remove($site);
            $em->flush();
        }

        return $this->redirectToRoute('site_index');
    }

    /**
     * Creates a form to delete a Site entity.
     *
     * @param Site $site The Site entity
     *
     * @return \Symfony\Component\Form\Form The form
     */
    private function createDeleteForm(Site $site) {
        return $this->createFormBuilder()
                        ->setAction($this->generateUrl('site_delete', array('id' => $site->getId())))
                        ->setMethod('DELETE')
                        ->getForm()
        ;
    }

}
