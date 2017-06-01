<?php

namespace QSCORBundle\Controller;

use QSCORBundle\Entity\Performance_Type;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use QSCORBundle\Entity\Project;
use QSCORBundle\Form\ProjectType;

trait ControllerTraitForProject{
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
 * Project controller.
 *
 * @Route("/projects")
 */
class ProjectController extends Controller
{
        use ControllerTraitForProject;
//    /**
//     * Lists all Project entities.
//     *
//     * @Route("/", name="project_index")
//     * @Method("GET")
//     */
//    public function indexAction()
//    {
//        $em = $this->getDoctrine()->getManager();
//
//        $projects = $em->getRepository('QSCORBundle:Project')->findByUser($this->getUser()->getId());
//
//
//
//        return $this->render('project/index.html.twig', array(
//            'projects' => $projects,
//        ));
//    }

    /**
     * Creates a new Project entity.
     *
     * @Route("/new", name="project_new")
     * @Method({"GET", "POST"})
     */
    public function newAction(Request $request)
    {
        $project = new Project();
        $form = $this->createForm('QSCORBundle\Form\ProjectType', $project);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $today = date("F j, Y, g:i a");
            $project->setDatecreation($today);
            $user = $this->getUser();
            $project->setUser($user);
//            $performancetype = new Performance_Type();

            $em->persist($project);
            $em->flush();

            return $this->redirectToRoute('homepage');

        }elseif (!$form->isValid() && $form->isSubmitted() ){
//            $this->get('session')->getFlashBag()->add('notice', "");
//            return $this->redirect('/');
//            die('ok');
            $em = $this->getDoctrine()->getManager();

            $projects = $em->getRepository('QSCORBundle:Project')->findByUser($this->getUser()->getId());
            $session = $this->get('session');
            $session->set('error',1);
            return $this->render('QSCORBundle:Workspace:projects.html.twig', array(
                'projects'=>$projects,
                'form' => $form->createView()
            ));

        }

        return $this->render('project/new.html.twig', array(
            'project' => $project,
            'form' => $form->createView(),
        ));
    }

    /**
     * Finds and displays a Project entity.
     *
     * @Route("/{id}", name="project_show")
     * @Method("GET")
     */
    public function showAction(Project $project)
    {
        $deleteForm = $this->createDeleteForm($project);

        return $this->render('project/show.html.twig', array(
            'project' => $project,
            'delete_form' => $deleteForm->createView(),
        ));
    }

    /**
     * Displays a form to edit an existing Project entity.
     *
     * @Route("/{projectname}/{id}/edit", name="project_edit")
     * @Method({"GET", "POST"})
     */
    public function editAction(Request $request, $id, $projectname)
    {

        $idproject = $this->get('nzo_url_encryptor')->decrypt($id);
        $project = $this->getDoctrine()->getRepository('QSCORBundle:Project')->find($idproject);
        if($project == null){
            //TODO make EXception
            return $this->redirectToRoute('homepage');
        }

        $projects = $this->AcessProject($projectname);
        if( count($projects) === 0) {
            return $this->redirectToRoute('homepage');
        }

        $deleteForm = $this->createDeleteForm($project);
        $editForm = $this->createForm('QSCORBundle\Form\ProjectType', $project);
        $editForm->handleRequest($request);


        if ($editForm->isSubmitted() && $editForm->isValid()) {

            $em = $this->getDoctrine()->getManager();
//            $today = date("F j, Y, g:i a");
//            $project->setDatecreation($today);
//            $user = $this->getUser();
//            $project->setUser($user);
            $em->persist($project);
            $em->flush();

            return $this->redirectToRoute('projects');
        }
//        else if (!$editForm->isValid() ) {
//            die('ok');
//        }
//        else if (!$editForm->isValid() && $editForm->isSubmitted() ){
////            $this->get('session')->getFlashBag()->add('notice', "");
////            return $this->redirect('/');
////            die('ok');
//            $session = $this->get('session');
//            $session->set('error', 1);
//
//            return $this->render('QSCORBundle:Workspace:settingproject.html.twig', array(
//                'projectname'=>$project->getName(),
//                'project'=>$project,
//                'form' => $editForm->createView(),
//            ));
//
//        }


        return $this->render('project/edit.html.twig', array(
            'project' => $project,
            'edit_form' => $editForm->createView(),
            'delete_form' => $deleteForm->createView(),
            'projectname' => $projectname,

        ));
    }

    /**
     * Deletes a Project entity.
     *
     * @Route("/{id}", name="project_delete")
     * @Method("DELETE")
     */
    public function deleteAction(Request $request, Project $project)
    {
        $form = $this->createDeleteForm($project);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $em->remove($project);
            $em->flush();
        }

        return $this->redirectToRoute('projects');
    }

    /**
     * Creates a form to delete a Project entity.
     *
     * @param Project $project The Project entity
     *
     * @return \Symfony\Component\Form\Form The form
     */
    private function createDeleteForm(Project $project)
    {
        return $this->createFormBuilder()
            ->setAction($this->generateUrl('project_delete', array('id' => $project->getId())))
            ->setMethod('DELETE')
            ->getForm()
        ;
    }
}
