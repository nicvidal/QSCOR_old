<?php

namespace QSCORBundle\Form;

use QSCORBundle\Entity\Flux;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class FluxType extends AbstractType
{
    /**
     * @param FormBuilderInterface $builder
     * @param array $options
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('libelleFlux')
            ->add('colorFlux', null, array(
                'attr' => array('class'=> 'jscolor')
            ))
            ->add('typeflux', ChoiceType::class, array(
                'choices'=>array(
                    Flux::fluxinformation => Flux::fluxinformation,
                    Flux::fluxphysique => Flux::fluxphysique
                )
            ))
        ;
    }
    
    /**
     * @param OptionsResolver $resolver
     */
    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults(array(
            'data_class' => 'QSCORBundle\Entity\Flux'
        ));
    }
}
