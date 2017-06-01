<?php

namespace QSCORBundle\Controller;

use QSCORBundle\Entity\Level;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use QSCORBundle\Entity\Site;
use QSCORBundle\Form\SiteType;

trait ControllerTraitForSites{
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
 * Site controller.
 * @Route("/projects/{projectname}/sites")
 */
class   SiteController extends Controller
{

    use ControllerTraitForSites;

    /**
     * Lists all Site entities.
     *
     * @Route("/", name="site_index")
     * @Method("GET")
     */
    public function indexAction(Request $request, $projectname)
    {
        $em = $this->getDoctrine()->getManager();

        $projects = $this->AcessProject($projectname);
        if( count($projects) === 0) {
            return $this->redirectToRoute('homepage');
        }
        $sites = $em->getRepository('QSCORBundle:Site')->findByProject($projects->getId());
        $companies = $em->getRepository('QSCORBundle:Company')->findByProject($projects->getId());
        $facility = $em->getRepository('QSCORBundle:Type_Facility')->findByProject($projects->getId());
        $paginator  = $this->get('knp_paginator');
        $deleteForm = $this->createFormBuilder()->getForm();
        $paginationsite = $paginator->paginate(
            $sites,
            $request->query->get('page', 1)/*page number*/,
            10/*limit per page*/
        );


        return $this->render('site/index.html.twig', array(
            'paginationsites' => $paginationsite,
            'projectname'     => $projectname,
            'delete_form' => $deleteForm->createView(),
            "companies"       => $companies,
            "facilities"       => $facility
        ));
    }

    /**
     * Creates a new Site entity.
     *
     * @Route("/new", name="site_new")
     * @Method({"GET", "POST"})
     */
    public function newAction(Request $request, $projectname)
    {
        $projects = $this->AcessProject($projectname);
        if( count($projects) === 0) {
            return $this->redirectToRoute('homepage');
        }
        $site = new Site();

        //TODO make the array connection with the form
        $form = $this->createForm('QSCORBundle\Form\SiteType',$site , array('projectId'=>$projects));
//        $form = $this->get('form.factory')
//            ->create('QSCORBundle\Form\SiteType',null, $site);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $em->persist($site);
            $em->flush();
            for($i = 0; $i < 3 ;$i++){
                $levels = new Level();
                $j= $i +1;
                $levels->setLibelleLevel("level$j");
                $levels->setSite($site);
                $em->persist($levels);
                $em->flush();
            }



            return $this->redirectToRoute('site_index', array('projectname' => $projectname));
        }

        return $this->render('site/new.html.twig', array(
            'site' => $site,
            'form' => $form->createView(),
            'projectname' => $projectname
        ));
    }

    /**
     * Finds and displays a Site entity.
     *
     * @Route("/{id}", name="site_show")
     * @Method("GET")
     */
    public function showAction(Site $site, $projectname)
    {
        $deleteForm = $this->createDeleteForm($site , $projectname);


        return $this->render('site/show.html.twig', array(
            'site' => $site,
            'delete_form' => $deleteForm->createView(),
            'projectname' => $projectname
        ));
    }

    /**
     * Displays a form to edit an existing Site entity.
     *
     * @Route("/{id}/edit", name="site_edit")
     * @Method({"GET", "POST"})
     */
    public function editAction(Request $request, $id , $projectname)
    {

//        $id= $this->get('nzo_url_encryptor')->encrypt($id);
        $idsite = $this->get('nzo_url_encryptor')->decrypt($id);
        $site = $this->getDoctrine()->getRepository('QSCORBundle:Site')->find($idsite);
        $projects = $this->AcessProject($projectname);
        if( count($projects) === 0) {
            return $this->redirectToRoute('homepage');
        }
        $editForm = $this->createForm('QSCORBundle\Form\SiteType',$site , array('projectId'=>$projects));
        $editForm->handleRequest($request);


        if ($editForm->isSubmitted() && $editForm->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $em->persist($site);
            $em->flush();

            return $this->redirectToRoute('site_index', array('projectname' => $projectname));
        }

        return $this->render('site/edit.html.twig', array(
            'site' => $site,
            'edit_form' => $editForm->createView(),
            'projectname' => $projectname
        ));
    }

    /**
     * Deletes a Site entity.
     *
     * @Route("/{id}", name="site_delete")
     * @Method("DELETE")
     */
    public function deleteAction(Request $request, Site $site, $projectname)
    {
        $form = $this->createDeleteForm($site , $projectname);
        $form->handleRequest($request);
        $projects = $this->AcessProject($projectname);
        if( count($projects) === 0) {
            return $this->redirectToRoute('homepage');
        }

        $em = $this->getDoctrine()->getManager();
        $em->remove($site);
        $em->flush();

        return $this->redirectToRoute('site_index', array('projectname' => $projectname));
    }

    /**
     * Creates a form to delete a Site entity.
     *
     * @param Site $site The Site entity
     *
     * @return \Symfony\Component\Form\Form The form
     */

    private function createDeleteForm(Site $site, $projectname)
    {
        return $this->createFormBuilder()
            ->setAction($this->generateUrl('site_delete', array('id' => $site->getId(),
                'projectname' => $projectname)))
            ->setMethod('DELETE')
            ->getForm()
            ;
    }
}
