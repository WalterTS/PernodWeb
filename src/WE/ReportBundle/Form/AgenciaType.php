<?php

namespace WE\ReportBundle\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

use Tetranz\Select2EntityBundle\Form\Type\Select2EntityType;
use WE\ReportBundle\Entity\Usuario;

class AgenciaType extends AbstractType
{
    /**
     * {@inheritdoc}
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder->add('nombre', null, [
                    'attr' => [
                        'class' => 'form-control',
                    ]
                ])
                 ->add('usuarios', Select2EntityType::class, [
                    'label' => 'Usuarios',
                    'multiple' => true,
                    'remote_route' => 'ajax_filter_ag',
                    'class' => Usuario::class,
                    'primary_key' => 'id',
                    'text_property' => 'nombre',
                    'property' => 'nombre',
                    'minimum_input_length' => 2,
                    'page_limit' => 10,
                    'allow_clear' => true,
                    'delay' => 250,
                    'cache' => true,
                    'cache_timeout' => 60000,
                    'language' => 'es',
                    'placeholder' => 'Selecciona un usuario',
                    'required' => false,
                ]); 
    }/**
     * {@inheritdoc}
     */
    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults(array(
            'data_class' => 'WE\ReportBundle\Entity\Agencia'
        ));
    }

    /**
     * {@inheritdoc}
     */
    public function getBlockPrefix()
    {
        return 'we_reportbundle_agencia';
    }


}
