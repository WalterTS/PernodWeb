<?php

namespace WE\ReportBundle\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Vich\UploaderBundle\Form\Type\VichFileType;

use WE\ReportBundle\Entity\Marca;
use WE\ReportBundle\Entity\Usuario;
use Symfony\Component\Form\Extension\Core\Type\CollectionType;
use Tetranz\Select2EntityBundle\Form\Type\Select2EntityType;

class CDCType extends AbstractType
{
    /**
     * {@inheritdoc}
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder->add('capacidad', null, [
                    'attr' => [
                        'class' => 'form-control',
                    ]
                ])
                ->add('nombre', null, [
                    'attr' => [
                        'class' => 'form-control',
                    ]
                ])
                ->add('cliente', null, [
                    'attr' => [
                        'class' => 'form-control',
                    ]
                ])
                ->add('cliente_id', null, [
                    'attr' => [
                        'class' => 'form-control',
                    ]
                ])
                ->add('image', \Symfony\Component\Form\Extension\Core\Type\HiddenType::class)->add('imageFile', VichFileType::class,array('required' => false))
                ->add('tipo')
                ->add('plaza')
                ->add('marcas', Select2EntityType::class, [
                    'label' => 'Marcas',
                    'multiple' => true,
                    'remote_route' => 'ajax_filter_cdc',
                    'class' => Marca::class,
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
                    'placeholder' => 'Selecciona una marca',
                    'required' => false,
                ])
                ->add('usuarios', Select2EntityType::class, [
                    'label' => 'Usuarios',
                    'multiple' => true,
                    'remote_route' => 'ajax_filter_cdc',
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
                    'placeholder' => 'Selecciona usuarios',
                    'required' => false,
                ]);
    }/**
     * {@inheritdoc}
     */
    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults(array(
            'data_class' => 'WE\ReportBundle\Entity\CDC'
        ));
    }

    /**
     * {@inheritdoc}
     */
    public function getBlockPrefix()
    {
        return 'we_reportbundle_cdc';
    }


}
