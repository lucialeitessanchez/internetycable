<?php

namespace PruebaBundle\Controller;

use PruebaBundle\Entity\servicio;
use PruebaBundle\PruebaBundle;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;

class ServicioController extends Controller
{
    public function allAction()
    {
        $repository = $this->getDoctrine()->getRepository('PruebaBundle:servicio');
        // finds *all* servicios
        $servicios = $repository->findAll();
        return $this->render('@Prueba/Servicios/all.html.twig',array("servicios"=>$servicios));
    }
    


    
}
