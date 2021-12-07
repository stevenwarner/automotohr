<?php ?>
<div class="main">
    <div class="container-fluid">
        <div class="row">		
            <div class="inner-content">
                <?php $this->load->view('templates/_parts/admin_column_left_view'); ?>
                <div class="col-lg-9 col-md-9 col-xs-12 col-sm-9 no-padding">
                    <div class="dashboard-content">
                        <div class="dash-inner-block">
                            <div class="row">
                                <div class="col-lg-12 col-md-12 col-xs-12 col-sm-12">
                                    <div class="heading-title page-title">
                                        <h1 class="page-title"><i class="fa fa-user"></i><?php echo $page_title; ?></h1>
                                        <a href="<?php echo base_url('manage_admin/employers/edit_employer')."/".$employee_detail["sid"]; ?>" class="btn black-btn float-right">Back</a>
                                    </div>
                                </div>
                                <div class="col-lg-12 col-md-12 col-xs-12 col-sm-12"> 
                                    <div class="heading-title page-title employee_info_section">
                                        <h1 class="page-title">Company Name : <?php echo $companyName; ?></h1>
                                        <br>
                                        <h1 class="page-title">Employee Name : <?php echo $employeeName; ?></h1>
                                    </div> 
                                </div> 
                                <div class="col-lg-12 col-md-12 col-xs-12 col-sm-12">
                                    <a class="btn btn-success pull-right status_btn" href="<?php echo base_url("manage_admin/employers/ChangeStatus")."/".$employee_detail["sid"]; ?>">Change Employee Status</a>
                                </div>
                                <div class="col-lg-12 col-md-12 col-xs-12 col-sm-12">
                                    <div class="table-responsive table-outer">
                                        <table class="table table-bordered table-hover">
                                            <thead>
                                                <tr>
                                                    <th class="col-xs-2 text-center">Status</th>
                                                    <th class="col-xs-2 text-center">Termination Reason</th>
                                                    <th class="col-xs-2 text-center">Termination Date</th>
                                                    <th class="col-xs-2 text-center">Involuntary Termination</th>
                                                    <th class="col-xs-1 text-center">Do Not Rehire</th>
                                                    <th class="col-xs-2 text-center">Status Change Date</th>
                                                    <th class="col-xs-1 text-center">Action</th>
                                                </tr>
                                            </thead>
                                            <tbody>
                                                <?php if (!empty($employee_status_records)) {
                                                    $i=0;
                                                    foreach ($employee_status_records as $record) { 
                                                ?>
                                                            <tr <?= $i%2 == 0 ? 'style="background-color: #f9f9f9 !important;"' : '';?>>
                                                                <td class="text-center" style="font-size: 14px; vertical-align: middle;">
                                                                    <?php       
                                                                        if (isset($record['employee_status']) && !empty($record['employee_status'])) {
                                                                            $employee_status = $record['employee_status'];

                                                                            if ($employee_status == 1) {
                                                                                echo 'Terminated';
                                                                            } else if ($employee_status == 2) {
                                                                                echo 'Retired';
                                                                            } else if ($employee_status == 3) {
                                                                                echo 'Deceased';
                                                                            } else if ($employee_status == 4) {
                                                                                echo 'Suspended';
                                                                            } else if ($employee_status == 5) {
                                                                                echo 'Active';
                                                                            } else if ($employee_status == 6) {
                                                                                echo 'Inactive';
                                                                            } else if ($employee_status == 7) {
                                                                                echo 'Leave';
                                                                            } else if ($employee_status == 8) {
                                                                                echo 'Rehired';
                                                                            } else {
                                                                                echo 'N/A';
                                                                            }
                                                                        } 
                                                                    ?>
                                                                </td>
                                                                <td class="text-center" style="vertical-align: middle;">
                                                                    <?php  
                                                                        if (isset($record['termination_reason']) && !empty($record['termination_reason'])) {
                                                                            $reason = $record['termination_reason'];

                                                                            if ($reason == 1) {
                                                                                echo 'Resignation';
                                                                            } else if ($reason == 2) {
                                                                                echo 'Fired';
                                                                            } else if ($reason == 3) {
                                                                                echo 'Tenure Completed';
                                                                            } else {
                                                                                echo 'N/A';
                                                                            }
                                                                        } else {
                                                                            echo 'N/A';
                                                                        } 
                                                                    ?>
                                                                </td>
                                                                <td class="text-center" style="vertical-align: middle;">
                                                                    <?php       
                                                                        if (isset($record['termination_date']) && !empty($record['termination_date'])) {
                                                                            $termination_date = $record['termination_date'];
                                                                            echo formatDateToDB($termination_date, DB_DATE, DATE);
                                                                        } else {
                                                                            echo 'N/A';
                                                                        } 
                                                                    ?>
                                                                </td>
                                                                <td class="text-center" style="vertical-align: middle;">
                                                                    <?php       
                                                                        if (isset($record['involuntary_termination']) && !empty($record['involuntary_termination'])) {
                                                                            $involuntary_termination = $record['involuntary_termination'];
                                                                            if ($involuntary_termination == 1) {
                                                                                echo '<i class="fa fa-check fa-2x text-success"></i>';
                                                                            }
                                                                        } else {
                                                                            echo 'N/A';
                                                                        } 
                                                                    ?>
                                                                </td>
                                                                <td class="text-center" style="vertical-align: middle;">
                                                                    <?php       
                                                                        if (isset($record['do_not_hire']) && !empty($record['do_not_hire'])) {
                                                                            $do_not_hire = $record['do_not_hire'];
                                                                            if ($do_not_hire == 1) {
                                                                                echo '<i class="fa fa-check fa-2x text-success"></i>';
                                                                            }
                                                                        } else {
                                                                            echo 'N/A';
                                                                        } 
                                                                    ?>
                                                                </td>
                                                                <td class="text-center" style="vertical-align: middle;">
                                                                    <?php       
                                                                        if (isset($record['status_change_date']) && !empty($record['status_change_date'])) {
                                                                            $status_change_date = $record['status_change_date'];
                                                                            echo formatDateToDB($status_change_date, DB_DATE, DATE);
                                                                        } 
                                                                    ?>
                                                                </td>
                                                                <td class="text-center" style="vertical-align: middle;">
                                                                    <a class="btn btn-success btn-sm" href="<?= base_url('manage_admin/employers/EditEmployeeStatus').'/'.$employeeSid.'/'.$record['sid']; ?>"><i class="fa fa-edit"></i></a>
                                                                </td>
                                                            </tr>
                                                            <tr <?= $i++%2 == 0 ? 'style="background-color: #f9f9f9 !important;"' : '';?>>
                                                                <td colspan="7">
                                                                    <div class="row">
                                                                        <?php 
                                                                            if (isset($record['details']) && !empty($record['details'])) {
                                                                                $details = $record['details'];
                                                                                $status_detail = html_entity_decode($details); 
                                                                        ?>

                                                                                <div class="col-lg-12 col-md-12 col-xs-12 col-sm-12">
                                                                                    <p><strong>Details :</strong> <?php echo $status_detail; ?></p>
                                                                                </div>
                                                                        <?php } ?>

                                                                        <?php 
                                                                            if (isset($record['changed_by']) && !empty($record['changed_by'])) {
                                                                            $changed_by = $record['changed_by'];
                                                                            $user_name = db_get_employee_profile($changed_by);
                                                                            $changed_by_name = $user_name[0]['first_name'] . ' ' . $user_name[0]['last_name']; 
                                                                        ?>
                                                                            <div class="col-lg-12 col-md-12 col-xs-12 col-sm-12">
                                                                                <p><strong>Changed By: </strong><?php echo $changed_by_name; ?></p>
                                                                                <p><strong>Added On: </strong><?php echo !empty($record['created_at']) && $record['created_at'] != NULL ? reset_datetime(array('datetime' => $record['created_at'], '_this' => $this)) : 'N/A'; ?></p>
                                                                            </div>
                                                                        <?php } ?>
                                                                        <?php 
                                                                            if (isset($record['attach_documents']) && !empty($record['attach_documents'])) { 
                                                                        ?>
                                                                            <div class="col-lg-12 col-md-12 col-xs-12 col-sm-12">
                                                                                <p>
                                                                                    <strong>Attached Document: </strong>
                                                                                    <?php                      
                                                                                        $attach_documents = $record['attach_documents'];

                                                                                        foreach ($attach_documents as $document) { 
                                                                                    ?>
                                                                                        <a  data-toggle="tooltip"
                                                                                            data-placement="top"
                                                                                            class="btn btn-default btn-sm"
                                                                                            href="javascript:;"
                                                                                            onclick="display_document(this);"
                                                                                            document_title="<?php echo $document['file_name']; ?>"
                                                                                            document_url="<?php echo AWS_S3_BUCKET_URL . $document['file_code']; ?>"
                                                                                            print_url="<?php echo $document['file_code']; ?>"
                                                                                            document_ext="<?php echo $document['file_type']; ?>">
                                                                                            <i class="fa fa-file"></i>
                                                                                        </a>
                                                                                    <?php } ?>
                                                                                </p>
                                                                            </div>
                                                                        <?php } ?>
                                                                    </div>
                                                                </td>
                                                            </tr>
                                                    <?php } ?>
                                                <?php } else { ?>  
                                                    <tr>
                                                        <td colspan="7">
                                                            <h3 class="text-center">
                                                                <?php 
                                                                    $active = $employer['active'];
                                                                    $archived = $employer['archived'];

                                                                    if ($active) {
                                                                        echo 'Active Employee';
                                                                    } else { 
                                                                        if($archived != 1) {
                                                                            echo 'Onboarding or Deactivated Employee';
                                                                        } else {
                                                                            echo 'Archived Employee';
                                                                        } 
                                                                    } 
                                                                ?>
                                                            </h3>
                                                        </td>
                                                    </tr>
                                                <?php } ?>  
                                            </tbody>
                                        </table>
                                    </div>
                                </div>  
                            </div>  
                        </div>      
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<style>
    .status_btn {
        margin: 10px 0px;
    }

    .employee_info_section {
        margin: 8px 0px;
    }
</style>

<script type="text/javascript">
    function display_document(source) {
        var iframe_url = '';
        var modal_content = '';
        var footer_content = '';
        var document_title = $(source).attr('document_title');
        var document_url = $(source).attr('document_url');
        var file_extension = $(source).attr('document_ext');
        var print_url = $(source).attr('print_url');
        var document_url_segment = print_url.split('.')[0];
        var unique_sid = '';
        var document_sid = '';

        if (document_url != '') {
            switch (file_extension.toLowerCase()) {
                case 'pdf':
                    iframe_url = document_url;
                    modal_content = '<iframe src="' + iframe_url + '" id="preview_iframe" class="uploaded-file-preview"  style="width:100%; height:500px;" frameborder="0"></iframe>';
                    footer_print_btn = '<a target="_blank" href="https://docs.google.com/viewerng/viewer?url=https://automotohrattachments.s3.amazonaws.com/' + document_url_segment + '.pdf" class="btn btn-success">Print</a>';
                    break;
                case 'doc':
                    iframe_url = 'https://view.officeapps.live.com/op/embed.aspx?src=' + document_url;
                    modal_content = '<iframe src="' + iframe_url + '" id="preview_iframe" class="uploaded-file-preview"  style="width:100%; height:500px;" frameborder="0"></iframe>';
                    footer_print_btn = '<a target="_blank" href="https://view.officeapps.live.com/op/view.aspx?src=https%3A%2F%2Fautomotohrattachments%2Es3%2Eamazonaws%2Ecom%3A443%2F' + document_url_segment + '%2Edoc&wdAccPdf=0" class="btn btn-success">Print</a>';
                    break;
                case 'docx':
                    iframe_url = 'https://view.officeapps.live.com/op/embed.aspx?src=' + document_url;
                    modal_content = '<iframe src="' + iframe_url + '" id="preview_iframe" class="uploaded-file-preview"  style="width:100%; height:500px;" frameborder="0"></iframe>';
                    footer_print_btn = '<a target="_blank" href="https://view.officeapps.live.com/op/view.aspx?src=https%3A%2F%2Fautomotohrattachments%2Es3%2Eamazonaws%2Ecom%3A443%2F' + document_url_segment + '%2Edocx&wdAccPdf=0" class="btn btn-success">Print</a>';
                    break;
                case 'xls':
                    iframe_url = 'https://view.officeapps.live.com/op/embed.aspx?src=' + document_url;
                    modal_content = '<iframe src="' + iframe_url + '" id="preview_iframe" class="uploaded-file-preview"  style="width:100%; height:500px;" frameborder="0"></iframe>';
                    footer_print_btn = '<a target="_blank" href="https://view.officeapps.live.com/op/view.aspx?src=https%3A%2F%2Fautomotohrattachments%2Es3%2Eamazonaws%2Ecom%3A443%2F' + document_url_segment + '%2Exls" class="btn btn-success">Print</a>';
                    break;
                case 'xlsx':
                    //using office docs
                    iframe_url = 'https://view.officeapps.live.com/op/embed.aspx?src=' + document_url;
                    modal_content = '<iframe src="' + iframe_url + '" id="preview_iframe" class="uploaded-file-preview"  style="width:100%; height:500px;" frameborder="0"></iframe>';
                    footer_print_btn = '<a target="_blank" href="https://view.officeapps.live.com/op/view.aspx?src=https%3A%2F%2Fautomotohrattachments%2Es3%2Eamazonaws%2Ecom%3A443%2F' + document_url_segment + '%2Exlsx" class="btn btn-success">Print</a>';
                    break;
                case 'jpg':
                case 'jpe':
                case 'jpeg':
                case 'png':
                case 'gif':
                    modal_content = '<img src="' + document_url + '" style="width:100%; height:500px;" />';
                    footer_print_btn = '<a target="_blank" href="<?php echo base_url('onboarding/print_applicant_upload_img/') ?>' + document_file_name + '" class="btn btn-success">Print</a>';
                    break;
                default :
                    //using google docs
                    iframe_url = 'https://docs.google.com/gview?url=' + document_url + '&embedded=true';
                    modal_content = '<iframe src="' + iframe_url + '" id="preview_iframe" class="uploaded-file-preview"  style="width:100%; height:500px;" frameborder="0"></iframe>';
                    break;
            }
            footer_content = '<a target="_blank" class="btn btn-success" href="<?php echo base_url('Hr_documents_management/download_upload_document') ?>' + '/' + print_url + '">Download</a>';
        } else {
            modal_content = '<h5>No ' + document_title + ' Uploaded.</h5>';
            footer_content = '';
        }

        $('#document_modal_body').html(modal_content);
        $('#document_modal_footer').html(footer_content);
        $('#document_modal_footer').append(footer_print_btn);
        $('#document_modal_title').html(document_title);
        $('#file_preview_modal').modal("toggle");
        $('#file_preview_modal').on("shown.bs.modal", function () {
            
            if (iframe_url != '') {
                $('#preview_iframe').attr('src', iframe_url);
            }
        });
    }
</script>
