<?php

namespace QSCORBundle\Controller;

use Symfony\Component\HttpFoundation\Request;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use QSCORBundle\Entity\Contact_Personne;
use QSCORBundle\Form\Contact_PersonneType;


trait ControllerTraitForContact_Person{
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
    /**
     * @param $projectname
     * @return mixed
     */
    public function AcessSite($sitename)
    {

        $em = $this->getDoctrine()->getManager();
        $query = $em->createQuery(
            'SELECT s
        FROM QSCORBundle:Site s
        WHERE LOWER (s.name) = LOWER(:name)'
        )->setParameter('name', $sitename);


        $projects =  $query->setMaxResults(1)->getOneOrNullResult();
        return $projects;


    }
}
/**
 * Contact_Personne controller.
 * @Route("/projects/{projectname}/sites/{sitename}/Contact_Person")
 */
class Contact_PersonneController extends Controller
{
    use ControllerTraitForContact_Person;
    /**
     * Lists all Contact_Personne entities.
     *
     * @Route("/", name="contact_personne_index")
     * @Method("GET")
     */
    public function indexAction(Request $request, $projectname, $sitename)
    {
        $em = $this->getDoctrine()->getManager();

//        var_dump($siteid);
//        die();

        $projects = $this->AcessProject($projectname);
        if( count($projects) === 0) {
            return $this->redirectToRoute('homepage');
        }
        $sites = $this->AcessSite($sitename);
        if( count($sites) === 0) {
            return $this->redirectToRoute('site_index', array(
                'projectname'=> $projectname
            ));
        }

        $deleteForm = $this->createFormBuilder()->getForm();
        $contact_Personnes = $em->getRepository('QSCORBundle:Contact_Personne')->findBySite($sites->getId());

        $paginator  = $this->get('knp_paginator');
        $paginationperson = $paginator->paginate(
            $contact_Personnes,
            $request->query->get('page', 1)/*page number*/,
            4/*limit per page*/
        );
        return $this->render('contact_personne/index.html.twig', array(
            'paginationperson' => $paginationperson,
            'projectname' => $projectname,
            'delete_form' => $deleteForm->createView(),
        ));
    }

    /**
     * Creates a new Contact_Personne entity.
     *
     * @Route("/new", name="contact_personne_new")
     * @Method({"GET", "POST"})
     */
    public function newAction(Request $request, $projectname, $sitename)
    {

        $projects = $this->AcessProject($projectname);
        if( count($projects) === 0) {
            return $this->redirectToRoute('homepage');
        }
        $sites = $this->AcessSite($sitename);
        if( count($sites) === 0) {
            return $this->redirectToRoute('site_index', array(
                'projectname'=> $projectname
            ));
        }
        $contact_Personne = new Contact_Personne();
        $form = $this->createForm('QSCORBundle\Form\Contact_PersonneType', $contact_Personne);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $contact_Personne->setSite($sites);
            $em->persist($contact_Personne);
            $em->flush();

            return $this->redirectToRoute('contact_personne_index', array('projectname' => $projectname,
                        'sitename' => $sitename));
        }

        return $this->render('contact_personne/new.html.twig', array(
            'contact_Personne' => $contact_Personne,
            'form' => $form->createView(),
            'projectname' => $projectname,
            'sitename' => $sitename
        ));
    }

    /**
     * Finds and displays a Contact_Personne entity.
     *
     * @Route("/{id}", name="contact_personne_show")
     * @Method("GET")
     */
    public function showAction(Contact_Personne $contact_Personne , $projectname, $sitename)
    {
        $deleteForm = $this->createDeleteForm($contact_Personne, $projectname, $sitename);

        return $this->render('contact_personne/show.html.twig', array(
            'contact_Personne' => $contact_Personne,
            'delete_form' => $deleteForm->createView(),
            'projectname' => $projectname
        ));
    }

    /**
     * Displays a form to edit an existing Contact_Personne entity.
     *
     * @Route("/{id}/edit", name="contact_personne_edit")
     * @Method({"GET", "POST"})
     */
    public function editAction(Request $request, $id,  $projectname, $sitename)
    {

        $idcontactpersonne = $this->get('nzo_url_encryptor')->decrypt($id);
        $contact_Personne = $this->getDoctrine()->getRepository('QSCORBundle:Contact_Personne')->find($idcontactpersonne);
        $projects = $this->AcessProject($projectname);
        if( count($projects) === 0) {
            return $this->redirectToRoute('homepage');
        }
        $sites = $this->AcessSite($sitename);
        if( count($sites) === 0) {
            return $this->redirectToRoute('site_index', array(
                'projectname'=> $projectname
            ));
        }

        $editForm = $this->createForm('QSCORBundle\Form\Contact_PersonneType', $contact_Personne);
        $editForm->handleRequest($request);

        if ($editForm->isSubmitted() && $editForm->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $contact_Personne->setSite($sites);
            $em->persist($contact_Personne);
            $em->flush();

            return $this->redirectToRoute('contact_personne_index', array('projectname' => $projectname,
                'sitename' => $sitename));
        }

        return $this->render('contact_personne/edit.html.twig', array(
            'contact_Personne' => $contact_Personne,
            'edit_form' => $editForm->createView(),
            'projectname' => $projectname
        ));
    }

    /**
     * Deletes a Contact_Personne entity.
     *
     * @Route("/{id}", name="contact_personne_delete")
     * @Method("DELETE")
     */
    public function deleteAction(Request $request, Contact_Personne $contact_Personne,  $projectname, $sitename)
    {
        $projects = $this->AcessProject($projectname);
        if( count($projects) === 0) {
            return $this->redirectToRoute('homepage');
        }
        $sites = $this->AcessSite($sitename);
        if( count($sites) === 0) {
            return $this->redirectToRoute('site_index', array(
                'projectname'=> $projectname
            ));
        }

        $form = $this->createDeleteForm($contact_Personne, $projectname, $sitename);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $em->remove($contact_Personne);
            $em->flush();
        }

        return $this->redirectToRoute('contact_personne_index', array('projectname' => $projectname,
                    'sitename' => $sitename));
    }

    /**
     * Creates a form to delete a Contact_Personne entity.
     *
     * @param Contact_Personne $contact_Personne The Contact_Personne entity
     *
     * @return \Symfony\Component\Form\Form The form
     */
    private function createDeleteForm(Contact_Personne $contact_Personne, $projectname, $sitename)
    {
        return $this->createFormBuilder()
            ->setAction($this->generateUrl('contact_personne_delete', array(
                'id' => $contact_Personne->getId(),
                'projectname' => $projectname,
                'sitename' => $sitename
            )))
            ->setMethod('DELETE')
            ->getForm()
        ;
    }
}
