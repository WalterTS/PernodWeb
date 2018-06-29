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

class ReporteFilterType extends AbstractType {

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
                ->add('region', Select2EntityType::class, [
                    'label' => 'Regiones',
                    'multiple' => true,
                    'remote_route' => 'ajax_filter',
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
                ->add('plaza', Select2EntityType::class, [
                    'label' => 'Plazas',
                    'multiple' => true,
                    'remote_route' => 'ajax_filter',
                    'class' => Plaza::class,
                    'primary_key' => 'id',
                    'text_property' => 'nombre',
                    'minimum_input_length' => 2,
                    'page_limit' => 10,
                    'allow_clear' => true,
                    'delay' => 250,
                    'cache' => true,
                    'req_params' => ['region' => 'parent.children[region]'],
                    'property' => 'nombre',
                    'cache_timeout' => 60000,
                    'language' => 'es',
                    'placeholder' => 'Selecciona plaza',
                    'required' => false,
                    'callback' => function (QueryBuilder $qb, $data) {
                        $qb->leftJoin('e.region', 'r')
                        ->andWhere('r.id IN (:region)');
                        if ($data instanceof Request) {
                            $qb->setParameter('region', $data->get('region'));
                        } else {
                            $qb->setParameter('region', $data->get('region'));
                        }
                    },
                ])
                ->add('usuario', Select2EntityType::class, [
                    'label' => 'Ejecutivos',
                    'multiple' => true,
                    'remote_route' => 'ajax_filter',
                    'class' => Usuario::class,
                    'primary_key' => 'id',
                    'text_property' => 'nombre',
                    'minimum_input_length' => 2,
                    'page_limit' => 10,
                    'allow_clear' => true,
                    'delay' => 250,
                    'cache' => true,
                    'property' => 'nombre',
                    'cache_timeout' => 60000,
                    'language' => 'es',
                    'placeholder' => 'Selecciona Ejecutivo',
                    'required' => false,
                    'callback' => function (QueryBuilder $qb, $data) {
                        $qb->andWhere('e.enabled = true')
                        ->andWhere('e.roles LIKE :roles')
                        ->setParameter('roles', '%ROLE_USER_EJECUTIVO%');
                    }
                ])
                ->add('cdc_tipo', \Symfony\Bridge\Doctrine\Form\Type\EntityType::class, [
                    'class' => \WE\ReportBundle\Entity\CDCType::class,
                    'multiple' => false,
                    'expanded' => false,
                    'required' => false,
                ])
                ->add('cdc', Select2EntityType::class, [
                    'label' => 'Centros de consumo',
                    'multiple' => true,
                    'remote_route' => 'ajax_filter',
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
                    'req_params' => ['region' => 'parent.children[region]', 'plaza' => 'parent.children[plaza]'],
                    'callback' => function (QueryBuilder $qb, $data) {
                        $qb->leftJoin('e.plaza', 'p')
                        ->leftJoin('p.region', 'r')
                        ->andWhere('p.id IN (:plaza)')
                        ->andWhere('r.id IN (:region)');
                        if ($data instanceof Request) {
                            $qb->setParameter('region', $data->get('region'));
                            $qb->setParameter('plaza', $data->get('plaza'));
                        } else {
                            $qb->setParameter('region', $data->get('region'));
                            $qb->setParameter('plaza', $data->get('plaza'));
                        }
                    },
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
