<?php

namespace QSCORBundle\Controller;

use Doctrine\ORM\Persisters\Collection\CollectionPersister;
use Proxies\__CG__\QSCORBundle\Entity\CountryMaps;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use QSCORBundle\Entity\Company;
use QSCORBundle\Form\CompanyType;


trait ControllerTraitForCompany{
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
 * Company controller.
 * @Route("/projects/{projectname}/company", name="project_company")
 */
class CompanyController extends Controller
{

    use ControllerTraitForCompany;
    /**
     * Lists all Company entities.
     *
     * @Route("/", name="company_index")
     * @Method("GET")
     */
    public function indexAction(Request $request, $projectname)
    {
        $em = $this->getDoctrine()->getManager();

        $projects = $this->AcessProject($projectname);
        if( count($projects) === 0) {
            return $this->redirectToRoute('homepage');
        }

        $companies = $em->getRepository('QSCORBundle:Company')->findByProject($projects->getId());
        $deleteForm = $this->createFormBuilder()->getForm();
        $company = new Company();
        $new_form = $this->createForm('QSCORBundle\Form\CompanyType', $company);

        $paginator  = $this->get('knp_paginator');
        $pagination = $paginator->paginate(
            $companies,
            $request->query->get('page', 1)/*page number*/,
            10/*limit per page*/
        );

//        $session = $this->get('session');


        return $this->render('company/index.html.twig', array(
//            'companies' => $companies,
            'projectname' => $projectname,
            'pagination' => $pagination,
            'delete_form' => $deleteForm->createView(),
            'new_form' => $new_form->createView()

        ));
    }

    /**
     * Creates a new Company entity.
     *
     * @Route("/new/", name="company_new")
     * @Method({"GET", "POST"})
     */
    public function newAction(Request $request, $projectname)
    {

        $company = new Company();
        $projects = $this->AcessProject($projectname);
        if( count($projects) === 0) {
            return $this->redirectToRoute('homepage');
        }


        $form = $this->createForm('QSCORBundle\Form\CompanyType', $company);
        $form->handleRequest($request);

        $session = $this->get('session');


        if ($form->isSubmitted() && $form->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $company->setProject($projects);
            $em->persist($company);
            $em->flush();

//            return $this->redirectToRoute('company_show', array('id' => $company->getId()));


            return $this->redirectToRoute($session->get('routename'), array( 'projectname' => $request->get('projectname')));
        }
//        else if ($form->isSubmitted() && !$form->isValid()){
////            var_dump($namepage);
////            die('ok');
//            $session->set('error', 1);
//            return $this->redirectToRoute('company_index', array( 'projectname' => $request->get('projectname')));
//        }
//                die('ok');

//        return $this->render('company/new.html.twig', array(
//            'company' => $company,
//            'form' => $form->createView(),
//            'projectname' => $projectname
//        ));
        return $this->render('company/new.html.twig', array(
            'company' => $company,
            'form' => $form->createView(),
            'projectname' => $projectname,
            'new_form'=>$form->createView()
        ));
    }

    /**
     * Finds and displays a Company entity.
     *
     * @Route("/{id}", name="company_show")
     * @Method("GET")
     */
    public function showAction(Company $company, $projectname)
    {

        $deleteForm  = $this->createDeleteForm($company, $projectname);

        return $this->render('company/show.html.twig', array(
            'company' => $company,
            'delete_form' => $deleteForm->createView(),
            'projectname' => $projectname

        ));
    }

    /**
     * Displays a form to edit an existing Company entity.
     *
     * @Route("/{id}/edit", name="company_edit")
     * @Method({"GET", "POST"})
     */
    public function editAction(Request $request, $id, $projectname)
    {

//        $company = new Company();
//        $em = $this->getDoctrine()->getManager();
//        $company = $em->getRepository('QSCORBundle:Company')->find($id);
//        die('ok');
        $idcompany = $this->get('nzo_url_encryptor')->decrypt($id);
        $company = $this->getDoctrine()->getRepository('QSCORBundle:Company')->find($idcompany);
        $editForm = $this->createForm('QSCORBundle\Form\CompanyType', $company);
        $editForm->handleRequest($request);

        if ($editForm->isSubmitted() && $editForm->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $em->persist($company);
            $em->flush();

//            if($this->get('router')->)
            $session = $this->get('session');
            return $this->redirectToRoute($session->get('routename'), array( 'projectname' => $request->get('projectname')));
        }

        return $this->render('company/edit.html.twig', array(
            'company' => $company,
            'edit_form' => $editForm->createView(),
            'projectname' => $projectname
//            'delete_form' => $deleteForm->createView(),
        ));
    }

    /**
     * Deletes a Company entity.
     *
     * @Route("/{id}", name="company_delete")
     * @Method("DELETE")
     */
    public function deleteAction(Request $request, Company $company, $projectname)
    {

        $session = $this->get('session');


//        var_dump();
//        die('ok');
        $projects = $this->AcessProject($projectname);
        if( count($projects) === 0) {
            return $this->redirectToRoute('homepage');
        }
        $form = $this->createDeleteForm($company, $projectname);
        $form->handleRequest($request);

//        var_dump($form->isSubmitted());
//        die('ok');
//        var_dump($form);
//        die("ok");

            $em = $this->getDoctrine()->getManager();
            $em->remove($company);
            $em->flush();

        return $this->redirectToRoute($session->get('routename'),array('projectname' => $projectname));
//        var_dump($request->get('_route'));
//        die('ok');



    }

    /**
     * Creates a form to delete a Company entity.
     *
     * @return \Symfony\Component\Form\Form The form
     */
    private function createDeleteForm(Company $company, $projectname)
    {
        return $this->createFormBuilder()
            ->setAction($this->generateUrl('company_delete', array('id' => $company->getId(),
                'projectname' => $projectname)))
            ->setMethod('DELETE')
            ->getForm()
        ;
    }



}
