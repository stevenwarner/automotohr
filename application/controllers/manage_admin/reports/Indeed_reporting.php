<?php defined('BASEPATH') or exit('No direct script access allowed');

class Indeed_reporting extends Admin_Controller
{

    public function __construct()
    {
        parent::__construct();
        $this->load->library('ion_auth');
        $this->load->model('manage_admin/advanced_report_model');
        $this
            ->load
            ->model(
                "Indeed_model",
                "indeed_model"
            );
        $this->load->library('form_validation');
        $this->load->library("pagination");
        $this->form_validation->set_error_delimiters('<p class="error_message"><i class="fa fa-exclamation-circle"></i>', '</p>');
    }

    public function index(int $pageNumber = 0)
    {
        // ** Check Security Permissions Checks - Start ** //
        $redirect_url       = 'manage_admin';
        $function_name      = 'indeed_reporting';

        $admin_id = $this->ion_auth->user()->row()->id;
        $security_details = db_get_admin_access_level_details($admin_id);
        $this->data['security_details'] = $security_details;
        check_access_permissions($security_details, $redirect_url, $function_name); // Param2: Redirect URL, Param3: Function Name
        // ** Check Security Permissions Checks - End ** //

        // query params
        $this->data["filter"]["companies"] = $this->input->get("companies", true) ?? ["All"];
        $this->data["filter"]["status"] = $this->input->get("status", true) ?? ["All"];
        $this->data["filter"]["startDate"] = $this->input->get("start", true) ?? "";
        $this->data["filter"]["endDate"] = $this->input->get("end", true) ?? "";

        $this->data['page_title'] = 'Indeed Reports';
        $this->data['companies'] = $this->advanced_report_model->get_all_companies();
        $this->data['flag'] = true;

        // get the records
        $this->data["counts"] = $this
            ->indeed_model
            ->getQueueRecordCount(
                $this->data["filter"]
            );

        // set pagination
        $per_page = PAGINATION_RECORDS_PER_PAGE;
        $page_number = $pageNumber;
        $offset = 0;
        if ($page_number > 1) {
            $offset = ($page_number - 1) * $per_page;
        }

        $config = array();
        $config["base_url"] = base_url("manage_admin/reports/indeed");
        $config["total_rows"] = $this->data["counts"]["records"];
        $config["per_page"] = $per_page;
        $config["uri_segment"] = 2;
        $config["num_links"] = 2;
        $config["use_page_numbers"] = true;
        $config['full_tag_open'] = '<nav class="hr-pagination"><ul>';
        $config['full_tag_close'] = '</ul></nav><!--pagination-->';
        $config['first_link'] = '<i class="fa fa-angle-double-left"></i>';
        $config['first_tag_open'] = '<li class="prev page">';
        $config['first_tag_close'] = '</li>';
        $config['last_link'] = '<i class="fa fa-angle-double-right"></i>';
        $config['last_tag_open'] = '<li class="next page">';
        $config['last_tag_close'] = '</li>';
        $config['next_link'] = '<i class="fa fa-angle-right" style="line-height: 32px;"></i>';
        $config['next_tag_open'] = '<li class="next page">';
        $config['next_tag_close'] = '</li>';
        $config['prev_link'] = '<i class="fa fa-angle-left" style="line-height: 32px;"></i>';
        $config['prev_tag_open'] = '<li class="prev page">';
        $config['prev_tag_close'] = '</li>';
        $config['cur_tag_open'] = '<li class="active"><a href="">';
        $config['cur_tag_close'] = '</a></li>';
        $config['num_tag_open'] = '<li class="page">';
        $config['num_tag_close'] = '</li>';

        $this->pagination->initialize($config);
        $this->data["page_links"] = $this->pagination->create_links();
        $this->data['current_page'] = $page_number;
        $this->data['from_records'] = $offset == 0 ? 1 : $offset;
        $this->data['to_records'] = $this->data["counts"]["records"] < $per_page ? $this->data["counts"]["records"] : $offset + $per_page;


        //ExportData
        if ($this->input->get("perform_action", true)) {

            $exportBy = $this->ion_auth->user()->row()->first_name . ' ' . $this->ion_auth->user()->row()->last_name;

            // query params
            $data["filter"]["companies"] = $this->input->get("companies", true) ?? ["All"];
            $data["filter"]["status"] = $this->input->get("status", true) ?? ["All"];
            $data["filter"]["startDate"] = $this->input->get("start", true) ?? "";
            $data["filter"]["endDate"] = $this->input->get("end", true) ?? "";


            $records = $this
                ->indeed_model
                ->getQueueRecordsCSV(
                    $data["filter"]
                );

            header('Content-Type: text/csv; charset=utf-8');
            header("Content-Disposition: attachment; filename= indeed_report_" . (date('Y_m_d_H_i_s', strtotime('now'))) . ".csv");
            $output = fopen('php://output', 'w');


            fputcsv($output, array(
                "Exported By",
                $exportBy
            ));

            if(!empty($data["filter"]["startDate"]) && !empty($data["filter"]["endDate"])){
            fputcsv($output, array(
                "Period: " ,
                 $data["filter"]["startDate"] . " - " . $data["filter"]["endDate"],
            ));
        }


            fputcsv($output, array(
                "Export Date",
                date('m/d/Y H:i:s ', strtotime('now'))
            ));

            fputcsv($output, array('', ''));

            fputcsv(
                $output,
                array(
                    'Job Title',
                    'Source Posting Id',
                    'Tracking Key',
                    'Type',
                    'Status'
                )
            );


            if (!empty($records)) {
                $companyCache = [];

                foreach ($records as $row) {

                    $companyCache[$row["user_sid"]] =
                        $companyCache[$row["user_sid"]] ?? getCompanyColumnById($row["user_sid"], "CompanyName")["CompanyName"];
                    $a = [];

                    $a[] = $row['Title'] . "\n\n" . "Company: " . $companyCache[$row["user_sid"]];
                    $a[] = $row["indeed_posting_id"] ?? "-";
                    $a[] = $row["tracking_key"] ?? "-";
                    $a[] = $row["is_expired"] ? "EXPIRED" : "NEW";
                    $status = '';
                    if ($row["is_processed"]) {
                        $status = 'PROCESSED';
                    } elseif ($row["is_processing"] == 0) {
                        $status = 'PENDING';
                    } elseif ($row["is_processing"] == 1 && $row["has_errors"]) {
                        $status = 'ERRORS';
                    } elseif ($row["is_processing"] == 1) {
                        $status = 'PROCESSING';
                    }

                    $a[] = $status;

                    //
                    fputcsv($output, $a);
                }
            }

            fclose($output);
            exit;
        }

        // get the records
        $this->data["records"] = $this
            ->indeed_model
            ->getQueueRecords(
                $this->data["filter"],
                $per_page,
                $offset
            );
        $this->render('manage_admin/reports/indeed_report');
    }


    public function log(int $logId)
    {
        // get the log from logId
        $record = $this
            ->indeed_model
            ->getLogById(
                $logId
            );

        return SendResponse(
            200,
            [
                "view" => $this->load->view("manage_admin/reports/indeed_log", ["record" => $record], true)
            ]
        );
    }

    //
    public function history(int $jobId)
    {
        $records = $this
            ->indeed_model
            ->getHistoryById(
                $jobId
            );

        return SendResponse(
            200,
            [
                "view" => $this->load->view("manage_admin/reports/indeed_history", ["records" => $records], true)
            ]
        );
    }
}
