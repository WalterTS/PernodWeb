<?php

namespace WE\ReportBundle\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Security\Core\Authentication\Token\Storage\TokenStorage;

class UsuarioActivacionProductorType extends AbstractType {

    protected $tokenStorage;

    public function __construct(TokenStorage $tokenStorage) {
        $this->tokenStorage = $tokenStorage;
    }

    /**
     * {@inheritdoc}
     */
    public function buildForm(FormBuilderInterface $builder, array $options) {
        $role = 'ROLE_USER_PRODUCTOR';
        $user = $this->tokenStorage->getToken()->getUser();
        $builder->add('tipo', \Symfony\Component\Form\Extension\Core\Type\HiddenType::class, array('data' => 'Productor'))
                ->add('usuario', \Symfony\Bridge\Doctrine\Form\Type\EntityType::class, [
                    'label' => false,
                    'class' => \WE\ReportBundle\Entity\Usuario::class,
                    'query_builder' => function(\Doctrine\ORM\EntityRepository $repo) use ($role,$user) {
                        $qb = $repo->createQueryBuilder('u')
                                ->where('u.roles LIKE :roles')
                                ->andWhere('u.agencia = :agencia')
                                ->setParameter('agencia', $user->getAgencia())
                                ->setParameter('roles', '%"' . $role . '"%');

                        return $qb;
                    }]);
    }

    /**
     * {@inheritdoc}
     */
    public function configureOptions(OptionsResolver $resolver) {
        $resolver->setDefaults(array(
            'data_class' => 'WE\ReportBundle\Entity\UsuarioActivacion'
        ));
    }

    /**
     * {@inheritdoc}
     */
    public function getBlockPrefix() {
        return 'UsuarioActivacionSupervisor';
    }

}
