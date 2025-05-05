<?php defined('BASEPATH') or exit('No direct script access allowed');
require_once APPPATH . 'controllers/survey/Base_survey.php';

/**
 * Survey employer
 *
 * @author  AutomotoHR Dev Team
 * @version 1.0
 */
class Survey_template extends Base_survey
{
    public function __construct()
    {
        parent::__construct(true);
    }

    public function listing()
    {
        // Set the page title
        $this->data['title'] = 'Survey Template';
        // set add
        $this->setPlugin("v1/plugins/jquery/jquery-ui.min", "css");
        $this->setPlugin("v1/plugins/jquery/jquery-ui.min", "js");
        $this->setPlugin("v1/plugins/ms_recorder/main");
        $this->setJs("templates/common");
        $this->setJs("templates/add_question");
        $this->setJs("templates/edit_question");
        $this->setJs("templates/add");
        $this->setJs("templates/view");
        // Load the required JavaScript file
        $this->renderView('survey/templates/listing');
    }

}
