<?php

/**
 * Make performance management urls
 * 
 * @employee  Mubashir Ahmed
 * @date      02/02/2021
 * 
 * @param Array|String $args (Optional)
 * 
 * @return String
 */
if(!function_exists('purl')){
    function purl($args = ''){
        $url =  rtrim(base_url('performance-management'), '/');
        //
        if(is_array($args) && count($args)) $url .=  implode('/', $args);
        else if(!empty($args)) $url .= '/'.$args;
        //
        return rtrim($url, '/');
    }
}

/**
 * Get random data
 * 
 * @employee  Mubashir Ahmed
 * @date      02/03/2021
 * 
 * @param String $args (Optional)
 * 
 * @return String
 */
if(!function_exists('randomData')){
    function randomData($args = 'img'){
        $imgBank = [
            'https://automotohrattachments.s3.amazonaws.com/images-(1)-guQ8Vm.jpg',
            'https://automotohrattachments.s3.amazonaws.com/images-(2)-gtCmf0.jpg',
            'https://automotohrattachments.s3.amazonaws.com/images-HTdv7h.jpg',
            'https://automotohrattachments.s3.amazonaws.com/download-t1J0BV.jpg',
            'https://automotohrattachments.s3.amazonaws.com/8578-profile_picture-8579-trY.png'
        ];
        //
        $nameBank = [
            'John Doe',
            'John Smith',
            'Alexandar Jones',
            'Micheal Jords',
        ];

        shuffle($imgBank);
        shuffle($nameBank);
        //
        return $args == 'name' ? $nameBank[0] : $imgBank[0];
    }
}

/**
 * Send JSON response to browser
 * 
 * @employee Mubashir Ahmed
 * @date     02/10/2021
 * 
 * @param Array $in
 * @return VOID
 */
if(!function_exists('res')){
    function res($in){
        header('Content-Type: application/json');
        echo json_encode($in);
        exit(0);
    }
}

/**
 * Get Question Body
 * 
 * @employee Mubashir Ahmed
 * @date     02/10/2021
 * 
 * @param Array $question
 * @param Array $answer (Optional)
 * 
 * @return String
 */
if(!function_exists('getQuestionBody')){
    function getQuestionBody($question, $answer = []){
        //
        $html = '';
        // For rating
        if($question['question_type'] != 'text'){
            //
            $html .= '<ul>';
            //
            for($i = 1; $i <= $question['scale']; $i++){
                $html .= '<li>';
                $html .= '  <div class="csFeedbackViewBoxTab">';
                $html .= '      <p class="mb0">'.($i).'</p>';
                if($question['labels_flag'] == 1):
                    $html .= '  <p>'.(getLabel($i, $question['label_question'])).'</p>';
                endif;
                $html .= '  </div>';
                $html .= '</li>';
            }
            //
            $html .= '</ul>';
        }
        // For text
        if($question['question_type'] != 'rating'){
            $html .= '<div class="csFeedbackViewBoxComment">';
            $html .= '  <div class="row">';
            $html .= '    <div class="col-sm-12 col-xs-12 ma10">';
            $html .= '        <textarea class="form-control"></textarea>';
            $html .= '    </div>';
            $html .= '  </div>';
            $html .= '</div>';
        }
        //
        return $html;
    }
}

/**
 * Get Label
 * 
 * @employee Mubashir Ahmed
 * @date     02/10/2021
 * 
 * @param Integer $scale
 * @param Array   $labels (Optional)
 * 
 * @return String
 */
if(!function_exists('getLabel')){
    function getLabel($scale, $labels){
        //
        if(!count($labels) || $labels == '[]') $labels = getDefaultLabel();
        foreach($labels as $k => $label) if($k == $scale) return $label;
        return '';
    }
}

/**
 * Get Default Labels
 * 
 * @employee Mubashir Ahmed
 * @date     02/10/2021
 * 
 * @return Array
 */
if(!function_exists('getDefaultLabel')){
    function getDefaultLabel(){
        return [
            1 => 'Strongly Disagree',
            2 => 'Disagree',
            3 => 'Neutral',
            4 => 'Agree',
            5 => 'Strongly Agree'
        ];
    }
}

/**
 * Get logged in employer access level
 * 
 * @employee Mubashir Ahmed
 * @date     02/11/2021
 * 
 * @method get_instance
 * 
 * @return Array
 */
if(!function_exists('getEmployerAccessLevel')){
    function getEmployerAccessLevel(){
        // Get CI instance
        $_this = &get_instance();
        //
        $ses = $_this->session->userdata('logged_in');
        //
        if(empty($ses)) return 0;
        //
        if($ses['employer_detail']['access_level_plus'] == 1 || $ses['employer_detail']['pay_plan_flag'] == 1) return 1;
        //
        return 0;
    }
}