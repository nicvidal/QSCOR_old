<?php

namespace QSCORBundle\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;

class Blocks_TypeType extends AbstractType
{
    /**
     * @param FormBuilderInterface $builder
     * @param array $options
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('libelleBlocksType')
            ->add('libelleAbr')
            ->add('description', TextareaType::class,array(
                'trim'=>true
            ))
            ->add('comment',TextareaType::class,array(
                'trim'=>true
            ))
//            ->add('level_type')
        ;
    }
    
    /**
     * @param OptionsResolver $resolver
     */
    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults(array(
            'data_class' => 'QSCORBundle\Entity\Blocks_Type'
        ));
    }
}
