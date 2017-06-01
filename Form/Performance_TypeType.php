<?php

namespace QSCORBundle\Form;

use QSCORBundle\Entity\Performance_Type;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;

class Performance_TypeType extends AbstractType
{
    /**
     * @param FormBuilderInterface $builder
     * @param array $options
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('libellePerformance',ChoiceType::class, array(
            'choices'=>array(
               Performance_Type::COST => Performance_Type::COST,
                Performance_Type::AGILITY => Performance_Type::AGILITY,
                Performance_Type::RELIABILITY => Performance_Type::RELIABILITY,
                Performance_Type::RESPONSIVENESS => Performance_Type::RESPONSIVENESS,
                Performance_Type::ASSETMANAGEMENT => Performance_Type::ASSETMANAGEMENT

            ),
                'expanded' => true,
                'multiple' => true
            ))
//            ->add('project')
        ;
    }
    
    /**
     * @param OptionsResolver $resolver
     */
    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults(array(
            'data_class' => 'QSCORBundle\Entity\Performance_Type'
        ));
    }
}
