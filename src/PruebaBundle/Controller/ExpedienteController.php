<?php

namespace PruebaBundle\Controller;

use PruebaBundle\Entity\servicio;
use PruebaBundle\Entity\Expediente;
use PruebaBundle\Entity\Factura;
use PruebaBundle\PruebaBundle;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;


/**
 * Expediente controller.
 *
 */
class ExpedienteController extends Controller
{
    /**
     * Lists all expediente entities.
     *
     */
    public function indexAction()
    {
        $em = $this->getDoctrine()->getManager();

        $expedientes = $em->getRepository('PruebaBundle:Expediente')->findAll();

        return $this->render('expediente/index.html.twig', array(
            'expedientes' => $expedientes,
        ));
    }

    /**
     * Creates a new expediente entity.
     *
     */
    public function newAction(Request $request)
    {
        $expediente = new Expediente();
        $form = $this->createForm('PruebaBundle\Form\ExpedienteType', $expediente);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $em->persist($expediente);
            $em->flush();

            return $this->redirectToRoute('expediente_show', array('id' => $expediente->getId()));
        }

        return $this->render('expediente/new.html.twig', array(
            'expediente' => $expediente,
            'form' => $form->createView(),
        ));
    }

    /**
     * Finds and displays a expediente entity.
     *
     */
    public function showAction(Expediente $expediente)
    {
        $deleteForm = $this->createDeleteForm($expediente);

        return $this->render('expediente/show.html.twig', array(
            'expediente' => $expediente,
            'delete_form' => $deleteForm->createView(),
        ));

    }

    /**
     * Displays a form to edit an existing expediente entity.
     *
     */
    public function editAction(Request $request, Expediente $expediente)
    {
        $deleteForm = $this->createDeleteForm($expediente);
        $editForm = $this->createForm('PruebaBundle\Form\ExpedienteType', $expediente);
        $editForm->handleRequest($request);

        if ($editForm->isSubmitted() && $editForm->isValid()) {
            $this->getDoctrine()->getManager()->flush();

            return $this->redirectToRoute('expediente_edit', array('id' => $expediente->getId()));
        }

        return $this->render('expediente/edit.html.twig', array(
            'expediente' => $expediente,
            'edit_form' => $editForm->createView(),
            'delete_form' => $deleteForm->createView(),
        ));
    }

    /**
     * Deletes a expediente entity.
     *
     */
    public function deleteAction(Request $request, Expediente $expediente)
    {
        $form = $this->createDeleteForm($expediente);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $em->remove($expediente);
            $em->flush();
        }

        return $this->redirectToRoute('expediente_index');
    }

    /**
     * Creates a form to delete a expediente entity.
     *
     * @param Expediente $expediente The expediente entity
     *
     * @return \Symfony\Component\Form\FormInterface The form
     */
    private function createDeleteForm(Expediente $expediente)
    {
        return $this->createFormBuilder()
            ->setAction($this->generateUrl('expediente_delete', array('id' => $expediente->getId())))
            ->setMethod('DELETE')
            ->getForm()
        ;
    }

    function fechaCastellano ($fecha) {
        $fecha = substr($fecha, 0, 10);
        $numeroDia = date('d', strtotime($fecha));
        $dia = date('l', strtotime($fecha));
        $mes = date('F', strtotime($fecha));
        $dias_ES = array("Lunes", "Martes", "Miércoles", "Jueves", "Viernes", "Sábado", "Domingo");
        $dias_EN = array("Monday", "Tuesday", "Wednesday", "Thursday", "Friday", "Saturday", "Sunday");
        $nombredia = str_replace($dias_EN, $dias_ES, $dia);
        $meses_ES = array("Enero", "Febrero", "Marzo", "Abril", "Mayo", "Junio", "Julio", "Agosto", "Septiembre", "Octubre", "Noviembre", "Diciembre");
        $meses_EN = array("January", "February", "March", "April", "May", "June", "July", "August", "September", "October", "November", "December");
        $nombreMes = str_replace($meses_EN, $meses_ES, $mes);
        return $nombredia." ".$numeroDia." de ".$nombreMes." de ".date('Y');
    }

    public function imprimirAction(Expediente $expediente)
    {
        $deleteForm = $this->createDeleteForm($expediente);

        //aca obtengo las facturas que tiene el expediente
        $exp = $this->getDoctrine()->getRepository('PruebaBundle:Expediente')->find($expediente);
        $factura = $exp->getFacturas();

    

        $cantidadFacturas =sizeof($factura);//tamaño del objeto factura/s

        $fechaActual = date('d M Y');
        $fechaActual = $this->fechaCastellano($fechaActual);

        if($cantidadFacturas == 1){
         
        return $this->render("expediente/imprimir.html.twig", array(
            'expediente' => $expediente,
            'facturas' => $factura,
            'fecha' => $fechaActual,
            'delete_form' => $deleteForm->createView()
        ));
    }                   }




        //$repo1 = $this->getDoctrine()->getManager()->getRepository(Factura::class)->findAll(); //me traigo todos los objetos factura de la bd
       // $facturas = $repo1->find(($expediente->getFacturas())); // tengo el conjunto de facturas relacionadas con este expediente
       // $cantidadFacturas = sizeof($facturas->getId()); // le cuento los id asi se cuantas hay


        //$repo2 = $this->getDoctrine()->getManager()->getRepository(servicio::class)->findAll();
       // $servicios = $repo2->find(($facturas->getService())); //tengo el o los servicios que tiene la factura

        //llamo a la funcion para guardar la fecha en español

        //primero me tengo que fijar la cantidad de facturas, despues la cantidad de servicios
       /*

        if ($cantidadFacturas == 1) {
            $cantidadServicios = sizeof($repo2->getId());
            if ($cantidadServicios == 1) { */
                //si entra aca, el expediente tiene una factura de un solo servicio
              /*  if (($servicios->getDireccion()) == "CORRIENTES 2879") {
                    return $this->render('@Prueba/Expediente/imprimirAca.html.twig', array(
                            'expediente' => $expediente,
                            'servicio' => $servicios,
                            'factura' => $facturas,
                            'fecha' => $fechaActual,
                            'delete_form' => $deleteForm->createView()
                        )

                    );
                } else {
                    //si no muestra el archivo de dependencia de esta secretaria

                    return $this->render("expediente/imprimir.html.twig", array(
                        'expediente' => $expediente,
                        'facturas' => $factura,
                        'fecha' => $fechaActual,
                        'delete_form' => $deleteForm->createView()
                    ));
                } */
                                         //   }
            //me tengo que fijar que todos sean de la misma direccion
            //el caso que tiene una factura pero muchos servicios

      /*  }
        else{ //me fijo si la empresa es la misma y mando todo junto
            return $this->render('@Prueba/Expediente/variosServicios.html.twig', array(
                'expediente' => $expediente,
                'servicios' => $servicios,
                'facturas' => $facturas,
                'fecha' => $fechaActual,
                'delete_form' => $deleteForm->createView()
            ));
        }*/

  //  }
       /* if(  (($servicio->getDireccion()) == "CORRIENTES 2879" )){ // pregunto si es de esa calle por el formato
            return $this->render('expediente/imprimirAca.html.twig', array(
                'expediente' => $expediente,
                'servicio'=>$servicio,
                'facturas'=>$factura,
                'fecha'=>$fechaActual,
                //'factures'=>$factures,
                'delete_form' => $deleteForm->createView()
            ));
                                                                }

        else{ //si no muestra el archivo de dependencia de esta secretaria
        return $this->render('expediente/imprimir.html.twig', array(
            'expediente' => $expediente,
            'servicio'=>$servicio,
            'facturas'=>$factura,
            'fecha'=>$fechaActual,
            //'factures'=>$factures,
            'delete_form' => $deleteForm->createView()
                                                                        ));
            }*/




}
