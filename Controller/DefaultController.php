<?php

namespace QSCORBundle\Controller;
use Symfony\Component\Serializer\Serializer;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Ob\HighchartsBundle\Highcharts\Highchart;

class DefaultController extends Controller
{
    /**
     * @Route("/", name="homepage")
     */
    public function indexAction()
    {

//        var_dump( $jsonCountries);
//        die();
//        return $this->render('QSCORBundle:Default:index.twig.html.twig',array("countries"=> $jsonCountries));
        if(!$this->get('security.authorization_checker')->isGranted('ROLE_USER'))
            return $this->render('QSCORBundle:Default:index.html.twig');



        return $this->redirectToRoute('projects');
    }

    /**
     * @Route("/account/overview/", name="account")
     */
    public function accountAction()
    {
        $user = $this->getUser();
        /** @var $formFactory \FOS\UserBundle\Form\Factory\FactoryInterface */
        $formFactory = $this->get('fos_user.profile.form.factory');

        $form = $formFactory->createForm();
        $form->setData($user);

        $formFactorypass = $this->get('fos_user.change_password.form.factory');


        $formpass = $formFactorypass->createForm();
        $formpass->setData($user);

        return $this->render('QSCORBundle:Default:account.html.twig', array(
            'form' => $form->createView(),
            'formpass' => $formpass->createView(),
            'error' => 'null'
        ));


    }

    /**
     * @Route("/map", name="maps")
     */
    public function mapsAction(){
        $serializer = $this->get('serializer');
        $countries = $this->getDoctrine()->getRepository("QSCORBundle:CountryMaps")->findAll();
        $jsonCountries = $serializer->serialize($countries, 'json');


        return $this->render("QSCORBundle:Default:map.html.twig",array("countries"=> $jsonCountries));
    }

    /**
     *
     *
     * @Route("/graph", name="graphtest")
     */
    public function chartAction()
    {
        $series = array(
            array( "name" => 'Supplier', "data" => array(56.33)),
            array("name" => 'MyCompany', "data" =>array(24.03)),
            array("name" => 'Customers', "data" =>array(10.38)),
//            array('dataLabels'=>array(
//                'enabled' =>true,
//                'rotation'=>-90,
//                'color'=>'#FFFFFF',
//                'align'=>'right',
//                'format'=>'{point.y:.1f}',
//                'y'=>10,
//                'style'=>array(
//                    'fontSize'=>'13px',
//                    'fontFamily'=>'Verdana, sans-serif'
//                )
//            ))
        );
        $ob = new Highchart();
        $ob->chart->renderTo('linechart');
        $ob->chart->type('column');
        $ob->title->text('Performance SCOR');
        $ob->xAxis->title(array('text'  => null));
        $ob->xAxis->categories(array('Supply Chain'));
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
//        $ob->yAxis->enabled(true);
//        $ob->yAxis->style(array('fontWeight'=>'bold'));

        $ob->legend->enabled(true);
        $ob->plotOptions->bar(array(
            'dataLabels'=>array(
                'enabled'=>true
            )
        ));

        $ob->series($series);

        return $this->render('QSCORBundle:Default:graph.html.twig', array(
            'chart' => $ob
        ));
    }

    /**
     * @Route("/graphmult", name="graphmulttest")
     *
     */

    public function graphMultipleAction(){

        //This Code For Making multiple performence for one category

        $series = array(
            array("name" => "Supplier",   'color' => '#4572A7', "data" => array(49.9, 71.5, 106.4)),
            array("name" => "MyCompany",  'color' => '#AA4643',  "data" => array(83.6, 78.8, 98.5)),
            array("name" => "Customrs",  'color' => '#1B81E6',  "data" => array(48.9, 38.8, 39.3))
        );


        $yData = array(
            'min'=>0,
            'title'=>array(
                'text'=>'Days',
            ),
//            'stackLabels'=>array(
//                'enabled'=>true,
//                'style'=> array(
//                    'fontWeight'=>'bold',
//                    'color'=>'#1B81E'
//                )
//            )


        );



        $ob = new Highchart();

        $ob->chart->renderTo('linechart');  // The #id of the div where to render the chart
        $ob->chart->type('column');
        $ob->title->text('Chart Title');
//        $ob->xAxis->title(array('text'  => "Horizontal axis title"));
        $ob->xAxis->categories(array('Supplier', 'MyCompany','Customrs'  ));
        $ob->xAxis->crosshair(true);
        $ob->yAxis($yData);
//        $ob->legend->align('right');
//        $ob->legend->x(-30);
//        $ob->legend->verticalAlign('top');
//        $ob->legend->y(25);
//        $ob->legend->floating(true);
////        $ob->legend->backgroundColor('#1B81E');
//        $ob->legend->borderColor('#CCC');
//        $ob->legend->borderWith(1);
//        $ob->legend->shadow(false);

        $ob->tooltip->headerFormat( '<span style="font-size:10px">{point.key}</span><table>');
        $ob->tooltip->pointFormat( '<tr><td style="color:{series.color};padding:0">{series.name}: </td>' +
                '<td style="padding:0"><b>{point.y:.1f} mm</b></td></tr>');
        $ob->tooltip->footerFormat('</table>');
        $ob->tooltip->shared(true);
        $ob->tooltip->useHTML(true);
//        $ob->plotOptions->column(array('stacking'=>'norma',
//                                'dataLabels'=>array(
//                                    'enabled'=>true,
//                                    "color"=>'(Highcharts.theme && Highcharts.theme.dataLabelsColor) || \'white\'',
//                                    'style'=>array(
//                                        'textShadow'=> '0 0 3px black'
//                                    )
//
//                                )));
        $ob->plotOptions->column(array(
            'pointPadding' => 0.2,
            'BorderWidth'  => 0
        ));

        $ob->plotOptions->column(array('stacking'=>'normal'));
        $ob->plotOptions->column(array('dataLabels'=>array(
            'enabled'=>true,
            'color'=>'#092636',

        )));



        $ob->series($series);

        return $this->render('QSCORBundle:Default:graph.html.twig', array(
            'chart' => $ob
        ));
    }


    /**
     *
     * @Route("/flowprocess", name="flowprocess")
     *
     */

    public function FlowProcessAction(){

        return $this->render('QSCORBundle:Default:flowprocess.html.twig');
//        return $this->render('QSCORBundle:Default:flowprocessVersion2.html.twig');
    }

    /**
     * @Route("/search", name="searchposition")
     *
     */

    public function SearchPositionAction(){
        return $this->render('QSCORBundle:Default:search.html.twig');
    }


    /**
     * @Route("/drag", name="dragposition")
     *
     */

    public function DragAction(){
        return $this->render('QSCORBundle:Default:dragula.html.twig');
    }
}
