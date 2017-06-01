<?php

namespace QSCORBundle\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Doctrine\ORM\EntityRepository;
use Symfony\Component\Form\Extension\Core\Type\HiddenType;
class SiteType extends AbstractType
{

//    public $projectid;
//
//    /**
//     * @return mixed
//     */
//    public function getProjectid()
//    {
//        return $this->projectid;
//    }
//
//    public function __construct($projectid)
//    {
//        $this->projectid = $projectid;
//    }

    /**
     * @param FormBuilderInterface $builder
     * @param array $options
     *
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {

        $builder
            ->add('name')
//            ->add('adresse')
//            ->add('coordonne')
            ->add('adresse', HiddenType::class)
            ->add('coordonne', HiddenType::class)


            ->add('company',EntityType::class, array(
                'attr'=>array('class'=>"selectsymfony",),
                'label'=>'Companies',
                'query_builder' => function (EntityRepository $er) use($options){
                    return $er->createQueryBuilder('c')
                        ->join('c.project','p')
                        ->where('p.id = :id')
                        ->setParameter('id', $options['projectId']->getId());
//            ->orderBy('p.price', 'ASC')
//                        ->getQuery();
                },
//                'choices_as_values' => true,
//                'choices'=>$this->loadChoiceList()
                'class' => 'QSCORBundle\Entity\Company',
                'choice_label' => 'name',

            ))
            ->add('facility',EntityType::class, array(
                'attr'=>array('class'=>"selectsymfony",),
                'label'=>'Facilities',
                'query_builder' => function (EntityRepository $er) use($options) {
                    return $er->createQueryBuilder('c')
                        ->join('c.project', 'p')
                        ->where('p.id = :id')
                        ->setParameter('id', $options['projectId']->getId());
                },
//                'choices_as_values' => true,
//                'choices'=>$this->loadChoiceList()
                'class' => 'QSCORBundle\Entity\Type_Facility',
                'choice_label' => 'name',

            ))
        ;
    }
    
    /**
     * @param OptionsResolver $resolver
     */
    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults(array(
            'data_class' => 'QSCORBundle\Entity\Site',
            'projectId' => null
        ));
    }
}
