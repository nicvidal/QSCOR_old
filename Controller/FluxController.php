<?php

namespace QSCORBundle\Controller;

use Symfony\Component\HttpFoundation\Request;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use QSCORBundle\Entity\Flux;
use QSCORBundle\Form\FluxType;


trait ControllerTraitForFlux{
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
 * Flux controller.
 *
* @Route("/projects/{projectname}/networking/flow_site/flux")
 */
class FluxController extends Controller
{
    use ControllerTraitForFlux;

    /**
     * Lists all Flux entities.
     *
     * @Route("/", name="flux_index")
     * @Method("GET")
     */
    public function indexAction(Request $request, $projectname)
    {
        $em = $this->getDoctrine()->getManager();

        $projects = $this->AcessProject($projectname);
        if( count($projects) === 0) {
            return $this->redirectToRoute('homepage');
        }

        $fluxes = $em->getRepository('QSCORBundle:Flux')->findByProject($projects->getId());
//        var_dump($fluxes);
//        die('ok');


        $paginator  = $this->get('knp_paginator');
        $pagination = $paginator->paginate(
            $fluxes,
            $request->query->get('page', 1)/*page number*/,
            10/*limit per page*/
        );
        $deleteForm  = $this->createFormBuilder()->getForm();


        return $this->render('flux/index.html.twig', array(
            'fluxes' => $fluxes,
            'pagination' => $pagination,
            'projectname' => $projectname,
            'delete_form' => $deleteForm->createView(),
        ));
    }


    /**
     * Creates a new Flux entity.
     *
     * @Route("/new", name="flux_new")
     * @Method({"GET", "POST"})
     */
    public function newAction(Request $request , $projectname)
    {
        $flux = new Flux();
        $projects = $this->AcessProject($projectname);
        if( count($projects) === 0) {
            return $this->redirectToRoute('homepage');
        }

        $form = $this->createForm('QSCORBundle\Form\FluxType', $flux);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $flux->setProject($projects);
            $em->persist($flux);
            $em->flush();
                return $this->redirectToRoute('flux_index', array('projectname' => $projectname));
            }



        return $this->render('flux/new.html.twig', array(
            'flux' => $flux,
            'form' => $form->createView(),
            'projectname' => $projectname
        ));
    }

    /**
     * Finds and displays a Flux entity.
     *
     * @Route("/{id}", name="flux_show")
     * @Method("GET")
     */
    public function showAction(Flux $flux ,$projectname)
    {
        $deleteForm  = $this->createDeleteForm($flux, $projectname);

        return $this->render('flux/show.html.twig', array(
            'flux' => $flux,
            'delete_form' => $deleteForm->createView(),
            'projectname' => $projectname
        ));
    }

    /**
     * Displays a form to edit an existing Flux entity.
     *
     * @Route("/{id}/edit", name="flux_edit")
     * @Method({"GET", "POST"})
     */
    public function editAction(Request $request, Flux $flux, $projectname)
    {
        $editForm = $this->createForm('QSCORBundle\Form\FluxType', $flux);
        $editForm->handleRequest($request);

        $projects = $this->AcessProject($projectname);
        if( count($projects) === 0) {
            return $this->redirectToRoute('homepage');
        }
        if ($editForm->isSubmitted() && $editForm->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $em->persist($flux);
            $em->flush();

            return $this->redirectToRoute('flux_index', array('projectname' => $projectname));
        }

        return $this->render('flux/edit.html.twig', array(
            'flux' => $flux,
            'edit_form' => $editForm->createView(),
            'projectname' => $projectname
        ));
    }

    /**
     * Deletes a Flux entity.
     *
     * @Route("/{id}", name="flux_delete")
     * @Method("DELETE")
     */
    public function deleteAction(Request $request, Flux $flux,  $projectname)
    {
        $form = $this->createDeleteForm($flux, $projectname);
        $form->handleRequest($request);


            $em = $this->getDoctrine()->getManager();
            $em->remove($flux);
            $em->flush();


        return $this->redirectToRoute('flux_index',array('projectname' => $projectname));
    }

    /**
     * Creates a form to delete a Flux entity.
     *
     * @param Flux $flux The Flux entity
     *
     * @return \Symfony\Component\Form\Form The form
     */
    private function createDeleteForm(Flux $flux, $projectname)
    {
        return $this->createFormBuilder()
            ->setAction($this->generateUrl('flux_delete', array('id' => $flux->getId(),
                'projectname' => $projectname)))
            ->setMethod('DELETE')
            ->getForm()
        ;
    }
}
