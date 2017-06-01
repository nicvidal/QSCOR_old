<?php

namespace QSCORBundle\Controller;

use QSCORBundle\Entity\Blocks_Type;
use QSCORBundle\Entity\Level;
use QSCORBundle\Entity\Process_blocks;
use Symfony\Component\Finder\Finder;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use QSCORBundle\Entity\Level_Type;
use QSCORBundle\Form\Level_TypeType;


trait ControllerTraitForLevelType{
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
 * Level_Type controller.
 *
 * @Route("/projects/{projectname}/modelling/{idsite}/level/level_type")
 */
class Level_TypeController extends Controller
{
    use ControllerTraitForLevelType;
    /**
     * Lists all Level_Type entities.
     *
     * @Route("/{idlevel}", name="modelling_level_type_index")
     * @Method("GET")
     */
    public function indexAction( $projectname, $idlevel)
    {

        $projects = $this->AcessProject($projectname);
        if( count($projects) === 0) {
            return $this->redirectToRoute('homepage');
        }
        $em = $this->getDoctrine()->getManager();
//        $session = $this->get('session');
//        $session->set('idlevel', $idlevel);

        $level_id = $this->get('nzo_url_encryptor')->decrypt($idlevel);
        $level = $em->getRepository('QSCORBundle:Level')->find($level_id);



        $level_Types = null;
        $levelprev_Types = null;
        $blocks_types_json = null;

        $projects = $this->AcessProject($projectname);
        if( count($projects) === 0) {
            return $this->redirectToRoute('homepage');
        }
        if($level->getLibelleLevel() == 'level1'){
            $level_Types = $em->getRepository('QSCORBundle:Level_Type')->findAll();
            return $this->render('level_type/level1.html.twig', array(
                'level_Types' => $level_Types,
                'level'       => $level,
                'projectname' => $projectname,
            ));
        }elseif($level->getLibelleLevel() == 'level2'){
            $levelprev = $em->getRepository('QSCORBundle:Level')->findOneBy(
                array('libelleLevel'=> 'level1')
            );
            $levelprev_Types = $levelprev->getLevelsTypes();

//            var_dump(count($levelprev_Types));
//            die('ok');

            $blockstypes = $em->getRepository('QSCORBundle:Blocks_Type')->findAll();
            $kernel =$this->get('kernel');
            $file = $kernel->locateResource('@QSCORBundle/Resources/json/blocks_types.json');

            $blocks_types_json = json_decode(file_get_contents($file), true);
            return $this->render('level_type/level2.html.twig', array(
                'blocksjson'=> $blocks_types_json,
                'levelprev_types' => $levelprev_Types,
                'blockstypes'=>$blockstypes,
                'projectname' => $projectname,
                'level'       => $level
            ));
        }elseif($level->getLibelleLevel() == 'level3'){
            $levelprev = $em->getRepository('QSCORBundle:Level')->findOneBy(
                array('libelleLevel'=> 'level1')
            );
            $levelprev_Types = $levelprev->getLevelsTypes();
//
////            var_dump(count($levelprev_Types));
////            die('ok');
//
            $processblocks= $em->getRepository('QSCORBundle:Process_blocks')->findAll();
            $kernel =$this->get('kernel');
            $file = $kernel->locateResource('@QSCORBundle/Resources/json/process_blocks.json');
//
            $processblocks_json = json_decode(file_get_contents($file), true);
            return $this->render('level_type/level3.html.twig', array(
                'processjson'=> $processblocks_json,
                'levelprev_types' => $levelprev_Types,
                'processblocks'=>$processblocks,
                'projectname' => $projectname,
                'level'       => $level
            ));
        }



        return $this->render('level_type/index.html.twig', array(
            'level_Types' => $level_Types,
            'projectname' => $projectname,
            'level'       => $level,
            'levelprev_types' => $levelprev_Types,
            'blocksjson'=> $blocks_types_json
        ));
    }

    /**
     * Creates a new Level_Type entity.
     *
     * @Route("/{idlevel}", name="level_types_creation")
     * @Method({"GET", "POST"})
     */
    public function createtypelevelAction(Request $request, $idlevel){


        $em = $this->getDoctrine()->getManager();
        $idlevel = $this->get('nzo_url_encryptor')->decrypt($idlevel);
        $level = $em->getRepository('QSCORBundle:Level')->find($idlevel);
//        var_dump($request->request);
        if($level->getLibelleLevel() == 'level1'){
            foreach ($request->request as $value){

                $this->createLevelsTypes($value, $level);

            }
        }elseif($level->getLibelleLevel() == 'level2') {
            $kernel =$this->get('kernel');
            $file = $kernel->locateResource('@QSCORBundle/Resources/json/blocks_types.json');


            $blocks_types_json = json_decode(file_get_contents($file));
            var_dump($request->request);
            foreach ($request->request as $value) {
                $this->createBlocksTypes($value, $blocks_types_json);
            }

        }elseif($level->getLibelleLevel() == 'level3'){

            $kernel =$this->get('kernel');
            $file = $kernel->locateResource('@QSCORBundle/Resources/json/process_blocks.json');
            $process_types_json = json_decode(file_get_contents($file));
//            var_dump($request->request);
//            die('ok');
            foreach ($request->request as $value) {
                $this->createProcessTypes($value, $process_types_json);
            }


        }

        return $this->redirectToRoute('modelling_level_type_index', array(
            'projectname' => $request->get('projectname'),
            'idsite'      => $request->get('idsite'),
            'idlevel'     => $this->get('nzo_url_encryptor')->encrypt($idlevel)
        ));
    }
    public function createLevelsTypes($idleveltype, $level ){
        if( $idleveltype != 'null') {
            $idleveltype = $this->get('nzo_url_encryptor')->decrypt($idleveltype);

            $em = $this->getDoctrine()->getManager();
            $level_Type = $em->getRepository('QSCORBundle:Level_Type')->find($idleveltype);
            $level->addLevelsType($level_Type);
            $em->persist($level);
            $em->flush();
//
        }
    }

    public function createBlocksTypes($libelleabr, $blocks_types_json ){
        if( $libelleabr != 'null') {

//            echo count($blocks_types_json);
            $em = $this->getDoctrine()->getManager();
            foreach ($blocks_types_json as $block => $key){

                for($i = 0 ; $i < count($key); $i++){
                    if($block == 'deliver return' || $block == 'source return') {
                        $level_Types = $em->getRepository('QSCORBundle:Level_Type')->findOneBy(array(
                            'libelleType' => 'return'
                        ));
                    }else{
                        $level_Types = $em->getRepository('QSCORBundle:Level_Type')->findOneBy(array(
                            'libelleType' => $block
                        ));
                    }
//////                    var_dump($block[$i]['libelleAbr']);
                    if($key[$i]->libelleAbr == $libelleabr){
//
                        $blocktype = new Blocks_Type();
                        $blocktype->setLibelleBlocksType($key[$i]->libelleBlocksType);
                        $blocktype->setLibelleAbr($libelleabr);
                        $blocktype->setDescription($key[$i]->description);
                        $blocktype->setComment('this is comment');
                        $blocktype->setLevelType($level_Types);
                        $em->persist($blocktype);
                        $em->flush();
                    }
                }
            }
        }
    }

    public function createProcessTypes($libelleabr, $process_types_json ){

        if( $libelleabr != 'null') {



//            echo count($blocks_types_json);
            $em = $this->getDoctrine()->getManager();
            foreach ($process_types_json as $block => $key){

                for($i = 0 ; $i < count($key); $i++){
//                    if($block == 'deliver return' || $block == 'source return') {
//                        $level_Types = $em->getRepository('QSCORBundle:Level_Type')->findOneBy(array(
//                            'libelleType' => 'return'
//                        ));
//                    }else{
                        $block_Types = $em->getRepository('QSCORBundle:Blocks_Type')->findOneBy(array(
                            'libelleAbr' => $block
                        ));
//                    }
//                    var_dump($block);
////////                    var_dump($block[$i]['libelleAbr']);'
//                    die('ok');
                    if($key[$i]->libelleAbr == $libelleabr){
//
                        $processtype = new Process_blocks();
                        $processtype->setLibelleType($key[$i]->libelleProcessType);
                        $processtype->setLibelleAbr($libelleabr);
                        $processtype->setDescription($key[$i]->description);
                        $processtype->setComment('this is comment');
                        $processtype->setBlockType($block_Types);
                        $em->persist($processtype);
                        $em->flush();
                    }
                }
            }
        }
    }




    /**
     * @Route("{idlevel}/delete/block/{idblocktype}", name="delete_lvltwo_blocktype")
     * @Method({"GET", "POST"})
     */
    public function deleteblocktypelevel(Request $request,$idblocktype, $idlevel){

//        die('ok');
        $em = $this->getDoctrine()->getManager();
        $idblocktype = $this->get('nzo_url_encryptor')->decrypt($idblocktype);
        $blocktype = $em->getRepository('QSCORBundle:Blocks_Type')->find($idblocktype);
        foreach ( $blocktype->getProcessBlocks()as $process_block){
            $blocktype->removeProcessBlock($process_block);
        }

        $em->remove($blocktype);
        $em->flush();
        return $this->redirectToRoute('modelling_level_type_index', array(
            'projectname' => $request->get('projectname'),
            'idsite'      => $request->get('idsite'),
            'idlevel'     => $idlevel
        ));

    }

    /**
     * @Route("{idlevel}/delete/process/{idprocesstype}", name="delete_lvlthree_processtype")
     * @Method({"GET", "POST"})
     */
    public function deleteprocesstypelevel(Request $request,$idprocesstype, $idlevel){


        $em = $this->getDoctrine()->getManager();
        $idprocesstype = $this->get('nzo_url_encryptor')->decrypt($idprocesstype);
        $processtype = $em->getRepository('QSCORBundle:Process_blocks')->find($idprocesstype);
        $em->remove($processtype);
        $em->flush();
        return $this->redirectToRoute('modelling_level_type_index', array(
            'projectname' => $request->get('projectname'),
            'idsite'      => $request->get('idsite'),
            'idlevel'     => $idlevel
        ));

    }

    /**
     * Creates a new Level_Type entity.
     *
     * @Route("/{idlevel}/delete", name="level_types_deleteall")
     * @Method({"GET", "POST"})
     */
    public function deleteAlltypelevelAction(Request $request,$idlevel)
    {



        $em = $this->getDoctrine()->getManager();
        $idlevel = $this->get('nzo_url_encryptor')->decrypt($idlevel);
        $level = $em->getRepository('QSCORBundle:Level')->find($idlevel);

        foreach ( $level->getLevelsTypes()as $level_type){
            foreach ( $level_type->getBlockType() as $block_type){

//                $level_type->removeBlockType($block_type);
                $em->remove($block_type);
            }
                $level->removeLevelsType($level_type);
        }


        $em->persist($level);
        $em->flush();

        return $this->redirectToRoute('modelling_level_type_index', array(
            'projectname' => $request->get('projectname'),
            'idsite'      => $request->get('idsite'),
            'idlevel'     => $this->get('nzo_url_encryptor')->encrypt($idlevel)
        ));
    }
    /**
     * Creates a new Level_Type entity.
     *
     * @Route("/new", name="modelling_level_type_new")
     * @Method({"GET", "POST"})
     */
    public function newAction(Request $request)
    {
        $level_Type = new Level_Type();
        $form = $this->createForm('QSCORBundle\Form\Level_TypeType', $level_Type);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $em->persist($level_Type);
            $em->flush();

            return $this->redirectToRoute('modelling_level_type_show', array('id' => $level_Type->getId()));
        }

        return $this->render('level_type/new.html.twig', array(
            'level_Type' => $level_Type,
            'form' => $form->createView(),
        ));
    }

    /**
     * Finds and displays a Level_Type entity.
     *
     * @Route("/{id}", name="modelling_level_type_show")
     * @Method("GET")
     */
    public function showAction(Level_Type $level_Type)
    {
        $deleteForm = $this->createDeleteForm($level_Type);

        return $this->render('level_type/show.html.twig', array(
            'level_Type' => $level_Type,
            'delete_form' => $deleteForm->createView(),
        ));
    }

    /**
     * Displays a form to edit an existing Level_Type entity.
     *
     * @Route("/{id}/edit", name="modelling_level_type_edit")
     * @Method({"GET", "POST"})
     */
    public function editAction(Request $request, $id )
    {
//        $deleteForm = $this->createDeleteForm($level_Type);
        $em = $this->getDoctrine()->getManager();


        $idlevel_type = $this->get('nzo_url_encryptor')->decrypt($id);
        $level_Type = $em->getRepository('QSCORBundle:Level_Type')->find($idlevel_type);
        $libelletypelevel = $level_Type->getLibelleType();
        $editForm = $this->createForm('QSCORBundle\Form\Level_TypeType', $level_Type);
        $editForm->handleRequest($request);
//        var_dump($level_Type);
//        die('ok');

        if ($editForm->isSubmitted() && $editForm->isValid()) {

            $level_Type->setLibelleType($libelletypelevel);
            $em->persist($level_Type);
            $em->flush();

//            return $this->redirectToRoute('modelling_level_type_index', array('id' => $level_Type->getId()));
            return $this->redirectToRoute('modelling_level_type_index', array(
                'projectname' => $request->get('projectname'),
                'idsite'      => $request->get('idsite'),
                'idlevel'     => $request->get('idlevel')
            ));
        }

        return $this->render('level_type/edit.html.twig', array(
            'level_Type' => $level_Type,
            'edit_form' => $editForm->createView(),
//            'delete_form' => $deleteForm->createView(),
        ));
    }

    /**
     * Deletes a Level_Type entity.
     *
     * @Route("/{id}", name="modelling_level_type_delete")
     * @Method("DELETE")
     */
    public function deleteAction(Request $request, Level_Type $level_Type)
    {
        $form = $this->createDeleteForm($level_Type);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $em->remove($level_Type);
            $em->flush();
        }

        return $this->redirectToRoute('modelling_level_type_index');
    }

    /**
     * Creates a form to delete a Level_Type entity.
     *
     * @param Level_Type $level_Type The Level_Type entity
     *
     * @return \Symfony\Component\Form\Form The form
     */
    private function createDeleteForm(Level_Type $level_Type)
    {
        return $this->createFormBuilder()
            ->setAction($this->generateUrl('modelling_level_type_delete', array('id' => $level_Type->getId())))
            ->setMethod('DELETE')
            ->getForm()
        ;
    }
}
