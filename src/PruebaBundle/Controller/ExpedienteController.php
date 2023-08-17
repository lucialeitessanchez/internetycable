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
        $dia_semana = date('w');
        $dias_ES = array("Domingo", "Lunes", "Martes", "Miércoles", "Jueves", "Viernes", "Sábado");
        $dias_EN = array("Monday", "Tuesday", "Wednesday", "Thursday", "Friday", "Saturday", "Sunday");
        $nombredia = str_replace($dias_EN, $dias_ES, $dia);
        $meses_ES = array("Enero", "Febrero", "Marzo", "Abril", "Mayo", "Junio", "Julio", "Agosto", "Septiembre", "Octubre", "Noviembre", "Diciembre");
        $meses_EN = array("January", "February", "March", "April", "May", "June", "July", "August", "September", "October", "November", "December");
        $nombreMes = str_replace($meses_EN, $meses_ES, $mes);
        
        return $dias_ES[$dia_semana]." ".$numeroDia." de ".$nombreMes." de ".date('Y');
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

function expedientePDFOne(Expediente $expediente,Factura $factura){ 
    /******* */
    $pdf = new MYPDF(PDF_PAGE_ORIENTATION, PDF_UNIT, PDF_PAGE_FORMAT, true, 'UTF-8', false); //llamo a la clase que extiende tcpdf
    $pdf->AddPage();
    $pdf->SetMargins(PDF_MARGIN_LEFT, PDF_MARGIN_TOP, PDF_MARGIN_RIGHT); //margenes

    //saber de donde viene el expediente
    $numE=substr($expediente->getNumeroExpe(),0,1);
    if($numE == 0){
        $txt="Ref.: Expte. Nro 0".$expediente->getNumeroExpe();
        
    } 

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

 public function expedientePDFAction(Request $request,int $id){

    //base de datos
    $em = $this->getDoctrine()->getManager();
    $expediente = $em->getRepository('PruebaBundle:Expediente')->find($id); //obtengo el objeto expediente
    
    $facturas = $expediente->getFacturas(); //obtengo el objeto factura relacionado a ese expediente
   
    $numFactura = $facturas[0]->getNumFactura(); //tengo el primer objeto si o si 

    // ********* Se crea la clase de pdf, y se agrega fecha y ref de expediente, para todas las notas es lo mismo******
    $pdf = new MYPDF(PDF_PAGE_ORIENTATION, PDF_UNIT, PDF_PAGE_FORMAT, true, 'UTF-8', false); //llamo a la clase que extiende tcpdf
            $pdf->AddPage();
            $pdf->SetMargins(23, PDF_MARGIN_TOP, PDF_MARGIN_RIGHT); //margenes

            //saber de donde viene el expediente
            $numE=substr($expediente->getNumeroExpe(),0,0); 
            if($numE == 0){
                $txt="Ref.: Expte. Nro 0".$expediente->getNumeroExpe();
            } 
                else{
                    $txt="Ref.: Expte. Nro 00".$expediente->getNumeroExpe(); 
                }
            

            //transformo la fecha en texto y en español
            $fechaActual = date('d M Y');
            $fechaActual = $this->fechaCastellano($fechaActual);
            
            //textos derecha
            $pdf->Write(3, ' ', '', 0, 'R', true, 0, false, false, 0); 
            $pdf->Write(3, ' ', '', 0, 'R', true, 0, false, false, 0);
            $pdf->Write(3, ' ', '', 0, 'R', true, 0, false, false, 0);
            $pdf->Write(3, ' ', '', 0, 'R', true, 0, false, false, 0); 
            $pdf->Write(3, ' ', '', 0, 'R', true, 0, false, false, 0);
            $pdf->Write(3, ' ', '', 0, 'R', true, 0, false, false, 0);
            $txt2="Santa Fe, ".$fechaActual;
            $pdf->SetFont('helvetica', 'B', 12);
            $pdf->Write(0, $txt, 'B', 0, 'R', true, 0, false, false, 0);
            $pdf->SetFont('', '', 12);
            $pdf->Write(0, $txt2, '', 0, 'R', true, 0, false, false, 0);

            //espacios
            $pdf->Write(3, ' ', '', 0, 'R', true, 0, false, false, 0); 
            $pdf->Write(3, ' ', '', 0, 'R', true, 0, false, false, 0);
            $pdf->Write(3, ' ', '', 0, 'R', true, 0, false, false, 0); 

    if (count($facturas)==1){
        
            $servicios = $facturas[0]->getService(); 

            
            if (count($servicios)==1){ 
                //parrafo principal
                $txt3="Se informa que corresponde a la factura n°: ".$numFactura;
                foreach($servicios as $servicio) {
                    $txt4=" por el Servicio de ".$servicio->getTipo();
                    $txt5=" del período de ".$facturas[0]->getPeriodo();
                    $txt6=" prestado por la empresa ".$servicio->getCompania();

                    if($servicio->getDireccion() == "CORRIENTES 2879"){
                        $txt7=" en este Ministerio de Igualdad, Género y Diversidad de calle ".$servicio->getDireccion(); 

                    }
                    else{
                        $txt7=" en la dependecia del Ministerio de Igualdad, Género y Diversidad de calle ".$servicio->getDireccion();

                    }
                    $txt8=" de la ciudad de ".$servicio->getCiudad();
                    $txt9=" con el número de referencia: ".$servicio->getReferencia()."\n";   

                }

            $total= $facturas[0]->getTotal();
            $txt10=", con un total de $".$total; //el total de la factura
            $envio="\n\n\n".$request->get('select')."\n";
          
            $pdf->Write(0, $txt3.$txt4.$txt5.$txt6.$txt7.$txt8.$txt9.$txt10, '', 0, 'J', true, 0, false, false, 0);
            $pdf->Write(0,$envio, '', 0, 'J', true, 0, false, false, 0);
            
            }   
            //hay mas de un servicio en una factura 
            else{
                $txt3="Se informa que pertenece a la factura n°: ".$numFactura;
                $txt4=" correspondiente a los servicios detallados en el cuadro siguiente, prestados a este Ministerio de Igualdad, Género y Diversidad y sus dependencias, pertenecientes al periodo ".$facturas[0]->getPeriodo();
                $total= $facturas[0]->getTotal();
                $txt5=" con un total de $".$total."\n\n";
                $envio="\n\n\n".$request->get('select')."\n";
             
                $pdf->Write(0, $txt3.$txt4.$txt5, '', 0, 'J', true, 0, false, false, 0);
       
                
                //cuadro
                foreach($servicios as $servicio){
                    
                    $data[] = [$servicio->getReferencia(),$servicio->getDireccion(),$servicio->getCiudad(),$servicio->getTipo()];
                    
                }
                
               
                // column titles
                $header = array('Nº de referencia', 'Dirección', 'Ciudad', 'Tipo de servicio');
                // print colored table
                $pdf->ColoredTable($header, $data);
                
                $pdf->Write(0,$envio, '', 0, 'J', true, 0, false, false, 0);
            
            } 
            
            
    } 

    //si hay mas de una factura
    else{
        $numFacturaAnterior = null;
        $totalImpreso = false;
        //parrafo principal
        $txt3="Se Informa los siguientes servicios que fueron prestados a este Ministerio de Igualdad, Genero y Diversidad, y sus dependencias, en el siguiente cuadro: \n\n";
        $pdf->Write(0, $txt3, '', 0, 'J', true, 0, false, false, 0);
        $envio="\n\n\n".$request->get('select')."\n";
          
        //cuadro
        $data=[];
          foreach($facturas as $factura){

                $servicios=$factura->getService();
                foreach($servicios as $servicio) {
                // Verificar si el número de factura es igual al anterior
                if ($factura->getNumFactura() !== $numFacturaAnterior) {
                    $totalImpreso = false;
                    $numFacturaAnterior = $factura->getNumFactura();
                }

                // Imprimir el total solo en la primera aparición
                $thisTotal = $factura->getTotal();
                $totalColumnValue = $totalImpreso ? 0 : $thisTotal;
                $data[] = [
                    $factura->getNumFactura(),
                    $factura->getPeriodo(),
                    $servicio->getReferencia(),
                    $servicio->getDireccion(),
                    $servicio->getCiudad(),
                    $servicio->getTipo(),
                    $totalColumnValue,
                ];
                $totalImpreso = true;
                }
            }

            // column titles
            $header = array('Factura nº','Periodo','Referencia','Dirección','Ciudad','Servicio', 'Total');
             // arma el cuadro con la funcion
            $pdf->FacturasTable($header, $data);
            
            $pdf->Write(0,$envio, '', 0, 'J', true, 0, false, false, 0);
            


    }   

    //salida PDF, genera en la cache y luego de guardarlo lo borra para no generar basura en el servidor
    $cache_dir = $this->getParameter('kernel.cache_dir');
    $filename = $cache_dir.'/nota.pdf';
    $pdf->Output($filename , 'F');
    
    return $this->file($filename)->deleteFileAfterSend(true);
 }
}
 class MYPDF extends \TCPDF {

    // Constants for page size and margins
    const PDF_PAGE_ORIENTATION = 'P'; // Portrait orientation
    const PDF_UNIT = 'mm'; // Measurement unit is millimeters
    const PDF_PAGE_FORMAT = 'A4'; // Page size is A4

    // Custom margins (adjust these values based on your needs)
    const PDF_MARGIN_TOP = 20;
    const PDF_MARGIN_RIGHT = 15;
    const PDF_MARGIN_BOTTOM = 20;
    const PDF_MARGIN_LEFT = 15;

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
        $this->SetFont('times', 'I', 8);
        $image_file = 'imagenes/logo ministerio-negro_sin fondo.png';
        $this->Image('imagenes/logo ministerio-negro_sin fondo.png');
        // Footer
        $this->Cell(0, 10, "Sectorial de Informática  
        Ministerio de Igualdad, Género y Diversidad
        Corrientes 2879 - (3000) Santa Fe
        Tel: (0342) 4572888 / 4589468 int. 30904", 0, false, 'C', 0, '', 0, false, 'T', 'M');
        
    }


   
    // cuadro en forma de tabla para la factura con varios servicios
    public function ColoredTable($header,$data) {
        // Colors, line width and bold font
        $this->SetFillColor(255, 0, 0);
        $this->SetTextColor(255);
        $this->SetDrawColor(128, 0, 0);
        $this->SetLineWidth(0.3);
        $this->SetFont('', 'B');
        // Header
        $w = array(45, 55, 45, 45); //tamaño del ancho del cuadro
        $num_headers = count($header);
        for($i = 0; $i < $num_headers; ++$i) {
            $this->Cell($w[$i], 7, $header[$i], 1, 0, 'C', 1);
        }
        $this->Ln();
        // Color and font restoration
        $this->SetFillColor(224, 235, 255);
        $this->SetTextColor(0);
        $this->SetFont('');
       //recorro el arreglo con la info y por cada elemento lo voy colocando en las dif. columnas
        $fill = 0;
        foreach($data as $row) {
            $this->Cell($w[0], 6, $row[0], 'LR', 0, 'L', $fill);
            $this->Cell($w[1], 6, $row[1], 'LR', 0, 'L', $fill);
            $this->Cell($w[2], 6, ($row[2]), 'LR', 0, 'R', $fill);
            $this->Cell($w[3], 6, ($row[3]), 'LR', 0, 'R', $fill);
            $this->Ln();
            $fill=!$fill;
        }
        
        $this->Cell(array_sum($w), 0, '', 'T');
    }

    public function FacturasTable($header, $data) {
        // Colors, line width and bold font
        $this->SetFillColor(255, 0, 0);
        $this->SetTextColor(255);
        $this->SetDrawColor(255, 0, 0);
        $this->SetFont('', 'B');
        // Header
    
        // Get the number of columns
        $num_columns = count($header);
    
        // Initialize an array to store the maximum width of each column
        $column_widths = array_fill(0, $num_columns, 0);
    
        // Calculate the maximum width of each column based on the header
        for ($i = 0; $i < $num_columns; ++$i) {
            $width = $this->GetStringWidth($header[$i]) + 6; // Add some padding
            if ($width > $column_widths[$i]) {
                $column_widths[$i] = $width;
            }
        }
    
        $this->Ln();
        // Color and font restoration
        $this->SetFillColor(224, 235, 255);
        $this->SetTextColor(0);
         // Ajustar el tamaño de la fuente a 10
          $this->SetFont('', '', 8);
    
        // Calculate the maximum width of each column based on the content
        foreach ($data as $row) {
            foreach ($row as $key => $value) {
                $width = $this->GetStringWidth($value) + 6; // Add some padding
                if ($width > $column_widths[$key]) {
                    $column_widths[$key] = $width;
                }
            }
        }
    
        // Header
        for ($i = 0; $i < $num_columns; ++$i) {
            $this->Cell($column_widths[$i], 7, $header[$i], 1, 0, 'L', 1);
        }
        $this->Ln();
    
        //recorro el arreglo con la info y por cada elemento lo voy colocando en las dif. columnas
        $fill = 0;
        foreach ($data as $row) {
            for ($i = 0; $i < $num_columns; ++$i) {
                $this->Cell($column_widths[$i], 6, $row[$i], 'LR', 0, 'L', $fill);
            }
            $this->Ln();
            $fill = !$fill;
        }
    }


}



