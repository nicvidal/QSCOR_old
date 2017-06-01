<?php

namespace QSCORBundle\Controller;

use QSCORBundle\Entity\Company;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use QSCORBundle\Entity\Flow_Site;
use QSCORBundle\Form\Flow_SiteType;


trait ControllerTraitForFlow{
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
 * Flow_Site controller.
 *
 * * @Route("/projects/{projectname}/networking/flow_site", name="flow_site")
 */
class Flow_SiteController extends Controller
{
    use ControllerTraitForFlow;


    /**
     * Lists all Flow_Site entities.
     *
     * @Route("/flow", name="flow_site")
     * @Method("GET")
     */
    public function flowsiteAction(Request $request, $projectname)
    {
        $em = $this->getDoctrine()->getManager();

        $projects = $this->AcessProject($projectname);
        if( count($projects) === 0) {
            return $this->redirectToRoute('homepage');
        }

        return $this->render('flow_site/flowsite.html.twig', array(
            'projectname' => $projectname
        ));
    }
    /**
     * Lists all Flow_Site entities.
     *
     * @Route("/", name="flow_site_index")
     * @Method("GET")
     */
    public function indexAction(Request $request, $projectname)
    {
        $em = $this->getDoctrine()->getManager();

        $projects = $this->AcessProject($projectname);
        if( count($projects) === 0) {
            return $this->redirectToRoute('homepage');
        }
        $flux = $em->getRepository('QSCORBundle:Flux')->findAll();
        $sites = $em->getRepository('QSCORBundle:Site')->findByProject($projects->getId());
        $flow_Sites = $em->getRepository('QSCORBundle:Flow_Site')->findAll();

        $paginator  = $this->get('knp_paginator');
        $paginationflow = $paginator->paginate(
            $flow_Sites,
            $request->query->get('page', 1)/*page number*/,
            4/*limit per page*/
        );
        $deleteForm = $this->createFormBuilder()->getForm();

        return $this->render('flow_site/index.html.twig', array(
            'flow_Sites' => $paginationflow,
            'sites'      => $sites,
            'delete_form' => $deleteForm->createView(),
            'projectname' => $projectname,
            'flux'        => $flux
        ));
    }

    /**
     * Creates a new Flow_Site entity.
     *
     * @Route("/new", name="flow_site_new")
     */

    //     * @Method({"GET", "POST"})
    public function newAction(Request $request, $projectname)
    {



        $projects = $this->AcessProject($projectname);
        if( count($projects) === 0) {
            return $this->redirectToRoute('homepage');
        }
        $flow_Site = new Flow_Site();
        $form = $this->createForm(Flow_SiteType::class, $flow_Site , array('projectId'=>$projects));
        $form->handleRequest($request);



        if ($form->isSubmitted() && $form->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $em->persist($flow_Site);
            $em->flush();

            return $this->redirectToRoute('flow_site_index', array('projectname' => $projectname));
        }

        return $this->render('flow_site/new.html.twig', array(
            'flow_Site' => $flow_Site,
            'form' => $form->createView(),
            'projectname' => $projectname
        ));
    }

//    /**
//     * Finds and displays a Flow_Site entity.
//     *
//     * @Route("/{id}", name="flow_site_show")
//     * @Method("GET")
//     */
//    public function showAction(Flow_Site $flow_Site,  $projectname)
//    {
//        $deleteForm = $this->createDeleteForm($flow_Site , $projectname);
//
//        return $this->render('flow_site/show.html.twig', array(
//            'flow_Site' => $flow_Site,
//            'delete_form' => $deleteForm->createView(),
//            'projectname' => $projectname
//        ));
//    }

    /**
     * Displays a form to edit an existing Flow_Site entity.
     *
     * @Route("/{id}/edit", name="flow_site_edit")
     * @Method({"GET", "POST"})
     */
    public function editAction(Request $request, Flow_Site $flow_Site, $projectname)
    {
        //TODO pass to From the object flow_Site for create Querybuilder

        $projects = $this->AcessProject($projectname);
        if( count($projects) === 0) {
            return $this->redirectToRoute('homepage');
        }
        $editForm = $this->createForm(Flow_SiteType::class, $flow_Site , array('projectId'=>$projects));
//        die();

        $editForm->handleRequest($request);


        if ($editForm->isSubmitted() && $editForm->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $em->persist($flow_Site);
            $em->flush();

            return $this->redirectToRoute('flow_site_index', array('projectname' => $projectname));
        }

        return $this->render('flow_site/edit.html.twig', array(
            'flow_Site' => $flow_Site,
            'edit_form' => $editForm->createView(),
            'projectname' => $projectname
        ));
    }

    /**
     * Deletes a Flow_Site entity.
     *
     * @Route("/{id}", name="flow_site_delete")
     * @Method("DELETE")
     */
    public function deleteAction(Request $request, Flow_Site $flow_Site, $projectname)
    {

        $form = $this->createDeleteForm($flow_Site, $projectname);
        $form->handleRequest($request);

        $projects = $this->AcessProject($projectname);
        if( count($projects) === 0) {
            return $this->redirectToRoute('homepage');
        }

            $em = $this->getDoctrine()->getManager();
            $em->remove($flow_Site);
            $em->flush();


        return $this->redirectToRoute('flow_site_index', array('projectname' => $projectname));
    }

    /**
     * Creates a form to delete a Flow_Site entity.
     *
     * @param Flow_Site $flow_Site The Flow_Site entity
     *
     * @return \Symfony\Component\Form\Form The form
     */
    private function createDeleteForm(Flow_Site $flow_Site, $projectname)
    {
        return $this->createFormBuilder()
            ->setAction($this->generateUrl('flow_site_delete', array('id' => $flow_Site->getId(),
                'projectname' => $projectname,
                )))
            ->setMethod('DELETE')
            ->getForm()
        ;
    }
}
