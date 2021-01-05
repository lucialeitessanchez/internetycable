<?php

namespace PruebaBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Response;

class DefaultController extends Controller
{
    public function indexAction()
    {
        return $this->render('@Prueba/Default/index.html.twig');
    }
    //esto es un redireccionamieneto, si no escribe santa fe o reconquista te lleva a google
    public function contactarAction($lugar)
    {
            if($lugar=='santafe'){
                //le digo que genere la url y la busque en la ruta prueba_nombre2
                return $this->redirect($this->generateUrl('prueba_nombre2'));
            }elseif ($lugar=='Reconquista'){
                return $this->render('@Prueba/Default/index.html.twig');
            }else{
                //realiza una excepcion
                throw $this->createNotFoundException('Estas equivocade');
            }

    }
    
    public function nombreAction($nPila)
    {
        return $this->render('@Prueba/Default/nombre.html.twig',array('nPila'=>$nPila));
    }

    public function nombreSinParamAction()
    {
        return $this->render('@Prueba/Default/index.html.twig');
    }

    //te muestra el nombre que recibe como parametro, esta concatenado mediantes los .
    public function nombreRAction($nPila)
    {
        return new Response('<html><head><body>Hola '.$nPila.'</body></head></html>');
    }

    public function nombresAction(){
        $nombres = [
            "Lucia"=> ["nombre"=>"Lucia",
                         "activo"=>true],
            "Paco"=>["nombre"=>"Paco",
            "activo"=>false],
            "Luis"=>["nombre"=>"Luis",
            "activo"=>true],
            "Maria"=>["nombre"=>"Maria",
            "activo"=>false]];
        return $this->render('@Prueba/Default/nombres.html.twig', array('nombres'=>$nombres));
    }


}
