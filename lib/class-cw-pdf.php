<?php

//require_once(get_template_directory() . '/tcpdf/config/lang/eng.php');
require_once(get_template_directory() . '/tcpdf/tcpdf.php');

class CW_PDF extends TCPDF {

	public function Header() {
		//array('B' => array('width' => 1, 'cap' => 'butt', 'join' => 'miter', 'dash' => 0, 'color' => array(180, 180, 180)))
		//$this->SetLineStyle(array('B' => array('width' => 1, 'cap' => 'butt', 'join' => 'miter', 'dash' => 0, 'color' => array(180, 180, 180))));
		//$this->SetLineStyle(array('width' => 1, 'cap' => 'butt', 'join' => 'miter', 'dash' => 0, 'color' => array(0, 0, 0)));
		$this->setJPEGQuality(90);
		$this->Image(
			get_template_directory() . '/assets/img/student-studio-logo.png',
			120,	// $x
			10,		// $y
			75,		// $width
			0,		// $height
			'PNG',
			'http://studentstudio.co.uk'
			);
		//$this->Cell($w='', $h=5, $txt='', $border=1);

	}

	public function Footer() {
		$this->SetY(-30);
		// $pdf->SetFont('times', 'BI', 20, '', 'false');
		//$this->SetFont(PDF_FONT_NAME_MAIN, 'U', 14);

		// http://www.tcpdf.org/doc/code/classTCPDF.html#a33b265e5eb3e4d1d4fedfe29f8166f31
		$this->Cell(0, 10, 'studentstudio.co.uk - work experience.', array('T' => array('width' => 1, 'cap' => 'butt', 'join' => 'miter', 'dash' => 0, 'color' => array(180, 180, 180))), false, 'C');
		$this->Ln(5);
		$this->Cell(20, 10, 'Date:' . date('d.m.Y'),
			0, 0, 'L'
			);
		$this->Cell(0, 10, 'page ' . $this->getAliasNumPage() . ' of ' .
			$this->getAliasNbPages(), 0, 0, 'R' );
		//$this->SetY(-10);
    $this->SetFont('times', 'N', 9);
		//$this->writeHtmlCell($widthheader,3,20,4,'<p>Page '.$pdf->getAliasNumPage().' of  '.' '.$pdf->getAliasNbPages().'</p>','',1,0,false,'R');
	}
	/*public function CreateTextBox($textval, $x = 0, $y, $width = 0, $height = 10, $fontsize = 10, $fontstyle = '', $align = 'L') {
		$this->SetXY($x+20, $y); // 20 = margin left
		$this->SetFont(PDF_FONT_NAME_MAIN, $fontstyle, $fontsize);
		$this->Cell($width, $height, $textval, 0, false, $align);
	}*/
}
