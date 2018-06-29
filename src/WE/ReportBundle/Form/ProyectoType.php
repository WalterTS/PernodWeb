<?php

namespace WE\ReportBundle\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\DateTimeType;
use Symfony\Component\Form\Extension\Core\Type\CollectionType;
use Doctrine\Common\Collections\ArrayCollection;
use Symfony\Component\Form\FormInterface;
use Symfony\Component\Form\FormEvents;
use Symfony\Component\Form\FormEvent;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\IntegerType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Tetranz\Select2EntityBundle\Form\Type\Select2EntityType;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use WE\ReportBundle\Entity\Region;
use WE\ReportBundle\Entity\Plaza;
use WE\ReportBundle\Entity\CDC;
use Doctrine\ORM\QueryBuilder;

class ProyectoType extends AbstractType {

    /**
     * {@inheritdoc}
     */
    public function buildForm(FormBuilderInterface $builder, array $options) {
        $builder->add('nombre', TextType::class, [
                    'attr' => ['class' => 'form-control']
                ])
                ->add('fecha_inicio', DateTimeType::class, [
                    'format' => 'yyyy-MM-dd',
                    'widget' => 'single_text',
                    'html5' => false,
                    'attr' => ['class' => 'form-control js-datepicker', 'autocomplete' => 'off']
                        ]
                )
                ->add('fecha_fin', DateTimeType::class, [
                    'format' => 'yyyy-MM-dd',
                    'widget' => 'single_text',
                    'html5' => false,
                    'attr' => ['class' => 'form-control js-datepicker', 'autocomplete' => 'off']
                        ]
                )
                ->add('total_activaciones', IntegerType::class, [
                    'attr' => ['class' => 'form-control']
                ])
                ->add('kpi_tipo', ChoiceType::class, [
                    'label' => 'Tipo de venta',
                    'choices' => ['Botella' => '1', 'Copa' => '2'],
                    'attr' => ['class' => 'form-control']
                ])
                ->add('kpi_total', IntegerType::class, [
                    'label' => 'Venta total',
                    'attr' => ['class' => 'form-control']
                ])
                ->add('maximo_plaza', IntegerType::class, [
                    'attr' => ['class' => 'form-control']
                ])
                ->add('tiempo_cancelacion', ChoiceType::class, [
                    'choices' => ['24 Horas' => '24', '48 Horas' => '48', '72 Horas' => '72'],
                    'attr' => ['class' => 'form-control']
                ])
                ->add('descripcion')
                ->add('marca')
                ->add('agencia')
                ->add('activaciones_tipo')
                ->add('regiones', Select2EntityType::class, [
                    'label' => 'Regiones',
                    'multiple' => true,
                    'remote_route' => 'ajax_filter_p',
                    'class' => Region::class,
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
                    'placeholder' => 'Selecciona region',
                    'required' => false,
                ])
                ->add('plazas', Select2EntityType::class, [
                    'label' => 'Plazas',
                    'multiple' => true,
                    'remote_route' => 'ajax_filter_p',
                    'class' => Plaza::class,
                    'primary_key' => 'id',
                    'text_property' => 'nombre',
                    'minimum_input_length' => 2,
                    'page_limit' => 10,
                    'allow_clear' => true,
                    'delay' => 250,
                    'cache' => true,
                    'req_params' => ['regiones' => 'parent.children[regiones]'],
                    'property' => 'nombre',
                    'cache_timeout' => 60000,
                    'language' => 'es',
                    'placeholder' => 'Selecciona plaza',
                    'required' => false,
                    'callback' => function (QueryBuilder $qb, $data) {
                        $qb->leftJoin('e.region', 'r')
                        ->andWhere('r.id IN (:region)');
                        if ($data instanceof Request) {
                            $qb->setParameter('region', $data->get('regiones'));
                        } else {
                            $qb->setParameter('region', $data->get('regiones'));
                        }
                    },
                ])
                ->add('cdcs', Select2EntityType::class, [
                    'label' => 'Centros de consumo',
                    'multiple' => true,
                    'remote_route' => 'ajax_filter_p',
                    'class' => CDC::class,
                    'primary_key' => 'id',
                    'text_property' => 'cliente',
                    'minimum_input_length' => 2,
                    'page_limit' => 10,
                    'allow_clear' => true,
                    'delay' => 250,
                    'cache' => true,
                    'property' => 'cliente',
                    'cache_timeout' => 60000,
                    'language' => 'es',
                    'placeholder' => 'Selecciona CDC',
                    'required' => false,
                    'req_params' => ['regiones' => 'parent.children[regiones]', 'plazas' => 'parent.children[plazas]'],
                    'callback' => function (QueryBuilder $qb, $data) {
                        $qb->leftJoin('e.plaza', 'p')
                        ->leftJoin('p.region', 'r')
                        ->andWhere('p.id IN (:plaza)')
                        ->andWhere('r.id IN (:region)');
                        if ($data instanceof Request) {
                            $qb->setParameter('region', $data->get('regiones'));
                            $qb->setParameter('plaza', $data->get('plazas'));
                        } else {
                            $qb->setParameter('region', $data->get('regiones'));
                            $qb->setParameter('plaza', $data->get('plazas'));
                        }
                    },
                ])
                ->add('asignacionesRegion', CollectionType::class, array(
                    'label' => 'Aignación / Región',
                    'entry_type' => ProyectoAsignacionRegionType::class,
                    'allow_add' => true,
                    'allow_delete' => true,
                    'by_reference' => false,
                    'entry_options' => [
                        'label' => false
                    ],
                    'attr' => array(
                        'class' => 'my-selector d-fields-inline',
                    ),
                    'prototype' => true,
                ))
                ->add('asignacionesSquare', CollectionType::class, array(
                    'label' => 'Asignación / Plaza',
                    'entry_type' => ProyectoAsignacionPlazaType::class,
                    'allow_add' => true,
                    'allow_delete' => true,
                    'by_reference' => false,
                    'entry_options' => [
                        'label' => false
                    ],
                    'attr' => array(
                        'class' => 'my-selector d-fields-inline',
                    ),
                    'prototype' => true,
                ))
                ->add('asignacionesCdc', CollectionType::class, array(
                    'label' => 'Asignación / Centro de Consumo',
                    'entry_type' => ProyectoAsignacionCdcType::class,
                    'allow_add' => true,
                    'allow_delete' => true,
                    'by_reference' => false,
                    'entry_options' => [
                        'label' => false
                    ],
                    'attr' => array(
                        'class' => 'my-selector d-fields-inline',
                    ),
                    'prototype' => true,
                ))
                ->add('kpi_impactos', null,[
                    'label' => 'Impactos',
                ])
                ->add('kpi_degustaciones', null,[
                    'label' => 'Degustaciones',
                ])
        ;
    }

    /**
     * {@inheritdoc}
     */
    public function getBlockPrefix() {
        return 'we_reportbundle_proyecto';
    }

}
