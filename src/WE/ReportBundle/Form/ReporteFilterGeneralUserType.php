<?php

namespace WE\ReportBundle\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Tetranz\Select2EntityBundle\Form\Type\Select2EntityType;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use WE\ReportBundle\Entity\Region;
use WE\ReportBundle\Entity\Plaza;
use WE\ReportBundle\Entity\CDC;
use Doctrine\ORM\QueryBuilder;
use WE\ReportBundle\Entity\Usuario;

class ReporteFilterGeneralUserType extends AbstractType {

    /**
     * {@inheritdoc}
     */
    public function buildForm(FormBuilderInterface $builder, array $options) {
        $builder
                ->add('date_from', TextType::class, array('label' => 'Fecha desde', 'required' => false, 'attr' => array('class' => 'date-from form-control')))
                ->add('date_to', TextType::class, array('label' => 'Fecha hasta', 'required' => false, 'attr' => array('class' => 'date-to form-control')))
                ->add('marca', \Symfony\Bridge\Doctrine\Form\Type\EntityType::class, [
                    'class' => \WE\ReportBundle\Entity\Marca::class,
                    'multiple' => false,
                    'expanded' => false,
                    'required' => false,
                ])
        ;
    }

    /**
     * {@inheritdoc}
     */
    public function getBlockPrefix() {
        return 'filter_form_report';
    }

}
