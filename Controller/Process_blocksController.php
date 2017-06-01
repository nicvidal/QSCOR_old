<?php

namespace QSCORBundle\Controller;

use Symfony\Component\HttpFoundation\Request;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use QSCORBundle\Entity\Process_blocks;
use QSCORBundle\Form\Process_blocksType;
trait ControllerTraitForProcessType{
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
 * Process_blocks controller.
 *
 * @Route("/projects/{projectname}/modelling/{idsite}/level/{idlevel}/process_block")
 */
class Process_blocksController extends Controller
{
    use ControllerTraitForProcessType;
    /**
     * Lists all Process_blocks entities.
     *
     * @Route("/", name="process_blocks_index")
     * @Method("GET")
     */
    public function indexAction()
    {
        $em = $this->getDoctrine()->getManager();

        $process_blocks = $em->getRepository('QSCORBundle:Process_blocks')->findAll();

        return $this->render('process_blocks/index.html.twig', array(
            'process_blocks' => $process_blocks,
        ));
    }

    /**
     * Creates a new Process_blocks entity.
     *
     * @Route("/new", name="process_blocks_new")
     * @Method({"GET", "POST"})
     */
    public function newAction(Request $request)
    {
        $process_block = new Process_blocks();
        $form = $this->createForm('QSCORBundle\Form\Process_blocksType', $process_block);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $em->persist($process_block);
            $em->flush();

            return $this->redirectToRoute('process_blocks_show', array('id' => $process_block->getId()));
        }

        return $this->render('process_blocks/new.html.twig', array(
            'process_block' => $process_block,
            'form' => $form->createView(),
        ));
    }

    /**
     * Finds and displays a Process_blocks entity.
     *
     * @Route("/{id}", name="process_blocks_show")
     * @Method("GET")
     */
    public function showAction(Process_blocks $process_block)
    {
        $deleteForm = $this->createDeleteForm($process_block);

        return $this->render('process_blocks/show.html.twig', array(
            'process_block' => $process_block,
            'delete_form' => $deleteForm->createView(),
        ));
    }

    /**
     * Displays a form to edit an existing Process_blocks entity.
     *
     * @Route("/{id}/edit", name="process_blocks_edit")
     * @Method({"GET", "POST"})
     */
    public function editAction(Request $request, $id, $projectname)
    {

        $projects = $this->AcessProject($projectname);
        if( count($projects) === 0) {
            return $this->redirectToRoute('homepage');
        }
        $em = $this->getDoctrine()->getManager();

        $idprocess_type = $this->get('nzo_url_encryptor')->decrypt($id);

        $process_Type = $em->getRepository('QSCORBundle:Process_blocks')->find($idprocess_type);
        $libelleprocesstype = $process_Type->getLibelleType();
        $editForm = $this->createForm('QSCORBundle\Form\Process_blocksType', $process_Type);
        $editForm->handleRequest($request);

        if ($editForm->isSubmitted() && $editForm->isValid()) {
            $process_Type->setLibelleType($libelleprocesstype);
            $em->persist($process_Type);
            $em->flush();

            return $this->redirectToRoute('modelling_level_type_index', array(
                'projectname' => $request->get('projectname'),
                'idsite'      => $request->get('idsite'),
                'idlevel'     => $request->get('idlevel')
            ));
        }

        return $this->render('process_blocks/edit.html.twig', array(
            'process_block' => $process_Type,
            'edit_form' => $editForm->createView(),
        ));
    }

    /**
     * Deletes a Process_blocks entity.
     *
     * @Route("/{id}", name="process_blocks_delete")
     * @Method("DELETE")
     */
    public function deleteAction(Request $request, Process_blocks $process_block)
    {
        $form = $this->createDeleteForm($process_block);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $em->remove($process_block);
            $em->flush();
        }

        return $this->redirectToRoute('process_blocks_index');
    }

    /**
     * Creates a form to delete a Process_blocks entity.
     *
     * @param Process_blocks $process_block The Process_blocks entity
     *
     * @return \Symfony\Component\Form\Form The form
     */
    private function createDeleteForm(Process_blocks $process_block)
    {
        return $this->createFormBuilder()
            ->setAction($this->generateUrl('process_blocks_delete', array('id' => $process_block->getId())))
            ->setMethod('DELETE')
            ->getForm()
        ;
    }
}
