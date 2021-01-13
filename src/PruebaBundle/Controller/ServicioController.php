<?php

namespace PruebaBundle\Controller;

use PruebaBundle\Entity\Expediente;
use PruebaBundle\Entity\Factura;
use Symfony\Component\HttpFoundation\Request;
use PruebaBundle\Entity\servicio;
use PruebaBundle\Form\servicioType;
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

    public function crearServicioAction()
    {
        //generar un nuevo objeto del tipo servicio y setearle los atributos
        $empresa = new servicio();
        $empresa ->setCompania("FIBERCORP");
        $empresa->setReferencia(80065067);
        $empresa->setEstado(0);
        $empresa->setDireccion("SAN JOSE 1701");
        $empresa ->setCiudad("SANTA FE");

        //DOCTRINE
        $mangDoct=$this->getDoctrine()->getManager();
        //añado el objeto a la bd
        $mangDoct->persist($empresa);
        $mangDoct->flush($empresa);

        return $this->render('@Prueba/Servicios/crearServicio.html.twig',array("empresaId"=>$empresa->getId()));
    }

    public function buscarServicioAction($id){
        //recuperar el repositorio de la entidad servicio
        $repository = $this->getDoctrine()->getRepository('PruebaBundle:servicio');
        $empresa = $repository->find($id);
        return $this->render('@Prueba/Servicios/compania.html.twig',array("id"=>$empresa->getId(),"compania"=>$empresa->getCompania()));
    }

    public function buscarServicioPorCompaniaAction($compania){
        //recuperar el repositorio de la entidad servicio
        $repository = $this->getDoctrine()->getRepository('PruebaBundle:servicio');
        $empresa = $repository->findOneByCompania($compania); //aca podria ser finOneByNombre, es el nombre del atributo de la entidad
        return $this->render('@Prueba/Servicios/compania.html.twig',array("id"=>$empresa->getId(),"compania"=>$empresa->getCompania()));
    }

    public function actualizarServicioAction($id,$compania){
        $repository = $this->getDoctrine()->getManager();
        $empresa = $repository->getRepository(servicio::class)->find($id);
        $empresa->setCompania($compania); // segun el id que le llega le pone la compania que le llega tambien
        $repository->flush();
        return $this->redirectToRoute('home');
    }

    public function nuevoAction(Request $request){
        $service = new servicio(); //instancia de la entidad servicio
        $form = $this->createForm(servicioType::class);
        //llamo al request
        $form->handleRequest($request);
        //en caso de que el request se haya enviado y sea valido
        if($form->isSubmitted() && $form->isValid()){
            $service = $form->getData(); //toma los datos ingresados en el formulario

            //pongo los datos que tomo en la base de datos
            $em =$this->getDoctrine()->getManager();
            $em->persist($service);
            $em->flush();


            return $this->redirect($this->generateUrl('prueba_nombre2'));
        }

        return $this->render('@Prueba/Servicios/nuevo.html.twig',array("form"=>$form->createView())); //le paso el formulario y le mando la vista
    }

    public function nuevoExpAction(){
        $em = $this->getDoctrine()->getManager();
        //servicio generado
        $service = new servicio();
        $service->setReferencia(80065067);
        $service->setEstado(false);
        $service->setDireccion("SAN JOSE 1701");
        $service->setCiudad("SANTA FE");
        $service->setCompania("FIBERCORP");

        $em->persist($service);
        $em->flush();


        //genero un nuevo expediente para ese servicio
        $expediente = new Expediente();
        $expediente->setNumeroExpe(250100009687);
        $expediente->setEstado("En comunicaciones");

        //enlazar el expediente al servicio
        $expediente->setService($service);

        //Guardar en la base de datos

       // $em->persist($service);
        $em->persist($expediente);
        $em->flush();

        return $this->redirectToRoute('all_servicios');
    }


    /*Listado de servicios
    */

    public function indexAction()
    {
        $em = $this->getDoctrine()->getManager();


        $servicios = $em->getRepository('PruebaBundle:servicio')->findAll();

        //aca tengo que preguntar si factura esta vacio retorna esto si no que mande un 0 al indez
        return $this->render('@Prueba/Servicios/index.html.twig', array('servicios' => $servicios,
        ));
    }

    /**
     * Creates a new factura entity.
     *
     */
    public function newAction(Request $request)
    {
        $servici = new servicio();
        $form = $this->createForm('PruebaBundle\Form\servicioType', $servici);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $em->persist($servici);
            $em->flush();

            return $this->redirectToRoute('servicio_show', array('id' => $servici->getId()));
        }

        return $this->render('@Prueba/Servicios/nuevo.html.twig', array(
            'servicio' => $servici,
            'form' => $form->createView(),
        ));
    }

    /**
     * Finds and displays a factura entity.
     *
     */
    public function showAction(servicio $servici)
    {
        $deleteForm = $this->createDeleteForm($servici);

        return $this->render('@Prueba/Servicios/show.html.twig', array(
            'servicio' => $servici,
            'delete_form' => $deleteForm->createView(),
        ));
    }

    /**
     * Displays a form to edit an existing factura entity.
     *
     */
    public function editAction(Request $request, servicio $servici)
    {
        $deleteForm = $this->createDeleteForm($servici);
        $editForm = $this->createForm('PruebaBundle\Form\servicioType', $servici);
        $editForm->handleRequest($request);

        if ($editForm->isSubmitted() && $editForm->isValid()) {
            $this->getDoctrine()->getManager()->flush();

            return $this->redirectToRoute('servicio_edit', array('id' => $servici->getId()));
        }

        return $this->render('@Prueba/Servicios/edit.html.twig', array(
            'servicio' => $servici,
            'edit_form' => $editForm->createView(),
            'delete_form' => $deleteForm->createView(),
        ));
    }

    /**
     * Deletes a factura entity.
     *
     */
    public function deleteAction(Request $request, servicio $servici)
    {
        $form = $this->createDeleteForm($servici);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $em->remove($servici);
            $em->flush();
        }

        return $this->redirectToRoute('servicio_index');
    }

    /**
     * Creates a form to delete a factura entity.
     *
     * @param servicio $servici The factura entity
     *
     * @return \Symfony\Component\Form\Form The form
     */
    private function createDeleteForm(servicio $servici)
    {
        return $this->createFormBuilder()
            ->setAction($this->generateUrl('servicio_delete', array('id' => $servici->getId())))
            ->setMethod('DELETE')
            ->getForm()
            ;
    }

}
