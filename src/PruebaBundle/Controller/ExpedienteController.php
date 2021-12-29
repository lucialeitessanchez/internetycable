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

            return $this->redirectToRoute('expediente_index');
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

        if($cantidadFacturas == 1){ // si en el expediente tengo una sola factura, que puede tener uno o muchos servicios eso le importa al front



        return $this->render("expediente/imprimir.html.twig", array(
            'expediente' => $expediente,
            'facturas' => $factura,
            'fecha' => $fechaActual,
            'delete_form' => $deleteForm->createView()
        ));
    }                   
    if($cantidadFacturas > 1){
        return $this->render("expediente/imprimirVariasFact.html.twig", array(
            'expediente' => $expediente,
            'facturas' => $factura,
            'fecha' => $fechaActual,
            'delete_form' => $deleteForm->createView()
        ));
    }

}

 public function expedientePDFAction(Request $request,int $id){

    //base de datos
    $em = $this->getDoctrine()->getManager();
    $expediente = $em->getRepository('PruebaBundle:Expediente')->find($id); //obtengo el objeto expediente
    $factura = $expediente->getFacturas(); //obtengo el objeto factura relacionado a ese expediente

    $numFactura = $factura->getNumFactura();

    $pdf = new MYPDF(PDF_PAGE_ORIENTATION, PDF_UNIT, PDF_PAGE_FORMAT, true, 'UTF-8', false); //llamo a la clase que extiende tcpdf
    $pdf->AddPage();
    $pdf->SetMargins(PDF_MARGIN_LEFT, PDF_MARGIN_TOP, PDF_MARGIN_RIGHT); //margenes

    //saber de donde viene el expediente
    $numE=substr($expediente->getNumeroExpe(),0,1);
    if($numE == 0){
        $txt="Ref.: Expte. Nro 0".$expediente->getNumeroExpe();
        
    } 
    //echo $numE;

    //transformo la fecha en texto y en español
    $fechaActual = date('d M Y');
    $fechaActual = $this->fechaCastellano($fechaActual);
    
    //textos derecha
    $txt="Ref.: Expte. Nro 0".$expediente->getNumeroExpe();
    $txt2="Santa Fe, ".$fechaActual;
    $pdf->Write(0, $txt, '', 0, 'R', true, 0, false, false, 0);
    $pdf->Write(0, $txt2, '', 0, 'R', true, 0, false, false, 0);

    //espacios

    $pdf->Write(3, ' ', '', 0, 'R', true, 0, false, false, 0); 
    $pdf->Write(3, ' ', '', 0, 'R', true, 0, false, false, 0);
    $pdf->Write(3, ' ', '', 0, 'R', true, 0, false, false, 0); 

    //parrafo principal
    $txt3="Se Informa que corresponde a la factura n°: ".$numFactura;
    $txt4="por el Servicio de ";
    $txt5="del período de ";
    $txt6=" prestado por la empresa ";
    $txt7=" en la dependecia de este Ministerio de Igualdad, Género y Diversidad de calle "; //fijarse si es dependencia o no
    
    //$txt4="por el Servicio de".$factura->getService();
    $pdf->Write(0, $txt3, '', 0, 'J', true, 0, false, false, 0);


    //salida PDF
    $pdf->Output('nota.pdf', 'I');

 }
}
 class MYPDF extends \TCPDF {

    //Page header
    public function Header() {
        // Logo
       // $image_file = 'imagenes/logo ministerio-negro_sin fondo.png';
       // $this->Image($image_file, 10, 10, 15, '', 'PNG', '', 'T', false, 300, '', false, false, 0, false, false, false);
        // Set font
        $this->SetFont('helvetica', 'B', 20);
        // Title
        //$this->Cell(0, 15, '<< TCPDF Example 003 >>', 0, false, 'C', 0, '', 0, false, 'M', 'M');
    }

    // Page footer
    public function Footer() {
        // Position at 15 mm from bottom
        $this->SetY(-15);
        // Set font
        $this->SetFont('helvetica', 'I', 8);
        //$image_file = 'imagenes/logo ministerio-negro_sin fondo.png';
        $this->Image('imagenes/logo ministerio-negro_sin fondo.png');
        // Footer
        $this->Cell(0, 10, 'Sectorial de Informática
        Ministerio de Igualdad, Género y Diversidad
        Corrientes 2879 - (3000) Santa Fe
        Tel: (0342) 4572888 / 4589468', 0, false, 'C', 0, '', 0, false, 'T', 'M');
        
    }
}



