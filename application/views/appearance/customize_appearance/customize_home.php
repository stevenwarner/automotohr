    <?php 
    $this->load->view('appearance/customize_appearance/common_config');
    $this->load->view('appearance/customize_appearance/customize_home_section_01');
    $view_data = array();
    $view_data['box'] = $section_02_meta;
    $view_data['box']['sid'] = 1000;
    $view_data['panel_title'] = 'Section Two';
    $view_data['perform_action'] = 'save_config_section_xx';
    $view_data['theme'] = $theme;
    $view_data['section_id'] = 'section_02';
    $this->load->view('appearance/customize_appearance/additional_section', $view_data);
    //$this->load->view('appearance/customize_appearance/customize_home_section_02');

    $view_data = array();
    $view_data['box'] = $section_03_meta;
    $view_data['box']['sid'] = 2000;
    $view_data['panel_title'] = 'Section Three';
    $view_data['perform_action'] = 'save_config_section_xx';
    $view_data['theme'] = $theme;
    $view_data['section_id'] = 'section_03';
    $this->load->view('appearance/customize_appearance/additional_section', $view_data);
    //$this->load->view('appearance/customize_appearance/customize_home_section_03');
    $this->load->view('appearance/customize_appearance/customize_home_section_04'); 
    $this->load->view('appearance/customize_appearance/customize_home_section_05'); 
    $this->load->view('appearance/customize_appearance/customize_home_section_06'); 
    $this->load->view('appearance/customize_appearance/customize_home_section_07'); 
    $this->load->view('appearance/customize_appearance/customize_home_section_08'); 
    $this->load->view('appearance/customize_appearance/customize_home_section_09'); 

    foreach ($additional_boxes as $key => $box) {
        $view_data = array();
        $view_data['box'] = $box;
        $view_data['panel_title'] = 'Additional Section ' . str_pad(($key + 1), 3, '0', STR_PAD_LEFT);
        $view_data['perform_action'] = 'Save Section';
        $view_data['theme'] = $theme;
        $this->load->view('appearance/customize_appearance/additional_section_new', $view_data);
    }