<?php

namespace WE\ReportBundle\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\CollectionType;
use WE\ReportBundle\Entity\UsuarioActivacion;
class ActivacionProductorType extends AbstractType {

    /**
     * {@inheritdoc}
     */
    public function buildForm(FormBuilderInterface $builder, array $options) {
        $builder->add('supervisores', CollectionType::class, array(
            'label' => 'Supervisores',
            'entry_type' => UsuarioActivacionSupervisorType::class,
            'allow_add' => true,
            'allow_delete' => true,
            'by_reference' => false,
            'entry_options' => [
                'label' => false
            ],
            'attr' => array(
                'class' => 'my-selector add-one-item',
            ),
            'prototype' => true,
            'mapped' => false
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
        return 'we_reportbundle_activacion';
    }

}
