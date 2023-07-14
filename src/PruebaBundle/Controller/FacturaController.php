<?php

namespace PruebaBundle\Controller;

use Doctrine\DBAL\Exception\UniqueConstraintViolationException;
use Doctrine\DBAL\Types\stdClass;
use PruebaBundle\Entity\Expediente;
use PruebaBundle\Entity\Factura;
use PruebaBundle\Entity\servicio;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use PruebaBundle\Repository\FacturaRepository;
use PruebaBundle\Reportes\MiPDF;

/**
 * Factura controller.
 *
 */
class FacturaController extends Controller
{
    /**
     * Lists all factura entities.
     *
     */
    public function indexAction()
    {
        $em = $this->getDoctrine()->getManager();
        $facturas = $em->getRepository('PruebaBundle:Factura')->findAllArray();
        //aca tengo que preguntar si factura esta vacio retorna esto si no que mande un 0 al indez
        return $this->render('factura/index.html.twig', array('facturas' => $facturas ));
    }

    /**
     * Lists all servicios con facturas y expedientes
     *
     */
    public function reportesAction(Request $request)
    {

        $em = $this->getDoctrine();
        $facturas = $this->getDoctrine()->getRepository(Factura::class)->findAll();
        $expedientes = $this->getDoctrine()->getRepository(Expediente::class)->findAll();
        $servicios = $this->getDoctrine()->getRepository(servicio::class)->findBy(['estado' => true]); //tengo todos los servicios activos
        
        
        
       if($request->get('tabla')){
         // 
         var_dump($request->get('tabla'));

         $pdf = new MiPDF(PDF_PAGE_ORIENTATION, PDF_UNIT, PDF_PAGE_FORMAT, true, 'UTF-8', false); //llamo a la clase que extiende tcpdf
         $pdf->AddPage();
         $pdf->SetMargins(PDF_MARGIN_LEFT, PDF_MARGIN_TOP, PDF_MARGIN_RIGHT); //margenes
 
         $filas = $request->get('tabla'); //lo que le pido al front 
         
         foreach($filas as $fila){
         $pdf->Write(0, $fila, '', 0, 'C', true, 0, false, false, 0);
         }
         //salida PDF
         $pdf->Output('reporte.pdf', 'I');
       }


        return $this->render('factura/reportes.html.twig', array('facturas' => $facturas,'expedientes' => $expedientes, 'servicios' => $servicios));
    }

    

    public function expteFaltantesAction($mes, $servicios){
        return $this->render();
    }
    /**
     * listado de facturas sin pagar
     */
    public function facturasImpagasAction(){
        
        $em = $this->getDoctrine()->getManager();
        $facturas = $em->getRepository('PruebaBundle:Factura')->findAllArray();
       return $this->render('factura/impagas.html.twig', array('facturas' => $facturas));
    }

    /**
     * Creates a new factura entity.
     *
     */
    public function newAction(Request $request)
    {
        $factura = new Factura();
        $form = $this->createForm('PruebaBundle\Form\FacturaType', $factura);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $em = $this->getDoctrine()->getManager();

           // $repository = $this->getRepository(Factura::class);//le pido a la base de datos los objetos factura



            $em->persist($factura); // si no existe la factura, listo la guarda y llama al mostrar
            try{

                $em->flush();

                return $this->redirectToRoute('factura_show', array('id' => $factura->getId()));
            }

            catch(UniqueConstraintViolationException $e){ //esto es porque puse que el numero de factura es unico, como sucede que se "viola" eso llamo a la pantalla del "error"
                return $this->render('factura/factura_repet.html.twig');
            }

        }


        return $this->render('factura/new.html.twig', array(
            'factura' => $factura,
            'form' => $form->createView(),
        ));
    }

    /**
     * Finds and displays a factura entity.
     *
     */
    public function showAction(Factura $factura)
    {
        $deleteForm = $this->createDeleteForm($factura);


        return $this->render('factura/show.html.twig', array(
            'factura' => $factura,
            'delete_form' => $deleteForm->createView(),
        ));
    }

    /**
     * Displays a form to edit an existing factura entity.
     *
     */
    public function editAction(Request $request, Factura $factura)
    {
        $deleteForm = $this->createDeleteForm($factura);
        $editForm = $this->createForm('PruebaBundle\Form\FacturaType', $factura);
        $editForm->handleRequest($request);

        if ($editForm->isSubmitted() && $editForm->isValid()) {
            $this->getDoctrine()->getManager()->flush();

            return $this->redirectToRoute('factura_index');
        }

        return $this->render('factura/edit.html.twig', array(
            'factura' => $factura,
            'edit_form' => $editForm->createView(),
            'delete_form' => $deleteForm->createView(),
        ));
    }

    /**
     * cambia el estado de la factura a pagado
     *
     */
    public function pagarFAction(Request $request, Factura $factura)
    {
            $em = $this->getDoctrine();
            $e=$factura->getExpediente();
            $expediente = $this->getDoctrine()->getRepository(Expediente::class)->find($e);
       
            $factura->setPago(true);
            $expediente->setEstado("DGA");
            $this->getDoctrine()->getManager()->flush();

            return $this->redirectToRoute('factura_index');
       
    }
    /**
     * Deletes a factura entity.
     *
     */
    public function deleteAction(Request $request, Factura $factura)
    {
        $form = $this->createDeleteForm($factura);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $em->remove($factura);
            $em->flush();
        }

        return $this->redirectToRoute('factura_index');
    }

    /**
     * Creates a form to delete a factura entity.
     *
     * @param Factura $factura The factura entity
     *
     * @return \Symfony\Component\Form\Form The form
     */
    private function createDeleteForm(Factura $factura)
    {
        return $this->createFormBuilder()
            ->setAction($this->generateUrl('factura_delete', array('id' => $factura->getId())))
            ->setMethod('DELETE')
            ->getForm()
        ;
    }

    public function imprimirNotaAction(Expediente $expediente, $fecha ){
        
        $facturaRepo=$this->getDoctrine()->getRepository(Factura::class);
        $facturas = $facturaRepo->find(($expediente->getFacturas())); //todas las facturas que tiene el expediente que me traje

        $servicioRepo=$this->getDoctrine()->getRepository(servicio::class);
        $servicios = $servicioRepo->findBy(($facturas->getService)); //Todos los servicios relacionados a esa factura

        //tengo que preguntar la cantidad de servicios y si todos esos servicios tiene la misma direccion
        if((sizeof($servicios))<=1) { //caso base una factura tiene un solo servicio
            if ((($servicios->getDireccion()) == "CORRIENTES 2879")) { // pregunto si es de esa calle por el formato
                return $this->render('expediente/imprimirAca.html.twig', array(
                    'expediente' => $expediente,
                    'servicio' => $servicios,
                    'facturas' => $facturas,
                    'fecha' => $fecha
                ));
            }
            else{
                return $this->render('expediente/imprimir.html.twig', array(
                    'expediente' => $expediente,
                    'servicio' => $servicios,
                    'facturas' => $facturas,
                    'fecha' => $fecha
                ));
            }

        /*else{ //si no muestra el archivo de dependencia de esta secretaria
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


                                                                    }

    

}
