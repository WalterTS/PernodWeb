<?php

namespace WE\ReportBundle\Controller;

use Symfony\Component\Form\Extension\Core\Type\CollectionType;
use JavierEguiluz\Bundle\EasyAdminBundle\Controller\AdminController as BaseAdminController;
use WE\ReportBundle\Form\SeccionType;
use WE\ReportBundle\Form\UsuarioActivacionType;
use Vich\UploaderBundle\Form\Type\VichFileType;

class AdminController extends BaseAdminController {

    public function createEntityForm($entity, array $entityProperties, $view) {
        $form = parent::createEntityForm($entity, $entityProperties, $view);
        $form->remove('updated_at');
        if ($this->entity['name'] == 'CDC' || $this->entity['name'] == 'Marca' || $this->entity['name'] == 'Usuario') {
            $form->remove('image');
            $form->add('imageFile', VichFileType::class, array('required' => false));
        }
        if ($this->entity['name'] === 'Activacion') {
            $form->remove('filas');
            $form->remove('usuarios');

            $form->add('usuarios', CollectionType::class, array(
                'label' => 'Usuarios',
                'entry_type' => UsuarioActivacionType::class,
                'allow_add' => true,
                'allow_delete' => true,
                'by_reference' => false,
                'entry_options' => [
                    'label' => false
                ]
            ));
        }

        if ($this->entity['name'] === 'ActivacionTipo') {
            $form->remove('talento');

            $form->add('talento', CollectionType::class, array(
                'label' => 'Talento',
                'entry_type' => \WE\ReportBundle\Form\ActivacionTipoTalentoType::class,
                'allow_add' => true,
                'allow_delete' => true,
                'by_reference' => false,
                'entry_options' => [
                    'label' => false
                ]
            ));
        }

        if ($this->entity['name'] === 'Usuario') {
            $form->remove('notificaciones_recibidas');
            $form->remove('notificaciones_enviadas');
            $form->remove('usernameCanonical');
            $form->remove('emailCanonical');
            $form->remove('salt');
            $form->remove('password');
            $form->remove('confirmationToken');
            $form->remove('passwordRequestedAt');
            $form->remove('lastLogin');
            $form->add('plainPassword');
        }
        if ($this->entity['name'] === 'Proyecto') {
            $form->remove('responsable');
            $form->add('kpi_tipo', \Symfony\Component\Form\Extension\Core\Type\ChoiceType::class, array('choices' => array('Botella' => '1', 'Copa' => '2')));
            $form->add('tiempo_cancelacion', \Symfony\Component\Form\Extension\Core\Type\ChoiceType::class, array('choices' => array('24 Horas' => '24', '48 Horas' => '48', '72 Horas' => '72')));
            $form->remove('activaciones');
            $form->remove('asignacionesRegion');
            $form->remove('asignacionesPlaza');
            $form->remove('asignacionesCdc');
        }
        if ($this->entity['name'] === 'Instrumento') {
            $form->remove('secciones');
            $form->add('secciones', CollectionType::class, array(
                'label' => 'Secciones',
                'entry_type' => SeccionType::class,
                'allow_add' => true,
                'allow_delete' => true,
                'by_reference' => false,
                'entry_options' => [
                    'label' => false
                ]
            ));
        }

        return $form;
    }

    public function createNewUsuarioEntity() {
        return $this->get('fos_user.user_manager')->createUser();
    }

    public function prePersistUsuarioEntity($user) {
        $this->get('fos_user.user_manager')->updateUser($user, false);
    }

    public function preUpdateUsuarioEntity($user) {
        $this->get('fos_user.user_manager')->updateUser($user, false);
    }

    public function persistProyectoEntity($entity) {
        $this->container->get('status_generator')->setProyecto($entity);
        $entity->setResponsable($this->getUser());
        parent::persistEntity($entity);
    }

}
