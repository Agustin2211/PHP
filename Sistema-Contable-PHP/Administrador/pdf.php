<?php

require('../Administrador/FPDF/fpdf.php');

date_default_timezone_set('America/Argentina/Buenos_Aires');

$message = '';

class PDF extends FPDF  {
// Cabecera de página
    function Header(){
        // Arial bold 15
        $this->SetFont('Arial','B',10);
        // Título
        $this->Cell(130);
        $this->Cell(0,0, "RECIBO DE HABERES Ley no 20.744");
        $this->Ln(5);
        $this->Cell(0,0,"Ferreteria La Tuerca");
        //Corrernos para abajo
        $this->Ln(5);
        $this->Cell(0,0,'Galvez 2564');
        $this->Ln(5);
        $this->Cell(0,0, "Rosario");
        $this->Ln(5);
        $this->Cell(0,0, "CUIT: 20-40416523-0");
        // Salto de línea
        $this->Ln(10);
    }  
}

?>                                                                                                       
