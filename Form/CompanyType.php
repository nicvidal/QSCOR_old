<?php

namespace QSCORBundle\Form;

use QSCORBundle\Entity\Company;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\ChoiceList\ChoiceListInterface;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\ChoiceList\Loader\ChoiceLoaderInterface;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;

class CompanyType extends AbstractType
{
    /**
     * @param FormBuilderInterface $builder
     * @param array $options
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {

        $builder
            ->add('name', null, array(
                'label' => 'Name Company '
            ))
            ->add('description')
//            ->add('country', Select2EntityType::class,array(
//                'class' => 'QSCORBundle\Entity\CountryMaps',
//                'remote_path'=>'QSCORBundle\Entity\CountryMaps',
//                'remote_route'=>null
//            ))
            ->add('country',EntityType::class, array(
                'attr'=>array('class'=>"selectsymfony",),
                'label'=>'Country of The Head Office',
//                'choices_as_values' => true,
//                'choices'=>$this->loadChoiceList()
                'class' => 'QSCORBundle\Entity\CountryMaps',
                'choice_label' => 'Country_Name',
                'choice_value'  =>'Country_Name'

            ))
            ->add('city')
            ->add('category',ChoiceType::class, array(
                'choices' => array(
                Company::COMPANY => Company::COMPANY ,
                Company::CUSTOMR => Company::CUSTOMR,
                Company::SUPPLIER => Company::SUPPLIER,
                Company::SUPPLIEROFSUPPLIER => Company::SUPPLIEROFSUPPLIER,
                Company::CUSTOMEROFCUSTORMER => Company::CUSTOMEROFCUSTORMER

                ),
                'attr'=>array('class'=>"category",)
                ))
        ;
    }
    
    /**
     * @param OptionsResolver $resolver
     */
    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults(array(
            'data_class' => 'QSCORBundle\Entity\Company'
        ));
    }


}
