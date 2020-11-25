<?php

namespace PruebaBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;

class DefaultController extends Controller
{
    public function indexAction()
    {
        return $this->render('@Prueba/Default/index.html.twig');
    }
    
    public function nombreAction($nPila)
    {
        return $this->render('@Prueba/Default/nombre.html.twig',array('nPila'=>$nPila));
    }

    public function nombreSinParamAction()
    {
        return $this->render('@Prueba/Default/index.html.twig');
    }

    
}
