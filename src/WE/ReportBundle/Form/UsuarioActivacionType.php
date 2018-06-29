<?php

namespace WE\ReportBundle\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;

class UsuarioActivacionType extends AbstractType {

    /**
     * {@inheritdoc}
     */
    public function buildForm(FormBuilderInterface $builder, array $options) {
        $builder->add('tipo', ChoiceType::class, array('choices' => array(
                'KAM' => 'KAM',
                'Gerente de marca' => 'Gerente de marca',
                'Ejecutivo de cuenta' => 'Ejecutivo de cuenta',
                'Productor' => 'Productor',
                'Supervisor' => 'Supervisor',
    )))->add('usuario');
    }

    /**
     * {@inheritdoc}
     */
    public function configureOptions(OptionsResolver $resolver) {
        $resolver->setDefaults(array(
            'data_class' => 'WE\ReportBundle\Entity\UsuarioActivacion'
        ));
    }

    /**
     * {@inheritdoc}
     */
    public function getBlockPrefix() {
        return 'UsuarioActivacion';
    }

}
