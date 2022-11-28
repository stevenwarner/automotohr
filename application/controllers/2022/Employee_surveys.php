<?php defined('BASEPATH') or exit('No direct script access allowed');

/**
 * Employee Survey Module
 *
 * PHP version = 7.4.25
 *
 * @category   Module
 * @package    Employee Surveys
 * @author     AutomotoHR <www.automotohr.com>
 * @author     Mubashir Ahmed
 * @version    1.0
 * @link       https://www.automotohr.com
 */

class Employee_surveys extends Public_Controller
{
    // Set page path
    private $pp;
    // Set mobile path
    private $mp;
    /**
     * Holds the pages
     */
    private $pages;


    /**
     * 
     */
    public function __construct()
    {
        // Inherit parent properties and methods
        parent::__construct();
        // Load user agent
        $this->load->library('user_agent');
        //
        $this->pages = [
            'header' => 'main/header_2022',
            'footer' => 'main/footer_2022',
        ];
        //
        $this->mp = $this->agent->is_mobile() ? 'mobile/' : '';
    }


    /**
     *
     */
    public function overview()
    {
        //
        $data = [];
        $data['load_view'] = 1;
        $data['session'] = $this->session->userdata('logged_in');
        $data['employee'] = $data['session']['employer_detail'];
        //
        $this->load
            ->view($this->pages['header'], $data)
            ->view("{$this->mp}es/overview/overview")
            ->view($this->pages['footer']);
    }

    /**
     *
     */
    public function surveys()
    {
        //
        $data = [];
        $data['load_view'] = 1;
        $data['session'] = $this->session->userdata('logged_in');
     
        /*
$target_url = 'http://localhost:3000/employee_survey/templates';

$this->load->library('curl');


//  Setting URL To Fetch Data From
$this->curl->create('http://localhost:3000/employee_survey/templates');

//  To Temporarily Store Data Received From Server
$this->curl->option('buffersize', 10);
$this->curl->option('SSL_VERIFYPEER', false);
$this->curl->option('SSL_VERIFYHOST', false);

//  To support Different Browsers
$this->curl->option('useragent', 'Mozilla/5.0 (Windows; U; Windows NT 6.1; en-US; rv:1.9.2.8) Gecko/20100722 Firefox/3.6.8 (.NET CLR 3.5.30729)');

//  To Receive Data Returned From Server
$this->curl->option('returntransfer', 1);

//  To follow The URL Provided For Website
$this->curl->option('followlocation', 1);

//  To Retrieve Server Related Data
$this->curl->option('HEADER', true);

//  To Set Time For Process Timeout
$this->curl->option('connecttimeout', 600);

//  To Execute 'option' Array Into cURL Library & Store Returned Data Into $data
$data = $this->curl->execute();

//  To Display Returned Data
print_r($data);
echo $data;

die();
*/



        //
        $this->load
            ->view($this->pages['header'], $data)
            ->view("{$this->mp}es/surveys")
            ->view($this->pages['footer']);
    }

    /**
     *
     */
    public function create($id = 0, $step = 'getting_started')
    {
        //
        $data = [];
        $data['load_view'] = 1;
        $data['session'] = $this->session->userdata('logged_in');
        $data['employee'] = $data['session']['employer_detail'];
        //
        $data['step'] = $step;
        //
        $this->load
            ->view($this->pages['header'], $data)
            ->view("{$this->mp}es/create")
            ->view($this->pages['footer']);
    }



    /**
     *
     */
    public function companysurveys($id)
    {
        //
        $data = [];
        $data['load_view'] = 1;
        $data['session'] = $this->session->userdata('logged_in');
        $data['employee'] = $data['session']['employer_detail'];
        //
        $this->load
            ->view($this->pages['header'], $data)
            ->view("{$this->mp}es/companysurvey")
            ->view($this->pages['footer']);
    }

    /**
     *
     */
    public function surveyfeedback($id, $id2, $id3)
    {
        //
        $data = [];
        $data['load_view'] = 1;
        $data['session'] = $this->session->userdata('logged_in');
        $data['employee'] = $data['session']['employer_detail'];
        //
        $this->load
            ->view($this->pages['header'], $data)
            ->view("{$this->mp}es/surveyfeedback")
            ->view($this->pages['footer']);
    }


    /**
     *
     */
    public function settings()
    {
        //
        $data = [];
        $data['load_view'] = 1;
        $data['session'] = $this->session->userdata('logged_in');
        $data['employee'] = $data['session']['employer_detail'];
        //
        $this->load
            ->view($this->pages['header'], $data)
            ->view("{$this->mp}es/settings")
            ->view($this->pages['footer']);
    }




    /**
     *
     */
    public function reports()
    {
        //
        $data = [];
        $data['load_view'] = 1;
        $data['session'] = $this->session->userdata('logged_in');
        $data['employee'] = $data['session']['employer_detail'];
        //
        $this->load
            ->view($this->pages['header'], $data)
            ->view("{$this->mp}es/reports")
            ->view($this->pages['footer']);
    }


    /**
     *
     */
    public function faqs()
    {
        //
        $data = [];
        $data['load_view'] = 1;
        $data['session'] = $this->session->userdata('logged_in');
        $data['employee'] = $data['session']['employer_detail'];
        //
        $this->load
            ->view($this->pages['header'], $data)
            ->view("{$this->mp}es/faqs")
            ->view($this->pages['footer']);
    }


    /**
     *
     */
    public function surveyTemplateDetail($id)
    {
        //
        $data = [];
        $data['load_view'] = 1;
        $data['session'] = $this->session->userdata('logged_in');
        $data['employee'] = $data['session']['employer_detail'];
        $data['templateId'] = $id;
        //
        $this->load
            ->view($this->pages['header'], $data)
            ->view("{$this->mp}es/surveytemplatedetail")
            ->view($this->pages['footer']);
    }


  /**
     *
     */
    public function surveyTemplateSelect($id)
    {
        //
        $data = [];
        $data['load_view'] = 1;
        $data['session'] = $this->session->userdata('logged_in');
        $data['employee'] = $data['session']['employer_detail'];
        $data['templateId'] = $id;
        $data['company_id'] = $data["session"]["company_detail"]["sid"];
        $data['employer_id'] = $data["session"]["employer_detail"]["sid"];


        //
        $this->load
            ->view($this->pages['header'], $data)
            ->view("{$this->mp}es/surveytemplateselect")
            ->view($this->pages['footer']);
    }



}
