<?php

namespace QSCORBundle\Form;

use Doctrine\ORM\EntityManager;
use QSCORBundle\Entity\Company;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Form\FormEvents;
use Symfony\Component\Form\FormEvent;
use Symfony\Component\Form\FormInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Doctrine\ORM\EntityRepository;
use Symfony\Component\Form\Extension\Core\Type\HiddenType;

class Flow_SiteType extends AbstractType
{
    /**
     * @param FormBuilderInterface $builder
     * @param array $options
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {


        $builder

            ->add('companyorigin', EntityType::class,  array(
                'class' => 'QSCORBundle\Entity\Company',
                'label'=>'Company Origin',
                'attr'=>array('class'=>"selectsymfony",),
                'query_builder' => function (EntityRepository $er) use($options){
                    return $er->createQueryBuilder('c')
                        ->join('c.project','p')
                        ->where('p.id = :id')
                        ->setParameter('id', $options['projectId']->getId());

                },
//                'em'=> $entityManager->getEntityManagerName(),
//                'choices_as_values' => true,
//                'choices'=>$this->loadChoiceList()

                'choice_label' => 'name',
            ))

            ->add('companydestination', EntityType::class,  array(
                'class' => 'QSCORBundle:Company',
                'attr'=>array('class'=>"selectsymfony",),
                'query_builder' => function (EntityRepository $er) use($options){
                    return $er->createQueryBuilder('c')
                        ->join('c.project','p')
                        ->where('p.id = :id')
                        ->setParameter('id', $options['projectId']->getId());

                },
//                'choices_as_values' => true,
//                'choices'=>$this->loadChoiceList()
//                'em'=> 'QSCORBundle\Entity\Company',
                'choice_label' => 'name',

            ))
//            ->add('origin')
//            ->add('destination')
            ->add('flowfluxphysique', EntityType::class,  array(
                'class' => 'QSCORBundle\Entity\Flux',
                'multiple'=> true,
                'label'=>'Flux Physique',
                'expanded'=>true,
                'query_builder' => function (EntityRepository $er) use($options){
                     return $er->createQueryBuilder('f')
                    ->where('f.typeflux = :type')
                    ->setParameter('type', "Flux Physique");

                  },
                'choice_label' => 'libelleFlux',
            ))

            ->add('flowfluxinformation', EntityType::class,  array(
                'class' => 'QSCORBundle\Entity\Flux',
//                'attr'=>array('class'=>'switch-input'),
                'label'=>'Flux Information',
                'multiple'=> true,
                'expanded'=>true,
                'query_builder' => function (EntityRepository $er) use($options){
                    return $er->createQueryBuilder('f')
                        ->where('f.typeflux = :type')
                        ->setParameter('type', "Flux Information");

                },
                'choice_label' => 'libelleFlux',
            ))


        ;



        $formoriginModifier = function (FormInterface $form, Company $company = null) {
            $origins = null === $company ? array() : $company->getSites();


            $form->add('origin', EntityType::class, array(
                'class'       => 'QSCORBundle:Site',
//                'attr'=>array('class'=>"selectsymfony",),
                'placeholder' => '',
                'choices'     => $origins,
            ));

        };

        $formdestinationModifier = function (FormInterface $form, Company $company = null) {
            $destinations = null === $company ? array() : $company->getSites();

            $form->add('destination', EntityType::class, array(
                'class'       => 'QSCORBundle:Site',
//                'attr'=>array('class'=>"selectsymfony",),
                'placeholder' => '',
                'choices'     => $destinations,
            ));
        };

        $builder->addEventListener(
            FormEvents::PRE_SET_DATA,
            function (FormEvent $event) use ($formoriginModifier) {
                // this would be your entity, i.e. SportMeetup
                $data = $event->getData();
//                var_dump($data);
//                die('not null');

                $formoriginModifier($event->getForm(), $data->getCompanyorigin());
            }
        );



        $builder->addEventListener(
            FormEvents::PRE_SET_DATA,
            function (FormEvent $event) use ($formdestinationModifier) {
                // this would be your entity, i.e. SportMeetup
                $data = $event->getData();
//                var_dump($data);
//                die('not null');

                $formdestinationModifier($event->getForm(), $data->getCompanyorigin());
            }
        );


        $builder->get('companyorigin')->addEventListener(
            FormEvents::POST_SUBMIT,
            function (FormEvent $event) use ($formoriginModifier) {
                // It's important here to fetch $event->getForm()->getData(), as
                // $event->getData() will get you the client data (that is, the ID)
                $company = $event->getForm()->getData();
//                die('hh');

                // since we've added the listener to the child, we'll have to pass on
                // the parent to the callback functions!
                $formoriginModifier($event->getForm()->getParent(), $company);
            }
        );


        $builder->get('companyorigin')->addEventListener(
            FormEvents::POST_SET_DATA,
            function (FormEvent $event) use ($formoriginModifier) {
                // It's important here to fetch $event->getForm()->getData(), as
                // $event->getData() will get you the client data (that is, the ID)
                $company = $event->getForm()->getData();
//                die('hh');

                // since we've added the listener to the child, we'll have to pass on
                // the parent to the callback functions!
                $formoriginModifier($event->getForm()->getParent(), $company);
            }
        );
        $builder->get('companydestination')->addEventListener(
            FormEvents::POST_SET_DATA,
            function (FormEvent $event) use ($formoriginModifier) {
                // It's important here to fetch $event->getForm()->getData(), as
                // $event->getData() will get you the client data (that is, the ID)
                $company = $event->getForm()->getData();
//                die('hh');

                // since we've added the listener to the child, we'll have to pass on
                // the parent to the callback functions!
                $formoriginModifier($event->getForm()->getParent(), $company);
            }
        );

        $builder->get('companydestination')->addEventListener(
            FormEvents::POST_SUBMIT,
            function (FormEvent $event) use ($formdestinationModifier) {
                // It's important here to fetch $event->getForm()->getData(), as
                // $event->getData() will get you the client data (that is, the ID)
                $company = $event->getForm()->getData();
//                die('hh');

                // since we've added the listener to the child, we'll have to pass on
                // the parent to the callback functions!
                $formdestinationModifier($event->getForm()->getParent(), $company);
            }
        );

    }
    
    /**
     * @param OptionsResolver $resolver
     */
    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults(array(
            'data_class' => 'QSCORBundle\Entity\Flow_Site',
            'projectId' => null,
        ));
    }
}
