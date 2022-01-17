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

    public function transPDFAction()
    {
        $pdf = new TCPDF('L');
        $pdf->SetPrintHeader(true);
        $pdf->SetDefaultMonospacedFont(PDF_FONT_MONOSPACED);
        // set margins
        $pdf->SetMargins(PDF_MARGIN_LEFT, PDF_MARGIN_TOP, PDF_MARGIN_RIGHT);
        $pdf->SetFooterMargin(PDF_MARGIN_FOOTER);
        // set auto page breaks
        $pdf->SetAutoPageBreak(TRUE, PDF_MARGIN_BOTTOM);
        // set image scale factor
        $pdf->setImageScale(PDF_IMAGE_SCALE_RATIO);
        // set some language-dependent strings (optional)
        if (@file_exists(dirname(__FILE__) . '/lang/eng.php')) {
            require_once dirname(__FILE__) . '/lang/eng.php';
            $pdf->setLanguageArray(L);
        }
        // set font
        $pdf->SetFont('helvetica', '', 8);
        // add a page
        $pdf->AddPage();
        //get report data into $data variable
        $project = $this->getServiceLocator()->get('ProjectTable');
        $data = $project->getWsr();
        $view = new PhpRenderer();
        $resolver = new TemplateMapResolver();
        //set the path of the pdf.phtml file
        $resolver->setMap(array('PDFTemplate' => '/var/www/html/WSRAutomation/module/Application/view/application/index/pdf.phtml'));
        $view->setResolver($resolver);
        $viewModel = new ViewModel();
        $viewModel->setTemplate('PDFTemplate')->setVariables(array('projects' => $data, 'view' => 'pdf'));
        $html = $view->render($viewModel);
        $pdf->writeHTML($html, true, 0, true, 0);
        $pdf->Output('WsrReport.pdf', 'I');
    }
}
