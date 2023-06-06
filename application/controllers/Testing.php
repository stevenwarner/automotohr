<?php defined('BASEPATH') || exit('No direct script access allowed');

class Testing extends CI_Controller
{
    //
    public function __construct()
    {
        parent::__construct();
        // Call the model
        $this->load->model("test_model", "tm");
    }

    /**
     * 
     */
    public function redirectToComply(int $employeeId = 0)
    {
        // check if we need to read from session
        if ($employeeId === 0) {
            $employeeId = $this->session->userdata('logged_in')['employer_detail']['sid'];
        }
        // if employee is not found
        if ($employeeId == 0) {
            return redirect('/dashboard');
        }
        // generate link
        $complyLink = getComplyNetLink(0, $employeeId);
        //
        if (!$complyLink) {
            return redirect('/dashboard');
        }
        redirect($complyLink);
    }


    public function test()
    {
        //
        $this->load->library('scorm/parser', [], 'scorm_parser');

        $file = ROOTPATH . 'm12.xml'; // Single SCO 1.2
        $file = ROOTPATH . 'm2004_3.xml'; // Single SCO 2004 3rd edition
        $file = ROOTPATH . 'm12_multiple_sco.xml'; // Multiple SCO 1.2
        $file = ROOTPATH . 'm2004_3_multiple_sco.xml'; // Multiple SCO 2004 3rd edition
        $file = ROOTPATH . 'm12_runtime_calls_multiple_sco.xml'; // Runtime calls Multiple SCO 12
        $file = ROOTPATH . 'm2004_3_runtime_calls_multiple_sco.xml'; // Runtime calls Multiple SCO 12
        $file = ROOTPATH . 'm2004_3_advanced_calls_multiple_sco.xml'; // Advanced Runtime calls Multiple SCO 12
        $file = ROOTPATH . 'm2004_3_sequence.xml'; // Sequence
        $handler = fopen($file, 'r');
        $fileContents = fread($handler, filesize($file));
        fclose($handler);

        $this
            ->scorm_parser
            ->setContent($fileContents)
            ->parse();
    }
}
