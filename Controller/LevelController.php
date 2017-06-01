<?php

namespace QSCORBundle\Controller;

use Symfony\Component\HttpFoundation\Request;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use QSCORBundle\Entity\Level;
use QSCORBundle\Form\LevelType;
trait ControllerTraitForLevel{
    /**
     * @param $projectname
     * @return mixed
     */
    public function AcessProject($projectname)
    {

        $em = $this->getDoctrine()->getManager();
        $query = $em->createQuery(
            'SELECT p
        FROM QSCORBundle:Project p
        WHERE LOWER (p.name) = LOWER(:name)'
        )->setParameter('name', $projectname);


        $projects =  $query->setMaxResults(1)->getOneOrNullResult();
        return $projects;


    }
}
/**
 * Level controller.
 *
 * @Route("/projects/{projectname}/modelling/level")
 */
class LevelController extends Controller
{
    use ControllerTraitForLevel;
    /**
     * Lists all Level entities.
     *
     * @Route("/site/{idsite}", name="modelling_level_index")
     * @Method("GET")
     */
    public function indexAction(Request $request,$idsite, $projectname)
    {
        $em = $this->getDoctrine()->getManager();
        $projects = $this->AcessProject($projectname);
        if( count($projects) === 0) {
            return $this->redirectToRoute('homepage');
        }


        $idsite = $this->get('nzo_url_encryptor')->decrypt($idsite);
//


        $levels = $em->getRepository('QSCORBundle:Level')->findBySite($idsite);
        $paginator  = $this->get('knp_paginator');
        $paginationlevels = $paginator->paginate(
            $levels,
            $request->query->get('page', 1)/*page number*/,
            4/*limit per page*/
        );

        return $this->render('level/index.html.twig', array(
            'levels' => $paginationlevels,
            'projectname'=> $projectname
        ));
    }

    /**
     * Creates a new Level entity.
     *
     * @Route("/new", name="modelling_level_new")
     * @Method({"GET", "POST"})
     */
    public function newAction(Request $request)
    {
        $level = new Level();
        $form = $this->createForm('QSCORBundle\Form\LevelType', $level);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $em->persist($level);
            $em->flush();

            return $this->redirectToRoute('modelling_level_show', array('id' => $level->getId()));
        }

        return $this->render('level/new.html.twig', array(
            'level' => $level,
            'form' => $form->createView(),
        ));
    }

    /**
     * Finds and displays a Level entity.
     *
     * @Route("/{id}", name="modelling_level_show")
     * @Method("GET")
     */
    public function showAction(Level $level)
    {
        $deleteForm = $this->createDeleteForm($level);

        return $this->render('level/show.html.twig', array(
            'level' => $level,
            'delete_form' => $deleteForm->createView(),
        ));
    }

    /**
     * Displays a form to edit an existing Level entity.
     *
     * @Route("/{id}/edit", name="modelling_level_edit")
     * @Method({"GET", "POST"})
     */
    public function editAction(Request $request, Level $level)
    {
        $deleteForm = $this->createDeleteForm($level);
        $editForm = $this->createForm('QSCORBundle\Form\LevelType', $level);
        $editForm->handleRequest($request);

        if ($editForm->isSubmitted() && $editForm->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $em->persist($level);
            $em->flush();

            return $this->redirectToRoute('modelling_level_edit', array('id' => $level->getId()));
        }

        return $this->render('level/edit.html.twig', array(
            'level' => $level,
            'edit_form' => $editForm->createView(),
            'delete_form' => $deleteForm->createView(),
        ));
    }

    /**
     * Deletes a Level entity.
     *
     * @Route("/{id}", name="modelling_level_delete")
     * @Method("DELETE")
     */
    public function deleteAction(Request $request, Level $level)
    {
        $form = $this->createDeleteForm($level);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $em->remove($level);
            $em->flush();
        }

        return $this->redirectToRoute('modelling_level_index');
    }

    /**
     * Creates a form to delete a Level entity.
     *
     * @param Level $level The Level entity
     *
     * @return \Symfony\Component\Form\Form The form
     */
    private function createDeleteForm(Level $level)
    {
        return $this->createFormBuilder()
            ->setAction($this->generateUrl('modelling_level_delete', array('id' => $level->getId())))
            ->setMethod('DELETE')
            ->getForm()
        ;
    }
}
