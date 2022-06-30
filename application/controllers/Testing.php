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



//
    public function sync_header_video_overlay()
    {
        $this->db->select('company_id, meta_key, meta_value');
        $this->db->where('meta_key', 'site_settings');
        $result = $this->db->get('portal_themes_meta_data')->result_array();

        if (!empty($result)) {
            foreach ($result as $row) {
                $meta_values = unserialize($row['meta_value']);
                if (isset($meta_values['enable_header_overlay'])) {
                    $data =  array(
                        'header_video_overlay' => $meta_values['enable_header_overlay']
                    );

                    $this->db->where('user_sid', $row['company_id']);
                    $this->db->update('portal_employer', $data);
                }
            }
        }
    
    }
}
