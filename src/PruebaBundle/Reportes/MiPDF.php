<?php

namespace PruebaBundle\Reportes;

class MiPDF extends \TCPDF {

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


}