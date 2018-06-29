<?php

namespace WE\ReportBundle\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolverInterface;
use Symfony\Component\Form\Extension\Core\Type\CollectionType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\NumberType;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\OptionsResolver\OptionsResolver;

class ColumnaType extends AbstractType {

    /**
     * @param FormBuilderInterface $builder
     * @param array $options
     */
    public function buildForm(FormBuilderInterface $builder, array $options) {
        $builder
                ->add('texto', TextType::class, array('label' => 'Etiqueta'))
                ->add('posicion', NumberType::class, array('label' => 'Posición'))
                ->add('tipo', EntityType::class,array('class' => \WE\ReportBundle\Entity\TipoColumna::class))
                ->add('items', CollectionType::class, array(
                    'by_reference' => false,
                    'label' => 'Elementos',
                    'allow_add' => true,
                    'allow_delete' => true,
                    'entry_type' => ItemType::class
                ))
        ;
    }

    /**
     * @param OptionsResolverInterface $resolver
     */
    public function configureOptions(OptionsResolver $resolver) {
        $resolver->setDefaults(array(
            'data_class' => 'WE\ReportBundle\Entity\Columna',
        ));
    }

    /**
     * @return string
     */
    public function getName() {
        return 'we_reportbundle_columna';
    }

}
