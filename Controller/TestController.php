<?php
/**
 * Created by PhpStorm.
 * User: mm
 * Date: 09/05/16
 * Time: 20:40
 */

namespace QSCORBundle\Controller;


use Symfony\Bundle\FrameworkBundle\Command\ContainerAwareCommand;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Doctrine\ORM\EntityRepository;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Response;

class TestController extends Controller
{
    protected $em;
    protected $router;



      public function __construct(\Doctrine\ORM\EntityManager $em, \Symfony\Bundle\FrameworkBundle\Routing\Router $router)
      {
          $this->em = $em;
          $this->router = $router;

      }

    public function AcessProject($projectname)
    {

        $query = $this->em->createQuery(
            'SELECT p
        FROM QSCORBundle:Project p
        WHERE LOWER (p.name) = LOWER(:name)'
        )->setParameter('name', $projectname);

        $projects =  $query->setMaxResults(1)->getOneOrNullResult();
//        var_dump($projects);
//        die();

            return $this->router->generate('homepage');
//        return new RedirectResponse($this->generateUrl('homepage'));

    }
}