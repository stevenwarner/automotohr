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

    public function test()
    {
        //
        $records =
            $this->db
            ->order_by('sid', 'desc')
            ->get('portal_eeo_form')
            ->result_array();
        //
        $multipleEEOC = [];
        //
        foreach ($records as $record) {
            //
            $slug = $record['users_type'] . '_' . $record['application_sid'];
            //
            if (!isset($multipleEEOC[$slug])) {
                $multipleEEOC[$slug] = [];
            }
            //
            $multipleEEOC[$slug][] = $record;
        }

        //
        foreach ($multipleEEOC as $record) {
            //
            if (count($record) <= 1) {
                continue;
            }
            //
            $sid = $record[0]['sid'];
            $updateArray = [];
            $updateArray['us_citizen'] = $record['us_citizen'];
            $updateArray['visa_status'] = $record['visa_status'];
            $updateArray['group_status'] = $record['group_status'];
            $updateArray['veteran'] = $record['veteran'];
            $updateArray['disability'] = $record['disability'];
            $updateArray['gender'] = $record['gender'];
            // update tracker
            $this->db
                ->where([
                    'document_type' => 'eeoc',
                    'user_type' => $record[0]['users_type'],
                    'user_sid' => $record[0]['application_sid']
                ])
                ->update('verification_documents_track', [
                    'document_sid' => $sid
                ]);
            //
            foreach ($record as $key => $value) {
                //
                if ($key == 0) {
                    continue;
                }
                //
                if (!$updateArray['us_citizen'] && $value['us_citizen']) {
                    $updateArray['us_citizen'] = $value['us_citizen'];
                }
                //
                if (!$updateArray['visa_status'] && $value['visa_status']) {
                    $updateArray['visa_status'] = $value['visa_status'];
                }
                //
                if (!$updateArray['group_status'] && $value['group_status']) {
                    $updateArray['group_status'] = $value['group_status'];
                }
                //
                if (!$updateArray['veteran'] && $value['veteran']) {
                    $updateArray['veteran'] = $value['veteran'];
                }
                //
                if (!$updateArray['disability'] && $value['disability']) {
                    $updateArray['disability'] = $value['disability'];
                }
                //
                if (!$updateArray['gender'] && $value['gender']) {
                    $updateArray['gender'] = $value['gender'];
                }

                //
                $insArray = $value;
                $insArray['eeo_form_sid'] = $value['sid'];
                unset($insArray['sid']);
                // add to history
                $this->db->insert('portal_eeo_form_history', $insArray);
                // delete record
                $this->db->where('sid', $value['sid'])->delete('portal_eeo_form');
            }

            //
            $this->db->where('sid', $sid)->update('portal_eeo_form', $updateArray);
        }

        //
        exit('All done');
    }

    public function fix_merge()
    {
        $this->tm->get_merge_employee();
    }



    // Enable Rehired Employees


    // public function enableRehiredemployees()
    // {

    //     $employeesData = $this->tm->getRehiredemployees();

    //     if (!empty($employeesData)) {
    //         foreach ($employeesData as $employeeRow) {
    //             $this->tm->updateEmployee($employeeRow['sid']);
    //         }
    //     }
    //     echo "Done";
    // }
}
