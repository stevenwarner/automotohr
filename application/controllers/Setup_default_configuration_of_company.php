<?php

defined('BASEPATH') OR exit('No direct script access allowed');

class Setup_default_configuration_of_company extends Public_Controller {

    public function __construct() {
        parent::__construct();
        $this->load->model('setup_default_config_model');
        $this->config->load('default_group_categories');
    }

    public function index($company_sid = NULL){
        if($company_sid === NULL){
            $all_companies = $this->setup_default_config_model->get_all_companies();
            foreach($all_companies as $company){
                $company_groups = $this->setup_default_config_model->get_company_all_groups($company['sid']);
                $company_categories = $this->setup_default_config_model->get_company_all_categories($company['sid']);
                $default_groups = config_item('documents_group_management');
                $default_categories = config_item('documents_category_management');


                //******************************************************Check For Groups******************************************************
                if(sizeof($company_groups)){
                    //make separate groups array to check existence
                    $groups_array = array_map(function($v){
                        return strtolower($v['name']);
                    },$company_groups);
                    foreach($default_groups as $group){
                        if(!in_array(strtolower($group['name']), $groups_array)){ // Check if this default group is already assigned
                            $insert_array = $group;
                            $insert_array['ip_address'] = getUserIP();
                            $insert_array['company_sid'] = $company['sid'];
                            $insert_array['is_moved'] = 1;
                            $this->setup_default_config_model->insert_group_record($insert_array);
                        }
                    }

                }else{ //No Group assigned yet, assigned all default
                    foreach($default_groups as $group){
                        $insert_array = $group;
                        $insert_array['ip_address'] = getUserIP();
                        $insert_array['company_sid'] = $company['sid'];
                        $insert_array['is_moved'] = 1;
                        $this->setup_default_config_model->insert_group_record($insert_array);
                    }
                }


                //******************************************************Check For Categories******************************************************
                if(sizeof($company_categories)){
                    //make separate groups array to check existence
                    $cat_array = array_map(function($v){
                        return strtolower($v['name']);
                    },$company_categories);
                    foreach($default_categories as $category){
                        if(!in_array(strtolower($category['name']), $cat_array)){ // Check if this default category is already assigned
                            $insert_array = $category;
                            $insert_array['ip_address'] = getUserIP();
                            $insert_array['company_sid'] = $company['sid'];
                            $insert_array['created_date'] = date('Y-m-d H:i:s');
                            $this->setup_default_config_model->insert_category_record($insert_array);
                        }
                    }

                }else{ //No Group assigned yet, assigned all default
                    foreach($default_categories as $category){
                        $insert_array = $category;
                        $insert_array['ip_address'] = getUserIP();
                        $insert_array['company_sid'] = $company['sid'];
                        $insert_array['created_date'] = date('Y-m-d H:i:s');
                        $this->setup_default_config_model->insert_category_record($insert_array);
                    }
                }
                //Update Company's default config status
                $this->setup_default_config_model->update_company_default_config($company['sid'],array('default_group_categories_assigned' => 1));
            }
        }elseif($company_sid > 0){

            $company_default_check = $this->setup_default_config_model->check_if_default_document_already_added($company_sid);


            if($company_default_check == 0){
                $company_groups = $this->setup_default_config_model->get_company_all_groups($company_sid);
                $company_categories = $this->setup_default_config_model->get_company_all_categories($company_sid);
                $default_groups = config_item('documents_group_management');
                $default_categories = config_item('documents_category_management');


                //******************************************************Check For Groups******************************************************
                if(sizeof($company_groups)){
                    //make separate groups array to check existence
                    $groups_array = array_map(function($v){
                        return strtolower($v['name']);
                    },$company_groups);
                    foreach($default_groups as $group){
                        if(!in_array(strtolower($group['name']), $groups_array)){ // Check if this default group is already assigned
                            $insert_array = $group;
                            $insert_array['ip_address'] = getUserIP();
                            $insert_array['company_sid'] = $company_sid;
                            $insert_array['is_moved'] = 1;
                            $this->setup_default_config_model->insert_group_record($insert_array);
                        }
                    }

                }else{ //No Group assigned yet, assigned all default
                    foreach($default_groups as $group){
                        $insert_array = $group;
                        $insert_array['ip_address'] = getUserIP();
                        $insert_array['company_sid'] = $company_sid;
                        $insert_array['created_date'] = date('Y-m-d H:i:s');
                        $insert_array['is_moved'] = 1;
                        $this->setup_default_config_model->insert_group_record($insert_array);
                    }
                }


                //******************************************************Check For Categories******************************************************
                if(sizeof($company_categories)){
                    //make separate groups array to check existence
                    $cat_array = array_map(function($v){
                        return strtolower($v['name']);
                    },$company_categories);
                    foreach($default_categories as $category){
                        if(!in_array(strtolower($category['name']), $cat_array)){ // Check if this default category is already assigned
                            $insert_array = $category;
                            $insert_array['ip_address'] = getUserIP();
                            $insert_array['company_sid'] = $company_sid;
                            $insert_array['is_moved'] = 1;
                            $this->setup_default_config_model->insert_category_record($insert_array);
                        }
                    }

                }else{ //No Group assigned yet, assigned all default
                    foreach($default_categories as $category){
                        $insert_array = $category;
                        $insert_array['ip_address'] = getUserIP();
                        $insert_array['company_sid'] = $company_sid;
                        $insert_array['is_moved'] = 1;
                        $this->setup_default_config_model->insert_category_record($insert_array);
                    }
                }
                //Update Company's default config status
                $this->setup_default_config_model->update_company_default_config($company_sid,array('default_group_categories_assigned' => 1));
            }
            redirect(base_url('manage_admin/companies/manage_company/'.$company_sid),'refresh');
        }else{
            echo 'Company Not Found!';
        }
    }

}