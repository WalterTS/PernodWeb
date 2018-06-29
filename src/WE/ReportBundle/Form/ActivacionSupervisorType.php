<?php

namespace WE\ReportBundle\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\CollectionType;
use WE\ReportBundle\Entity\UsuarioActivacion;

class ActivacionSupervisorType extends AbstractType {

    /**
     * {@inheritdoc}
     */
    public function buildForm(FormBuilderInterface $builder, array $options) {
        $builder
                ->add('total', null, array('attr' => array('class' => 'form-control')))
                ->add('copeo', null, array('attr' => array('class' => 'form-control')))
                ->add('botellas', null, array('attr' => array('class' => 'form-control')))
                ->add('images', CollectionType::class, array(
                    'label' => 'Imagenes',
                    'entry_type' => GalleryType::class,
                    'allow_add' => true,
                    'allow_delete' => true,
                    'by_reference' => false,
                    'entry_options' => [
                        'label' => false
                    ],
                    'attr' => array(
                        'class' => 'my-selector',
                    ),
                    'prototype' => true
        ));
    }

    /**
     * {@inheritdoc}
     */
    public function configureOptions(OptionsResolver $resolver) {
        $resolver->setDefaults(array(
            'data_class' => 'WE\ReportBundle\Entity\Activacion'
        ));
    }

    /**
     * {@inheritdoc}
     */
    public function getBlockPrefix() {
        return 'ActivacionSupervisor';
    }

}
