<?php

//require_once(get_template_directory() . '/tcpdf/config/lang/eng.php');
require_once(get_template_directory() . '/tcpdf/tcpdf.php');

class CW_PDF extends TCPDF {

	public $workbook_title;
	public $workbook_intro;

	public function carawebs_set_title ( $workbook_title ) {

		$this->workbook_title = $workbook_title;

	}

	public function carawebs_set_intro ( $workbook_intro ) {

		$this->workbook_intro = $workbook_intro;

	}

	public function Header() {

		/**
		 * Set margins for pages without header
		 *
		 */
		TCPDF::SetMargins(20, 20, 20, true); // The margin for pages without a header

		/**
		 * @var $pageN int The page number
		 *
		 */
		$pageN = TCPDF::PageNo();

		if ( 1 == $pageN ) {

			/**
			 * Set margins for page 1 - which has the header
			 *
			 */
			TCPDF::SetMargins(20, 60, 20, true);

			$this->setJPEGQuality(90);

			/**
			 * Add a rectangle for a bottom border
			 *
			 */
			$this->Rect(20, 5, 170, 45,'F', array('B' => array('width' => 1, 'cap' => 'butt', 'join' => 'miter', 'dash' => 0, 'color' => array(180, 180, 180))), array(255, 255, 255));

			/**
			 * Title HTML
			 *
			 */
			$this->writeHTMLCell(
				90,
				10,
				20,
				10,
				$this->workbook_title,
				//array('TLBR' => array('width' => 2, 'cap' => 'butt', 'join' => 'miter', 'dash' => 0, 'color' => array(0, 0, 0))),
				array(),
				0,
				0,
				'',
				false
			);

			/**
			 * Logo HTML
			 *
			 */
			$this->Image(
				get_template_directory() . '/assets/img/student-studio-logo.png',
				115,	// $x
				10,		// $y
				75,		// $width
				0,		// $height
				'PNG',
				'http://studentstudio.co.uk'
				);

			/**
			 *  Intro HTML
			 *
			 */
			$this->writeHTMLCell(
				170,
				0,
				20,
				35,
				$this->workbook_intro,
				0,
				0,
				0,
				true,
				'L',
				false
			);


		} else {

			return;

		}

	}

	public function Footer() {
		$this->SetY(-30);
		//$this->SetFont('times', 'N', 9);

		$this->Cell(0, 0, '', array('T' => array('width' => 1, 'cap' => 'butt', 'join' => 'miter', 'dash' => 0, 'color' => array(180, 180, 180))), false, 'C');
		$this->Ln(5);
		$footer_html = '<a href="http://studentstudio.co.uk">studentstudio.co.uk</a> - work experience.';

		$this->writeHTMLCell(
			50, 					// cell width
			0,						// cell minimum height
			'',						// upper left corner x coord
			270,//'',						// upper left corner y coord
			$footer_html,	// HTML
			0,						// Border
			//array('T' => array('width' => 1, 'cap' => 'butt', 'join' => 'miter', 'dash' => 0, 'color' => array(180, 180, 180))),
			0,						// Current position after the call
			0,						// Fill
			true,					// if true reset last cell height
			'L',					// Alignment
			false					// autopadding - if true, uses internal padding and auto adjusts for line width
		);

		$imagepath = get_template_directory() . '/assets/img/think-up-logo-small-black.png';
		$this->Image(
			$imagepath,
			//get_template_directory() . '/assets/img/think-up-logo-small-black.png',
			20,	// $x
			275,		// $y
			15,		// $width
			0,		// $height
			'PNG',
			'http://thinkup.org'
			);

		//$this->Cell(0, 10, $footer_html, array('T' => array('width' => 1, 'cap' => 'butt', 'join' => 'miter', 'dash' => 0, 'color' => array(180, 180, 180))), false, 'C');
		$this->Ln(7);
		// Cell() method: http://www.tcpdf.org/doc/code/classTCPDF.html#a33b265e5eb3e4d1d4fedfe29f8166f31
		$this->Cell(20, 10, 'This PDF was created on: ' .
		//date('l jS \of F Y h:i:s A'),
			date('l jS \of F Y'),
			0, 0, 'L'
			);
		$this->Cell(0, 10, 'page ' . $this->getAliasNumPage() . ' of ' .
			$this->getAliasNbPages(), 0, 0, 'R' );

	}

}
