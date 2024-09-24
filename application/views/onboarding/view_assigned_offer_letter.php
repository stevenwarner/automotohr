<?php $assigned_offer_letters = []; ?>
<link rel="stylesheet" href="<?= base_url(); ?>/assets/mFileUploader/index.css" />
<div class="main-content">
    <div class="container-fluid">
        <div class="row">
            <div class="applicant-profile-wrp">
                <div class="col-lg-9 col-md-9 col-xs-12 col-sm-12">
                    <div class="row">
                        <div class="col-lg-12 col-md-12 col-xs-12 col-sm-12">
                            <?php $this->load->view('templates/_parts/admin_flash_message'); ?>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-lg-12 col-md-12 col-xs-12 col-sm-12">
                            <div class="application-header">
                                <article>
                                    <figure>
                                        <img src="<?php echo isset($user_info['pictures']) && $user_info['pictures'] != NULL && $user_info['pictures'] != '' ? AWS_S3_BUCKET_URL . $user_info['pictures'] : base_url('assets/images/default_pic.jpg'); ?>" alt="Profile Picture" />
                                    </figure>
                                    <div class="text">
                                        <?php
                                        $userInfoNew = get_user_datescolumns($user_info['sid']);
                                        ?>
                                        <h2>
                                            <?php echo $user_info["first_name"]; ?> <?= $user_info["last_name"] ?></h2>
                                        <?php if ($user_type === 'employee') { ?>
                                            <span>
                                                <?= remakeEmployeeName(getUserColumnByWhere(['sid' => $user_info['sid']]), false); ?>
                                            </span>
                                        <?php } ?>
                                        <h3 style="margin-top: -10px;margin-bottom: 5px">
                                            <span>
                                                <?= get_user_anniversary_date(
                                                    $userInfoNew[0]['joined_at'],
                                                    $userInfoNew[0]['registration_date'],
                                                    $userInfoNew[0]['rehire_date']
                                                );
                                                ?>
                                            </span>
                                        </h3>
                                        <div class="start-rating">
                                            <?php if ($user_type == 'applicant') { ?>
                                                <input readonly="readonly" id="input-21b" value="<?php echo isset($user_average_rating) ? $user_average_rating : 0; ?>" type="number" name="rating" class="rating" min=0 max=5 step=0.2 data-size="xs" />
                                            <?php } else if ($user_type == 'employee') { ?>
                                                <?php if ($this->session->userdata('logged_in')['employer_detail']['access_level_plus']) { ?>
                                                    <a class="btn-employee-status btn-warning" href="<?php echo base_url('employee_status/' . $employer['sid']); ?>">Employee Status</a>
                                                <?php } ?>
                                            <?php } ?>
                                        </div>
                                        <?php if (isset($employee_terminate_status) && !empty($employee_terminate_status)) {
                                            echo '<h4>' . $employee_terminate_status . '</h4>';
                                        } else if (isset($employer['active'])) { ?>
                                            <h4>
                                                <?php if ($employer['active']) { ?>
                                                    Active Employee
                                                <?php } else { ?>
                                                    <?php if ($employer['archived'] != '1') { ?>
                                                        Onboarding or Deactivated Employee
                                                    <?php } else { ?>
                                                        Archived Employee
                                                    <?php } ?>
                                                <?php } ?>
                                            </h4>
                                        <?php } else { ?>
                                            <span> <?php echo 'Applicant'; ?></span>
                                        <?php } ?>
                                    </div>
                                </article>
                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-lg-12 col-md-12 col-xs-12 col-sm-12">
                            <div class="page-header-area margin-top">
                                <?php if ($user_type == 'applicant') { ?>
                                    <span class="page-heading down-arrow"><?php $this->load->view('manage_employer/company_logo_name'); ?>
                                        <a class="dashboard-link-btn" href="<?php echo base_url('applicant_profile/' . $user_info['sid']); ?>">
                                            <i class="fa fa-chevron-left"></i>Applicant Profile</a>
                                        View Offer Letter / Pay Plan
                                    </span>
                                <?php } else if ($user_type == 'employee') { ?>
                                    <span class="page-heading down-arrow"><?php $this->load->view('manage_employer/company_logo_name'); ?>
                                        <a class="dashboard-link-btn" href="<?php echo base_url('employee_profile/' . $user_info['sid']); ?>">
                                            <i class="fa fa-chevron-left"></i>Employee Profile</a>
                                        View Offer Letter / Pay Plan
                                    </span>
                                <?php } ?>
                            </div>
                        </div>
                    </div>

                    <div class="col-lg-12 col-md-12 col-xs-12 col-sm-12">
                        <div class="page-header full-width">
                            <h1 class="section-ttile"> Review & Sign Offer Letter / Pay Plan</h1>
                            <strong> Information:</strong> If you are unable to view the offer letter / pay plan, kindly reload the page.
                            <?php if ($user_type == 'applicant') { ?>
                                <a class="btn btn-success float-right" href="<?php echo base_url("onboarding/send_offer_letter/applicant") . "/" . $user_info["sid"] . "/" . $job_list_sid; ?>">
                                    <i class="fa fa-envelope"></i>
                                    Assign Offer Letter / Pay Plan
                                </a>
                            <?php } else { ?>
                                <a class="btn btn-success float-right" href="<?php echo base_url("onboarding/send_offer_letter/employee") . "/" . $user_info["sid"]; ?>">
                                    <i class="fa fa-envelope"></i>
                                    Assign Offer Letter / Pay Plan
                                </a>
                            <?php } ?>
                        </div>
                    </div>
                    <div class="col-lg-12 col-md-12 col-xs-12 col-sm-12">
                        <?php if (!empty($offer_letters)) { ?>
                            <div class="accordion-colored-header header-bg-gray">
                                <div class="panel-group" id="onboarding-configuration-accordion">
                                    <div class="panel panel-default">
                                        <div class="panel-heading">
                                            <h4 class="panel-title">
                                                Assigned Offer Letter(s)
                                            </h4>
                                        </div>

                                        <div class="panel-body">
                                            <div class="table-responsive full-width">
                                                <table class="table table-plane js-uncompleted-docs">
                                                    <thead>
                                                        <tr>
                                                            <th class="col-lg-6">Document Name</th>
                                                            <th class="col-lg-2">Document Type</th>
                                                            <th class="col-lg-4 text-center" colspan="4">Actions</th>
                                                        </tr>
                                                    </thead>
                                                    <tbody>
                                                        <?php $ggit = []; ?>
                                                        <?php if (!empty($offer_letters)) { ?>
                                                            <?php foreach ($offer_letters as $of_key => $offer_letter) {
                                                                $offer_letter_description = isset($offer_letter['document_description']) ? $offer_letter['document_description'] : '';
                                                                $ggit[$offer_letter['letter_status'] == 'Current' ? $offer_letter['letter_sid'] : $offer_letter['sid']] = $offer_letter_description;
                                                            ?>
                                                                <?php
                                                                //
                                                                if ($offer_letter['letter_type'] == 'hybrid_document') $offer_letter['submitted_description'] = html_entity_decode($offer_letter['submitted_description']);
                                                                $assigned_offer_letters[] = $offer_letter;
                                                                //
                                                                $offer_letter_preview_url       = '';
                                                                $offer_letter_download_url      = '';
                                                                $offer_letter_print_url         = '';

                                                                if ($offer_letter['letter_type'] == 'uploaded') {

                                                                    $offer_letter_url         = $offer_letter['uploaded_file'];
                                                                    $offer_letter_document_info = get_required_url($offer_letter_url);

                                                                    $offer_letter_print_url = $offer_letter_document_info['print_url'];
                                                                    $offer_letter_download_url = $offer_letter_document_info['download_url'];
                                                                } else {
                                                                    $offer_letter_print_url = base_url('hr_documents_management/perform_action_on_document_content' . '/' . $offer_letter['letter_sid'] . '/submitted/assigned_document/print');
                                                                    $offer_letter_download_url = base_url('hr_documents_management/perform_action_on_document_content' . '/' . $offer_letter['letter_sid'] . '/submitted/assigned_document/download');
                                                                }
                                                                ?>
                                                                <tr>
                                                                    <td class="col-lg-6">
                                                                        <?php
                                                                        echo $offer_letter['letter_title'] . '&nbsp;';

                                                                        if (isset($offer_letter['letter_status']) && $offer_letter['letter_status'] == 'Current') {
                                                                            echo "<b>(" . $offer_letter['letter_status'] . ")</b>";
                                                                        }

                                                                        if (isset($offer_letter['assigned_date']) && $offer_letter['assigned_date'] != '0000-00-00 00:00:00') {
                                                                            echo "<br><b>Assigned On: </b>" . date('M d Y, D', strtotime($offer_letter['assigned_date']));
                                                                        }

                                                                        if (!empty($offer_letter['signed_on'])) {
                                                                            echo "<br><b>Signed On: </b>" . date('M d Y, D', strtotime($offer_letter['signed_on']));
                                                                        } else {
                                                                            echo "<br><b>Signed On: </b> N/A";
                                                                        }

                                                                        ?>
                                                                    </td>
                                                                    <td class="col-lg-2">
                                                                        <?php
                                                                        echo ucfirst($offer_letter['letter_type']);
                                                                        ?>
                                                                    </td>
                                                                    <td>
                                                                        <button data-id="<?php echo $offer_letter['letter_status'] == 'Current' ? $offer_letter['letter_sid'] : $offer_letter['sid']; ?>" data-table="<?php echo $offer_letter['letter_status'] == 'Current' ? 'documents_assigned' : 'documents_assigned_history'; ?>" data-title="<?php echo $offer_letter['letter_title']; ?>" data-type="<?php echo $offer_letter['letter_type']; ?>" data-vpr="<?php echo $offer_letter['visible_to_payroll']; ?>" date-assign="<?php echo date('m-d-Y', strtotime($offer_letter['assigned_date'])); ?>" date-signed="<?php echo !empty($offer_letter['signature_timestamp']) ? date('m-d-Y', strtotime($offer_letter['signature_timestamp'])) : date('m-d-Y'); ?>" data-file="<?php echo isset($offer_letter['uploaded_file']) && !empty($offer_letter['uploaded_file']) ? $offer_letter['uploaded_file'] : ''; ?>" data_description="<?php echo isset($offer_letter['document_description']) && !empty($offer_letter['document_description']) ? '' : ''; ?>" class="btn btn-success btn-sm btn-block manage_offer_letter">
                                                                            <i class="fa fa-cog" aria-hidden="true"></i>
                                                                            Manage
                                                                        </button>
                                                                    </td>
                                                                    <td>
                                                                        <?php if ($offer_letter['letter_type'] == 'hybrid_document') { ?>
                                                                            <button data-id="<?= $offer_letter['letter_sid']; ?>" data-type="offer_letter" data-document="submitted" class="btn btn-success btn-sm btn-block js-hybrid-preview">
                                                                                <i class="fa fa-eye" aria-hidden="true"></i>
                                                                                Preview
                                                                            </button>
                                                                        <?php } else if ($offer_letter['letter_type'] == 'uploaded') { ?>
                                                                            <button class="btn btn-success btn-sm btn-block" onclick="preview_latest_generic_function(this);" date-letter-type="uploaded" data-on-action="submitted" data-preview-url="<?php echo AWS_S3_BUCKET_URL . $offer_letter_url; ?>" data-s3-name="<?php echo $offer_letter_url; ?>">
                                                                                <i class="fa fa-eye" aria-hidden="true"></i>
                                                                                Preview
                                                                            </button>
                                                                        <?php } else { ?>
                                                                            <button class="btn btn-success btn-sm btn-block" onclick="preview_latest_generic_function(this);" date-letter-type="generated" data-doc-sid="<?php echo $offer_letter['letter_sid']; ?>" data-on-action="submitted" data-from="<?= $offer_letter['letter_status'] == 'Current' ? 'assigned_document' : 'assigned_document_history'; ?>">
                                                                                <i class="fa fa-eye" aria-hidden="true"></i>
                                                                                Preview
                                                                            </button>
                                                                        <?php } ?>
                                                                    </td>
                                                                    <?php if ($offer_letter['letter_type'] == 'hybrid_document') { ?>
                                                                        <td></td>
                                                                        <td></td>
                                                                    <?php } else { ?>
                                                                        <td>
                                                                            <a target="_blank" href="<?php echo $offer_letter_print_url; ?>" class="btn btn-success btn-sm btn-block">
                                                                                <i class="fa fa-print" aria-hidden="true"></i>
                                                                                Print
                                                                            </a>
                                                                        </td>
                                                                        <td>
                                                                            <a target="_blank" href="<?php echo $offer_letter_download_url; ?>" class="btn btn-success btn-sm btn-block">
                                                                                <i class="fa fa-download" aria-hidden="true"></i>
                                                                                Download
                                                                            </a>
                                                                        </td>
                                                                    <?php } ?>
                                                                </tr>
                                                            <?php } ?>
                                                        <?php } ?>
                                                    </tbody>
                                                </table>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        <?php } else { ?>
                            <div id="print_offer_letter" class="hr-box text-center" style="background: #fff; padding: 20px;">
                                <?php if ($is_assign == 0) { ?>
                                    <h1 class="section-ttile">An Offer Letter / Pay Plan has not been assigned to this person!</h1>
                                <?php } else if ($is_assign == 1) { ?>
                                    <h1 class="section-ttile">An Offer Letter / Pay Plan, has not been signed by this person!</h1>
                                <?php } ?>
                            </div>
                        <?php } ?>
                    </div>
                </div>
                <?php if ($user_type == 'applicant') { ?>
                    <?php $this->load->view('manage_employer/application_tracking_system/profile_right_menu_applicant'); ?>
                <?php } elseif ($user_type == 'employee') {
                    $this->load->view('manage_employer/employee_management/profile_right_menu_employee_new');
                } ?>
            </div>
        </div>
    </div>
</div>
<!-- Main End -->

<!-- Preview Latest Document Modal Start -->
<div id="show_latest_preview_document_modal" class="modal fade" role="dialog">
    <div class="modal-dialog modal-lg" role="document">
        <div class="modal-content">
            <div class="modal-header modal-header-bg">
                <button type="button" class="close" data-dismiss="modal">&times;</button>
                <h4 class="modal-title" id="latest_document_modal_title"></h4>
            </div>
            <div class="modal-body">
                <div id="latest-iframe-container" style="display:none;">
                    <div class="embed-responsive embed-responsive-4by3">
                        <div id="latest-iframe-holder" class="embed-responsive-item">
                        </div>
                    </div>
                </div>
                <div id="latest_assigned_document_preview" style="display:none;">

                </div>
            </div>
            <div class="modal-footer" id="latest_document_modal_footer">

            </div>
        </div>
    </div>
</div>
<!-- Preview Latest Document Modal Modal End -->

<!-- Manage Offer Letter  Modal Start -->

<div id="manage_offer_letter_modal" class="modal fade" tabindex="-1" role="dialog" aria-labelledby="myModalLabel">
    <div class="modal-dialog modal-lg" role="document">
        <div class="modal-content">
            <div class="modal-header modal-header-bg">
                <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                <h4 class="modal-title" id="uploaded_document_modal_title">Paper Form Upload</h4>
            </div>
            <div id="uploaded_document_modal_body" class="modal-body">
                <div class="loader" id="add_form_upload_document_loader" style="display: none;">
                    <i class="fa fa-spinner fa-spin"></i>
                </div>
                <form id="add_form_upload_document" enctype="multipart/form-data" method="post" action="<?php echo current_url(); ?>">
                    <div class="row">
                        <div class="col-xs-12 margin-top">
                            <label>Document Name<span class="staric">*</span></label>
                            <input type="text" name="document_title" id="offer_letter_title" value="" class="invoice-fields">
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-xs-12" id="document_upload_modify">
                            <label>Browse Document <span class="staric">*</span></label>
                            <input style="display: none;" type="file" name="document" id="upload_offer_letter">
                        </div>
                    </div>
                    <div class="row" id="document_description_modify">
                        <div class="col-xs-12 margin-top">
                            <label>Document Discription </label>
                            <textarea class="invoice-fields autoheight ckeditor" maxlength="250" name="offer_letter_description" id="offer_letter_description" cols="54" rows="6"></textarea>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-xs-6 margin-top">
                            <label>Assigned Date</label>
                            <input type="text" name="doc_assign_date" value="" class="invoice-fields date_time_picker" id="offer_letter_assign" readonly>
                        </div>
                        <div class="col-xs-6 margin-top">
                            <label>Signed Completed Date</label>
                            <input type="text" name="doc_sign_date" value="" class="invoice-fields date_time_picker" id="offer_letter_signed" readonly>
                        </div>
                    </div>
                    <div class="row" id="manual_doc_payroll_section">
                        <div class="col-xs-12 margin-top">
                            <label class="control control--checkbox font-normal">
                                Visible To Payroll Plus
                                <input id="visible_manual_doc_to_payroll" name="visible_manual_doc_to_payroll" type="checkbox" value="1" />
                                <div class="control__indicator"></div>
                            </label>
                        </div>
                    </div>
                    <div class="row" id="archive">
                        <div class="col-xs-12 margin-top">
                            <label class="control control--checkbox font-normal">
                                Archive
                                <input id="archive_offf" name="archive_offf" type="checkbox" value="1" />
                                <div class="control__indicator"></div>
                            </label>
                        </div>
                    </div>
                    <br />

                    <div class="row">
                        <div class="col-xs-12">
                            <input type="hidden" id="offer_letter_sid" />
                            <input type="hidden" id="offer_letter_table" />
                            <input type="hidden" id="offer_letter_type" />
                            <input type="hidden" id="offer_letter_user_sid" value="<?php echo $user_info["sid"]; ?>" />
                            <button type="button" class="btn btn-success pull-right update_offer_letter">Update Offer Letter</button>
                        </div>
                    </div>
                </form>
            </div>
            <div id="uploaded_document_modal_footer" class="modal-footer">

            </div>
        </div>
    </div>
</div>

<!-- Manage Offer Letter  Modal End -->
<script language="JavaScript" type="text/javascript" src="<?= base_url(); ?>/assets/mFileUploader/index.js"></script>
<script>
    var ggit = <?= json_encode($ggit); ?>;

    function get_select_box_value(select_box_name, select_box_val) {
        var data = select_box_val;
        let cc = '';

        if (select_box_val.indexOf(',') > -1) {
            data = select_box_val.split(',');
        }


        if ($.isArray(data)) {
            let modify_string = '';
            $.each(data, function(key, value) {
                if (modify_string == '') {
                    modify_string = ' ' + $(`select.js_select_document[name="${select_box_name}"] option[value="${value}"]`).text();
                } else {
                    modify_string = modify_string + ', ' + $(`select.js_select_document[name="${select_box_name}"] option[value="${value}"]`).text();
                }
            });
            cc = modify_string;
        } else {
            cc = $(`select.js_select_document[name="${select_box_name}"] option[value="${select_box_val}"]`).text();
        }

        return cc;
    }

    function preview_latest_generic_function(source) {
        var letter_type = $(source).attr('date-letter-type');
        var request_type = $(source).attr('data-on-action');
        var document_title = '';

        if (request_type == 'assigned') {
            document_title = 'Assigned Document';
        } else if (request_type == 'submitted') {
            document_title = 'Submitted Document';
        } else if (request_type == 'company') {
            document_title = 'Company Document';
        }

        if (letter_type == 'uploaded') {
            var preview_document = 1;
            var model_contant = '';
            var preview_iframe_url = '';
            var preview_image_url = '';
            var document_print_url = '';
            var document_download_url = '';

            var document_sid = $(source).attr('data-doc-sid');
            var file_s3_path = $(source).attr('data-preview-url');
            var file_s3_name = $(source).attr('data-s3-name');

            var file_extension = file_s3_name.substr(file_s3_name.lastIndexOf('.') + 1, file_s3_name.length);
            var document_file_name = file_s3_name.substr(0, file_s3_name.lastIndexOf('.'));
            var document_extension = file_extension.toLowerCase();


            switch (file_extension.toLowerCase()) {
                case 'pdf':
                    preview_iframe_url = 'https://docs.google.com/gview?url=' + file_s3_path + '&embedded=true';
                    document_print_url = 'https://docs.google.com/viewerng/viewer?url=https://automotohrattachments.s3.amazonaws.com/' + document_file_name + '.pdf';
                    break;
                case 'csv':
                    preview_iframe_url = 'https://docs.google.com/gview?url=' + file_s3_path + '&embedded=true';
                    document_print_url = 'https://docs.google.com/viewerng/viewer?url=https://automotohrattachments.s3.amazonaws.com/' + document_file_name + '.csv';
                    break;
                case 'doc':
                    preview_iframe_url = 'https://view.officeapps.live.com/op/embed.aspx?src=' + encodeURI(file_s3_path);
                    document_print_url = 'https://view.officeapps.live.com/op/view.aspx?src=https%3A%2F%2Fautomotohrattachments%2Es3%2Eamazonaws%2Ecom%3A443%2F' + document_file_name + '%2Edoc&wdAccPdf=0';
                    break;
                case 'docx':
                    preview_iframe_url = 'https://view.officeapps.live.com/op/embed.aspx?src=' + encodeURI(file_s3_path);
                    document_print_url = 'https://view.officeapps.live.com/op/view.aspx?src=https%3A%2F%2Fautomotohrattachments%2Es3%2Eamazonaws%2Ecom%3A443%2F' + document_file_name + '%2Edocx&wdAccPdf=0';
                    break;
                case 'ppt':
                    preview_iframe_url = 'https://docs.google.com/gview?url=' + file_s3_path + '&embedded=true';
                    document_print_url = 'https://docs.google.com/viewerng/viewer?url=https://automotohrattachments.s3.amazonaws.com/' + document_file_name + '.ppt';
                    break;
                case 'pptx':
                    dpreview_iframe_url = 'https://docs.google.com/gview?url=' + file_s3_path + '&embedded=true';
                    document_print_url = 'https://docs.google.com/viewerng/viewer?url=https://automotohrattachments.s3.amazonaws.com/' + document_file_name + '.pptx';
                    break;
                case 'xls':
                    preview_iframe_url = 'https://view.officeapps.live.com/op/embed.aspx?src=' + encodeURI(file_s3_path);
                    ocument_print_url = 'https://view.officeapps.live.com/op/view.aspx?src=https%3A%2F%2Fautomotohrattachments%2Es3%2Eamazonaws%2Ecom%3A443%2F' + document_file_name + '%2Exls';
                    break;
                case 'xlsx':
                    preview_iframe_url = 'https://view.officeapps.live.com/op/embed.aspx?src=' + encodeURI(file_s3_path);
                    document_print_url = 'https://view.officeapps.live.com/op/view.aspx?src=https%3A%2F%2Fautomotohrattachments%2Es3%2Eamazonaws%2Ecom%3A443%2F' + document_file_name + '%2Exlsx';
                    break;
                case 'jpg':
                case 'jpe':
                case 'jpeg':
                case 'png':
                case 'gif':
                case 'JPG':
                case 'JPE':
                case 'JPEG':
                case 'PNG':
                case 'GIF':
                    preview_document = 0;
                    preview_image_url = file_s3_path;
                    document_print_url = '<?php echo base_url("hr_documents_management/print_s3_image"); ?>' + '/' + file_s3_name;
                    break;
                default: //using google docs
                    preview_iframe_url = 'https://docs.google.com/gview?url=' + document_preview_url + '&embedded=true';
                    break;
            }

            document_download_url = '<?php echo base_url("hr_documents_management/download_upload_document"); ?>' + '/' + file_s3_name;

            $('#show_latest_preview_document_modal').modal('show');
            $("#latest_document_modal_title").html(document_title);
            $('#latest-iframe-container').show();

            if (preview_document == 1) {
                model_contant = $("<iframe />")
                    .attr("id", "offer_letter-pop-up-iframe")
                    .attr("class", "uploaded-file-preview")
                    .attr("src", preview_iframe_url);

            } else {
                model_contant = $("<img />")
                    .attr("id", "latest_image_tag")
                    .attr("class", "img-responsive")
                    .css("margin-left", "auto")
                    .css("margin-right", "auto")
                    .attr("src", preview_image_url);
            }


            $("#latest-iframe-holder").append(model_contant);
            loadIframe(preview_iframe_url, '#offer_letter-pop-up-iframe', true);

            footer_content = '<a target="_blank" class="btn btn-success" href="' + document_print_url + '">Print</a>';
            footer_content += '<a target="_blank" class="btn btn-success" href="' + document_download_url + '">Download</a>';
            $("#latest_document_modal_footer").html(footer_content);
        } else {
            var request_sid = $(source).attr('data-doc-sid');
            var request_from = $(source).attr('data-from');

            $.ajax({
                'url': '<?php echo base_url('hr_documents_management/get_document_content'); ?>' + '/' + request_sid + '/' + request_type + '/' + request_from,
                'type': 'GET',
                success: function(contant) {
                    var obj = jQuery.parseJSON(contant);
                    var requested_content = obj.requested_content;
                    var document_view = obj.document_view;
                    var form_input_data = obj.form_input_data;
                    var is_iframe_preview = obj.is_iframe_preview;

                    var print_url = '<?php echo base_url('hr_documents_management/perform_action_on_document_content'); ?>' + '/' + request_sid + '/' + request_type + '/' + request_from + '/print';
                    var download_url = '<?php echo base_url('hr_documents_management/perform_action_on_document_content'); ?>' + '/' + request_sid + '/' + request_type + '/' + request_from + '/download';

                    $('#show_latest_preview_document_modal').modal('show');
                    $("#latest_document_modal_title").html(document_title);

                    if (request_type == 'submitted') {
                        if (is_iframe_preview == 1) {
                            var model_contant = '';

                            $('#latest-iframe-container').show();
                            $('#latest_assigned_document_preview').hide();

                            var model_contant = $("<iframe />")
                                .attr("id", "latest_document_iframe")
                                .attr("class", "uploaded-file-preview")
                                .attr("src", requested_content);

                            $("#latest-iframe-holder").append(model_contant);
                        } else {
                            $('#latest-iframe-container').hide();
                            $('#latest_assigned_document_preview').show();
                            $("#latest_assigned_document_preview").html(document_view);

                            //
                            if ($('#show_latest_preview_document_modal').find('select').length >= 0) {
                                $('#show_latest_preview_document_modal').find('select').map(function(i) {
                                    //
                                    $(this).addClass('js_select_document');
                                    $(this).prop('name', 'selectDD' + i);
                                });
                            }

                            form_input_data = Object.entries(form_input_data);

                            $.each(form_input_data, function(key, input_value) {
                                if (input_value[0] == 'signature_person_name') {
                                    var input_field_id = input_value[0];
                                    var input_field_val = input_value[1];
                                    $('#' + input_field_id).val(input_field_val);
                                } else {
                                    var input_field_id = input_value[0] + '_id';
                                    var input_field_val = input_value[1];
                                    var input_type = $('#' + input_field_id).attr('data-type');

                                    if (input_type == 'text') {
                                        $('#' + input_field_id).val(input_field_val);
                                        $('#' + input_field_id).prop('disabled', true);
                                    } else if (input_type == 'checkbox') {
                                        //
                                        if ($('#' + input_field_id).attr('data-required') == "yes") {
                                            if (input_value[1] == 'yes') {
                                                $(`input[name="${input_value[0]}1"]`).prop('checked', true);
                                            } else {
                                                $(`input[name="${input_value[0]}2"]`).prop('checked', true);
                                            }
                                        }
                                        //
                                        if (input_field_val == 'yes') {
                                            $('#' + input_field_id).prop('checked', true);;
                                        }
                                        $('#' + input_field_id).prop('disabled', true);

                                    } else if (input_type == 'textarea') {
                                        $('#' + input_field_id).hide();
                                        $('#' + input_field_id + '_sec').show();
                                        $('#' + input_field_id + '_sec').html(input_field_val);
                                    } else if (input_value[0].match(/select/) !== -1) {
                                        if (input_value[1] != null) {
                                            let cc = get_select_box_value(input_value[0], input_value[1]);
                                            $(`select.js_select_document[name="${input_value[0]}"]`).html('');
                                            $(`select.js_select_document[name="${input_value[0]}"]`).hide(0);
                                            $(`select.js_select_document[name="${input_value[0]}"]`).after(`<strong style="font-size: 20px;">${cc}</strong>`)
                                        }
                                    }
                                }
                            });
                        }
                    } else {

                        model_contant = requested_content;
                        $('#latest-iframe-container').hide();
                        $('#latest_assigned_document_preview').show();
                        $("#latest_assigned_document_preview").html(document_view);

                        //
                        if ($('#show_latest_preview_document_modal').find('select').length >= 0) {
                            $('#show_latest_preview_document_modal').find('select').map(function(i) {
                                //
                                $(this).addClass('js_select_document');
                                $(this).prop('name', 'selectDD' + i);
                            });
                        }
                    }

                    footer_content = '<a target="_blank" class="btn btn-success" href="' + print_url + '">Print</a>';
                    footer_content += '<a target="_blank" class="btn btn-success" href="' + download_url + '">Download</a>';
                    $("#latest_document_modal_footer").html(footer_content);
                }
            });
        }
    }

    $('#show_latest_preview_document_modal').on('hidden.bs.modal', function() {
        $("#latest-iframe-holder").html('');
        $("#latest_document_iframe").remove();
        $("#latest_image_tag").remove();
        $('#latest-iframe-container').hide();
        $('#latest_assigned_document_preview').html('');
        $('#latest_assigned_document_preview').hide();
    });


    $('#show_offer_letter_modal').on('hidden.bs.modal', function() {
        $("#offer_letter-pop-up-iframe").remove();
        $('#offer_letter-pop-up-iframe-container').hide();
    });

    $('.date_time_picker').datepicker({
        dateFormat: 'mm-dd-yy',
        changeMonth: true,
        changeYear: true,
        yearRange: "-100:+50",
    }).val();

    $('.manage_offer_letter').on('click', function() {
        var offer_letter_type = $(this).attr('data-type');
        var offer_letter_title = $(this).attr('data-title');
        var offer_letter_vpr = $(this).attr('data-vpr');
        var offer_letter_assign = $(this).attr('date-assign');
        var offer_letter_signed = $(this).attr('date-signed');
        var offer_letter_sid = $(this).attr('data-id');
        var offer_letter_table = $(this).attr('data-table');

        $("#offer_letter_title").val(offer_letter_title);
        $("#offer_letter_assign").val(offer_letter_assign);
        $("#offer_letter_signed").val(offer_letter_signed);
        $("#offer_letter_sid").val(offer_letter_sid);
        $("#offer_letter_table").val(offer_letter_table);
        $("#offer_letter_type").val(offer_letter_type);

        if (offer_letter_vpr == 1) {
            $('#visible_manual_doc_to_payroll').prop('checked', true);
        } else {
            $('#visible_manual_doc_to_payroll').prop('checked', false);
        }

        if (offer_letter_type == 'uploaded') {
            $('#document_description_modify').hide();
            $('#document_upload_modify').show();
        } else if (offer_letter_type == 'generated') {
            $('#document_upload_modify').hide();
            $('#document_description_modify').show();
            var discription = ggit[offer_letter_sid];
            console.log(discription)
            CKEDITOR.instances.offer_letter_description.setData(discription);
        }
        let s3Name = '';
        $('#upload_offer_letter').mFileUploader({
            fileLimit: -1, // Default is '2MB', Use -1 for no limit (Optional)
            allowedTypes: ['jpg', 'jpeg', 'png', 'gif', 'pdf', 'doc', 'docx', 'rtf', 'ppt', 'xls', 'xlsx', 'csv'], //(Optional)
            placeholderImage: s3Name // Default is empty ('') but can be set any image  (Optional)
        });
        $('#manage_offer_letter_modal').modal('show');
    });

    $('.update_offer_letter').on('click', function() {
        $('#add_form_upload_document_loader').show();
        var offer_letter_type = $('#offer_letter_type').val();
        var baseURI = "<?= base_url('hr_documents_management/handler'); ?>";
        var offer_letter_sid = $('#offer_letter_sid').val();
        var offer_letter_table = $('#offer_letter_table').val();
        var offer_letter_title = $("#offer_letter_title").val();
        var assign_date = $("#offer_letter_assign").val();
        var signed_date = $("#offer_letter_signed").val();
        var user_sid = $("#offer_letter_user_sid").val();
        var formData = new FormData();

        if (offer_letter_type == 'uploaded') {
            var upload_file = $('#upload_offer_letter').mFileUploader('get');

            if (!$.isEmptyObject(upload_file) && upload_file.hasError == true) {
                alertify.alert('ERROR!', 'Please select a valid file format.');
                $('#add_form_upload_document_loader').hide();
                return false;
            } else if (!$.isEmptyObject(upload_file) && upload_file.hasError == false) {
                console.log('here');
                formData.append('document', upload_file);
            }
        } else {
            var discription = CKEDITOR.instances.offer_letter_description.getData();
            formData.append('document_discription', discription);
        }

        if ($('#visible_manual_doc_to_payroll').is(':checked')) {
            formData.append('visible_to_payroll', 1);
        } else {
            formData.append('visible_to_payroll', 0);
        }

        formData.append('user_sid', user_sid);
        formData.append('document_sid', offer_letter_sid);
        formData.append('title', offer_letter_title);
        formData.append('document_type', offer_letter_type);
        formData.append('assign_date', assign_date);
        formData.append('signed_date', signed_date);
        formData.append('table_name', offer_letter_table);
        // formData.append('signed_date', signed_date);
        formData.append('archive', $('#archive_offf').prop('checked') === true ? 1 : 0);
        formData.append('action', 'modify_offer_letter_data');

        $.ajax({
            url: baseURI,
            data: formData,
            method: 'POST',
            processData: false,
            contentType: false
        }).done(function(resp) {
            var successMSG = 'Offer Letter update successfully.';
            $('#manage_offer_letter_modal').modal('hide');
            alertify.alert('SUCCESS!', successMSG, function() {
                $('#add_form_upload_document_loader').hide();
                window.location.reload();
            });
        });




    });
</script>

<?php $this->load->view('iframeLoader'); ?>
<?php $this->load->view('hr_documents_management/hybrid/scripts', ['assigned_offer_letters' => $assigned_offer_letters]); ?>