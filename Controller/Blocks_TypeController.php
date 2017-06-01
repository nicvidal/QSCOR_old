<?php

namespace QSCORBundle\Controller;

use Symfony\Component\HttpFoundation\Request;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use QSCORBundle\Entity\Blocks_Type;
use QSCORBundle\Form\Blocks_TypeType;

trait ControllerTraitForBlockType{
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
 * Blocks_Type controller.
 *
 * @Route("/projects/{projectname}/modelling/{idsite}/level/{idlevel}/block_type")
 */
class Blocks_TypeController extends Controller
{
    use ControllerTraitForBlockType;
    /**
     * Lists all Blocks_Type entities.
     *
     * @Route("/", name="blocks_type_index")
     * @Method("GET")
     */
    public function indexAction()
    {
        $em = $this->getDoctrine()->getManager();

        $blocks_Types = $em->getRepository('QSCORBundle:Blocks_Type')->findAll();

        return $this->render('blocks_type/index.html.twig', array(
            'blocks_Types' => $blocks_Types,
        ));
    }

    /**
     * Creates a new Blocks_Type entity.
     *
     * @Route("/new", name="blocks_type_new")
     * @Method({"GET", "POST"})
     */
    public function newAction(Request $request)
    {
        $blocks_Type = new Blocks_Type();
        $form = $this->createForm('QSCORBundle\Form\Blocks_TypeType', $blocks_Type);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $em->persist($blocks_Type);
            $em->flush();

            return $this->redirectToRoute('blocks_type_show', array('id' => $blocks_Type->getId()));
        }

        return $this->render('blocks_type/new.html.twig', array(
            'blocks_Type' => $blocks_Type,
            'form' => $form->createView(),
        ));
    }

    /**
     * Finds and displays a Blocks_Type entity.
     *
     * @Route("/{id}", name="blocks_type_show")
     * @Method("GET")
     */
    public function showAction(Blocks_Type $blocks_Type)
    {
        $deleteForm = $this->createDeleteForm($blocks_Type);

        return $this->render('blocks_type/show.html.twig', array(
            'blocks_Type' => $blocks_Type,
            'delete_form' => $deleteForm->createView(),
        ));
    }

    /**
     * Displays a form to edit an existing Blocks_Type entity.
     *
     * @Route("/{id}/edit", name="blocks_type_edit")
     * @Method({"GET", "POST"})
     */
    public function editAction(Request $request,  $id, $projectname)
    {


        $em = $this->getDoctrine()->getManager();

        $idblock_type = $this->get('nzo_url_encryptor')->decrypt($id);

        $block_Type = $em->getRepository('QSCORBundle:Blocks_Type')->find($idblock_type);
        $libelleblocktype = $block_Type->getLibelleBlocksType();

        $editForm = $this->createForm('QSCORBundle\Form\Blocks_TypeType', $block_Type);
//        var_dump($block_Type);
        $projects = $this->AcessProject($projectname);
        if( count($projects) === 0) {
            return $this->redirectToRoute('homepage');
        }


        $editForm->handleRequest($request);


        if ($editForm->isSubmitted() && $editForm->isValid()) {

            $block_Type->setLibelleBlocksType($libelleblocktype);
            $em->persist($block_Type);
            $em->flush();

            return $this->redirectToRoute('modelling_level_type_index', array(
                'projectname' => $request->get('projectname'),
                'idsite'      => $request->get('idsite'),
                'idlevel'     => $request->get('idlevel')
            ));
        }


        return $this->render('blocks_type/edit.html.twig', array(
            'blocks_Type' =>  $block_Type,
            'edit_form' => $editForm->createView(),
//            'delete_form' => $deleteForm->createView(),
        ));
    }

    /**
     * Deletes a Blocks_Type entity.
     *
     * @Route("/{id}", name="blocks_type_delete")
     * @Method("DELETE")
     */
    public function deleteAction(Request $request, Blocks_Type $blocks_Type)
    {
        $form = $this->createDeleteForm($blocks_Type);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $em->remove($blocks_Type);
            $em->flush();
        }

        return $this->redirectToRoute('blocks_type_index');
    }

    /**
     * Creates a form to delete a Blocks_Type entity.
     *
     * @param Blocks_Type $blocks_Type The Blocks_Type entity
     *
     * @return \Symfony\Component\Form\Form The form
     */
    private function createDeleteForm(Blocks_Type $blocks_Type)
    {
        return $this->createFormBuilder()
            ->setAction($this->generateUrl('blocks_type_delete', array('id' => $blocks_Type->getId())))
            ->setMethod('DELETE')
            ->getForm()
        ;
    }
}
