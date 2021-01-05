<?php

namespace PruebaBundle\Controller;
use PruebaBundle\Entity\servicio;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\JsonResponse;


class ApiController extends Controller
{

    //MENSAJES DEFINIDOS PARA USAR COMO MENSAJES
    const BAD_NAME_COMPANY="Falta nombre en la petición de la compania";
    const NO_ALL_ELEMENTS="Falta algún parámetro del servicio para crear";
    const NO_CONTENT="No se ha encontrado el servicio";
    const BAD_NAME_COMPANY_HELP="Ejemplo -> http://cursosymfony.com/curso/api/empresa/nombre_empresa";

    //Funcion que serializa un objeto servicio
    private function serializeServicio(servicio $servicio)
    {
        return array(
            'compania' => $servicio->getCompania(),
            'ciudad' => $servicio->getCiudad(),
            'referencia' => $servicio->getReferencia(),
            'estado'=> $servicio->getEstado(),
            'direccion'=> $servicio->getDireccion()

        );
    }

    //Funcion que utilizamos para devolver una petición incorrecta
    private function badRequest($msg,$help=null)
    {
        return array(
            'mensage'=>$msg,
            'help'=>$help
        );
    }

//esta accion devuelve un servicio filtrado por compañia
    public function servicioAction($compania){
    if($compania=='Sin Definir'){
       $response = new JsonResponse($this->badRequest(self::BAD_NAME_COMPANY,self::BAD_NAME_COMPANY_HELP),400); // EL 400 ES POR CODIGO DE ERROR
    }else{
        $repository = $this->getDoctrine()->getRepository('PruebaBundle:servicio');
        $servicio = $repository->findOneByCompania($compania);
        if(isset($servicio)){
            $data['servicio'][] = $this->serializeServicio($servicio);
            $response = new JsonResponse($data, 200);
        }else{
            $response = new JsonResponse($this->badRequest(self::NO_CONTENT,"Servicio buscado: ".$compania), 200);
        }
    }
    return $response;

                                            }


    //Esta acción inserta un servicio
    public function crearServicioAction(Request $request)
    {
        //En primer lugar comprobaremos que todos los campos necesarios
        //para la inserción existen
        if(
            $request->request->get('referencia')==null
            ||
            $request->request->get('ciudad')==null
            ||
            $request->request->get('direccion')==null
            ||
            $request->request->get('estado')==null
            ||
            $request->request->get('compania')==null
        )
        {
            $response = new JsonResponse($this->badRequest(self::NO_ALL_ELEMENTS,""), 400);
        }else{
            //generamos la empresa a partir de los datos
            $servicio = new servicio();
            $servicio->setReferencia($request->request->get('referencia'));
            $servicio->setCiudad($request->request->get('ciudad'));
            $servicio->setDireccion($request->request->get('direccion'));
            $servicio->setEstado($request->request->get('estado'));
            $servicio->setCompania($request->request->get('compania'));
            //Guardamos el servicio
            $em = $this->getDoctrine()->getManager();
            $em->persist($servicio);
            $em->flush();
            //Devolvemos el servicio en el JsonResponse
            $data['servicio'][] = $this->serializeServicio($servicio);
            $response = new JsonResponse($data, 200);
        }
        return $response;
    }
}

