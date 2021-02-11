<?php
/**
 * Created by PhpStorm.
 * User: Adee
 * Date: 3/30/2018
 * Time: 6:05 PM
 */

defined('BASEPATH') OR exit('No direct script access allowed');
define('DOMPDF_ENABLE_AUTOLOAD', false);

use Dompdf\Dompdf;
//use Dompdf\Options;

class Pdfgenerator {

    public function generate($html, $filename='', $stream=TRUE, $paper='A4', $orientation='Landscape')
    {
//        $options = new Options();
//        $options->set('isRemoteEnabled', true);
//        $options->set('isHtml5ParserEnabled', true);
//        $dompdf = new Dompdf($options);
        $dompdf = new Dompdf();
        $dompdf->setPaper($paper, $orientation);
        $dompdf->loadHtml($html);
        // Render the HTML as PDF
        $dompdf->render();
        if ($stream) {
            // Output the generated PDF to Browser
            $dompdf->stream($filename.".pdf");
        } else {
            $dompdf->output();
        }
    }
}