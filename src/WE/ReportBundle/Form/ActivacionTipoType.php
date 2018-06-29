<?php

namespace WE\ReportBundle\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

use Symfony\Component\Form\Extension\Core\Type\CollectionType;
use Doctrine\Common\Collections\ArrayCollection;

class ActivacionTipoType extends AbstractType
{
    /**
     * {@inheritdoc}
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder->add('nombre')
        ->add('degustacion')
        ->add('degustacion_total')
        ->add('giveaway')
        ->add('giveaway_material')
        ->add('materiales')
    /*
                ->add('talento', CollectionType::class, array(
            'label' => 'Talento',
            'entry_type' => ActivacionTipoTalentoType::class,
            'allow_add' => true,
            'allow_delete' => true,
            'by_reference' => false,
            'entry_options' => [
                'label' => false
            ],
            'attr' => array(
                'class' => 'my-selector d-fields-inline collection-format',
            ),
            'prototype' => true,
            'mapped' => false
        ));
     * 
     */
                ;
         
    }/**
     * {@inheritdoc}
     */
    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults(array(
            'data_class' => 'WE\ReportBundle\Entity\ActivacionTipo'
        ));
    }

    /**
     * {@inheritdoc}
     */
    public function getBlockPrefix()
    {
        return 'we_reportbundle_activaciontipo';
    }


}
