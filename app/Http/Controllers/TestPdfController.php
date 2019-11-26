<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Vsmoraes\Pdf\Pdf;

class TestPdfController extends Controller
{
    //
    private $pdf;

    public function __construct(Pdf $pdf)
    {
        $this->pdf = $pdf;
    }

    public function index()
    {
        $html = view('test-pdf.index')->render();

        return $this->pdf
            ->load($html)
            ->show('abc.pdf');
    }
}
