<?php

namespace WE\ReportBundle\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\Extension\Core\Type\DateTimeType;
use Symfony\Component\Form\FormInterface;
use Symfony\Component\Form\FormEvents;
use Symfony\Component\Form\FormEvent;
use WE\ReportBundle\Entity\Proyecto;
use Symfony\Component\Security\Core\Authentication\Token\Storage\TokenStorage;

class ActivacionType extends AbstractType {

    protected $tokenStorage;

    public function __construct(TokenStorage $tokenStorage) {
        $this->tokenStorage = $tokenStorage;
    }

    protected function getIds($data) {
        $serializer = array();
        foreach ($data as $entry) {
            $serializer[] = $entry->getId();
        }

        return $serializer;
    }

    /**
     * {@inheritdoc}
     */
    public function buildForm(FormBuilderInterface $builder, array $options) {
        $user = $this->tokenStorage->getToken()->getUser();

        $builder
                ->add('proyecto', \Symfony\Bridge\Doctrine\Form\Type\EntityType::class, [
                    'class' => \WE\ReportBundle\Entity\Proyecto::class,
                    'placeholder' => "--SELECCIONA--",
                    'query_builder' => function(\Doctrine\ORM\EntityRepository $repo) use ($user) {
                        $qb = $repo->createQueryBuilder('p')
                                ->leftJoin('p.regiones', 'r')
                                ->leftJoin('p.cdcs', 'c');

                        if ($user->getCdcs()->count()) {
                            $qb->andWhere('c.id IN (:cdcs)')
                            ->setParameter('cdcs', $this->getIds($user->getCdcs()));
                        }

                        if ($user->getRegion()) {
                            $qb->andWhere('r.id = :region')
                            ->setParameter('region', $user->getRegion());
                        }

                        return $qb;
                    }])
                ->add('fecha', DateTimeType::class, [
                    'date_widget' => 'single_text',
                    'time_widget' => 'single_text',
                    'html5' => false,
                    'date_format' => 'yyyy-MM-dd'
                        ]
                )
                ->add('tipo', null, array('attr' => array('class' => 'form-control')));

        $formModifier = function (FormInterface $form, Proyecto $proyecto = null) use ($user) {
            $cdcs = null === $proyecto ? array() : $this->getCdcScope($proyecto, $user);
            $tipos = null === $proyecto ? array() : $proyecto->getActivacionesTipo();

            $form->add('cdc', EntityType::class, array(
                'label' => 'Centro de consumo',
                'class' => 'ReportBundle:CDC',
                'placeholder' => '--SELECCIONA--',
                'choices' => $cdcs,
            ));

            $form->add('tipo', EntityType::class, array(
                'class' => 'ReportBundle:ActivacionTipo',
                'placeholder' => '--SELECCIONA--',
                'choices' => $tipos,
            ));
        };


        $builder->addEventListener(FormEvents::PRE_SET_DATA, function(FormEvent $event) use ($formModifier) {
            $data = $event->getData();
            $formModifier($event->getForm(), $data->getProyecto());
        }
        );

        $builder->get('proyecto')->addEventListener(
                FormEvents::POST_SUBMIT, function (FormEvent $event) use ($formModifier) {
            $proyecto = $event->getForm()->getData();
            $formModifier($event->getForm()->getParent(), $proyecto);
        }
        );
    }

    protected function getCdcScope($proyecto, $user) {

        //PASARLE ESTE VALOR SI ES UN USUARIO QUE NO ES KAM
        $cdcs = array();

        if ($proyecto->getCdcs()->count()) {
            $cdcs = $proyecto->getCdcs();
        } else if ($proyecto->getPlazas()->count()) {
            $cdcs = $this->getCdcsByPlaza($proyecto->getPlazas());
        } else if ($proyecto->getRegiones()->count()) {
            $cdcs = $this->getCdcsByRegion($proyecto->getRegiones());
        }

        return $this->userCdcIntersection($cdcs, $user);
    }

    protected function getCdcsByPlaza($plazas) {
        $cdcs = array();
        foreach ($plazas as $plaza) {
            foreach ($plaza->getCdcs() as $cdc) {
                $cdcs[] = $cdc;
            }
        }
        return $cdcs;
    }

    protected function getCdcsByRegion($regiones) {
        $cdcs = array();
        foreach ($regiones as $region) {
            $cdcs = array_merge($cdcs, $this->getCdcsByPlaza($region->getPlazas()));
        }
        return $cdcs;
    }

    protected function userCdcIntersection($cdcs, $user) {
        $return = $cdcs;
        if ($user->getCdcs()->count() > 0) {
            $return = array_intersect($user->getCdcs()->toArray(), $cdcs->toArray());
        }

        return $return;
    }

    /**
     * {@inheritdoc}
     */
    public function configureOptions(OptionsResolver $resolver) {
        $resolver->setDefaults(array(
            'data_class' => 'WE\ReportBundle\Entity\Activacion'
        ));
    }

    /**
     * {@inheritdoc}
     */
    public function getBlockPrefix() {
        return 'we_reportbundle_activacion';
    }

}
