<?php

namespace WE\ReportBundle\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Vich\UploaderBundle\Form\Type\VichFileType;

use Symfony\Component\Form\Extension\Core\Type\CollectionType;
use Tetranz\Select2EntityBundle\Form\Type\Select2EntityType;
use WE\ReportBundle\Entity\CDC;
use WE\ReportBundle\Entity\Marca;

class UsuarioType extends AbstractType {

    /**
     * {@inheritdoc}
     */
    public function buildForm(FormBuilderInterface $builder, array $options) {

        $builder->add('username', null, [
                    'label' => 'Nombre de usuario',
                    'attr' => [
                        'class' => 'form-control',
                    ]
                ])
                ->add('email',null,[
                    'label' => 'Correo',
                    'attr' => [
                'class' => 'form-control',
            ]
                ])
                ->add('nombre', null, [
                    'attr' => [
                        'class' => 'form-control',
                    ]
                ])
                ->add('image', \Symfony\Component\Form\Extension\Core\Type\HiddenType::class)->add('imageFile', VichFileType::class,array('required' => false))
                ->add('marcas', Select2EntityType::class, [
                    'label' => 'Marcas',
                    'multiple' => true,
                    'remote_route' => 'ajax_filter_usr',
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
                ->add('cdcs', Select2EntityType::class, [
                    'label' => 'Centros de consumo',
                    'multiple' => true,
                    'remote_route' => 'ajax_filter_usr',
                    'class' => CDC::class,
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
                    'placeholder' => 'Selecciona un centro de consumo',
                    'required' => false,
                ])
                ->add('region')
                ->add('agencia')
                ->add('enabled', null, [
                    'label' => 'Activo'
                ])
                ->add('plainPassword', null, [
                    'label' => 'Contraseña',
                    'attr' => [
                        'class' => 'form-control',
                    ]
                ])
                ->add('roles', \Symfony\Component\Form\Extension\Core\Type\ChoiceType::class, [
                    'choices' => \WE\ReportBundle\Entity\Usuario::ROLES_LIST,
                    'expanded' => true,
                    'multiple' => true,
                        ]
        );
    }

    /**
     * {@inheritdoc}
     */
    public function configureOptions(OptionsResolver $resolver) {
        $resolver->setDefaults(array(
            'data_class' => 'WE\ReportBundle\Entity\Usuario'
        ));
    }

    /**
     * {@inheritdoc}
     */
    public function getBlockPrefix() {
        return 'we_reportbundle_usuario';
    }

}
