<?php

class PdfBuilder
{

	public $tcpdf = array();
	public $doc_details = array();

	public function __construct($doc_details = array())
	{
		//require_once('app/libraries/TCPDF/tcpdf.php');
		$this->doc_details = $doc_details;
		if (!empty($this->doc_details['mode'])) {
			if ($this->doc_details['mode'] == 'L') {
				$mode = 'L';
			} else {
				$mode = 'P';
			}
		} else {
			$mode = 'P';
		}

		// $original_mem = ini_get('memory_limit');
		// ini_set('memory_limit','640M');
		// ini_set('memory_limit',$original_mem);
		ini_set('max_execution_time', 120);

		// create new PDF document
		$this->tcpdf = new \TCPDF($mode, 'mm', 'A4', true, 'UTF-8', false);
		$this->tcpdf->SetCreator("IT Scient");
		$this->tcpdf->SetAuthor($this->doc_details['author']);
		$this->tcpdf->SetTitle($this->doc_details['title']);
		$this->tcpdf->SetSubject($this->doc_details['title']);

		// set default header data
		$topMarginValue = $this->doc_details['topMarginValue'];

		$this->tcpdf->SetHeaderData(PDF_HEADER_LOGO, PDF_HEADER_LOGO_WIDTH, "", "", array(0, 0, 0), array(255, 255, 255));
		$this->tcpdf->SetHeaderMargin('6');
		$topMarginValue = 10;
		$this->tcpdf->SetFont('dejavusans', '', 9);

		// set header and footer fonts
		$this->tcpdf->setHeaderFont(array(PDF_FONT_NAME_MAIN, '', PDF_FONT_SIZE_MAIN));
		$this->tcpdf->setFooterFont(array(PDF_FONT_NAME_DATA, '', PDF_FONT_SIZE_DATA));

		// set default monospaced font
		//$this->tcpdf->SetDefaultMonospacedFont(PDF_FONT_MONOSPACED);

		// set margins
		$this->tcpdf->SetMargins('5', $topMarginValue, '5');
		$this->tcpdf->SetFooterMargin('7');

		// set auto page breaks
		$this->tcpdf->SetAutoPageBreak(TRUE, 14);

		// set image scale factor
		$this->tcpdf->setImageScale('1.25');

		// set some language-dependent strings (optional)
		if (@file_exists(dirname(__FILE__) . '/lang/eng.php')) {
			require_once(dirname(__FILE__) . '/lang/eng.php');
			$this->tcpdf->setLanguageArray($l);
		}

		$this->tcpdf->SetPrintHeader(false);
		$this->tcpdf->SetPrintFooter(true);

		// add a page
		$this->tcpdf->AddPage();
	}

	function addPage()
	{
		$this->tcpdf->AddPage();
	}

	function space($height)
	{
		$this->tcpdf->Ln($height);
	}

	function setFont($name, $style, $size)
	{
		$this->tcpdf->SetFont($name, $style, $size);
	}

	function table($table, $table_pro = array())
	{

		$this->tcpdf->writeHTML($table, true, false, false, false);
	}

	function html($html, $align = "")
	{
		$this->tcpdf->writeHTML($html, true, false, false, false, $align);
	}

	function output($name, $viewordown = "I")
	{
		$this->tcpdf->lastPage();
		$this->tcpdf->Output($name, $viewordown);
	}
}
