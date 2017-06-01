<?php
/**
 * Created by PhpStorm.
 * User: mm
 * Date: 26/04/16
 * Time: 18:58
 */

namespace QSCORBundle\Controller;
use QSCORBundle\Entity\Company;
use QSCORBundle\Entity\Performance;
use QSCORBundle\Entity\Project;
use QSCORBundle\Entity\Type_Facility;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Serializer\Serializer;
use Symfony\Component\Serializer\Encoder\JsonEncoder;
use Symfony\Component\Serializer\Normalizer\ObjectNormalizer;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Ob\HighchartsBundle\Highcharts\Highchart;
use Symfony\Component\HttpFoundation\Session\Session;


trait ControllerTrait{
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
 * Workspace controller.
 *@Route("/projects")
 */

class WorkspaceController extends Controller
{

    use ControllerTrait;
    /**
     * @Route("/", name="projects")
     */
    public function projectAction(){

        $em = $this->getDoctrine()->getManager();

        $projects = $em->getRepository('QSCORBundle:Project')->findByUser($this->getUser()->getId());

        $project = new Project();
        $form = $this->createForm('QSCORBundle\Form\ProjectType', $project);
        $session = $this->get('session');
        $session->set('error',0);

        return $this->render('QSCORBundle:Workspace:projects.html.twig',
            array(
                'projects'=>$projects,
                'form'=> $form->createView(),
            ));

    }



    /**
     * @Route("/{projectname}/", name="project_action")
     *
     */
    public function projectactionAction($projectname){

        $projects = $this->AcessProject($projectname);
        if( count($projects) === 0) {
            return $this->redirectToRoute('homepage');
        }
        $session = $this->get('session');
        $session->set('projectid', $projects->getId());
//


        return $this->render('QSCORBundle:Workspace:index.html.twig', array(
            'projectname' => $projectname,
        ));
    }


    /**
     * @Route("/{projectname}/networking", name="networking")
     *
     */
    public function networkingAction(Request $request, $projectname){

//        die();
        $projects = $this->AcessProject($projectname);
        if( count($projects) === 0) {
            return $this->redirectToRoute('homepage');
        }
        $company = new Company();
        $form = $this->createForm('QSCORBundle\Form\CompanyType', $company);
        $em = $this->getDoctrine()->getManager();
        $session = $this->get('session');
        $session->set('routename', 'networking');


        $companies = $em->getRepository('QSCORBundle:Company')->findByProject($projects->getId());
        $paginator  = $this->get('knp_paginator');
        $pagination = $paginator->paginate(
            $companies,
            $request->query->get('page', 1)/*page number*/,
            8/*limit per page*/
        );
        return $this->render('QSCORBundle:Workspace:networking.html.twig', array(
            'form' => $form->createView(),
            'pagination' =>  $pagination,
            'projectname' => $projectname,

        ));
    }


    /**
     * @Route("/{projectname}/networking/mapsnetworking", name="networkingmaps")
     *
     */
    public function networkingMapsAction( $projectname){

        $session = $this->get('session');
        $session->set('routename', 'networking');
        $projects = $this->AcessProject($projectname);
        if( count($projects) === 0) {
            return $this->redirectToRoute('homepage');
        }

//        $normalizer = new ObjectNormalizer();
//        $normalizer->setIgnoredAttributes(array('company'));
//        $encoder = new JsonEncoder();
//
//        $serializer = new Serializer(array($normalizer), array($encoder));

        $normalizer = new ObjectNormalizer(null);
        $normalizer->setIgnoredAttributes(array('facility','project',
            'sites','companyorigin', 'companydestination', 'sitedestination', 'siteorigin'));
        $normalizer->setCircularReferenceHandler(function ($object) {
            return $object->getId();
        });
//        $normalizer->setIgnoredAttributes(array('company'));
        $encoder = new JsonEncoder();
//        $serializer = $this->get('serializer');
        $serializer = new Serializer(array($normalizer), array($encoder));

        $flowsites = $this->getDoctrine()->getRepository("QSCORBundle:Flow_Site")->findAll();
        $sites = $this->getDoctrine()->getRepository("QSCORBundle:Site")->findAll();

        $jsonflowsites =  $serializer->serialize( $flowsites, 'json');
        $jsonsites =  $serializer->serialize( $sites, 'json');

        return $this->render('QSCORBundle:Workspace:networkingmaps.html.twig',array(
            'projectname'=>$projectname,
            'flow_sites'=> $jsonflowsites,
            'sites'=> $jsonsites,
            'flowsites' => $flowsites
        ));


    }


    /**
     * @Route("/{projectname}/modelling", name="modelling")
     */
    public function modellingAction(Request $request,$projectname){
        $projects = $this->AcessProject($projectname);
        if( count($projects) === 0) {
            return $this->redirectToRoute('homepage');
        }
        $company = new Company();
        $form = $this->createForm('QSCORBundle\Form\CompanyType', $company);
        $em = $this->getDoctrine()->getManager();

        $session = $this->get('session');
        $session->set('routename', 'modelling');

        $companies = $em->getRepository('QSCORBundle:Company')->findByProject($projects->getId());
        $paginator  = $this->get('knp_paginator');
        $pagination = $paginator->paginate(
            $companies,
            $request->query->get('page', 1)/*page number*/,
            8/*limit per page*/
        );
        $company = new Company();
        $new_form = $this->createForm('QSCORBundle\Form\CompanyType', $company);
        $deleteForm = $this->createFormBuilder()->getForm();
//

//        if( !$session->has('error')){
            $session->set('error', 0);
//        }


        return $this->render('QSCORBundle:Workspace:modelling.html.twig',array(
            'form' => $form->createView(),
            'projectname'=>$projectname,
            'pagination' =>  $pagination,
            'new_form'   => $new_form->createView(),
            'delete_form' => $deleteForm->createView()


        ));
    }

    /**
     * @Route("/{projectname}/modelling/site/{idsite}", name="modellingschema")
     */

    public function modelingschemaAction($projectname, $idsite){

        $projects = $this->AcessProject($projectname);
        if( count($projects) === 0) {
            return $this->redirectToRoute('homepage');
        }

        $em = $this->getDoctrine()->getManager();
        $normalizer = new ObjectNormalizer(null);
        $normalizer->setIgnoredAttributes(array('project','company','facility','siteorigin','sitedestination','contactpersonnes'
            ));
        $normalizer->setCircularReferenceHandler(function ($object) {
            return $object->getId();
        });
        $encoder = new JsonEncoder();
        $serializer = new Serializer(array($normalizer), array($encoder));
        $idsite = $this->get('nzo_url_encryptor')->decrypt($idsite);
        $sites = $em->getRepository('QSCORBundle:Site')->find($idsite);


        $jsonsites =  $serializer->serialize( $sites, 'json');

//        var_dump($jsonsites);
//        die('ok');

        return $this->render('QSCORBundle:Workspace:modellingsiteschema.html.twig',array(
            'projectname'=>$projectname,
            'sites'=> $jsonsites,

        ));

    }
    /**
     * @Route("/{projectname}/modelling/projectschema", name="modellingprojectschema")
     */

    public function modelingprojectschemaAction($projectname){

        $projects = $this->AcessProject($projectname);
        if( count($projects) === 0) {
            return $this->redirectToRoute('homepage');
        }

        $em = $this->getDoctrine()->getManager();
        $normalizer = new ObjectNormalizer(null);
        $normalizer->setIgnoredAttributes(array('facility','siteorigin','level','sitedestination','contactpersonnes',
            'companyoriginflow', 'companydestinationflow', 'flux', 'user'
        ));
        $normalizer->setCircularReferenceHandler(function ($object) {
            return $object->getId();
        });
        $encoder = new JsonEncoder();
        $serializer = new Serializer(array($normalizer), array($encoder));

        $projects = $em->getRepository('QSCORBundle:Project')->find($projects->getId());


        $jsonprojects =  $serializer->serialize( $projects, 'json');

//        var_dump($jsonprojects);
//        die('ok');

        return $this->render('QSCORBundle:Workspace:modellingprojectschema.html.twig',array(
            'projectname'=>$projectname,
            'projects'=> $jsonprojects ,

        ));

    }




    /**
     * @Route("/{projectname}/performance", name="performance")
     */
    public function performanceAction(Request $request, $projectname){
        $projects = $this->AcessProject($projectname);
        if( count($projects) === 0) {
            return $this->redirectToRoute('homepage');
        }
        $company = new Company();
        $form = $this->createForm('QSCORBundle\Form\CompanyType', $company);
        $em = $this->getDoctrine()->getManager();

        $session = $this->get('session');
        $session->set('routename', 'performance');

        $companies = $em->getRepository('QSCORBundle:Company')->findByProject($projects->getId());
        $paginator  = $this->get('knp_paginator');
        $pagination = $paginator->paginate(
            $companies,
            $request->query->get('page', 1)/*page number*/,
            8/*limit per page*/
        );
        $company = new Company();
        $new_form = $this->createForm('QSCORBundle\Form\CompanyType', $company);
        $deleteForm = $this->createFormBuilder()->getForm();
//

        $session->set('error', 0);
        return $this->render('QSCORBundle:Workspace:performance.html.twig',array(
            'form' => $form->createView(),
            'projectname'=>$projectname,
            'pagination' =>  $pagination,
            'new_form'   => $new_form->createView(),
            'delete_form' => $deleteForm->createView()
        ));
    }

    /**
     * @Route("/{projectname}/performance/performancemode", name="performancemode")
     */
    public function performancemodeAction($projectname){
        $projects = $this->AcessProject($projectname);
        if( count($projects) === 0) {
            return $this->redirectToRoute('homepage');
        }
        $em = $this->getDoctrine()->getEntityManager();
//        $performance = $projects->getPerformanceType();
        $performance = $em->getRepository('QSCORBundle:Performance_Type')->findByProject($projects->getId());
//        die('ok');
        return $this->render('QSCORBundle:Workspace:performancemode.html.twig',array(
//            'form' => $form->createView(),
            'projectname'=>$projectname,
            'performancetype' => $performance
//            'pagination' =>  $pagination,
//            'new_form'   => $new_form->createView(),
//            'delete_form' => $deleteForm->createView()
        ));
    }


    /**
     * @Route("/{projectname}/performance/{idsite}/performancecalcul", name="performancecalcul")
     */
    public function performancecalculAction($projectname, $idsite){
        $projects = $this->AcessProject($projectname);
        if( count($projects) === 0) {
            return $this->redirectToRoute('homepage');
        }


        $idsite = $this->get('nzo_url_encryptor')->decrypt($idsite);
        $site = $this->getDoctrine()->getRepository('QSCORBundle:Site')->find($idsite);
//        $performance = $this->getDoctrine()->getRepository('QSCORBundle:Performance')->findAll();
        $performancetype = $projects->getPerformanceType();



        return $this->render('QSCORBundle:Workspace:performancesite.html.twig',array(
//            'form' => $form->createView(),
            'projectname'=>$projectname,
            'performancetypes' => $performancetype,
            "site" => $site,
        ));
    }
    /**
     * @Route("/{projectname}/performance/{idsite}/reliability", name="reliability")
     */
    public function performancereliabilityAction($projectname, $idsite){

        $projects = $this->AcessProject($projectname);
        if( count($projects) === 0) {
            return $this->redirectToRoute('homepage');
        }
        $performancetyperaibility = $this->getDoctrine()->getRepository('QSCORBundle:Performance_Type')->findOneBy(array('libellePerformance' =>'reliability'));
//        var_dump( $performancetyperaibility );
//        die('ok');
        if(is_null($performancetyperaibility))
            return $this->redirectToRoute('performancemode',array(
                'projectname' => $projectname
            ));
//
//        var_dump( $idsite);

        $idsite = $this->get('nzo_url_encryptor')->decrypt($idsite);
        $site = $this->getDoctrine()->getRepository('QSCORBundle:Site')->find($idsite);

//
//        die('ok');

        $perframanceraibility = $this->getDoctrine()->getRepository('QSCORBundle:Performance')->findBySiteandPerformance($site->getId(), $performancetyperaibility->getId());

        if( is_null($perframanceraibility))
            $valueperforomane = 0;
        else
            $valueperforomane = $perframanceraibility->getPerformancevalue();
        $ob = null;


        if(!is_null($performancetyperaibility)) {

//            $data = array(
//                array(
//                    'name' => 'Perfect Order Fullfillement',
//                ),
////            array('Opera', 1.5)
//            );
//            $series = array(
//                array("name"=>'Railibility', "colorByPoint" => true, 'data' => $data)
//            );

//            $series = array(
//                array( "name" => 'Perfect Order Fullfillement', "data" => $valueperforomane),
//            );

            $series = array(
                array( "name" => 'Perfect Order Fullfillement', "data" => array($valueperforomane)),
            );
            $ob = new Highchart();
            $ob->chart->renderTo('linechart');
            $ob->chart->type('column');
            $ob->title->text('Performance Railibility');
            $ob->xAxis->title(array('text'  => null));
//            $ob->xAxis->categories(array('Supply Chain'));
            $ob->yAxis->min(0);
            $ob->yAxis->title(array('text' =>'%',
                'align'=>'high'));
            $ob->tooltip->headerFormat('<b>{point.x}</b><br/>');
            $ob->tooltip->pointFormat('{series.name}: {point.y}<br/>');
            $ob->plotOptions->column(array('stacking'=>'normal'));
            $ob->plotOptions->column(array('dataLabels'=>array(
                'enabled'=>true,
                'color'=>'#092636',

            )));
//        $ob->yAxis->enabled(true);
//        $ob->yAxis->style(array('fontWeight'=>'bold'));

            $ob->legend->enabled(true);
            $ob->plotOptions->bar(array(
                'dataLabels'=>array(
                    'enabled'=>true
                )
            ));

            $ob->series($series);

        }


//        $performance = $this->getDoctrine()->getRepository('QSCORBundle:Performance')->findAll();
        $performancetype = $projects->getPerformanceType();



        return $this->render('QSCORBundle:Workspace:performancereliability.html.twig',array(
//            'form' => $form->createView(),
            'projectname'=>$projectname,
            'performancetypes' => $performancetype,
            "site" => $site,
            'performance' =>  $perframanceraibility,
            'chart' => $ob
        ));
    }
    /**
     * Displays a form to edit an existing Site entity.
     *
     * @Route("/{projectname}/performance/{idsite}/{idperformance}/reliability", name="reliabilitycalcule")
     * @Method({"GET", "POST"})
     */
    public function reliabilitycalcule(Request $request, $projectname, $idsite, $idperformance){


        $filfulment = $request->get('perfectorder') / $request->get('numberorder');



        $site = $this->getDoctrine()->getRepository('QSCORBundle:Site')->find($idsite);
        $perfromancetype = $this->getDoctrine()->getRepository('QSCORBundle:Performance_Type')->find($idperformance);

        $em = $this->getDoctrine()->getManager();
        $perfromance = new Performance();
        $perfromance->setSite($site);
        $perfromance->setPerformanceType($perfromancetype);
        $perfromance->setPerformancevalue($filfulment);

        $em->persist($perfromance);
        $em->flush();
        return $this->redirectToRoute('performancecalcul',array(
//            'form' => $form->createView(),
            'projectname'=>$projectname,
            "idsite" => $this->get('nzo_url_encryptor')->encrypt($site->getId())

            ));

    }

    /**
     * @Route("/{projectname}/performance/{idsite}/deleteperformancetype/{idperformance}", name="performancedelete")
     */
    public function performancereliabilitydelete($projectname, $idsite, $idperformance){

        $em = $this->getDoctrine()->getManager();
        $perfromance = $this->getDoctrine()->getRepository('QSCORBundle:Performance')->find($idperformance);
        $em->remove($perfromance);
        $em->flush();

        if($perfromance->getPerformanceType()->getLibellePerformance() == 'responsiveness'){
            return $this->redirectToRoute('responsiveness',array(
                'projectname'=>$projectname,
                "idsite" => $idsite

            ));
        }elseif($perfromance->getPerformanceType()->getLibellePerformance() == 'agility'){
            return $this->redirectToRoute('agility',array(
                'projectname'=>$projectname,
                "idsite" => $idsite

        ));
        }

        elseif($perfromance->getPerformanceType()->getLibellePerformance() == 'assetmanagement') {
            return $this->redirectToRoute('assetmanagement', array(
                'projectname' => $projectname,
                "idsite" => $idsite

            ));
        }

        elseif($perfromance->getPerformanceType()->getLibellePerformance() == 'cost') {
            return $this->redirectToRoute('cost', array(
                'projectname' => $projectname,
                "idsite" => $idsite

            ));
        }

        return $this->redirectToRoute('performancecalcul',array(
            'projectname'=>$projectname,
            "idsite" => $idsite

        ));


    }





    /**
     * @Route("/{projectname}/performance/{idsite}/performanceresponsiveness", name="responsiveness")
     */
    public function performanceresponsivenessAction($projectname, $idsite){

        $projects = $this->AcessProject($projectname);
        if( count($projects) === 0) {
            return $this->redirectToRoute('homepage');
        }
        $performancetyperesponsiveness = $this->getDoctrine()->getRepository('QSCORBundle:Performance_Type')->findOneBy(array('libellePerformance' =>'responsiveness'));
//        var_dump( $performancetyperaibility );
//        die('ok');
        if(is_null($performancetyperesponsiveness))
            return $this->redirectToRoute('performancemode',array(
                'projectname' => $projectname
            ));
//
//        var_dump( $idsite);

        $idsite = $this->get('nzo_url_encryptor')->decrypt($idsite);
        $site = $this->getDoctrine()->getRepository('QSCORBundle:Site')->find($idsite);

//
//        die('ok');

        $perframanceresponsiveness = $this->getDoctrine()->getRepository('QSCORBundle:Performance')->findBySiteandPerformance($site->getId(), $performancetyperesponsiveness->getId());

        if( is_null($perframanceresponsiveness))
            $valueperforomane = 0;
        else
            $valueperforomane = $perframanceresponsiveness->getPerformancevalue();
        $ob = null;


        if(!is_null($perframanceresponsiveness)) {

            $series = array(
                array( "name" => 'Order  Fulfillement Cycle Time(ou lead time)', "data" => array($valueperforomane)),
            );
            $ob = new Highchart();
            $ob->chart->renderTo('linechart');
            $ob->chart->type('column');
            $ob->title->text('Performance Responsiveness');
            $ob->xAxis->title(array('text'  => null));
            $ob->xAxis->categories(array('Responsiveness'));
            $ob->yAxis->min(0);
            $ob->yAxis->title(array('text' =>'%',
                'align'=>'high'));
            $ob->tooltip->headerFormat('<b>{point.x}</b><br/>');
            $ob->tooltip->pointFormat('{series.name}: {point.y}<br/>');
            $ob->plotOptions->column(array('stacking'=>'normal'));
            $ob->plotOptions->column(array('dataLabels'=>array(
                'enabled'=>true,
                'color'=>'#092636',

            )));
            $ob->legend->enabled(true);
            $ob->plotOptions->bar(array(
                'dataLabels'=>array(
                    'enabled'=>true
                )
            ));

            $ob->series($series);
        }


//        $performance = $this->getDoctrine()->getRepository('QSCORBundle:Performance')->findAll();
        $performancetype = $projects->getPerformanceType();

        return $this->render('QSCORBundle:Workspace:performanceresponsiveness.html.twig',array(
//            'form' => $form->createView(),
            'projectname'=>$projectname,
            'performancetypes' => $performancetype,
            "site" => $site,
            'performance' => $perframanceresponsiveness,
            'chart' => $ob
        ));
    }
    /**
     * Displays a form to edit an existing Site entity.
     *
     * @Route("/{projectname}/performance/{idsite}/{idperformance}/responsiveness", name="responsivenesscalcule")
     * @Method({"GET", "POST"})
     */
    public function responsivenesscalcule(Request $request, $projectname, $idsite, $idperformance){



        $filfulment = $request->get('perfectorder') / $request->get('numberorder');



        $site = $this->getDoctrine()->getRepository('QSCORBundle:Site')->find($idsite);
        $perfromancetype = $this->getDoctrine()->getRepository('QSCORBundle:Performance_Type')->find($idperformance);

        $em = $this->getDoctrine()->getManager();
        $perfromance = new Performance();
        $perfromance->setSite($site);
        $perfromance->setPerformanceType($perfromancetype);
        $perfromance->setPerformancevalue($filfulment);

        $em->persist($perfromance);
        $em->flush();
        return $this->redirectToRoute('responsiveness',array(
//            'form' => $form->createView(),
            'projectname'=>$projectname,
            "idsite" => $this->get('nzo_url_encryptor')->encrypt($site->getId())

        ));

    }




    /**
     * @Route("/{projectname}/performance/{idsite}/performanceagility", name="agility")
     */
    public function performanceagilityAction($projectname, $idsite){

        $projects = $this->AcessProject($projectname);
        if( count($projects) === 0) {
            return $this->redirectToRoute('homepage');
        }
        $performancetypeagility = $this->getDoctrine()->getRepository('QSCORBundle:Performance_Type')->findOneBy(array('libellePerformance' =>'agility'));
//        var_dump( $performancetyperaibility );
//        die('ok');
        if(is_null($performancetypeagility))
            return $this->redirectToRoute('performancemode',array(
                'projectname' => $projectname
            ));
//
//        var_dump( $idsite);

        $idsite = $this->get('nzo_url_encryptor')->decrypt($idsite);
        $site = $this->getDoctrine()->getRepository('QSCORBundle:Site')->find($idsite);

//
//        die('ok');

        $perframanceagility = $this->getDoctrine()->getRepository('QSCORBundle:Performance')->findBySiteandPerformance($site->getId(), $performancetypeagility->getId());

        if( is_null($perframanceagility)){
            $uf= 0;
            $ua= 0;
            $da= 0;
            $ovr= 0;
        }else{
            $uf= $perframanceagility->getPerformancevalue();
            $ua= $perframanceagility->getPerformancesecond();
            $da= $perframanceagility->getPerformancethreed();
            $ovr= $perframanceagility->getPerformancefour();

        }

        $ob = null;
        $ob1 = null;


        if(!is_null($perframanceagility)) {

            $series = array(
                array( "name" => 'Upside flexibility', "data" => array($uf)),
                array( "name" => 'Upside adaptability', "data" => array($ua)),
                array( "name" => 'Downside adaptability', "data" => array($da)),
            );
            $ob = new Highchart();
            $ob->chart->renderTo('linechart');
            $ob->chart->type('column');
            $ob->title->text('Performance Agility');
            $ob->xAxis->title(array('text'  => null));
            $ob->xAxis->categories(array('Agility'));
            $ob->yAxis->min(0);
            $ob->yAxis->title(array('text' =>'Days',
                'align'=>'high'));
            $ob->tooltip->headerFormat('<b>{point.x}</b><br/>');
            $ob->tooltip->pointFormat('{series.name}: {point.y}<br/>');
            $ob->plotOptions->column(array('stacking'=>'normal'));
            $ob->plotOptions->column(array('dataLabels'=>array(
                'enabled'=>true,
                'color'=>'#092636',

            )));
            $ob->legend->enabled(true);
            $ob->plotOptions->bar(array(
                'dataLabels'=>array(
                    'enabled'=>true
                )
            ));

            $ob->series($series);

            $series2 = array(
                array( "name" => 'Overall value at risk', "data" => array($ovr)),
            );
            $ob1 = new Highchart();
            $ob1->chart->renderTo('linechart2');
            $ob1->chart->type('column');
            $ob1->title->text('Performance Agility');
            $ob1->xAxis->title(array('text'  => null));
            $ob1->xAxis->categories(array('Agility'));
            $ob1->yAxis->min(0);
            $ob1->yAxis->title(array('text' =>'Euro',
                'align'=>'high'));
            $ob1->tooltip->headerFormat('<b>{point.x}</b><br/>');
            $ob1->tooltip->pointFormat('{series.name}: {point.y}<br/>');
            $ob1->plotOptions->column(array('stacking'=>'normal'));
            $ob1->plotOptions->column(array('dataLabels'=>array(
                'enabled'=>true,
                'color'=>'#092636',

            )));
            $ob1->legend->enabled(true);
            $ob1->plotOptions->bar(array(
                'dataLabels'=>array(
                    'enabled'=>true
                )
            ));

            $ob1->series($series2);
        }


//        $performance = $this->getDoctrine()->getRepository('QSCORBundle:Performance')->findAll();
        $performancetype = $projects->getPerformanceType();

        return $this->render('QSCORBundle:Workspace:performanceagility.html.twig',array(
//            'form' => $form->createView(),
            'projectname'=>$projectname,
            'performancetypes' => $performancetype,
            "site" => $site,
            'performance' => $perframanceagility,
            'chart1' => $ob,
            'chart2' => $ob1
        ));
    }
    /**
     * Displays a form to edit an existing Site entity.
     *
     * @Route("/{projectname}/performance/{idsite}/{idperformance}/agility", name="agilitycalcule")
     * @Method({"GET", "POST"})
     */
    public function agilitycalcule(Request $request, $projectname, $idsite, $idperformance){

        var_dump($request->get('uf'));
        var_dump($request->get('ua'));
        var_dump($request->get('da'));
        var_dump($request->get('ovr'));

        if(is_null($request->get('uf')))
            $uf = 0;
        else
            $uf = $request->get('uf');

        if(is_null($request->get('ua')))
            $ua = 0;
        else
            $ua = $request->get('ua');

        if(is_null($request->get('da')))
            $da = 0;
        else
            $da = $request->get('da');

        if(is_null($request->get('ovr')))
            $ovr = 0;
        else
            $ovr = $request->get('ovr');

        $site = $this->getDoctrine()->getRepository('QSCORBundle:Site')->find($idsite);
        $perfromancetype = $this->getDoctrine()->getRepository('QSCORBundle:Performance_Type')->find($idperformance);

        $em = $this->getDoctrine()->getManager();
        $perfromance = new Performance();
        $perfromance->setSite($site);
        $perfromance->setPerformanceType($perfromancetype);
        $perfromance->setPerformancevalue($uf);
        $perfromance->setPerformancesecond($ua);
        $perfromance->setPerformancethreed($da);
        $perfromance->setPerformancefour($ovr);


        $em->persist($perfromance);
        $em->flush();
        return $this->redirectToRoute('agility',array(
//            'form' => $form->createView(),
            'projectname'=>$projectname,
            "idsite" => $this->get('nzo_url_encryptor')->encrypt($site->getId())

        ));

    }


    /**
     * @Route("/{projectname}/performance/{idsite}/performanceassetmanagment", name="assetmanagement")
     */
    public function performanceassetmanagementAction($projectname, $idsite){

        $projects = $this->AcessProject($projectname);
        if( count($projects) === 0) {
            return $this->redirectToRoute('homepage');
        }
        $performancetypeassetmanagement = $this->getDoctrine()->getRepository('QSCORBundle:Performance_Type')->findOneBy(array('libellePerformance' =>'assetmanagement'));
//        var_dump( $performancetyperaibility );
//        die('ok');
        if(is_null($performancetypeassetmanagement))
            return $this->redirectToRoute('performancemode',array(
                'projectname' => $projectname
            ));
//
//        var_dump( $idsite);

        $idsite = $this->get('nzo_url_encryptor')->decrypt($idsite);
        $site = $this->getDoctrine()->getRepository('QSCORBundle:Site')->find($idsite);

//
//        die('ok');

        $perframanceassetmanagement = $this->getDoctrine()->getRepository('QSCORBundle:Performance')->findBySiteandPerformance($site->getId(), $performancetypeassetmanagement->getId());

        if( is_null($perframanceassetmanagement)){
            $cct= 0;
            $rscfa= 0;
            $rwc= 0;

        }else{
            $cct= $perframanceassetmanagement->getPerformancevalue();
            $rscfa= $perframanceassetmanagement->getPerformancesecond();
            $rwc= $perframanceassetmanagement->getPerformancethreed();


        }

        $ob = null;
        $ob1 = null;


        if(!is_null($perframanceassetmanagement)) {

            $series = array(
                array( "name" => 'Return on Supply Chain Fixed Assets', "data" => array($rscfa)),
                array( "name" => 'Return on Working Capital', "data" => array($rwc)),
            );
            $ob = new Highchart();
            $ob->chart->renderTo('linechart');
            $ob->chart->type('column');
            $ob->title->text('Performance Assets Management');
            $ob->xAxis->title(array('text'  => null));
            $ob->xAxis->categories(array('Assets Management'));
            $ob->yAxis->min(0);
            $ob->yAxis->title(array('text' =>'Euro',
                'align'=>'high'));
            $ob->tooltip->headerFormat('<b>{point.x}</b><br/>');
            $ob->tooltip->pointFormat('{series.name}: {point.y}<br/>');
            $ob->plotOptions->column(array('stacking'=>'normal'));
            $ob->plotOptions->column(array('dataLabels'=>array(
                'enabled'=>true,
                'color'=>'#092636',

            )));
            $ob->legend->enabled(true);
            $ob->plotOptions->bar(array(
                'dataLabels'=>array(
                    'enabled'=>true
                )
            ));

            $ob->series($series);

            $series2 = array(
                array( "name" => 'Overall value at risk', "data" => array($cct)),
            );
            $ob1 = new Highchart();
            $ob1->chart->renderTo('linechart2');
            $ob1->chart->type('column');
            $ob1->title->text('Performance Assets Management');
            $ob1->xAxis->title(array('text'  => null));
            $ob1->xAxis->categories(array('Assets Management'));
            $ob1->yAxis->min(0);
            $ob1->yAxis->title(array('text' =>'Days',
                'align'=>'high'));
            $ob1->tooltip->headerFormat('<b>{point.x}</b><br/>');
            $ob1->tooltip->pointFormat('{series.name}: {point.y}<br/>');
            $ob1->plotOptions->column(array('stacking'=>'normal'));
            $ob1->plotOptions->column(array('dataLabels'=>array(
                'enabled'=>true,
                'color'=>'#092636',

            )));
            $ob1->legend->enabled(true);
            $ob1->plotOptions->bar(array(
                'dataLabels'=>array(
                    'enabled'=>true
                )
            ));

            $ob1->series($series2);
        }


//        $performance = $this->getDoctrine()->getRepository('QSCORBundle:Performance')->findAll();
        $performancetype = $projects->getPerformanceType();

        return $this->render('QSCORBundle:Workspace:performanceassetmanagement.html.twig',array(
//            'form' => $form->createView(),
            'projectname'=>$projectname,
            'performancetypes' => $performancetype,
            "site" => $site,
            'performance' => $perframanceassetmanagement,
            'chart1' => $ob,
            'chart2' => $ob1
        ));
    }
    /**
     * Displays a form to edit an existing Site entity.
     *
     * @Route("/{projectname}/performance/{idsite}/{idperformance}/assetmanagement", name="assetmanagementcalcule")
     * @Method({"GET", "POST"})
     */
    public function assetmanagementcalcule(Request $request, $projectname, $idsite, $idperformance){

//        var_dump($request->get('cct'));
//        var_dump($request->get('rscfa'));
//        var_dump($request->get('rwc'));
//
//        die('ok');
//


        if(is_null($request->get('cct')))
            $cct = 0;
        else
            $cct = $request->get('cct');

        if(is_null($request->get('rscfa')))
            $rscfa = 0;
        else
            $rscfa = $request->get('rscfa');

        if(is_null($request->get('rwc')))
            $rwc = 0;
        else
            $rwc = $request->get('rwc');

        $site = $this->getDoctrine()->getRepository('QSCORBundle:Site')->find($idsite);
        $perfromancetype = $this->getDoctrine()->getRepository('QSCORBundle:Performance_Type')->find($idperformance);

        $em = $this->getDoctrine()->getManager();
        $perfromance = new Performance();
        $perfromance->setSite($site);
        $perfromance->setPerformanceType($perfromancetype);
        $perfromance->setPerformancevalue($cct);
        $perfromance->setPerformancesecond($rscfa);
        $perfromance->setPerformancethreed($rwc);


        $em->persist($perfromance);
        $em->flush();
        return $this->redirectToRoute('assetmanagement',array(
//            'form' => $form->createView(),
            'projectname'=>$projectname,
            "idsite" => $this->get('nzo_url_encryptor')->encrypt($site->getId())

        ));

    }




    /**
     * @Route("/{projectname}/performance/{idsite}/performancecost", name="cost")
     */
    public function performancecostAction($projectname, $idsite){

        $projects = $this->AcessProject($projectname);
        if( count($projects) === 0) {
            return $this->redirectToRoute('homepage');
        }
        $performancetypecost = $this->getDoctrine()->getRepository('QSCORBundle:Performance_Type')->findOneBy(array('libellePerformance' =>'cost'));
//        var_dump( $performancetyperaibility );
//        die('ok');
        if(is_null($performancetypecost))
            return $this->redirectToRoute('performancemode',array(
                'projectname' => $projectname
            ));
//
//        var_dump( $idsite);

        $idsite = $this->get('nzo_url_encryptor')->decrypt($idsite);
        $site = $this->getDoctrine()->getRepository('QSCORBundle:Site')->find($idsite);

//
//        die('ok');

        $perframancecost = $this->getDoctrine()->getRepository('QSCORBundle:Performance')->findBySiteandPerformance($site->getId(), $performancetypecost->getId());

        if( is_null($perframancecost)){
            $tcts = 0;
            $pc = 0;
            $sc = 0;
            $mlc = 0;
            $prc = 0;
            $omc = 0;
            $fc = 0;
            $rc = 0;
        }else{
            $tcts = $perframancecost->getPerformancevalue();
            $pc = $perframancecost->getPerformancesecond();
            $sc = $perframancecost->getPerformancethreed();
            $mlc = $perframancecost->getPerformancefour();
            $prc = $perframancecost->getPerformancefive();
            $omc = $perframancecost->getPerformancesix();
            $fc = $perframancecost->getPerformanceseven();
            $rc = $perframancecost->getPerformanceeight();
        }

        $ob = null;
        $ob1 = null;


        if(!is_null($perframancecost)) {

            $series = array(
                array( "name" => 'Planing Cost', "data" => array($pc)),
                array( "name" => 'Sourcing Cost', "data" => array($sc)),
                array( "name" => 'Material Landed Cost', "data" => array($mlc)),
                array( "name" => 'Production Cost', "data" => array($prc)),
                array( "name" => 'Oreder Mangament Cost', "data" => array($omc)),
                array( "name" => 'Fullfillment Cost', "data" => array($fc)),
                array( "name" => 'Returns Cost', "data" => array($rc)),
            );
            $ob = new Highchart();
            $ob->chart->renderTo('linechart');
            $ob->chart->type('column');
            $ob->title->text('Performance Cost');
            $ob->xAxis->title(array('text'  => null));
            $ob->xAxis->categories(array('Cost'));
            $ob->yAxis->min(0);
            $ob->yAxis->title(array('text' =>'Euro',
                'align'=>'high'));
            $ob->tooltip->headerFormat('<b>{point.x}</b><br/>');
            $ob->tooltip->pointFormat('{series.name}: {point.y}<br/>');
            $ob->plotOptions->column(array('stacking'=>'normal'));
            $ob->plotOptions->column(array('dataLabels'=>array(
                'enabled'=>true,
                'color'=>'#092636',

            )));
            $ob->legend->enabled(true);
            $ob->plotOptions->bar(array(
                'dataLabels'=>array(
                    'enabled'=>true
                )
            ));

            $ob->series($series);

            $data = array(
                array(
                    'name' => 'Planing Cost',
                    'y' => $pc / $tcts,
                    'drilldown' => 'Planing Cost',
                    'visible' => true
                ),
                array(
                    'name' => 'Sourcing Cost',
                    'y' => $sc / $tcts,
                    'drilldown' => 'Sourcing Cost',
                    'visible' => true
                ),
                array(
                    'name' => 'Material Landed Cost',
                    'y' => $mlc / $tcts,
                    'drilldown' => 'Material Landed Cost',
                    'visible' => true
                ),
                array(
                    'name' => 'Production Cost',
                    'y' => $prc / $tcts,
                    'drilldown' => 'Production Cost',
                    'visible' => true
                ),
                array(
                    'name' => 'Oreder Mangament Cost',
                    'y' => $omc / $tcts,
                    'drilldown' => 'Oreder Mangament Cost',
                    'visible' => true
                ),
                array(
                    'name' => 'Fullfillment Cost',
                    'y' => $fc / $tcts,
                    'drilldown' => 'Fullfillment Cost',
                    'visible' => true
                ),
                array(
                    'name' => 'Returns Cost',
                    'y' => $rc / $tcts,
                    'drilldown' => 'Returns Cost',
                    'visible' => true
                ),
                array(
                    'name' => 'Total Cost To Serve',
                    'y' => 100,
                    'drilldown' => 'Total Cost To Serve',
                    'visible' => true
                )
            );

            $ob1 = new Highchart();
            $ob1->chart->renderTo('linechart2');
            $ob1->chart->type('pie');
            $ob1->title->text('Perfromance Cost');
            $ob1->plotOptions->series(
                array(
                    'dataLabels' => array(
                        'enabled' => true,
                        'format' => '{point.name}: {point.y:.1f}%'
                    )
                )
            );
            $ob1->series(
                array(
                    array(
                        'name' => 'Browser share',
                        'colorByPoint' => true,
                        'data' => $data
                    )
                )
            );

        }


//        $performance = $this->getDoctrine()->getRepository('QSCORBundle:Performance')->findAll();
        $performancetype = $projects->getPerformanceType();

        return $this->render('QSCORBundle:Workspace:performancecost.html.twig',array(
//            'form' => $form->createView(),
            'projectname'=>$projectname,
            'performancetypes' => $performancetype,
            "site" => $site,
            'performance' => $perframancecost,
            'chart1' => $ob,
            'chart2' => $ob1
        ));
    }
    /**
     * Displays a form to edit an existing Site entity.
     *
     * @Route("/{projectname}/performance/{idsite}/{idperformance}/cost", name="costcalcule")
     * @Method({"GET", "POST"})
     */
    public function costcalcule(Request $request, $projectname, $idsite, $idperformance){

        if(is_null($request->get('sc')))
            $sc = 0;
        else
            $sc = $request->get('sc');

        if(is_null($request->get('omc')))
            $omc = 0;
        else
            $omc = $request->get('omc');

        if(is_null($request->get('pc')))
            $pc = 0;
        else
            $pc = $request->get('pc');

        if(is_null($request->get('mlc')))
            $mlc = 0;
        else
            $mlc = $request->get('mlc');

        if(is_null($request->get('prc')))
            $prc = 0;
        else
            $prc = $request->get('prc');

        if(is_null($request->get('fc')))
            $fc = 0;
        else
            $fc = $request->get('fc');

        if(is_null($request->get('rc')))
            $rc = 0;
        else
            $rc = $request->get('rc');

        $tcts = $pc + $mlc + $prc + $fc + $rc + $sc + $omc;
        $site = $this->getDoctrine()->getRepository('QSCORBundle:Site')->find($idsite);
        $perfromancetype = $this->getDoctrine()->getRepository('QSCORBundle:Performance_Type')->find($idperformance);

        $em = $this->getDoctrine()->getManager();
        $perfromance = new Performance();
        $perfromance->setSite($site);
        $perfromance->setPerformanceType($perfromancetype);
        $perfromance->setPerformancevalue($tcts);
        $perfromance->setPerformancesecond($pc);
        $perfromance->setPerformancethreed($sc);
        $perfromance->setPerformancefour($mlc);
        $perfromance->setPerformancefive($prc);
        $perfromance->setPerformancesix($omc);
        $perfromance->setPerformanceseven($fc);
        $perfromance->setPerformanceeight($rc);
        $em->persist($perfromance);
        $em->flush();
        return $this->redirectToRoute('cost',array(
//            'form' => $form->createView(),
            'projectname'=>$projectname,
            "idsite" => $this->get('nzo_url_encryptor')->encrypt($site->getId())

        ));

    }



    /**
     * Displays a form to edit an existing Site entity.
     *
     * @Route("/{projectname}/performance/", name="performanceglobal")
     *
     */
    public function perfomanceglobale($projectname){

        $projects = $this->AcessProject($projectname);
        if( count($projects) === 0) {
            return $this->redirectToRoute('homepage');
        }

        return $this->render('QSCORBundle:Workspace:Performanceglobale.html.twig',array(
            'projectname' => $projectname
        ));

    }


    /**
     * @Route("/{projectname}/settingsproject", name="niveau")
     */
    public function NiveauAction($projectname){

        $projects = $this->AcessProject($projectname);
        if( count($projects) === 0) {
            return $this->redirectToRoute('homepage');
        }
        return $this->render('QSCORBundle:Workspace:niveau.html.twig',array(
            'projectname'=>$projectname,
        ));
    }

    /**
     * @Route("/{projectname}/settingsproject", name="settingsproject")
     */
    public function settingsprojectAction($projectname){


        $projects = $this->AcessProject($projectname);
        if( count($projects) === 0) {
            return $this->redirectToRoute('homepage');
        }

        $session = $this->get('session');
        $session->set('error', 0);
        return $this->render('QSCORBundle:Workspace:settingproject.html.twig',array(
            'projectname'=>$projectname,
//            'edit_form'  => $editForm->createView(),
//            'delete_form' => $deleteForm->createView(),
            'project'=>$projects
        ));
    }

    private function createDeleteForm(Project $project)
    {
        return $this->createFormBuilder()
            ->setAction($this->generateUrl('project_delete', array('id' => $project->getId())))
            ->setMethod('DELETE')
            ->getForm()
            ;
    }

    /**
     * @Route("/networking/company/{id}", name="site_facility_index")
     */
    public function sitefacilityAction(Request $request, $id){

        $company = $this->getDoctrine()->getRepository('QSCORBundle:Company')->find($id);

        $sites = $this->getDoctrine()->getRepository('QSCORBundle:Site')->findByCompany($id);

        $facility = $this ->getDoctrine()->getRepository('QSCORBundle:Type_Facility')->findAll();


        $session = $this->get('session');


        $session->set('idcompany', $id);

//        var_dump($session->get('idcompany'));
//        die();


        $type_Facility = new Type_Facility();
        $form = $this->createForm('QSCORBundle\Form\Type_FacilityType', $type_Facility);
        $form->handleRequest($request);
        $paginator  = $this->get('knp_paginator');
        $paginationsites = $paginator->paginate(
            $sites,
            $request->query->get('page', 1)/*page number*/,
            4/*limit per page*/
        );

        $paginationfacilty = $paginator->paginate(
            $facility,
            $request->query->get('page', 1)/*page number*/,
            4/*limit per page*/
        );

        return $this->render('@QSCOR/Workspace/sitesconfigure.html.twig',array(
            'company'=> $company,
            'paginationsites'=>$paginationsites,
            'paginationfacility'=>$paginationfacilty,
            'form'=>$form->createView()
        ));
    }


}