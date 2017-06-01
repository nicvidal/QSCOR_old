<?php

namespace QSCORBundle\Controller;

use Symfony\Component\HttpFoundation\Request;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use QSCORBundle\Entity\Type_Facility;
use QSCORBundle\Form\Type_FacilityType;
use Symfony\Component\HttpFoundation\Session\Session;


trait ControllerTraitForFacility{
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
 * Type_Facility controller.
 *
 * @Route("/projects/{projectname}/facility_types")
 */
class Type_FacilityController extends Controller
{
    use ControllerTraitForFacility;
    /**
     * Lists all Type_Facility entities.
     *
     * @Route("/", name="type_facility_index")
     * @Method("GET")
     */
    public function indexAction(Request $request, $projectname)
    {
        $em = $this->getDoctrine()->getManager();
//        $params = $this->get('request')->attributes->get('projectname');
//        var_dump($params);
        $projects = $this->AcessProject($projectname);
        if( count($projects) === 0) {
            return $this->redirectToRoute('homepage');
        }
        $deleteForm = $this->createFormBuilder()->getForm();


        $facility = $this ->getDoctrine()->getRepository('QSCORBundle:Type_Facility')->findByProject($projects->getId());
        $paginator  = $this->get('knp_paginator');
        $paginationfacilty = $paginator->paginate(
            $facility,
            $request->query->get('page', 1)/*page number*/,
            4/*limit per page*/
        );

        return $this->render('type_facility/index.html.twig', array(
            'paginationfacility' => $paginationfacilty,
            'projectname' => $projectname,
            'delete_form' => $deleteForm->createView(),
        ));
    }

    /**
     * Creates a new Type_Facility entity.
     *
     * @Route("/new/", name="type_facility_new")
     * @Method({"GET", "POST"})
     */
    public function newAction(Request $request, $projectname)
    {
        $type_Facility = new Type_Facility();
        $form = $this->createForm('QSCORBundle\Form\Type_FacilityType', $type_Facility);
        $form->handleRequest($request);
        $projects = $this->AcessProject($projectname);
        if( count($projects) === 0) {
            return $this->redirectToRoute('homepage');
        }
//        $session = $this->get('session');
//        $id = $session->get('idcompany');

        if ($form->isSubmitted() && $form->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $type_Facility->setProject($projects);
            $em->persist($type_Facility);
            $em->flush();
        //   return $this->redirectToRoute('type_facility_show', array('id' => $type_Facility->getId()));
            return $this->redirectToRoute('type_facility_index',array(
                'projectname' => $projectname,
            ));
        }


        return $this->render('type_facility/new.html.twig', array(
            'type_Facility' => $type_Facility,
            'projectname' => $projectname,
            'form' => $form->createView(),
        ));
    }

    /**
     * Finds and displays a Type_Facility entity.
     *
     * @Route("/{id}/show", name="type_facility_show")
     * @Method("GET")
     */
    public function showAction(Type_Facility $type_Facility, $projectname)
    {

//        $deleteForm = $this->createDeleteForm($type_Facility);

//        $deleteForm = $this->createForm('QSCORBundle\Form\Type_FacilityType', $type_Facility);
            $deleteForm = $this->createFormBuilder()->getForm();
//
        return $this->render('type_facility/show.html.twig', array(
            'type_Facility' => $type_Facility,
            'delete_form' => $deleteForm->createView(),
            'projectname' =>$projectname
        ));
    }

    /**
     * Displays a form to edit an existing Type_Facility entity.
     *
     * @Route("/{id}/edit", name="type_facility_edit")
     * @Method({"GET", "POST"})
     */
    public function editAction(Request $request, $id)
    {
        $idfacility = $this->get('nzo_url_encryptor')->decrypt($id);
        $type_Facility = $this->getDoctrine()->getRepository('QSCORBundle:Type_Facility')->find($idfacility);
        $editForm = $this->createForm('QSCORBundle\Form\Type_FacilityType', $type_Facility);
        $editForm->handleRequest($request);

        if ($editForm->isSubmitted() && $editForm->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $em->persist($type_Facility);
            $em->flush();

            return $this->redirectToRoute('type_facility_index',array(
                'projectname' => $request->get('projectname')
            ));
        }

        return $this->render('type_facility/edit.html.twig', array(
            'type_Facility' => $type_Facility,
            'edit_form' => $editForm->createView(),
            'projectname' => $request->get('projectname'),
        ));
    }

    /**
     * Deletes a Type_Facility entity.
     *
     * @Route("/{id}", name="type_facility_delete")
     * @Method("DELETE")
     */
    public function deleteAction(Request $request, Type_Facility $type_Facility)
    {
        $form = $this->createDeleteForm($type_Facility,$request->get('projectname'));
        $form->handleRequest($request);




            $em = $this->getDoctrine()->getManager();
            $em->remove($type_Facility);
            $em->flush();

        return $this->redirectToRoute('type_facility_index', array(
            'projectname' => $request->get('projectname')
        ));
    }

    /**
     * Creates a form to delete a Type_Facility entity.
     *
     * @param Type_Facility $type_Facility The Type_Facility entity
     *
     * @return \Symfony\Component\Form\Form The form
     */
    private function createDeleteForm(Type_Facility $type_Facility, $projectname)
    {

        return $this->createFormBuilder()
            ->setAction($this->generateUrl('type_facility_delete', array('id' => $type_Facility->getId(),
                'projectname' => $projectname)))
            ->setMethod('DELETE')
            ->getForm()
        ;
    }
}
