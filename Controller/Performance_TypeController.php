<?php

namespace QSCORBundle\Controller;

use Symfony\Component\HttpFoundation\Request;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use QSCORBundle\Entity\Performance_Type;
use QSCORBundle\Form\Performance_TypeType;

trait ControllerTraitForPerformanceType{
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
 * Performance_Type controller.
 *
 * @Route("/projects/{projectname}/performance/performance_type")
 */
class Performance_TypeController extends Controller
{
    use ControllerTraitForPerformanceType;
    /**
     * Lists all Performance_Type entities.
     *
     * @Route("/", name="performance_type_index")
     * @Method("GET")
     */
    public function indexAction()
    {
        $em = $this->getDoctrine()->getManager();

        $performance_Types = $em->getRepository('QSCORBundle:Performance_Type')->findAll();

        return $this->render('performance_type/index.html.twig', array(
            'performance_Types' => $performance_Types,
        ));
    }


    /**
     * Creates a new Performance_Type entity.
     *
     * @Route("{id}/deleteperformance", name="perfromance_delete")
     * @Method({"GET", "POST"})
     *
     */
    public function deleteAllPerformance(Request $request, $id, $projectname){

        $projects = $this->AcessProject($projectname);
        if( count($projects) === 0) {
            return $this->redirectToRoute('homepage');
        }

        $em = $this->getDoctrine()->getEntityManager();
        $performancetype = $em->getRepository('QSCORBundle:Performance_Type')->find($id);
        $em->remove($performancetype);
        $em->flush();

        $performance = $em->getRepository('QSCORBundle:Performance_Type')->findByProject($projects->getId());
        return $this->redirectToRoute('performancemode', array(
            'projectname' => $projectname,
            'performancetype' => $performance
        ));

    }
    /**
     * Creates a new Performance_Type entity.
     *
     * @Route("/createperformance", name="perfromance_creation")
     * @Method({"GET", "POST"})
     *
     */
    public function craeteperformanceAction(Request $request, $projectname){

        $projects = $this->AcessProject($projectname);
        if( count($projects) === 0) {
            return $this->redirectToRoute('homepage');
        }

        foreach ($request->request as $value => $key){
            $this->createPerformanceType($value, $projects);
//            var_dump($value);
        }
        $em = $this->getDoctrine()->getEntityManager();
        $performance = $em->getRepository('QSCORBundle:Performance_Type')->findByProject($projects->getId());
       return $this->redirectToRoute('performancemode', array(
           'projectname' => $projectname,
           'performancetype' => $performance
       ));
    }

    public function createPerformanceType( $value , $project){

        $em = $this->getDoctrine()->getEntityManager();
        $performancetype = new Performance_Type();
        $performancetype->setLibellePerformance($value);
        $performancetype->setProject($project);
        $em->persist($performancetype);
        $em->flush();

    }

    /**
     * Creates a new Performance_Type entity.
     *
     * @Route("/new", name="performance_type_new")
     * @Method({"GET", "POST"})
     */
    public function newAction(Request $request)
    {
        $performance_Type = new Performance_Type();
        $form = $this->createForm('QSCORBundle\Form\Performance_TypeType', $performance_Type);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $em->persist($performance_Type);
            $em->flush();

            return $this->redirectToRoute('performance_type_show', array('id' => $performance_Type->getId()));
        }

        return $this->render('performance_type/new.html.twig', array(
            'performance_Type' => $performance_Type,
            'form' => $form->createView(),
        ));
    }

    /**
     * Finds and displays a Performance_Type entity.
     *
     * @Route("/{id}", name="performance_type_show")
     * @Method("GET")
     */
    public function showAction(Performance_Type $performance_Type)
    {
        $deleteForm = $this->createDeleteForm($performance_Type);

        return $this->render('performance_type/show.html.twig', array(
            'performance_Type' => $performance_Type,
            'delete_form' => $deleteForm->createView(),
        ));
    }

    /**
     * Displays a form to edit an existing Performance_Type entity.
     *
     * @Route("/{id}/edit", name="performance_type_edit")
     * @Method({"GET", "POST"})
     */
    public function editAction(Request $request, Performance_Type $performance_Type, $projectname)
    {
//        $deleteForm = $this->createDeleteForm($performance_Type);
        $editForm = $this->createForm('QSCORBundle\Form\Performance_TypeType', $performance_Type);
        $editForm->handleRequest($request);

        $projects = $this->AcessProject($projectname);
        if( count($projects) === 0) {
            return $this->redirectToRoute('homepage');
        }
        if ($editForm->isSubmitted() && $editForm->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $performance_Type->setProject($projects);
            $em->persist($performance_Type);
            $em->flush();

            return $this->redirectToRoute('performance_type_edit', array('id' => $performance_Type->getId()));
        }

        return $this->render('performance_type/edit.html.twig', array(
            'performance_Type' => $performance_Type,
            'projectname'      => $projectname,
            'edit_form' => $editForm->createView(),
//            'delete_form' => $deleteForm->createView(),
        ));
    }

    /**
     * Deletes a Performance_Type entity.
     *
     * @Route("/{id}", name="performance_type_delete")
     * @Method("DELETE")
     */
    public function deleteAction(Request $request, Performance_Type $performance_Type)
    {
        $form = $this->createDeleteForm($performance_Type);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $em->remove($performance_Type);
            $em->flush();
        }

        return $this->redirectToRoute('performance_type_index');
    }

    /**
     * Creates a form to delete a Performance_Type entity.
     *
     * @param Performance_Type $performance_Type The Performance_Type entity
     *
     * @return \Symfony\Component\Form\Form The form
     */
    private function createDeleteForm(Performance_Type $performance_Type)
    {
        return $this->createFormBuilder()
            ->setAction($this->generateUrl('performance_type_delete', array('id' => $performance_Type->getId())))
            ->setMethod('DELETE')
            ->getForm()
        ;
    }
}
