<?php defined('BASEPATH') or exit('No direct script access allowed');
require_once APPPATH . 'controllers/survey/Base_survey.php';

/**
 * Survey employer
 *
 * @author  AutomotoHR Dev Team
 * @version 1.0
 */
class Surveys extends Base_survey
{
    public function __construct()
    {
        parent::__construct(true);
    }

    public function listing()
    {
        // Set the page title
        $this->data['title'] = 'Surveys';
        // set add
        $this->setPlugin("v1/plugins/jquery/jquery-ui.min", "css");
        $this->setPlugin("v1/plugins/jquery/jquery-ui.min", "js");
        $this->setPlugin("v1/plugins/ms_recorder/main");
        $this->setJs("surveys/common");
        $this->setJs("templates/add_question");
        $this->setJs("surveys/edit_question");
        $this->setJs("surveys/add");
        $this->setJs("surveys/edit");
        $this->setJs("surveys/view");
        // Load the required JavaScript file
        $this->renderView('survey/listing');
    }

}
