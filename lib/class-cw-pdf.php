<?php

//require_once(get_template_directory() . '/tcpdf/config/lang/eng.php');
require_once(get_template_directory() . '/tcpdf/tcpdf.php');

class CW_PDF extends TCPDF {
	public function Header() {
		$this->setJPEGQuality(90);
		$this->Image( get_template_directory() . '/assets/img/student-studio-logo.png', 120, 10, 75, 0, 'PNG', 'http://studentstudio.co.uk');

	}/*
	public function Footer() {
		$this->SetY(-15);
		$this->SetFont(PDF_FONT_NAME_MAIN, 'I', 8);
		$this->Cell(0, 10, 'finalwebsites.com - PHP Script Resource, PHP classes and code for web developer', 0, false, 'C');
	}
	public function CreateTextBox($textval, $x = 0, $y, $width = 0, $height = 10, $fontsize = 10, $fontstyle = '', $align = 'L') {
		$this->SetXY($x+20, $y); // 20 = margin left
		$this->SetFont(PDF_FONT_NAME_MAIN, $fontstyle, $fontsize);
		$this->Cell($width, $height, $textval, 0, false, $align);
	}*/
}
