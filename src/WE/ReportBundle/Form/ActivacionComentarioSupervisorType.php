<?php

namespace WE\ReportBundle\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\HiddenType;

class ActivacionComentarioSupervisorType extends AbstractType {

    /**
     * {@inheritdoc}
     */
    public function buildForm(FormBuilderInterface $builder, array $options) {
        $builder->add('comentario')
                ->add('user_from', null, [
                    'label' => 'Usuario',
                ]) 
                ->add('activacion')
                ->add('tipo', \Symfony\Component\Form\Extension\Core\Type\ChoiceType::class,array('choices' => array('Comentario' => 'Comentario','Promoción' => 'Promoción')));
    }

    /**
     * {@inheritdoc}
     */
    public function configureOptions(OptionsResolver $resolver) {
        $resolver->setDefaults(array(
            'data_class' => 'WE\ReportBundle\Entity\ActivacionComentario'
        ));
    }

    /**
     * {@inheritdoc}
     */
    public function getBlockPrefix() {
        return 'we_reportbundle_activacioncomentario';
    }

}
