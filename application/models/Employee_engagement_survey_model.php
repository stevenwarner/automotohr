<?php if (!defined('BASEPATH')) exit('No direct script access allowed');

class Employee_engagement_survey_model extends CI_Model{
    //
    private $tables;

    //
    function __construct(){
        //
        $this->tables = [];
        $this->tables[] = 'employee_engagement_templates';
    }
}
