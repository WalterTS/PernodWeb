<?php

namespace WE\ReportBundle\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Vich\UploaderBundle\Form\Type\VichFileType;

class UsuarioAgenciaType extends AbstractType {

    /**
     * {@inheritdoc}
     */
    public function buildForm(FormBuilderInterface $builder, array $options) {

        $roles = array('Ejecutivo de Cuenta' => 'ROLE_USER_EJECUTIVO',
            'Ejecutivo de Cuenta' => 'ROLE_USER_CUENTA',
            'Productor' => 'ROLE_USER_PRODUCTOR',
            'Supervisor' => 'ROLE_USER_SUPERVISOR');


        $builder->add('username', null, [
                    'label' => 'Nombre de usuario',
                    'attr' => [
                        'class' => 'form-control',
                    ]
                ])
                ->add('email', null, [
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
                ->add('image', \Symfony\Component\Form\Extension\Core\Type\HiddenType::class)->add('imageFile', VichFileType::class, array('required' => false))
                ->add('enabled', null, [
                    'label' => 'Activo',
                    'attr' => [
                        'class' => '',
                    ]
                ])
                ->add('plainPassword', null, [
                    'label' => 'Contraseña',
                    'attr' => [
                        'class' => 'form-control',
                    ]
                ])
                ->add('roles', \Symfony\Component\Form\Extension\Core\Type\ChoiceType::class, [
                    'choices' => $roles,
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
