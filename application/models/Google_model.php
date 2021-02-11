<?php 
class Google_model extends CI_Model {
    function __construct() {
        parent::__construct();
    }

    public function Insert($id, $unique_key, $aws_file_name){
        $data = array();
        $data['unique_key'] = $unique_key;
        $data['aws_file_name'] = $aws_file_name;

        $this->db->insert('google_drive_attachments', $data);
    }

    public function Update($id, $unique_key, $aws_file_name){
        $data = array();
        $data['aws_file_name'] = $aws_file_name;

        $this->db->where('unique_key', $unique_key);

        $this->db->update('google_drive_attachments', $data);
    }

    public function Save($id, $unique_key, $aws_file_name)
    {
        if ($id == null) {
            $this->Insert($id, $unique_key, $aws_file_name);
        } else {
            $this->Update($id, $unique_key, $aws_file_name);
        }
    }

    public function GetSingleByKey($unique_key){
        $this->db->where('unique_key', $unique_key);
        $data = $this->db->get('google_drive_attachments')->result_array();

        if(!empty($data)){
            return $data[0];
        }else{
            return array();
        }
    }



}