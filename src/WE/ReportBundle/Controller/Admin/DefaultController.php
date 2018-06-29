<?php

namespace WE\ReportBundle\Controller\Admin;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Symfony\Component\HttpFoundation\Request;
use WE\ReportBundle\Entity\Valor;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use WE\ReportBundle\Controller\MainController;

/**
 * @Route("/administracion")
 */
class DefaultController extends MainController {

    /**
     * @Route("/",name="administracion_index")
     * @Template()
     */
    public function indexAction(Request $request) { 
  
    }

   
}
